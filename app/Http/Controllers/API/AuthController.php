<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('username','password');

        if($token = auth()->attempt($credentials)) {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ])->header('Authorization', $token)
            ->header('Access-Control-Allow-Headers','Authorization')
            ->header('Access-Control-Expose-Headers','Authorization');
        }
        return response()->json(['error'=>'Unauthorized'], 401);
    }

    public function register(Request $request) {
        
    }

    public function logout() {
        Auth::guard()->logout();

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out successfully'
        ], 200);
    }

    public function user(Request $r) {
        $user = User::find(Auth::user()->id);        
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh() {

    }
}
