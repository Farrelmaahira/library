<?php

namespace App\Http\Controllers\v1\api\auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\v1\api\BaseController as ApiBaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiBaseController
{
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
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
        {
            return $this->sendError('Validation Error.', $validate->errors());      
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        $success['data'] = $user;
        $success['token'] = $user->createToken('library')->plainTextToken;

        return $this->sendResponse($success, 'Register Success');
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
