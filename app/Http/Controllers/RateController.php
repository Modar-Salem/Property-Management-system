<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Estate;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
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
                    'type' => 'required | in:car,estate'
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
            }elseif($request['type'] == 'car')
            {
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

    private function validate_Get_Rate_Request(Request $request)
    {
        return Validator::make($request->all() ,
            [
                'type' => 'Required | in:estate,car',
                'estate_id' => 'exists:estates,id' ,
                'car_id' => 'exists:cars,id'
            ]) ;

    }

    /**
     * give a rate to the product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Get_Rate(Request $request){
        try
        {
            $validate = $this->validate_Get_Rate_Request($request) ;
            if($validate->fails())
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => $validate->errors()
                ]) ;
            }

            $sum = 0  ;
            $count = 0 ;

            if($request['type'] == 'estate')
            {
                $count = Rate::where('estate_id', '=', $request['estate_id'])->count() ;
                $sum = Rate::where('estate_id', '=', $request['estate_id'])->sum('rate') ;
            }

            elseif($request['type'] == 'car')
            {
                $count = Rate::where('car_id', '=', $request['car_id'])->count() ;
                $sum = Rate::where('car_id', '=', $request['car_id'])->sum('rate') ;
            }

            if($count == 0)
                return response()->json([
                    'Status' => true ,
                    'rate' => 0
                ]) ;
            else
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

}
