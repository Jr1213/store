<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\CreateUserRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Resources\auth\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function join(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);
            return response()->json([
                'success'   => true,
                'token'     => $user->createToken(env('token_name'))->plainTextToken
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'not exsit'
                ], Response::HTTP_UNAUTHORIZED);
            }
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'not exsit'
                ], Response::HTTP_UNAUTHORIZED);
            }
            return response()->json([
                'success'   => true,
                'token'     => $user->createToken(env('token_name'))->plainTextToken,
                'data'      => new UserResource($user)
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
