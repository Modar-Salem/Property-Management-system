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
                ->select('estates.location')
                ->get();

            $relatedPosts = collect([]);
            foreach ($favorites_location as $favorite) {
                $relatedPosts = $relatedPosts->merge(Estate::where('location', $favorite->location)->get());
            }

            $paginatedRelatedPosts = $this->paginate($relatedPosts, 4);

            $Top_Rating_Posts = DB::table('rates')
                ->join('estates', 'rates.estate_id', '=', 'estates.id')
                ->where('rates.property_type', '=', 'estate')
                ->select('estate_id', DB::raw('AVG(rate) as average_rate'))
                ->groupBy('estate_id')
                ->orderByDesc('average_rate')
                ->get();

            $paginatedTopRatingPosts = $this->paginate($Top_Rating_Posts, 4);

            return response()->json([
                'related posts' => $paginatedRelatedPosts->toArray(),
                'Top Rating' => $paginatedTopRatingPosts->toArray()
            ], 201);
        } catch (\Throwable $exception) {
            return response()->json([
                'Status' => false,
                'Error Message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function Car_Home()
    {
        try {
            $user_id = Auth::id();
            $favorites_location = DB::table('favorites')
                ->join('cars', 'favorites.car_id', '=', 'cars.id')
                ->where('favorites.user_id', '=', $user_id)
                ->select('cars.location')
                ->get();

            $relatedPosts = collect([]);
            foreach ($favorites_location as $favorite) {
                $relatedPosts[] = Car::where('location', $favorite['location'])->get();
            }
            $paginatedRelatedPosts = $this->paginate($relatedPosts, 4)->toArray();

            $Top_Rating_Posts = DB::table('rates')
                ->join('cars', 'rates.car_id', '=', 'cars.id')
                ->where('rates.property_type', '=', 'car')
                ->select('car_id', DB::raw('AVG(rate) as average_rate'))
                ->groupBy('car_id')
                ->orderByDesc('average_rate')
                ->get();

            $paginatedTopRatingPosts = $this->paginate($Top_Rating_Posts, 4)->toArray();

            return response()->json([
                'related Products' => $paginatedRelatedPosts,
                'Top Rating' => $paginatedTopRatingPosts
            ], 201);

        } catch (\Throwable $exception) {
            return response()->json([
                'Status' => false,
                'Error Message' => $exception->getMessage(),
            ], 500);
        }
    }
}
