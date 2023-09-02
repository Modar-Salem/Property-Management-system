<?php

namespace App\Http\Controllers;

use App\Http\Requests\Estate\MyPostRequest;
use App\Http\Requests\ForgotPassword\ResetPasswordRequest;
use App\Http\Requests\Image\ImageRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{



    public function profile()
    {
        try
        {
            $id = Auth::id() ;
            $user = User::find($id) ;

            return response()->json([
                'User' => $user
            ]) ;

        } catch (\Exception $exception)
        {
            return response() -> json([
                'Status' => false  ,
                'Message' => $exception->getMessage() ,
            ] ) ;
        }
    }


    public function GetUser($id)
    {
        try {
            $user = User::find($id);
            if ($user)
            {
                return response()->json([
                    'User' => $user
                ], 201);
            }else {
                return \response()->json([
                    'Message' => 'User Not Exist'
                ]);
            }
        }catch (\Exception $exception)
        {
            return response() -> json([
                'Status' => false  ,
                'Message' => $exception->getMessage() ,
            ] ) ;
        }
    }


    public function User_insert_image(ImageRequest $request)
    {

        try
        {
            $user_id = Auth::id() ;

            $user= \App\Models\User::find($user_id)  ;
            $Image = new ImageController() ;

            if ($user['image']!=null)
            {
                $Image->delete_image_from_Storage($user['image']) ;
            }

            $path = $Image->store_image_User($request) ;

            //create Object in Database
            $user->update(['image' => URL::asset('storage/' . $path)]);

            return response()->json([
                    'Status' => true ,
                    'Message' => 'Image are inserted Successfully' ,
                ]) ;

        } catch(\Exception $exception)
        {

            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {

            $user = Auth::user();

            $user->update($request->all());
            return response()->json([
                'Status' => true,
                'Message' => 'User has been Updated Successfully' ,
                'User' => $user
            ]);

        }catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try
        {
            if ($id == Auth::id() or \auth()->user()->role == 'admin')
            {
                $user = User::find($id) ;

                if ($user)
                {

                    $user->delete();
                    $reg = new RegisterController() ;
                    $reg->LogOut() ;

                    return response()->json([
                        'Status' => true,
                        'Message' => 'User has been deleted successfully'
                    ]);
                }else
                {
                    return response()->json([
                        'Status' => false ,
                        'Message' => 'User Not Found'
                    ]);
                }

            }else
            {
                return response()->json([
                    'Status' => false ,
                    'Message' => 'Access denied'
                ]);
            }

        }
        catch (\Exception $exception)
        {
            return  response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]) ;
        }
    }


    public function resetPassword(ResetPasswordRequest $request)
    {

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password is incorrect.'], 422);
        }

        $user->updatePassword($request->new_password);

        return response()->json(['message' => 'Password has been updated.']);
    }

    private function my_estate_posts(User $user)
    {
        $posts = User::with('estates.images')->find(Auth::id()) ;
        // Process the retrieved data to add the 'is_favorite' field to each estate
        $posts->estates->each(function ($estate) use ($user) {
            $estate->is_favorite = $user->isEstateFavorite($estate);
        });
        return $posts ;
    }

    private function my_car_posts(User $user)
    {
        $posts = User::with('cars.images')->find(Auth::id()) ;
        // Process the retrieved data to add the 'is_favorite' field to each estate
        $posts->cars->each(function ($car) use ($user) {
            $car->is_favorite = $user->isCarFavorite($car);
        });
        return $posts ;
    }

    public function my_posts(MyPostRequest $request)
    {
        $user= User::find(Auth::id()) ;

        if($request['type']=='estate')
        {
            return response() -> json([
                'Status' => true ,
                'Estates' => $this->my_estate_posts($user)
            ]);
        }

        if($request['type']=='car')
        {

                return response() -> json([
                    'Status' => true ,
                    'Cars' => $this->my_car_posts($user)
                ]);
        }
        if($request['type'] == 'all')
        {
            return response() -> json([
                'Status' => true ,
                'Estates' => $this->my_estate_posts($user) ,
                'Cars' => $this->my_car_posts($user)
            ]);

        }else
        {
            return response() -> json([
                'Status' => true ,
                'Message' => 'User Not Exist'
            ]);
        }
    }

}
