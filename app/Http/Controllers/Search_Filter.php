<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Search_Filter extends Controller
{
    private function GetCarsWithImages($posts)
    {
        $user = Auth::user();

        $postsWithImages = [];

        foreach ($posts as $car) {
            $images = $car->images()->get();
            $favorite = $user->isCarFavorite($car);
            $postWithImage = [
                'post' => $car,
                'images' => $images,
                'favorite' => $favorite
            ];
            array_push($postsWithImages, $postWithImage);
        }
        return $postsWithImages;

    }

    private function GetEstateWithImages($posts)
    {
        $user = Auth::user();
        $postsWithImages = [];

        foreach ($posts as $estate) {
            $images = $estate->images()->get();
            $favorite = $user->isEstateFavorite($estate);
            $postWithImage = [
                'post' => $estate,
                'images' => $images,
                'favorite' => $favorite
            ];
            array_push($postsWithImages, $postWithImage);
        }
        return $postsWithImages;
    }

    public function Filter(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'type' => 'required | in:estate,car',
            ]);
            if ($validate->fails())
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ]);

            $user = Auth::user();
            if ($request['type'] == 'estate') {

                $post = \App\Models\Estate::all();
                if ($request['estate_type'] != null)
                    $post = \App\Models\Estate::where('estate_type', $request['estate_type']);

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

                    return response()->json([
                        'Status' => true,
                        'Posts' => $this->GetEstateWithImages($post)
                    ], 201);

                }
            }


            if ($request['type'] == 'car') {
                $post = \App\Models\Car::all();

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

                    return response()->json([
                        'Status' => true,
                        'Posts' => $this->GetCarsWithImages($post)
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



    public function Search(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all(), [
                'type' => 'required | in:estate,car' ,
                'description' => 'required'
            ]);
            if ($validate->fails())
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ]);

            if ($request['type'] == 'car' )
            {
                $posts = Car::where('description', 'like', '%' . $request['description'] . '%')->orWhere('address', 'like', '%' . $request['description'] . '%')->get();
                if ($posts) {
                    return response()->json([
                        'Status' => true,
                        'Posts' => $this->GetCarsWithImages($posts)
                    ], 201);
                }
            }
            if ($request['type'] == 'estate' )
            {
                $posts = Estate::where('description', 'like', '%' . $request['description'] . '%')->orWhere('address', 'like', '%' . $request['description'] . '%')->get();
                if ($posts) {
                    return response()->json([
                        'Status'=>true ,
                        'Posts'=> $this->GetEstateWithImages($posts)
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
