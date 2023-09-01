<?php

namespace App\Http\Controllers;

use App\Http\Requests\Verify\CheckCodeEmailVerifyRequest;
use App\Http\Requests\Verify\SendEmailVerificationCodeRequest;
use App\Models\User;
use App\Models\verify_email;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{

    //Send The verification code
    public function sendEmailVerificationCode(Request $request)
    {
        verify_email::where('email' , $request['email'])->delete() ;
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

    //Send The verification code
    public function ResendEmailVerificationCode(SendEmailVerificationCodeRequest $request)
    {
        try
        {
            $this->sendEmailVerificationCode($request) ;
        }
        catch (\Exception $exception)
        {
            return response() -> json([
                'Status' => false  ,
                'Message' => $exception->getMessage() ,
            ] ) ;
        }
    }

    //check if the sent code is true
    public function check_code_email_verify(CheckCodeEmailVerifyRequest $request)
    {

        try{
            //find the code
            $Code = verify_email::where('code', $request['code'])->first();

            //check if it is not expired:the time is one hour
            if ($Code['created_at'] > now()->addHour()) {
                $Code->delete();
                return response()->json(['Message' => trans('code is expire')], 422);
            }

            //find users email
            $user = User::where('email', $Code['email'])->first();

            if ($user->email != $request['email']) {
                return response()->json([
                    'Message' => 'code incorrect '
                ]);
            }
            //update user password
            $user = User::where('email', $Code['email']);
            $user->update(['email_verified_at' => now()]);
            $Code->delete();


            // Create token

            $user = User::where('email', $Code['email'])->first();

            $token_init = $user->createToken('API TOKEN');

            // Set expiration date
            $token_init->expires_at = now()->addDays(7);

            $token = $token_init->plainTextToken ;

            return response()->json([
                'Status' => true,
                'User' => $user ,
                'token' => $token,
                'Message' => 'email is verified'
            ]);
        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
        }
    }

    //SMS  verification
    public function sendSmsVerificationCode(Request $request)
    {
        try{
            $apiKey = config('services.vonage.api_key');
            $apiSecret = config('services.vonage.api_secret');
            $from = config('services.vonage.sms_from');

            // Generate the verification code
            $code = mt_rand(100000, 999999);
            $phoneNumber = $request->input('phone');

            $client = new Client(new \Vonage\Client\Credentials\Basic($apiKey, $apiSecret));

            $client->sms()->send([
                'to' => $phoneNumber,
                'from' => $from,
                'text' => 'Your verification code: ' . $code,
            ]);

            return response()->json([
                'Status' => true ,
                'Message' => 'code.sent'
            ]) ;
        }catch(\Exception $exception)
        {
            return response()->json([
                'Status' => false ,
                'Message' => $exception->getMessage()
            ]);
        }
    }

}
