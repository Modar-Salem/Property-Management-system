<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PostsController extends Controller
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




    private function ValidateStoreEstateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'operation_type' => 'required',
            'governorate'=>'required',
            'description'=>'required',
            'price'=>'required',
            'space' => 'required' ,
            'estate_type' => 'required' ,
            'image' => 'mimes:jpeg,jpg,png,gif ',
            'image1' => 'mimes:jpeg,jpg,png,gif' ,
            'image2' => 'mimes:jpeg,jpg,png,gif'  ,
            'image3' => 'mimes:jpeg,jpg,png,gif' ,
            'image4' => 'mimes:jpeg,jpg,png,gif' ,
            'image5' => 'mimes:jpeg,jpg,png,gif' ,
            'image6' => 'mimes:jpeg,jpg,png,gif' ,
            'image7' => 'mimes:jpeg,jpg,png,gif' ,
            'image8' => 'mimes:jpeg,jpg,png,gif' ,
            'image9' => 'mimes:jpeg,jpg,png,gif'

        ]);
    }

    public function store_estate(Request $request)
    {

        try
        {
            //Validate
            try
            {
                $validate = $this->ValidateStoreEstateRequest($request) ;
                if ($validate->fails())
                    return response()->json([
                        'Status' => false,
                        'Validation Error' => $validate->errors()
                    ]);
            }
            catch (\Exception $exception ) {
                return response()->json([
                    'Status' => false ,
                    'Message' => $exception->getMessage()
                ]) ;
            }
            // create product
            try
            {

                $id = Auth::id();

                $estate = \App\Models\Estate::create([
                    'owner_id'=> $id,
                    'operation_type' => $request['operation_type'],
                    'governorate'=> $request['governorate'],
                    'locationInDamascus'=> $request['locationInDamascus'],
                    'description'=> $request['description'],
                    'price'=> $request['price'],
                    'space' => $request['space'] ,
                    'estate_type' => $request['estate_type'] ,
                    'beds' => $request['beds'] ,
                    'level' => $request['level'] ,
                    'baths' => $request ['baths'] ,
                    'garage' => $request ['garage'] ,
                    'status' => $request ['status'] ,
                    'address' => $request['address']
                ]) ;

                $image= new ImageController() ;
                if ($request->hasFile('image'))
                {
                    $image->store_image_post($request->file('image') , $estate->id , 'estate') ;
                }

                if ($request->hasFile('image1'))
                {
                    $image->store_image_post($request->file('image1'), $estate->id , 'estate');
                }

                if ($request->hasFile('image2'))
                {
                    $image->store_image_post($request->file('image2'), $estate->id , 'estate');
                }

                if ($request->hasFile('image3'))
                {
                    $image->store_image_post($request->file('image3'), $estate->id , 'estate');
                }
                if ($request->hasFile('image4'))
                {
                    $image->store_image_post($request->file('image4') , $estate->id , 'estate') ;
                }

                if ($request->hasFile('image5'))
                {
                    $image->store_image_post($request->file('image5'), $estate->id , 'estate');
                }

                if ($request->hasFile('image6'))
                {
                    $image->store_image_post($request->file('image6'), $estate->id , 'estate');
                }

                if ($request->hasFile('image7'))
                {
                    $image->store_image_post($request->file('image7'), $estate->id , 'estate');
                }
                if ($request->hasFile('image8'))
                {
                    $image->store_image_post($request->file('image8'), $estate->id , 'estate');
                }

                if ($request->hasFile('image9'))
                {
                    $image->store_image_post($request->file('image9'), $estate->id , 'estate');
                }


                return response() ->json([
                    'Status' => true ,
                    'estate'=> $estate,
                    'images' => $estate->images()->get()
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

    public function GetEstateWithImages($posts)
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

    public function Get_User_Estates($id)
    {
        try{
            $owner = User::find($id);

            if ($owner) {
                return response()->json([
                    'posts' => $this->GetEstateWithImages($owner->estates()->get())
                ]);
            }else
            {
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
        }catch (\Throwable $exception)
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
        }catch (\Throwable $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }

    public function get_estate($estate_id)
    {
        try
        {
            $user = Auth::user();
            $estate = Estate::find($estate_id);
            if ($estate) {
                $favorite = $user->isEstateFavorite($estate);
                $estateimage = $estate->images()->get() ;
                $estateowner = $estate->owner()->get() ;

                return response()->json([
                    'Status' => true,
                    'Estate' => $estate,
                    'images' => $estateimage,
                    'owner' => $estateowner[0],
                    'favorite' => $favorite
                ]);
            } else {
                return response()->json([
                    'Status' => false,
                    'Estate' => "Estate does not exist"
                ]);
            }
     }catch (\Throwable $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }

    }




    public function update_post(Request $request)
    {
        try{
            $user_id = Auth::id();
            if ($request['type'] == 'car') {
                $car = Car::find($request['id']);
                if ($car) {
                    if ($car['owner_id'] == $user_id)
                        $car->update($request->all());
                    else
                        return response()->json([
                            'Message' => 'Access denied'
                        ]);
                } else
                    return response()->json([
                        'Message' => 'Car Not Exist'
                    ]);

            } elseif ($request['type'] == 'estate') {
                $estate = Estate::find($request['id']);
                if ($estate) {
                    if ($estate['owner_id'] == $user_id)
                        $estate->update($request->all());
                    else
                        return response()->json([
                            'Message' => 'Access denied'
                        ]);
                } else
                    return response()->json([
                        'Message' => 'Estate Not Exist'
                    ]);

            }
            return null;
        }catch(\Exception $exception)
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


    public function remove_estate_post($post_id)
    {
        try{
            $post = Estate::find($post_id);
            if (!$post)
            {
                return response()->json([
                    'Message' => 'Post Not Exist'
                ]);
            }


            if ($post->owner_id == Auth::id() || \auth()->user()->role == 'admin')
            {
                $post->delete();

                return response()->json([
                    'Status' => true,
                    'Message' => 'Deleted Successfully'
                ]);
            }else
            {
                return response()->json([
                    'Status' => false,
                    'Message' => 'Denied'
                ]);
            }

        }catch (\Throwable $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }


}