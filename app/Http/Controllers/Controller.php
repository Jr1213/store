<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function exceptionResponse(Exception $e): JsonResponse
    {
        return response()->json([
            'success'   => false,
            'message'   => $e->getMessage(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine()
        ], Response::HTTP_BAD_REQUEST);
    }

    public function successResponse(mixed $data, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'data'      => $data
        ], $status);
    }
}
