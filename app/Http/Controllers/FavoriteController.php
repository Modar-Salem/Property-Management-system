<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Models\Car;
use App\Models\Estate;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{

    private function Get_Favorite_Estate()
    {

        $user = Auth::user() ;

        $Estates = Estate::join('favorites', 'estates.id', '=', 'favorites.estate_id')
        ->where('favorites.user_id', '=', $user->id)
        ->get();
        $postsWithImages = [];

        foreach ($Estates as $estate) {
            $images = $estate->images()->get();
            $postWithImage = [
                'post' => $estate,
                'images' => $images,
                'favorite' => true
            ];
            array_push($postsWithImages, $postWithImage);
        }

        return $postsWithImages;

    }

    private function Get_Favorite_Car()
    {
        $user = Auth::user() ;

        $Car = Car::join('favorites', 'cars.id', '=', 'favorites.car_id')
            ->where('favorites.user_id', '=', $user->id)
            ->get();

        $postsWithImages = [];

        foreach ($Car as $car) {
            $images = $car->images()->get();
            $postWithImage = [
                'post' => $car,
                'images' => $images,
                'favorite' => true
            ];
            array_push($postsWithImages, $postWithImage);
        }

        return $postsWithImages ;
    }



    public function Get_All_Favorite(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all() ,
                [
                    'type' => 'Required |  in:estate,car,all',
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }

            if($request['type'] == 'estate')
            {
                return response()->json([
                    'favorite_estates : ' => $this->Get_Favorite_Estate()
                ]) ;


            }elseif($request['type'] == 'car')
            {
                return response()->json([
                    'favorite_cars : ' => $this->Get_Favorite_Car()
                ]) ;

            }elseif($request['type'] == 'all')
                return response()->json([
                    'favorites Estate: ' => $this->Get_Favorite_Estate() ,
                    'favorites Car: ' => $this->Get_Favorite_Car()
                ]) ;

        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
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
            $validate = Validator::make($request->all() ,
                [
                    'type' => 'Required |  in:estate,car,all',
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }
            $car = null ;
            $estate= null ;

            $user_id = Auth::id() ;
            $favorite = null ;
            if($request['type'] == 'estate')
            {
                $estate = Estate::find($request['id']) ;
                if($estate)
                    $favorite = \App\Models\Favorite::where('user_id', $user_id)->where('estate_id', '=', $request['id']);
                else
                    return response()->json([
                        'Message' => 'Estate Not Exist'
                    ]) ;
            }
            elseif($request['type'] == 'car')
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
            }else
            {
                $user_id = Auth::id();

                if($request['type'] == 'estate')
                {
                    \App\Models\Favorite::create([
                        'estate_id' => $request['id'],
                        'user_id' => $user_id,
                        'property_type' => 'estate'
                    ]);

                    broadcast(new LikeEvent('estate' , $estate->owner_id ,$estate->id)) ;
                }
                if($request['type'] == 'car')
                {
                    \App\Models\Favorite::create([
                        'car_id' => $request['id'],
                        'user_id' => $user_id,
                        'property_type' => 'car'
                    ]);
                    broadcast(new LikeEvent('car' , $car->owner_id ,$car->id)) ;
                }
                return response()->json([
                    'Status' => true,
                    'Message' => 'product favorite'
                ]);


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




    private function Car_Likes_Number(Request $request)
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
    }
    private function Estate_Likes_Number(Request $request)
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
    public function Likes_number(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all() ,
                [
                    'type' => 'Required |  in:estate,car',
                ]) ;

            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }
            if($request['type'] == 'car')
            {
                return $this->Car_Likes_Number($request) ;
            }elseif ($request['type']== 'estate')
            {
                return $this->Estate_Likes_Number($request);
            }

        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }

}
