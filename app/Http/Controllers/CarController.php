<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{


    private function ValidateStoreCarRequest(Request $request)
    {
        return  Validator::make($request->all(), [

            'operation_type' => 'required',
            'transmission_type' =>'required',
            'brand' => 'required',
            'governorate'=>'required',
            'description'=>'required',
            'price'=>'required',
            'kilometers' =>'required',
            'year' => 'required' ,
            'image' => 'mimes:jpeg,jpg,png,gif ' ,
            'image1' => 'mimes:jpeg,jpg,png,gif ' ,
            'image2' => 'mimes:jpeg,jpg,png,gif ' ,
            'image3' => 'mimes:jpeg,jpg,png,gif ' ,
            'image4' => 'mimes:jpeg,jpg,png,gif ' ,
            'image5' => 'mimes:jpeg,jpg,png,gif ' ,
            'image6' => 'mimes:jpeg,jpg,png,gif ' ,
            'image7' => 'mimes:jpeg,jpg,png,gif ' ,
            'image8' => 'mimes:jpeg,jpg,png,gif ' ,
            'image9' => 'mimes:jpeg,jpg,png,gif '
        ]);
    }

    public function store_car(Request $request)
    {

        try
        {

            $validate = $this->ValidateStoreCarRequest($request) ;

            if ($validate->fails())
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ]);

            // Store Car In Database
            $id = Auth::id();

            $Car = \App\Models\Car::create([

                'owner_id'=> $id,
                'operation_type' => $request['operation_type'],
                'transmission_type' => $request['transmission_type'],
                'brand'=> $request['brand'],
                'secondary_brand'=> $request['secondary_brand'],
                'governorate'=> $request['governorate'],
                'locationInDamascus'=> $request['locationInDamascus'],
                'color'=> $request['color'],
                'description'=> $request['description'],
                'price'=> $request['price'],
                'kilometers'=> $request['kilometers'],
                'address'=>  $request['address'] ,
                'fuel_type' => $request['fuel_type'] ,
                'status' => $request['status'] ,
                'driving_force' => $request ['driving_force'] ,
                'year'=> $request['year']
            ]) ;


            $image = new ImageController() ;
            //Store Images
            if ($request->hasFile('image'))
            {
                $image->store_image_post($request->file('image') , $Car->id, 'car') ;
            }

            if ($request->hasFile('image1'))
            {
                $image->store_image_post($request->file('image1'), $Car->id, 'car');
            }

            if ($request->hasFile('image2'))
            {
                $image->store_image_post($request->file('image2'), $Car->id, 'car');
            }

            if ($request->hasFile('image3'))
            {
                $image->store_image_post($request->file('image3'), $Car->id, 'car');
            }
            if ($request->hasFile('image4'))
            {
                $image->store_image_post($request->file('image4') , $Car->id, 'car') ;
            }

            if ($request->hasFile('image5'))
            {
                $image->store_image_post($request->file('image5'), $Car->id, 'car');
            }

            if ($request->hasFile('image6'))
            {
                $image->store_image_post($request->file('image6'), $Car->id, 'car');
            }

            if ($request->hasFile('image7'))
            {
                $image->store_image_post($request->file('image7'), $Car->id, 'car');
            }
            if ($request->hasFile('image8'))
            {
                $image->store_image_post($request->file('image8'), $Car->id , 'car');
            }
            if ($request->hasFile('image9'))
            {
                $image->store_image_post($request->file('image9'), $Car->id , 'car');
            }

            return response() ->json([
                'Status' => true ,
                'Car'=> $Car,
                'images' => $Car->images()->get()
            ],201) ;



        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }

    }


    public function GetCarsWithImages($posts)
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

    public function Get_User_Cars($id)
    {
        try
        {
            $owner = User::find($id);

            if ($owner)
            {
                return response()->json([
                    'posts' => $this->GetCarsWithImages($owner->cars()->get())
                ]);
            }else {
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }


    public function get_car($car_id)
    {
        try{
            $user = Auth::user();
            $car = Car::find($car_id);
            if ($car)
            {
                $car_image = $car->images()->get() ;
                $car_owner = $car->owner()->get() ;
                $favorite = $user->isCarFavorite($car);
                return \response()->json([
                    'Status' => true,
                    'Car : ' => $car,
                    'images : ' => $car_image,
                    'Owner : ' => $car_owner[0],
                    'favorite' => $favorite
                ]);
            }else {
                return \response()->json([
                    'Status' => false,
                    'Car : ' => "Car Not exist"
                ]);
            }
        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }

    public function remove_car_post($post_id)
    {
        try
        {
            $post = Car::find($post_id) ;
            if(!$post)
            {
                return response()->json([
                    'Message' => 'Post Not Exist'
                ]) ;
            }

            if ($post->owner_id == Auth::id() || \auth()->user()->role == 'admin')
            {
                $post->delete() ;

                return response()->json([
                    'Status' => true ,
                    'Message' => 'Deleted Successfully'
                ]) ;
            }else
            {
                return response()->json([
                    'Status' => false,
                    'Message' => 'Denied'
                ]);
            }

        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }




}
