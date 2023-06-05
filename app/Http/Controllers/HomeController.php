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

    public function paginate($items, $perPage = 4, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage;
        $itemstoshow = collect($items)->slice($offset, $perPage)->all();
        return new LengthAwarePaginator($itemstoshow, $total, $perPage);
    }

    public function Estate_Home()
    {
        try {
            $user_id = Auth::id();

            $favorites_location = DB::table('favorites')
                ->join('estates', 'favorites.estate_id', '=', 'estates.id')
                ->where('favorites.user_id', '=', $user_id)
                ->select('estates.governorate')
                ->get() ;


            $postsWithImages = [];
            $relatedPosts = collect([]);

            foreach ($favorites_location as $favorite) {
                $relatedPosts [] = Estate::where('governorate', $favorite->location)->get() ;
                foreach ($relatedPosts as $estate) {
                    $images = $estate->images()->get();
                    $postWithImage = [
                        'post' => $estate,
                        'images' => $images
                    ];
                    array_push($postsWithImages, $postWithImage);
                }
            }

            $paginatedRelatedPosts = $this->paginate($postsWithImages, 4)->toArray();

            $Top_Rating_Posts = DB::table('rates')
                ->join('estates', 'rates.estate_id', '=', 'estates.id')
                ->where('rates.property_type', '=', 'estate')
                ->select('estate_id', DB::raw('AVG(rate) as average_rate'))
                ->groupBy('estate_id')
                ->orderByDesc('average_rate')
                ->get() ;

            $postsWithImages2 = [] ;
            foreach ($Top_Rating_Posts as $estate) {
                $images = $estate->images()->get();
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images
                ];
                array_push($postsWithImages2, $postWithImage);
            }

            $paginatedTopRatingPosts = $this->paginate($postsWithImages2, 4)->toArray();


            $All_Estate = Estate::all() ;

            $postsWithImages3 = [] ;
            foreach ($All_Estate as $estate) {
                $images = $estate->images()->get();
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images
                ];
                array_push($postsWithImages3, $postWithImage);
            }

            $paginate_all = $this->paginate($postsWithImages3 , 4)->toArray() ;

            return response()->json([
                'related posts' => $paginatedRelatedPosts,
                'Top Rating' => $paginatedTopRatingPosts ,
                 'AllPost' => $paginate_all
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
        try
        {
            $user_id = Auth::id();
            $favorites_location = DB::table('favorites')
                ->join('cars', 'favorites.car_id', '=', 'cars.id')
                ->where('favorites.user_id', '=', $user_id)
                ->select('cars.governorate')
                ->get();

            $relatedPosts = collect([]);
            $postsWithImages1 = [];

            foreach ($favorites_location as $favorite) {
                $relatedPosts[] = Car::where('governorate', $favorite['governorate'])->get();
                foreach ($relatedPosts as $car) {
                    $images = $car->images()->get();
                    $postWithImage = [
                        'post' => $car,
                        'images' => $images
                    ];
                    array_push($postsWithImages1, $postWithImage);
                }
            }
            $paginatedRelatedPosts = $this->paginate($postsWithImages1, 4)->toArray();


            $Top_Rating_Posts = DB::table('rates')
                ->join('cars', 'rates.car_id', '=', 'cars.id')
                ->where('rates.property_type', '=', 'car')
                ->select('car_id', DB::raw('AVG(rate) as average_rate'))
                ->groupBy('car_id')
                ->orderByDesc('average_rate')
                ->get();

            $postsWithImages2 = [] ;
            foreach ($Top_Rating_Posts as $car) {
                $images = $car->images()->get();
                $postWithImage = [
                    'post' => $car,
                    'images' => $images
                ];
                array_push($postsWithImages2, $postWithImage);
            }

            $paginatedTopRatingPosts = $this->paginate($postsWithImages2, 4)->toArray();

            $All_Car = Car::all() ;

            $postsWithImages3 = [] ;
            foreach ($All_Car as $car) {
                $images = $car->images()->get();
                $postWithImage = [
                    'post' => $car,
                    'images' => $images
                ];
                array_push($postsWithImages3, $postWithImage);
            }

            $paginate_all = $this->paginate($postsWithImages3 , 4)->toArray() ;

            return response()->json([
                'related Products' => $paginatedRelatedPosts,
                'Top Rating' => $paginatedTopRatingPosts ,
                'All_Post' => $paginate_all
            ]);

        } catch (\Throwable $exception) {
            return response()->json([
                'Status' => false,
                'Error Message' => $exception->getMessage(),
            ]) ;
        }
    }

}
