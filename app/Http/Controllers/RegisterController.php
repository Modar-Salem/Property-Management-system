<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register\LogInRequest;
use App\Http\Requests\Register\SignUpRequest;
use App\Jobs\SendEmailVerify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{


    public function SignUp(SignUpRequest $request)
    {

        try {

            //store the user
            $User = \App\Models\User::create([
                'name' => $request['name'],

                'email' => $request['email'],

                'password' => Hash::make($request['password']),

                'phone_number' => $request['phone_number'],

                'facebook_URL' => $request['facebook_URL'],

                'instagram_URL' => $request['instagram_URL'],

                'twitter_URL' => $request['twitter_URL']
            ]);

            SendEmailVerify::dispatch($request['email']) ;

            return response()->json([
                'Status' => true,
                'User' => $User,
            ]);

        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
        }

    }



    public function LogIn(LogInRequest $request)
    {
        try
        {
            //if the email is Google Email
            $user = User::where('email' , $request['email'])->get();
            if($user[0]->google_id != null)
                return response()->json([
                   'Status' => false
                    ,'Message'=>'You can access with this email only with Gmail'
                ]);

            //Normal Email
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials))
                return response()->json([
                    'Status' => false ,
                    'Message' => 'Invalid Data'
                ]);
            else
            {

                $User = \App\Models\User::where('email' , $request['email'])->first() ;
                if($User['email_verified_at'] == null)
                    return response()->json([
                        'Message' => 'This is Email Must Be verified'
                    ])  ;

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

}
