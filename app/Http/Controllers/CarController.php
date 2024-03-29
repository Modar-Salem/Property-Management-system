<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{


    public function store_car(Request $request)
    {

        try
        {

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



    public function Get_User_Cars($id)
    {
        try
        {
            $user = Auth::user() ;
            $owner_cars = User::with(['cars.images'])->find($id);

            if ($owner_cars)
            {
                // Process the retrieved data to add the 'is_favorite' field to each car
                $owner_cars->cars->each(function ($car) use ($user) {
                    $car->is_favorite = $user->isCarFavorite($car);
                });
                return response()->json([
                    'posts' => $owner_cars
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
            $user = Auth::user() ;
            $car = Car::with(['images' , 'owner'])
                ->find($car_id);
            if ($car)
            {

                $car->isFavorite = $user->isCarFavorite($car);
                return \response()->json([
                    'Status' => true,
                    'Car : ' => $car,
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
