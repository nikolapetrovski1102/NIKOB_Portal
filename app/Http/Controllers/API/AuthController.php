<?php
   
namespace App\Http\Controllers\API;
   
use App\Http\Controllers\Controller;
use Hash;
use Ichtrojan\Otp\Otp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
   
class AuthController extends Controller
{ 
    use VerifiesEmails;
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if( $user->hasVerifiedEmail() ) {
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API_TOKEN")->plainTextToken
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not confirmed',
                ], 400);
            }

            

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    protected function register(Request $request)
    {
        try {
            $otp = new Otp();

            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $otpToken = $otp->generate($request->input('email'), 8, 30);
            $user->sendApiEmailVerificationNotification($otpToken->token);

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'User Created Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    protected function me(Request $request ) {
        return response()->json([
            'status' => true,
            'user' => $request->user(),
        ], 200);
    }

    protected function verify(Request $request ) {
        return response()->json([
            'status' => true,
            'user' => $request->user(),
        ], 200);
    }

    public function logout()
    {
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }
   
}