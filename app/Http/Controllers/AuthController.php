<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
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

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }
}
