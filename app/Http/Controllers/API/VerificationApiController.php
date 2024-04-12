<?php

namespace App\Http\Controllers\API;
   
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Validator;
use Ichtrojan\Otp\Otp;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationApiController extends Controller
{

    use VerifiesEmails;

    /**
    * Show the email verification notice.
    *
    */

    public function show()
    {
    //
    }

    /**
    * Mark the authenticated user’s email address as verified.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    public function verify(Request $request)
    {        
        $user = User::where('email', $request["email"])->first();

        if ($user->hasVerifiedEmail()) {

            return response()->json([
                'status' => false,
                'message' => 'User already have verified email!',
            ], 422);
        }

        $validate = Validator::make($request->all(),
            [
                'email' => ['required', 'string', 'email', 'max:255'],
                'token' => ['required', 'digits:8'],
            ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 401);
        }

        $otp = new Otp();
        
        $check = $otp->validate($request->input('email'), $request->input('token'));

        if($check->status) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));

                return response()->json($check, 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong the email is not confirmed',
                ], 400);
            }
            
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User with that email is not found',
            ], 400); 
        }
       
        dd($check);

    }

    /**
    * Resend the email verification notification.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    public function resend(Request $request)
    {
        $user = User::where('email', $request["email"])->first();

        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User with that email is not found',
            ], 400); 
        }

        if ($user->hasVerifiedEmail()) {

            return response()->json([
                'status' => false,
                'message' => 'User already have verified email!',
            ], 422);
        }
        
        $otp = new Otp();

        $otpToken = $otp->generate($request->input('email'), 8, 30);
        $user->sendApiEmailVerificationNotification($otpToken->token);

        return response()->json([
            'status' => true,
            'message' => 'Email is successfully resend',
        ], 200);
    }

}