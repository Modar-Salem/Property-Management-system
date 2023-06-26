<?php

namespace App\Http\Controllers;

use App\Events\verifyemail;
use App\Models\Estate;
use App\Models\User;
use App\Models\verify_email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Register extends Controller
{

    public function profile()
    {
        try {
            $id = Auth::id() ;
            $user = User::find($id) ;

            return response()->json([
                'User' => $user
            ]) ;

        } catch (\Exception $exception)
        {
            return response() -> json([
                'Status' => false  ,
                'Error in  Token' => $exception->getMessage() ,
            ] ) ;
        }
    }

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
            ] ) ;
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
                ]);


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
                ] ) ;
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
                $this->send_code_verify($request) ;

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




    }

    public function send_code_verify(Request $request)
    {
        $code = mt_rand(100000,999999);

        //Send email to user
        Mail::to($request['email'])->send(new \App\Mail\verifyemail($code));
        verify_email::create([
            'email' => $request['email'] ,
            'code'=> $code
        ]);

    }
    public function check_code_email_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required' ,
            'code' =>'required|string|exists:verify,code'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'Status'=>false,
                'ErrorMessage'=>$validator->errors()]);
        }

        //find the code
        $Code= verify_email::where('code',$request['code'])->first();

        //check if it is not expired:the time is one hour
        if($Code['created_at'] > now()->addHour() )
        {
            $Code->delete();
            return response()->json(['Message'=>trans('code is expire')],422);
        }

        //find users email
        $user = User::where('email',$Code['email']);

        if($user['email'] != $request['email'])
        {
            return response()->json([
                'Message' => 'code incorrect '
            ]) ;
        }
        //update user password
        $user->update(['email_verified_at' => now()]);
        $Code->delete() ;

        return response()->json([
            'Status' => true ,
            'Message' => 'email is verified'
        ]) ;
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
            ]) ;
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
            ] ) ;
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

    public function resetPassword(Request $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password is incorrect.'], 422);
        }

        $user->updatePassword($request->new_password);

        return response()->json(['message' => 'Password has been updated.']);
    }

    public function my_posts(Request $request)
    {
        $id = Auth::id() ;

        $user = User::find($id) ;
        $carsWithImages = collect();
        $estatesWithImages = collect();
        if($request['type']=='estate') {
            foreach ($user->estates as $estate)
            {
                $images = $estate->images()->get();
                $favorite = $user->isEstateFavorite($estate);
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $estatesWithImages->push($postWithImage);

            }
            return response() -> json([
                'Status' => true ,
                'Estates' => $estatesWithImages ,
            ]);
        }
        if($request['type']=='car')
        {
            foreach ($user->cars as $cars) {

                $images = $cars->images()->get();
                $favorite = $user->isCarFavorite($cars);
                $postWithImage = [
                    'post' => $cars,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $carsWithImages->push($postWithImage);
                return response() -> json([
                    'Status' => true ,
                    'Cars' => $carsWithImages
                ]);
            }
        }
        if($request['type'] == 'all')
        {
            foreach ($user->cars as $cars) {

                $images = $cars->images()->get();
                $favorite = $user->isCarFavorite($cars);
                $postWithImage = [
                    'post' => $cars,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $carsWithImages->push($postWithImage) ;
            }
            foreach ($user->estates as $estate)
            {
                $images = $estate->images()->get();
                $favorite = $user->isEstateFavorite($estate);
                $postWithImage = [
                    'post' => $estate,
                    'images' => $images,
                    'favorite' => $favorite
                ];
                $estatesWithImages->push($postWithImage);

            }
            return response() -> json([
                'Status' => true ,
                'Estates' => $estatesWithImages ,
                'Cars' => $carsWithImages
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
