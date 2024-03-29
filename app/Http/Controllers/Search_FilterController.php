<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFilter\FilterRequest;
use App\Http\Requests\SearchFilter\SearchRequest;
use App\Models\Car;
use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Search_FilterController extends Controller
{

    public function Filter(FilterRequest $request)
    {
        try {
            $user = Auth::user() ;
            if ($request['type'] == 'estate') {

                $post = Estate::query() ;
                if ($request['estate_type'] != null)
                    $post = $post->where('estate_type', $request['estate_type']);

                if ($request['operation_type'] != null)
                    $post = $post->where('operation_type', $request['operation_type']);

                if ($request['governorate'] != null)
                    $post = $post->where('governorate', $request['governorate']);

                if ($request['locationInDamascus'] != null)
                    $post = $post->where('locationInDamascus', $request['locationInDamascus']);

                if ($request['max_price'] != null)
                    $post = $post->where('price', '<', $request['max_price']);

                if ($request['min_price'] != null)
                    $post = $post->where('price', '>', $request['min_price']);

                if ($request['max_space'] != null)
                    $post = $post->where('space', '<', $request['max_space']);

                if ($request['min_space'] != null)
                    $post = $post->where('space', '>', $request['max_space']);

                if ($request['max_level'] != null)
                    $post = $post->where('level', '<', $request['max_level']);

                if ($request['min_level'] != null)
                    $post = $post->where('level', '>', $request['min_level']);

                if ($request['status'] != null)
                    $post = $post->where('status', $request['status']);

                if ($post) {
                    $estates = $post->get() ;
                    $post = $estates->load('images' , 'owner') ;

                    $post->each(function ($estate) use ($user) {
                        $estate->is_favorite = $user->isEstateFavorite($estate);
                    });

                    return response()->json([
                        'Status' => true,
                        'Posts' => $post
                    ], 201);

                }
            }


            if ($request['type'] == 'car') {
                $post = \App\Models\Car::query();

                if ($request['operation_type'] != null)
                    $post = $post->where('operation_type', $request['operation_type']);

                if ($request['transmission_type'] != null)
                    $post = $post->where('transmission_type', $request['transmission_type']);

                if ($request['fuel_type'] != null)
                    $post = $post->where('fuel_type', $request['fuel_type']);

                if ($request['status'] != null)
                    $post = $post->where('status', $request['status']);

                if ($request['driving_force'] != null)
                    $post = $post->where('driving_force', $request['driving_force']);

                if ($request['brand'] != null)
                    $post = $post->where('brand', $request['brand']);

                if ($request['secondary_brand'] != null)
                    $post = $post->where('secondary_brand', $request['secondary_brand']);

                if ($request['color'] != null)
                    $post = $post->where('color', $request['color']);

                if ($request['governorate'] != null)
                    $post = $post->where('governorate', $request['governorate']);

                if ($request['locationInDamascus'] != null)
                    $post = $post->where('locationInDamascus', $request['locationInDamascus']);

                if ($request['max_year'] != null)
                    $post = $post->where('year', '<', $request['max_year']);

                if ($request['min_year'] != null)
                    $post = $post->where('year', '>', $request['min_year']);

                if ($request['max_price'] != null)
                    $post = $post->where('price', '<', $request['max_price']);

                if ($request['min_price'] != null)
                    $post = $post->where('price', '>', $request['min_price']);

                if ($request['max_kilometers'] != null)
                    $post = $post->where('kilometers', '<', $request['max_kilometers']);

                if ($post) {
                    $cars = $post->get() ;
                    $post = $cars->load('images' , 'owner') ;

                    $post->each(function ($car) use ($user) {
                        $car->is_favorite = $user->isCarFavorite($car);
                    });

                    return response()->json([
                        'Status' => true,
                        'Posts' => $post
                    ], 201);

                }

            }
        } catch (\Exception $exception) {

            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }

    }



    public function Search(SearchRequest $request)
    {
        try
        {
            $user= Auth::user() ;
            if ($request['type'] == 'car' )
            {
                $posts = Car::with('images' , 'owner')
                    ->where('description', 'like', '%' . $request['description'] . '%')->orWhere('address', 'like', '%' . $request['description'] . '%')->get();
                if ($posts)
                {

                    $posts->each(function ($car) use ($user) {
                        $car->is_favorite = $user->isCarFavorite($car);
                    });

                    return response()->json([
                        'Status' => true,
                        'Posts' => $posts
                    ], 201);
                }
            }
            if ($request['type'] == 'estate' )
            {
                $posts = Estate::with('images' , 'owner')->where('description', 'like', '%' . $request['description'] . '%')->orWhere('address', 'like', '%' . $request['description'] . '%')->get();
                if ($posts)
                {
                    $posts->each(function ($estate) use ($user) {
                        $estate->is_favorite = $user->isEstateFavorite($estate);
                    });

                    return response()->json([
                        'Status'=>true ,
                        'Posts'=> $posts
                    ],201) ;
                }
            }
        }catch (\Exception $exception){

            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }

}
