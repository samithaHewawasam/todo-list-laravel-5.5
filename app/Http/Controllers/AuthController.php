<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Authenticate an user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'code' => 1,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
        }

        $token = JWTAuth::attempt($credentials);

        if ($token) {
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['code' => 2, 'message' => 'Invalid credentials.'], 401);
        }
    }

    /**
     * Get the user by token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        JWTAuth::setToken($request->input('token'));
        $user = JWTAuth::toUser();
        return response()->json($user);
    }
}
