<?php

namespace App\Http\Controllers;

use App\Http\Requests\Estate\StoreEstateRequest;
use App\Http\Requests\Estate\UpdatePostRequest;
use App\Models\Car;
use App\Models\Estate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EstateController extends Controller
{



    public function store_estate(StoreEstateRequest $request)
    {

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


    public function Get_User_Estates($id)
    {
        try{

            $user = Auth::user() ;
            $owner_posts = User::with(['estates.images'])
                ->find($id);

            // Process the retrieved data to add the 'is_favorite' field to each estate
            $owner_posts->estates->each(function ($estate) use ($user) {
                $estate->is_favorite = $user->isEstateFavorite($estate);
            });
            if ($owner_posts) {
                return response()->json([
                    'posts' => $owner_posts
                ]);
            }else
            {
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

    public function get_estate($estate_id)
    {
        try
        {
            $user = Auth::user();
            $estate = Estate::with(['images', 'owner'])->find($estate_id);

            if ($estate) {
                $favorite = $user->isEstateFavorite($estate);

                return response()->json([
                    'Status' => true,
                    'Estate' => $estate,
                    'favorite' => $favorite,
                ]);
            } else {
                return response()->json([
                    'Status' => false,
                    'Estate' => "Estate does not exist",
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

        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }


    public function update_post(UpdatePostRequest $request)
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


}
