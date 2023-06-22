<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Rate;
use App\Models\User;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Event\Code\Throwable;

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
                    'kilometers' =>'required',
                    'image' => 'mimes:jpeg,jpg,png,gif' ,
                    'image1' => 'mimes:jpeg,jpg,png,gif' ,
                    'image2' => 'mimes:jpeg,jpg,png,gif' ,
                    'image3' => 'mimes:jpeg,jpg,png,gif' ,
                    'image4' => 'mimes:jpeg,jpg,png,gif' ,
                    'image5' => 'mimes:jpeg,jpg,png,gif' ,
                    'image6' => 'mimes:jpeg,jpg,png,gif' ,
                    'image7' => 'mimes:jpeg,jpg,png,gif' ,
                    'image8' => 'mimes:jpeg,jpg,png,gif' ,
                    'image9' => 'mimes:jpeg,jpg,png,gif'
                ]);
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
                ] ) ;
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
                    'images' => $Car->images()->get()
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
            ]) ;
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
            $NewfileName = $filename . '_' . time() . '_.' . $Extension;

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
                    'governorate'=>'required',
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

                ]);
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
                ] ) ;
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
                    'images' => $estate->images()->get()
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
            ]) ;
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
            $NewfileName = $filename . '_' . time() . '_.' . $Extension;

            //Upload Image
            $path = $image->storeAs('images', $NewfileName , 'public');


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
        $user = User::find($id);

        if ($user) {
            $postsWithImages = [];
            foreach ($user->cars()->get() as $car) {
                $images = $car->images()->get();
                $favorite = $user->isCarFavorite($car) ;
                $postWithImage = [
                    'post' => $car,
                    'images' => $images,
                    'Favorite' => $favorite
                ];
                array_push($postsWithImages, $postWithImage);
            }
            return response()->json([
                'posts' => $postsWithImages
            ]);
        } else {
            return response()->json([
                'message' => 'User not found'
            ]);
        }
    }

    public function Get_User_Estates($id)
    {
        $user = User::find($id);

        if ($user) {
            $postsWithImages = [];
            foreach ($user->estates()->get() as $estate) {
                $images = $estate->images()->get();
                $favorite = $user->isEstateFavorite($estate) ;
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images,
                    'Favorite' => $favorite
                ];
                array_push($postsWithImages, $postWithImage);
            }
            return response()->json([
                'posts' => $postsWithImages
            ]);
        } else
        {
            return response()->json([
                'message' => 'User not found'
            ]);
        }
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
        $user = Auth::user() ;
        $car = Car::find($car_id) ;
        if($car)
        {
            $favorite = $user->isCarFavorite($car) ;
            return \response()->json([
                'Status' => true ,
                'Car : ' => $car ,
                'images : ' => $car->images ,
                'Owner : ' => $car->owner ,
                'Favorite' => $favorite
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
        $user = Auth::user() ;
        $estate = Estate::find($estate_id) ;
        if($estate)
        {
            $favorite = $user->isEstateFavorite($estate) ;
            return \response()->json([
                'Status' => true ,
                'Estate : ' => $estate ,
                'images' => $estate->images ,
                'Owner : ' => $estate->owner ,
                'Favorite' => $favorite
            ]) ;
        }else
        {
            return \response()->json([
                'Status' => false ,
                'Estate : ' => "Estate Not exist"
            ]) ;
        }
    }

    /**
     * give a rate to the product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Rate(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all() ,
                [
                    'rate' => 'Required | min :1 | max :5 |integer ',
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }

            $user_id = Auth::id() ;
            if($request['type'] == 'estate')
            {
                $estate = Estate::find($request['estate_id']) ;

                if($estate)
                    Rate::updateOrCreate([
                        'user_id' => $user_id
                    ],
                        [
                            'rate' => $request['rate'],
                            'estate_id' => $request['estate_id'],
                            'property_type' => 'estate'
                        ]);
                else
                    return response()->json([
                        'Message' => 'Estate Not Exist'
                    ]) ;
            }
            else if($request['type'] == 'car') {
                $car = Car::find($request['car_id']) ;
                if($car)
                    Rate::updateOrCreate([
                    'user_id' => $user_id
                   ],
                    [
                        'rate' => $request['rate'],
                        'car_id' => $request['car_id'],
                        'property_type' => 'car'
                    ]);
                else
                    return response()->json([
                        'Message' => 'Car Not Exist'
                    ]) ;
            }
            return response()->json([
                'status' => true ,
                'Message' => 'rated Successfully'
            ]) ;


        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }


    }

    /**
     * give a rate to the product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Get_Rate(Request $request){
        try
        {
            $sum = 0  ;
            $count = 0 ;

            if($request['type'] == 'estate')
            {
                $count = Rate::where('estate_id', '=', $request['estate_id'])->count() ;
                $sum = Rate::where('estate_id', '=', $request['estate_id'])->sum('rate') ;
            }

            else if($request['type'] == 'car')
            {
                $count = Rate::where('car_id', '=', $request['car_id'])->count() ;
                $sum = Rate::where('car_id', '=', $request['car_id'])->sum('rate') ;
            }

            return response()->json([
                'Status' => true ,
                'rate' => $sum/$count
            ]) ;


        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;

        }
    }



    public function Get_All_Favorite(Request $request)
    {
        try
        {
            $user_id = Auth::id() ;
            $user = Auth::user() ;

            if($request['type'] == 'estate')
            {

                $Estates = Estate::join('favorites', 'estates.id', '=', 'favorites.estate_id')
                    ->where('favorites.user_id', '=', $user_id)
                    ->get();

                $estatesWithImages = collect();

                foreach ($Estates as $estate) {
                    $images = $estate->images()->get();

                    $estate1 = Estate::find($estate['estate_id']) ;
                    $favorite = $user->isEstateFavorite($estate1) ;

                    $postWithImage = [
                        'post' => $estate,
                        'images' => $images ,
                        'Favorite' => $favorite
                    ];
                    $estatesWithImages->push($postWithImage);
                }

                return response()->json([
                    'favorite_estates : ' => $estatesWithImages
                ]) ;

            }else if($request['type'] == 'car')
            {
                $Cars = Car::join('favorites', 'cars.id', '=', 'favorites.car_id')
                    ->where('favorites.user_id', '=', $user_id)
                    ->get();

                $CarsWithImages = collect();

                foreach ($Cars as $car) {
                    $images = $car->images()->get();

                    $car1 = Car::find($car['car_id']) ;
                    $favorite = $user->isCarFavorite($car1) ;

                    $postWithImage = [
                        'post' => $car1,
                        'images' => $images ,
                        'Favorite' => $favorite
                    ];

                    $CarsWithImages->push($postWithImage);
                }

                return response()->json([
                    'favorite_cars : ' => $CarsWithImages
                ]) ;

            }else if($request['type'] == 'all')
            {
                $Estates = Estate::join('favorites', 'estates.id', '=', 'favorites.estate_id')
                    ->where('favorites.user_id', '=', $user_id)
                    ->get();

                $Cars = Car::join('favorites', 'cars.id', '=', 'favorites.car_id')
                    ->where('favorites.user_id', '=', $user_id)
                    ->get();

                $estatesWithImages = collect();

                foreach ($Estates as $estate) {
                    $images = $estate->images()->get();
                    $estate1 = Estate::find($estate['estate_id']) ;
                    $favorite = $user->isEstateFavorite($estate1) ;
                    $postWithImage = [
                        'post' => $estate1,
                        'images' => $images ,
                        'Favorite' => $favorite
                    ];

                    $estatesWithImages->push($postWithImage);
                }

                $CarsWithImages = collect() ;
                foreach ($Cars as $car) {
                    $images = $car->images()->get();
                    $car1 = Car::find($car['car_id']) ;
                    $favorite = $user->isCarFavorite($car1) ;
                    $postWithImage = [
                        'post' => $car1,
                        'images' => $images ,
                        'Favorite' => $favorite
                    ];
                    $CarsWithImages->push($postWithImage);

                }

                return response()->json([
                    'favorites Estate: ' => $estatesWithImages ,
                    'favorites Car: ' => $CarsWithImages ,
                ]) ;

            }


        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status'=>false ,
                'Message'=>$exception->getMessage()
            ]) ;
        }
    }

    /**
     * Add the specified resource to favorite.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Add_To_Favorite(Request $request)
    {
        try
        {
            $user_id = Auth::id() ;

            if($request['type'] == 'estate') {
                $estate = Estate::find($request['id']) ;
                if($estate)
                    $favorite = \App\Models\Favorite::where('user_id', $user_id)->where('estate_id', '=', $request['id']);
                else
                    return response()->json([
                        'Message' => 'Estate Not Exist'
                    ]) ;
            }
            else if($request['type'] == 'car')
            {
                $car = Car::find($request['id']) ;
                if($car)
                    $favorite = \App\Models\Favorite::where('user_id', $user_id)->where('car_id', '=', $request['id']);
                else
                    return response()->json([
                        'Message' => 'Car Not Exist'
                    ]) ;
            }

            if ($favorite->first())
            {
                $favorite->delete() ;
                return response()->json([
                    'Status'=>true ,
                    'Message' => 'product un favorite'
                ]) ;
            }
            else
            {
                $user_id = Auth::id();

                if($request['type'] == 'estate')
                    \App\Models\Favorite::create([
                        'estate_id' => $request['id'],
                        'user_id' => $user_id ,
                        'property_type' => 'estate'
                    ]);

                if($request['type'] == 'car')
                    \App\Models\Favorite::create([
                        'car_id' => $request['id'],
                        'user_id' => $user_id ,
                        'property_type' => 'car'
                    ]);

                return response()->json([
                    'Status' => true,
                    'Message' => 'product favorite'
                ]);


            }
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'Status'=>false ,
                'Message'=>$exception->getMessage()
            ]) ;
        }

    }
    public function Likes_number(Request $request)
    {
        try
        {
            if($request['type'] == 'car')
            {
                $car = Car::find($request['id']) ;
                if ($car)
                {
                    $count = Favorite::where('car_id' , $request['id'])->count() ;
                    return \response()->json([
                        'Likes_number' => $count
                    ]);
                }else
                {
                    return \response()->json(
                     [
                         'Message' => 'Car Not Exist'
                     ]) ;
                }
            }else if ($request['type']== 'estate')
            {
                $estate = Estate::find($request['id']) ;
                if ($estate)
                {
                    $count = Favorite::where('estate_id' , $request['id'])->count() ;
                    return \response()->json([
                        'Likes_number' => $count
                    ]);
                }else
                {
                    return \response()->json(
                        [
                            'Message' => 'estate Not Exist'
                        ]) ;
                }
            }
        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ] ) ;
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

            } else if ($request['type'] == 'estate') {
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
}
