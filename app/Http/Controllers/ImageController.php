<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{

    public function validateImageRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png',
        ]);
    }

    public function store_image_User(Request $request)
    {

        $image = $request->file('image');

        //Get FileName with extension
        $filenameWithExt = $image->getClientOriginalName();

        //Get FileName without Extension
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //Get Extension
        $Extension = $image->getClientOriginalExtension();

        //New_File_Name
        $NewfileName = $filename . '_' . time() . '_.' . $Extension;

        //Upload Image
        return $path = $image->storeAs('images', $NewfileName, 'public');
    }


    public function store_image_post($image , $id , $type)
    {

        try
        {
            $path = Null;
            //Get FileName with extension
            $filenameWithExt = $image->getClientOriginalName();

            //Get FileName without Extension
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //Get Ex4tension
            $Extension = $image->getClientOriginalExtension();

            //New_File_Name
            $NewfileName = $filename . '_' . time() . '_.' . $Extension;

            //Upload Image
            $path = $image->storeAs('images', $NewfileName, 'public');


            if($type == 'car')
                \App\Models\Image::create([
                    'name'=>URL::asset('storage/' . $path) ,
                    'car_id'=>$id ,
                    'property_type' => 'car'
                ]) ;

            elseif ($type == 'estate')
                \App\Models\Image::create([
                    'name'=>URL::asset('storage/' . $path) ,
                    'estate_id'=>$id ,
                    'property_type' => 'estate'
                ]) ;


        } catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }


    public function delete_image_from_Storage($image)
    {
        $imagePath = str_replace('/storage', '', parse_url($image, PHP_URL_PATH));
        return Storage::delete($imagePath);
    }
}
