<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelpers
{
    public static function jsonResponse($success, $message, $data = null, $code = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
