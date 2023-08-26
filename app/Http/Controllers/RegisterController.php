<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailVerify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    private function validateSignUpRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:34',
        ]);
    }

    public function getIPInfo($ipAddress)
    {
        $apiKey = 'lH2nuxQOrwSAj+O+ulDjJg==bDSrfkObt70jmymU';
        $apiUrl = "https://api.api-ninjas.com/v1/iplookup?address={$ipAddress}";

        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {
            $ipInfo = $response->json();
            return response()->json($ipInfo);
        } else {
            $error = [
                'error' => true,
                'message' => "Error: {$response->status()} {$response->body()}",
            ];
            return response()->json($error, $response->status());
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
            //Validate $request
            $validate = $this->validateSignUpRequest($request);
            if ($validate->fails())
            {
                $error = $validate->errors() ;
                if($error->has('email') && $error->get('email')[0] === "The email has already been taken.")
                {
                    $user_temp = User::where('email' , $request['email'])->first() ;
                    if($user_temp->email_verified_at == null)
                    {
                        return response()->json([
                            'Status' => false,
                            'Validation Error' => 'email must be verified'
                        ]);
                    }
                }
                return response()->json([
                    'Status' => false,
                    'Validation Error' => $validate->errors()
                ]);
            }
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

            //store the image
            if ($request->hasFile('image')) {
                $image = new ImageController() ;

                //validate image
                $validateImage = $image->validateImageRequest($request);
                if ($validateImage->fails())
                    return response()->json([
                        'Status' => false,
                        'Validation Error' => $validate->errors()
                    ]);

                //store image in storage
                $path = $image->store_image_User($request) ;

                //store image in database
                \App\Models\User::find($User['id'])
                    ->update(
                        ['image' => URL::asset('/storage/' . $path)]
                    );


                return response()->json([
                        'Status' => true,
                        'User' => $User,
                        'Message' => 'Image are inserted Successfully',
                ]);

            }

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




    private function validateLogInRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email |exists:users,email',
            'password' => 'required|string|min:8|max:34',
        ]);
    }

    /**
     * LogIn .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function LogIn(Request $request)
    {
        try
        {
            $validate = $this->validateLogInRequest($request) ;
            if ($validate->fails())
                return response()->json([
                    'Status' => false ,
                    'Validation Error' => $validate->errors()
                ]) ;

            $user = User::where('email' , $request['email'])->get();
            if($user[0]->google_id != null)
                return response()->json([
                   'Status' => false
                    ,'Message'=>'You can access with this email only with Gmail'
                ]);

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

}
