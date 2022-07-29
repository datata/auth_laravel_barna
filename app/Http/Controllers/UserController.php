<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    const SUPER_ADMIN_ROLE = 3;

    public function addSuperAdminRoleToUser($id)
    {
        try {
            $user = User::query()->find($id);
            $user->roles()->attach(self::SUPER_ADMIN_ROLE);

            return response()->json([
                'success' => true,
                'message' => "User role changed successfull",
                'data' => $user,
            ]);
        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be superAdmin'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeSuperAdminRoleToUser($id)
    {
        try {
            $user = User::query()->find($id);
            $user->roles()->detach(self::SUPER_ADMIN_ROLE);

            return response()->json([
                'success' => true,
                'message' => "Super Admin Role deleted successfull",
                'data' => $user,
            ]);
        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be superAdmin'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
