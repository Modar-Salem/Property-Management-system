<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Http\Requests\Favorite\AddFavoriteRequest;
use App\Http\Requests\Favorite\LikesNumberRequest;
use App\Http\Requests\Favorite\MyFavoriteRequest;
use App\Models\Car;
use App\Models\Estate;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
//    public function  __construct(ValidationFactory $validationFactory  , FavoriteService $favoriteService)
//    {
//            parent::__construct($validationFactory ,$favoriteService )  ;
//    }

    private function Get_Favorite_Estate()
    {

        $user = Auth::user();

        $Estates = Estate::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['images'])
            ->get();


        return $Estates;

    }
    private function Get_Favorite_Car()
    {
        $user = Auth::user() ;

        $Car = Car::whereHas('favorites' , function ($query) use ($user){
          $query->where('user_id', $user->id);
        })
        ->with('images')
        ->get();



        return $Car ;
    }

    public function Get_All_Favorite(MyFavoriteRequest $request)
    {
        try
        {

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
    //


    public function Add_To_Favorite(AddFavoriteRequest $request)
    {
        try
        {

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


    //
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

    public function Likes_number(LikesNumberRequest $request)
    {
        try
        {
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
