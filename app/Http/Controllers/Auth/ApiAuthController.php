<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use JWTAuth;
use JWTAuthException;
use App\User;

class ApiAuthController extends Controller
{

    public function __construct()
    {
        $this->user = new User;
    }

    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        $jwt = '';

        try {
            if (!$jwt = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'invalid_credentials',
                ], 401);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'failed_to_create_token',
            ], 500);
        }
        return response()->json([
            'response' => 'success',
            'result' => ['token' => $jwt]
        ]);
    }

    public function getAuthUser(Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        return response()->json(['result' => $user]);
    }

    public function profile_update(Request $request){

        $token = JWTAuth::getToken();
        $user = User::findOrFail(JWTAuth::toUser($token)->id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        if($request->get('password'))
        $user->password = \Hash::make($request->get('password'));
        $user->save();

        return response()->json(['result' => $user]);
    }


}
