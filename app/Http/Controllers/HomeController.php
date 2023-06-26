<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Estate_Home()
    {
        try
        {
            $postsWithImages =collect() ;
            $user_id = Auth::id();
            $user = Auth::user() ;
            $favorites_location = DB::table('favorites')
                ->join('estates', 'favorites.estate_id', '=', 'estates.id')
                ->where('favorites.user_id', '=', $user_id)
                ->select('estates.governorate')
                ->get();



            foreach ($favorites_location as $favorite) {
                $posts = Estate::where('governorate', $favorite->governorate)->get();
                foreach ($posts as $post) {
                    $images = $post->images()->get();
                    $favorite = $user->isEstateFavorite($post) ;
                    $postWithImage = [
                        'post' => $post,
                        'images' => $images ,
                        'favorite' => $favorite
                    ];
                    $postsWithImages->push($postWithImage);
                }
            }

            $Top_Rating_Posts = DB::table('rates')
                ->join('estates', 'rates.estate_id', '=', 'estates.id')
                ->where('rates.property_type', '=', 'estate')
                ->select('estate_id', DB::raw('AVG(rate) as average_rate'))
                ->groupBy('estate_id')
                ->orderByDesc('average_rate')
                ->get();


            foreach ($Top_Rating_Posts as $rating) {
                $estate = Estate::find($rating->estate_id);
                $images = $estate->images()->get();
                $favorite = $user->isEstateFavorite($estate) ;
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $postsWithImages->push($postWithImage);
            }

            $All_Estate = Estate::all();

            foreach ($All_Estate as $estate) {
                $images = $estate->images()->get();
                $favorite = $user->isEstateFavorite($estate) ;
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images ,
                    'favorite' => $favorite
                ];
                $postsWithImages->push($postWithImage);
            }

            $perPage = 4;
            $currentPage = Paginator::resolveCurrentPage() ?: 1;
            $items = $postsWithImages->all();
            $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);
            $relatedPosts = new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage);

            return response()->json([
                'AllPost' => $relatedPosts
            ]);

        } catch (\Throwable $exception) {
            return response()->json([
                'Status' => false,
                'Error Message' => $exception->getMessage(),
            ]);
        }
    }

    public function Car_Home()
    {
        try {
            $user_id = Auth::id();
            $user= Auth::user() ;
            $favorites_location = DB::table('favorites')
                ->join('cars', 'favorites.car_id', '=', 'cars.id')
                ->where('favorites.user_id', '=', $user_id)
                ->select('cars.governorate')
                ->get();

            $postsWithImages = collect();

            foreach ($favorites_location as $favorite) {
                $posts = Car::where('governorate', $favorite->governorate)->get();
                foreach ($posts as $post) {
                    $images = $post->images()->get();
                    $favorite = $user->isCarFavorite($post) ;
                    $postWithImage = [
                        'post' => $post,
                        'images' => $images,
                        'favorite' => $favorite
                    ];
                    $postsWithImages->push($postWithImage);
                }
            }

            $Top_Rating_Posts = DB::table('rates')
                ->join('cars', 'rates.car_id', '=', 'cars.id')
                ->where('rates.property_type', '=', 'car')
                ->select('car_id', DB::raw('AVG(rate) as average_rate'))
                ->groupBy('car_id')
                ->orderByDesc('average_rate')
                ->get();

            foreach ($Top_Rating_Posts as $rating) {
                $car = Car::find($rating->car_id);
                $images = $car->images()->get();
                $favorite = $user->isCarFavorite($car) ;
                $postWithImage = [
                    'post' => $car,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $postsWithImages->push($postWithImage);
            }

            $All_Car = Car::all();

            foreach ($All_Car as $car) {
                $images = $car->images()->get();
                $favorite = $user->isCarFavorite($car) ;
                $postWithImage = [
                    'post' => $car,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $postsWithImages->push($postWithImage);
            }

                $perPage = 4;
                $currentPage = Paginator::resolveCurrentPage() ?: 1;
                $items = $postsWithImages->all();
                $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);
                $postsWithImages = new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage);

                return response()->json([
                    'All_Post' => $postsWithImages
                ]);

            } catch (\Throwable $exception) {
                return response()->json([
                    'Status' => false,
                    'Error Message' => $exception->getMessage(),
                ]);
            }
    }

}
