<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    const ROLE_USER = 1;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => $validator->errors()

                ],
                400
            );
        }

        $user = User::create(
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->password)
            ]
        );

        $user->roles()->attach(self::ROLE_USER);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }

    public function me()
    {
        return response()->json(
            [
                "success" => true,
                "data" => auth()->user()
            ]
        );
    }
}
