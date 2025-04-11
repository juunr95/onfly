<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Invalid credentials.',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Was not possible create token.'
            ], 500);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth()->user()
        ]);
    }

    /**
     * Registra um novo usuário e gera o token JWT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed', // require 'password_confirmation'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => 'Validation error',
                'details' => $validator->errors(),
            ], 422);
        }

        $data = $request->only('email', 'password');
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => 3600 * 60,
        ], 201);
    }
}
