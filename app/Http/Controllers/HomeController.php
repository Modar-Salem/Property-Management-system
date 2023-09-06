<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
            $user = Auth::user() ;

            // Get the user's favorite estates with unique governorates
            $uniqueGovernorates = $user->favoriteEstates()->select('governorate')->distinct()->pluck('governorate');

            // Get the favorite estates within those unique governorates
            $favoriteEstates = Estate::whereIn('governorate', $uniqueGovernorates)->get();

            $topRatedEstates = Estate::leftJoin(DB::raw('(SELECT estate_id, AVG(rate) as average_rate FROM rates WHERE property_type = "estate" GROUP BY estate_id) as average_rates'), 'estates.id', '=', 'average_rates.estate_id')
                ->orderByDesc('average_rate')
                ->get();
            // Merge and deduplicate estates
            $estates = $favoriteEstates->concat($topRatedEstates)->unique();


            $perPage = 4;
            $currentPage = Paginator::resolveCurrentPage() ?: 1;
            $items = $estates->all();
            $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);
            $relatedPosts = new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage);

            return response()->json([
                'AllPost' => $relatedPosts
            ]);

        }catch (\Exception $exception) {
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
                $user= Auth::user() ;

                // Get the user's favorite estates with unique brands
                $uniqueBrands = $user->favoriteCar()->select('brand')->distinct()->pluck('brand');

                $favoriteCar = Car::whereIn('brand', $uniqueBrands)->get();

                $topRatedCars = Car::leftJoin(DB::raw('(SELECT car_id, AVG(rate) as average_rate FROM rates WHERE property_type = "car" GROUP BY car_id) as average_rates'), 'cars.id', '=', 'average_rates.car_id')
                ->orderByDesc('average_rate')
                ->get();

                // Merge and deduplicate estates
                $cars = $topRatedCars->concat($favoriteCar)->unique();

                $perPage = 4;
                $currentPage = Paginator::resolveCurrentPage() ?: 1;
                $items = $cars->all();
                $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);
                $relatedPosts = new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage);

                return response()->json([
                    'AllPost' => $relatedPosts
                ]);


        }catch (\Exception $exception) {
            return response()->json([
                'Status' => false,
                'Error Message' => $exception->getMessage(),
            ]);
        }

    }

}
