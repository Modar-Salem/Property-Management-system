<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\verify_email;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VerifyController extends Controller
{

    //Send The verification code
    public function sendEmailVerificationCode(Request $request)
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

    //check if the sent code is true
    public function check_code_email_verify(Request $request)
    {

        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required |email |exists:users,email',
                'code' => 'required|string|exists:verify,code'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'Status' => false,
                    'ErrorMessage' => $validator->errors()]);
            }

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

            return response()->json([
                'Status' => true,
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
