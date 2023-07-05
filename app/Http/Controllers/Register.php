<?php

namespace App\Http\Controllers;

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


    private function validateImageRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png',
        ]);
    }


    private function validateSignUpRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:34',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:34',
            'phone_number' => 'required|string|min:8|max:14',
            'image' => 'mimes:jpeg,jpg,png',
        ]);
    }

    private function store_image(Request $request)
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
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SignUp(Request $request)
    {

        try {
            //Validate $request
            $validate = $this->validateSignUpRequest($request);
            if ($validate->fails())
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ]);

            //store the user
            $User = \App\Models\User::create([
                'name' => $request['name'],

                'email' => $request['email'],

                'password' => \Illuminate\Support\Facades\Hash::make($request['password']),

                'phone_number' => $request['phone_number']
            ]);

            //create token
            $token = $User->createToken('API TOKEN')->plainTextToken;

            //send code to verify
            $this->send_code_verify($request);

            //store the image
            if ($request->hasFile('image')) {

                //validate image
                $validateImage = $this->validateImageRequest($request);
                if ($validateImage->fails())
                    return response()->json([
                        'Status' => false,
                        'Validation Error' => $validate->errors()
                    ]);

                //store image in storage
                $path = $this->store_image($request) ;

                //store image in database
                \App\Models\User::find($User['id'])
                    ->update(
                        ['image' => URL::asset('/storage/' . $path)]
                    );


                return response()->json([
                        'Status' => true,
                        'User' => $User,
                        "Token" => $token,
                        'Message' => 'Image are inserted Successfully',
                ]);

            }

            return response()->json([
                'Status' => true,
                'User' => $User,
                "Token" => $token
            ]);

        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
        }

    }

    public function send_code_verify(Request $request)
    {
        try{
            //validate request
            $validate = Validator::make($request->all(), [
                'email' => 'required | email',
            ]);
            if ($validate->fails())
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ]);

            //generate code
            $code = mt_rand(100000, 999999);

            //Send email to user
            Mail::to($request['email'])->send(new \App\Mail\verifyemail($code));
            verify_email::create([
                'email' => $request['email'],
                'code' => $code
            ]);

            return response()->json([
                'Status' => true,
                'Message' => 'code is sent successfully'
            ]);
        }
        catch (\Exception $exception)
        {
            return response() -> json([
                'Status' => false  ,
                'Message' => $exception->getMessage() ,
            ] ) ;
        }

    }


    public function check_code_email_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required | exists:users,email' ,
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
        $user = User::where('email',$Code['email'])->first();

        if($user->email != $request['email'])
        {
            return response()->json([
                'Message' => 'code incorrect '
            ]) ;
        }
        //update user password
        $user = User::where('email',$Code['email']);
        $user->update(['email_verified_at' => now()]);
        $Code->delete() ;

        return response()->json([
            'Status' => true ,
            'Message' => 'email is verified'
        ]) ;
    }




    private function validateLogInRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:34',
        ]);
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

            $validate = $this->validateLogInRequest($request) ;
            if ($validate->fails())
                return response()->json([
                    'Status' => false ,
                    'Validation Error' => $validate->errors()
                ]) ;

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials))
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
        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
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

            Auth::user()->tokens()->delete();

            return  response()->json([
                "Status" => true ,
                "Message" => "LogOut Successfully"
            ] ) ;

        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
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

                    $user->delete();

                    $this->LogOut() ;

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
