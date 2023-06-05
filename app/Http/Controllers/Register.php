<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PHPUnit\Event\Code\Throwable ;
use Illuminate\Support\Facades\Validator;

class Register extends Controller
{

    public function GetUser($id)
    {
        try {

            $user = User::find($id);
            if ($user) {
                return response()->json([
                    'User' => $user
                ], 201);
            } else {
                return \response()->json([
                    'Message' => 'User Not Exist'
                ]);
            }
        } catch (\Exception $exception)
        {
            return response() -> json([
                'Status' => false  ,
                'Error in Create the Token' => $exception->getMessage() ,
            ] ,  500) ;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SignUp(Request $request)
    {
        try {

            $validate = Validator::make($request->all(), [
                'name' => 'required | string | min:5 | max :34',

                'email' => 'required | email | unique:users,email',

                'password' => 'required | string | min : 8 | max:34 ',

                'phone_number' => 'required | string | min :8 | max : 14' ,

                'image' => 'mimes:jpeg,jpg,png'

            ]);
            if ($validate->fails())
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ], 401);


            $User = \App\Models\User::create([
                'name' => $request['name'],

                'email' => $request['email'],

                'password' => \Illuminate\Support\Facades\Hash::make($request['password']),

                'phone_number' => $request['phone_number']
            ]) ;

            //create Token
            try
            {
                $token = $User->createToken('API TOKEN')->plainTextToken ;
            } catch (\Exception $Th)
            {
                return response() -> json([
                    'Status' => false  ,
                    'Error in Create the Token' => $Th->getMessage() ,
                ] ,  500) ;
            }

            if($request->hasFile('image'))
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
                $path = $image->storeAs('images', $NewfileName, 'public');


                //create Object in Database
                $user = \App\Models\User::find($User['id'])
                    ->update(
                        ['image' => URL::asset('/storage/' . $path)]
                    );

                if ($user)
                    return response()->json([
                        'Status' => true,
                        'User' =>$User ,
                        "Token" => $token,
                        'Message' => 'Image are inserted Successfully',
                    ]);

            }
            return response()->json([
                'Status' => true,
                'User' =>$User ,
                "Token" => $token,
            ]);

        } catch (\Exception $exception)
        {
            return response()->json([
                'Status' => false,
                'Message' => $exception->getMessage()
            ]);
        }


    }
    /**
     * LogIn .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function LogIn(Request $request) {

        try
        {
            $validate = Validator::make($request->all() , [
                'email' => 'required | email',

                'password'  => 'required | string | min : 8 | max:34 ' ,
            ])  ;

            if ($validate->fails())
                return response()->json([
                    'Status' => false ,
                    'Validation Error' => $validate->errors()
                ]) ;


            if (!Auth::attempt($request->only('email' , 'password' )))
                return response()->json([
                    'Status' => false ,
                    'Message' => 'Invalid Data'
                ]);

            else
            {

                $User = \App\Models\User::where('email' , $request['email'])->first() ;
                $token = $User->createToken('API TOKEN')->plainTextToken ;

                return response() ->json([
                    'Status'=> true ,
                    'User' => $User,
                    'Token' => $token ,
                ]) ;

            }
        }
        catch (\Throwable $Th)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $Th->getMessage()
            ],500) ;
        }
    }

    /**
     * Logout .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function LogOut()
    {
        try
        {

            Auth::user()->tokens->each(function ($token){
                $token->delete() ;
                return response()->json([
                    'Status' => true ,
                    'Message' => 'LogOut Successfully'
                ]) ;
            }) ;
            return  response()->json([
                "Status" => true ,
                "Message" => "LogOut Successfully"
            ] , 201) ;
        }
        catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
        }

    }

    public function insert_image(Request $request)
    {
        $user_id = Auth::id() ;

        $validate = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png,gif | required']);

        if ($validate->fails()) {
            return response()->json([
                'Status' => false,
                'Message' => $validate->errors()
            ]);
        }
        if ($request->hasFile('image')) {
            try
            {
                $user= \App\Models\User::find($user_id)  ;

                if ($user['image']!=null)
                {
                    $imagePath = str_replace('/storage', '', parse_url($user->image, PHP_URL_PATH));
                    $isdeleted = Storage::delete($imagePath) ;
                }

                $path = Null;
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
                $path = $image->storeAs('images', $NewfileName , 'public');


                //create Object in Database
                $user->update(['image' => URL::asset('storage/' . $path)]);

                if($user)
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

            $user = User::find(Auth::id());

            $user->update($request->all());
            return response()->json([
                'Status' => true,
                'Message' => 'User has been Updated Successfully' ,
                'User' => User::find(Auth::id())
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
            if ($id == Auth::id() or \auth()->user()->role = 'admin')
            {
                $user = User::find($id) ;

                if ($user)
                {
                    $isdeleted = false ;
                    if($user['image']!=null)
                    {
                        $imagePath = str_replace('/storage', '', parse_url($user->image, PHP_URL_PATH));
                        $isdeleted = Storage::delete($imagePath) ;
                    }
                    $user->delete();

                    $this->LogOut() ;

                    return response()->json([
                        'Status' => true,
                        'Message' => 'User has been deleted successfully'
                        ,'Image Is deleted' => $isdeleted
                    ]);
                }else
                {
                    return response()->json([
                        'Status' => false ,
                        'Message' => 'User Not Found'
                    ]);
                }

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
}
