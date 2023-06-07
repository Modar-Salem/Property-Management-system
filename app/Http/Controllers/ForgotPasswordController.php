<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{


    public function userForgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Status'=>false,
                'ErrorMessage'=>$validator->errors()]);
        }

        $data = $validator->validated();
        //Delete all old code that user send before
        ResetCodePassword::query()->where('email',$request['email'])->delete();
        //Generate random code
        $data['code']=mt_rand(100000,999999);
        //Create a new code
        $CodeData=ResetCodePassword::query()->create($data);
        //Send email to user
        Mail::to($request['email'])->send(new SendCodeResetPassword($CodeData['code']));

        return response()->json([
            'Status'=>true,
            'Message'=>trans('code.sent')
            ]);
    }
    public function userCheckCode(Request $request){

        $validator = Validator::make($request->all(), [
            'code' =>'required|string|exists:reset_code_passwords,code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'Status'=>false,
                'ErrorMessage'=>$validator->errors()]);
        }

         $validator->validated();
        //find the code
        $PasswordReset=ResetCodePassword::query()->firstWhere('code',$request['code']);
        //check if it is not expired:the time is one hour
        if($PasswordReset['created_at'] > now()->addHour())
        {
            $PasswordReset->delete();
            return response()->json(['message'=>trans('password.code_is_expire')],422);

        }
        return response()->json([
            'Status'=>true,
            'code' => $PasswordReset['code'],
            'Message' => trans('password.code_is_valid')
        ]);
    }
    public function userResetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:reset_code_passwords,code',
            'password' => ['required','confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Status'=>false,
                'ErrorMessage'=>$validator->errors()]);
        }

        $input = $validator->validated();
        //find the code
        $PasswordReset=ResetCodePassword::query()->firstWhere('code',$request['code']);
        //check if it is not expired:the time is one hour
        if($PasswordReset['created_at'] > now()->addHour() )
        {
            $PasswordReset->delete();
            return response()->json(['Message'=>trans('password code is expire')],422);
        }
        //find users email
        $user = User::query()->firstWhere('email',$PasswordReset['email']);
        //update user password
        $input['password'] = bcrypt($input['password']);
        $user->update(['password' => $input['password']]);
        //delete current code
        $PasswordReset->delete();
        return response()->json([
            'Status'=>true,
            'Message' => 'password has been successfully reset']);
    }
}
