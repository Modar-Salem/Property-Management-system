<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use App\Models\Image;
use App\Models\User;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class Posts extends Controller
{
    public function store_car(Request $request)
    {

        try
        {
            try
            {

                $validate = Validator::make($request->all(), [

                    'operation_type' => 'required',
                    'transmission_type' =>'required',
                    'brand' => 'required',
                    'governorate'=>'required',
                    'description'=>'required',
                    'price'=>'required',
                    'year'=>'required | min:1940 |max : 2023 | numeric ',
                    'kilometers' =>'required',
                    'image' => 'mimes:jpeg,jpg,png,gif' ,
                    'image1' => 'mimes:jpeg,jpg,png,gif' ,
                    'image2' => 'mimes:jpeg,jpg,png,gif' ,
                    'image3' => 'mimes:jpeg,jpg,png,gif' ,

                ]);
                if ($validate->fails())
                    return response()->json([
                        'Status' => false,
                        'Validation Error' => $validate->errors()
                    ], 401);
            }
            catch (\Exception $exception ) {
                return response()->json([
                    'Status' => false ,
                    'Message' => $exception->getMessage()
                ], 401 ) ;
            }
            // create product

            try
            {

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
                    'year'=> $request['year'],
                    'kilometers'=> $request['kilometers'],
                    'address'=>  $request['address'] ,
                    'fuel_type' => $request['fuel_type'] ,
                    'status' => $request['status'] ,
                    'driving_force' => $request ['driving_force']
                ]) ;


                if ($request->hasFile('image'))
                {
                    $this->store_image_cars($request->file('image') , $Car->id) ;
                }

                if ($request->hasFile('image1'))
                {
                    $this->store_image_cars($request->file('image1'), $Car->id);
                }

                if ($request->hasFile('image2'))
                {
                    $this->store_image_cars($request->file('image2'), $Car->id);
                }

                if ($request->hasFile('image3'))
                {
                    $this->store_image_cars($request->file('image3'), $Car->id);
                }
                if ($request->hasFile('image4'))
                {
                    $this->store_image_cars($request->file('image4') , $Car->id) ;
                }

                if ($request->hasFile('image5'))
                {
                    $this->store_image_cars($request->file('image5'), $Car->id);
                }

                if ($request->hasFile('image6'))
                {
                    $this->store_image_cars($request->file('image6'), $Car->id);
                }

                if ($request->hasFile('image7'))
                {
                    $this->store_image_cars($request->file('image7'), $Car->id);
                }
                if ($request->hasFile('image8'))
                {
                    $this->store_image_cars($request->file('image8'), $Car->id);
                }

                if ($request->hasFile('image9'))
                {
                    $this->store_image_cars($request->file('image9'), $Car->id);
                }

                return response() ->json([
                    'Status' => true ,
                    'Car'=> $Car,
                    'Images' => $Car->images()->get()
                ],201) ;

            }
            catch (\Exception $exception)
            {
                return response()->json([
                    'Status' => false ,
                    'Error  '=> $exception->getMessage()
                ]) ;
            }

        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status' => true ,
                'Message' => $exception->getMessage()
            ], 401) ;
        }

    }

    public function store_image_cars($image , $car_id)
    {
        //validate Image
        try
        {
            $path = Null;

            //Get FileName with extension
            $filenameWithExt = $image->getClientOriginalName();

            //Get FileName without Extension
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //Get Extension
            $Extension = $image->getClientOriginalExtension();

            //New_File_Name
            $NewfileName = $filename . '_' . time() . '_ .' . $Extension;

            //Upload Image
            $path = $image->storeAs('images', $NewfileName, 'public');


            \App\Models\Image::create([
                'name'=>URL::asset('storage/' . $path) ,
                'car_id'=>$car_id ,
                'property_type' => 'car'
            ]) ;


        } catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }
    }




    public function store_estate(Request $request)
    {

        try
        {
            //Validate
            try
            {

                $validate = Validator::make($request->all(), [
                    'operation_type' => 'required',
                    'location'=>'required',
                    'description'=>'required',
                    'price'=>'required',
                    'space' => 'required' ,
                    'estate_type' => 'required' ,
                    'image' => 'mimes:jpeg,jpg,png,gif' ,
                    'image1' => 'mimes:jpeg,jpg,png,gif' ,
                    'image2' => 'mimes:jpeg,jpg,png,gif' ,
                    'image3' => 'mimes:jpeg,jpg,png,gif' ,
                    'image4' => 'mimes:jpeg,jpg,png,gif' ,
                    'image5' => 'mimes:jpeg,jpg,png,gif' ,
                    'image6' => 'mimes:jpeg,jpg,png,gif' ,
                    'image7' => 'mimes:jpeg,jpg,png,gif' ,
                    'image8' => 'mimes:jpeg,jpg,png,gif' ,
                    'image9' => 'mimes:jpeg,jpg,png,gif' ,
                    'locationInDamascus'
                ]);
                if ($validate->fails())
                    return response()->json([
                        'Status' => false,
                        'Validation Error' => $validate->errors()
                    ], 401);
            }
            catch (\Exception $exception ) {
                return response()->json([
                    'Status' => false ,
                    'Message' => $exception->getMessage()
                ], 401 ) ;
            }
            // create product

            try
            {

                $id = Auth::id();

                $estate = \App\Models\Estate::create([
                    'owner_id'=> $id,
                    'operation_type' => $request['operation_type'],
                    'location'=> $request['location'],
                    'locationInDamascus'=> $request['locationInDamascus'],
                    'description'=> $request['description'],
                    'price'=> $request['price'],
                    'space' => $request['space'] ,
                    'estate_type' => $request['estate_type'] ,
                    'beds' => $request['beds'] ,
                    'level' => $request['level'] ,
                    'baths' => $request ['baths'] ,
                    'garage' => $request ['garage'] ,
                    'status' => $request ['status']
                ]) ;

                if ($request->hasFile('image'))
                {
                    $this->store_image_estate($request->file('image') , $estate->id) ;
                }

                if ($request->hasFile('image1'))
                {
                    $this->store_image_estate($request->file('image1'), $estate->id);
                }

                if ($request->hasFile('image2'))
                {
                    $this->store_image_estate($request->file('image2'), $estate->id);
                }

                if ($request->hasFile('image3'))
                {
                    $this->store_image_estate($request->file('image3'), $estate->id);
                }
                if ($request->hasFile('image4'))
                {
                    $this->store_image_estate($request->file('image4') , $estate->id) ;
                }

                if ($request->hasFile('image5'))
                {
                    $this->store_image_estate($request->file('image5'), $estate->id);
                }

                if ($request->hasFile('image6'))
                {
                    $this->store_image_estate($request->file('image6'), $estate->id);
                }

                if ($request->hasFile('image7'))
                {
                    $this->store_image_estate($request->file('image7'), $estate->id);
                }
                if ($request->hasFile('image8'))
                {
                    $this->store_image_estate($request->file('image8'), $estate->id);
                }

                if ($request->hasFile('image9'))
                {
                    $this->store_image_estate($request->file('image9'), $estate->id);
                }


                return response() ->json([
                    'Status' => true ,
                    'estate'=> $estate,
                    'Images' => $estate->images()->get()
                ],201) ;

            }
            catch (\Exception $exception)
            {
                return response()->json([
                    'Status' => false ,
                    'Error'=> $exception->getMessage()
                ]) ;
            }

        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status' => true ,
                'Message' => $exception->getMessage()
            ], 401) ;
        }

    }

    public function store_image_estate($image , $estate_id)
    {
        //validate Image
        try
        {
            $path = Null;

            //Get FileName with extension
            $filenameWithExt = $image->getClientOriginalName();

            //Get FileName without Extension
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //Get Extension
            $Extension = $image->getClientOriginalExtension();

            //New_File_Name
            $NewfileName = $filename . '_' . time() . '_ .' . $Extension;

            //Upload Image
            $path = $image->storeAs('images', $NewfileName, 'public');


            //create Object in Database
            \App\Models\Image::create([
                'name'=>URL::asset('storage/' . $path),
                'estate_id' => $estate_id,
                'property_type' => 'estate'
            ]);


        } catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }
    }

    public function Get_User_Cars($id)
    {
        $user= User::find($id) ;
        if ($user)
        {
            $WithImage = [] ;
            foreach ($user->cars()->get() as $cars)
            {
                $car = Car::find($cars['id']) ;
                $image = $car->images()->get() ;
                array_push($WithImage , $image);
            }

            return response()->json([
                'Post'=> $user->cars()->get(),
                'Image' =>  $WithImage
            ]);

        }
        else
            return response()->json([
                'Message' => 'User Not Exist'
            ]) ;
    }

    public function Get_User_Estates($id)
    {
        $user= User::find($id) ;

        if ($user)
        {
            $WithImage = [] ;
            foreach ($user->estates()->get() as $estates)
            {
                $estate = Estate::find($estates['id']) ;
                $image = $estate->images()->get() ;
                array_push($WithImage , $image);
            }
            return response()->json([
                'Post' => $user->estates()->get() ,
                'Image' =>  $WithImage
            ]);
        }
        else
            return response()->json([
                'Message' => 'User Not Exist'
            ]) ;
    }

    public function remove_estate_posts($post_id)
    {

        $post = Estate::find($post_id) ;
        if(!$post)
        {
            return response()->json([
                'Message' => 'Post Not Exist'
            ]) ;
        }
        $owner_id  = $post['owner_id'] ;
        try
        {
            if ($owner_id == Auth::id() or \auth()->user()->role = 'admin')
            {
                $post->delete() ;

                return response()->json([
                    'Status' => true ,
                    'Message' => 'Deleted Successfully'
                ]) ;

            }
        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Error' => $exception->getMessage()
            ]) ;
        }
    }

    public function remove_car_posts($post_id)
    {

        $post = Car::find($post_id) ;
        if(!$post)
        {
            return response()->json([
                'Message' => 'Post Not Exist'
            ]) ;
        }
        $owner_id  = $post['owner_id'] ;
        try
        {
            if ($owner_id == Auth::id() or \auth()->user()->role = 'admin')
            {
                $post->delete() ;

                return response()->json([
                    'Status' => true ,
                    'Message' => 'Deleted Successfully'
                ]) ;
            }
        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Error' => $exception->getMessage()
            ]) ;
        }
    }

    public function get_car($car_id)
    {
        $car = Car::find($car_id) ;
        if($car)
        {
            return \response()->json([
                'Status' => true ,
                'Car : ' => $car ,
                'Images : ' => $car->images
            ]) ;
        }else
        {
            return \response()->json([
                'Status' => false ,
                'Car : ' => "Car Not exist"
            ]) ;
        }
    }

    public function get_estate($estate_id)
    {
        $estate = Estate::find($estate_id) ;
        if($estate)
        {
            return \response()->json([
                'Status' => true ,
                'Estate : ' => $estate ,
                'Images' => $estate->images
            ]) ;
        }else
        {
            return \response()->json([
                'Status' => false ,
                'Estate : ' => "Estate Not exist"
            ]) ;
        }
    }


}
