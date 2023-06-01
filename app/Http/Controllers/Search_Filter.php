<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use Illuminate\Http\Request;

class Search_Filter extends Controller
{

    public function Filter(Request $request)
    {
        try
        {

            $post = null ;

            if ($request['type'] == 'estate' )
            {
                if ($request['estate_type'] != null)
                    $post = \App\Models\Estate::where('estate_type' , $request['estate_type']);

                if ($request['operation_type'] != null)
                    $post = $post->where('operation_type' , $request['operation_type']) ;

                if ($request['location'] != null)
                    $post = $post->where('location' , $request['location'] );

                if ($request['locationInDamascus'] != null)
                    $post = $post->where('locationInDamascus' , $request['locationInDamascus']) ;

                if ($request['max_price']!=null)
                    $post = $post->where('price' , '<' , $request['max_price']) ;

                if ($request['min_price']!=null)
                    $post = $post->where('price' , '>' , $request['min_price']) ;

                if ($request['max_space']!=null)
                    $post = $post->where('space' , '<' , $request['max_space']) ;

                if ($request['min_space']!=null)
                    $post = $post->where('space' , '>' , $request['max_space']) ;


                if ($request['max_level']!=null)
                    $post = $post->where('level' , '<' , $request['max_level']) ;

                if ($request['min_level']!=null)
                    $post = $post->where('level' , '>' , $request['min_level']) ;

                if ($request['status'] != null)
                    $post =  $post->where('status' , $request['status']) ;

                if($post)
                    return response()->json([
                        'Status'=>true ,
                        'PostsSeeder'=> $post->paginate(4)
                    ],201) ;

            }


            if ($request['type'] == 'car' )
            {
                if ($request['operation_type'] != null)
                    $post = \App\Models\Car::where('operation_type' , $request['operation_type']) ;

                if ($request['transmission_type'] != null)
                    $post =  $post->where('transmission_type' , $request['transmission_type']) ;

                if ($request['fuel_type'] != null)
                    $post =  $post->where('fuel_type' , $request['fuel_type']) ;

                if ($request['status'] != null)
                    $post =  $post->where('status' , $request['status']) ;

                if ($request['driving_force'] != null)
                    $post =  $post->where('driving_force' , $request['driving_force']) ;


                if ($request['brand'] != null)
                    $post =  $post->where('brand' , $request['brand']) ;

                if ($request['secondary_brand'] != null)
                    $post =  $post->where('secondary_brand' , $request['secondary_brand']) ;

                if ($request['color'] != null)
                    $post =  $post->where('color' , $request['color']) ;

                if ($request['location'] != null)
                    $post =  $post->where('location' , $request['location'] );

                if ($request['locationInDamascus'] != null)
                    $post =  $post->where('locationInDamascus' , $request['locationInDamascus']) ;

                if ($request['max_year']!=null)
                    $post = $post->where('year' , '<' , $request['max_year']) ;

                if ($request['min_year']!=null)
                    $post = $post->where('year' , '>' , $request['min_year']) ;


                if ($request['max_price']!=null)
                    $post = $post->where('price' , '<' , $request['max_price']) ;


                if ($request['min_price']!=null)
                    $post = $post->where('price' , '>' , $request['min_price']) ;

                if ($request['max_kilometers']!=null)
                    $post = $post->where('kilometers' , '<' , $request['max_kilometers']) ;

                if($post)
                    return response()->json([
                        'Status'=>true ,
                        'Posts'=> $post->paginate(4)
                    ],201) ;
            }
        }
        catch (\Exception $exception){

            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ], 401) ;
        }

    }

    public function Search(Request $request)
    {
        try
        {
             if ($request['type'] == 'car' )
            {
                $post = Car::where('description' ,'like' ,'%'.$request['description'].'%') ->orWhere('address' ,'like' ,'%'.$request['description'].'%');
                if($post)
                    return response()->json([
                        'Status'=>true ,
                        'Estates'=> $post->paginate(4),

                    ],201) ;
            }
            if ($request['type'] == 'estate' )
            {
                $post = Estate::where('description' ,'like' ,'%'.$request['description'].'%')->orWhere('address' ,'like' ,'%'.$request['description'].'%');
                if($post)
                    return response()->json([
                        'Status'=>true ,
                        'Estates'=> $post->paginate(4)
                    ],201) ;
            }



        }
        catch (\Exception $exception){

            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ], 401) ;
        }
    }
}
