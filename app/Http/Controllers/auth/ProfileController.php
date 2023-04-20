<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\UpdateProfileRequest;
use App\Http\Resources\auth\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success'   => true,
                'data'      => new UserResource(request()->user())
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            if (!Hash::check($request->password_old, $user->password)) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'not accepted'
                ], Response::HTTP_UNAUTHORIZED);
            }
            $user->update([
                'password'  => Hash::make($request->password),
            ]);
            return response()->json([
                'success'   => true,
                'message'   => 'updated'
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id)
    {
        try {
            $user = $request->user();
            $data = [];
            $request->name  ? $data['name'] = $request->name : '';
            $request->email ? $data['email'] = $request->email : '';
            $user->update($data);
            return response()->json([
                'success'   => true,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = request()->user();
            $user->delete();
            return response()->json([
                'success'   => false,
                'message'   => 'deleted'
            ], Response::HTTP_NO_CONTENT);
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
