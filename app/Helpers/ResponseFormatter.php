<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ResponseFormatter
{
    public static function success(
        $data = null,
        $message = 'Success',
        $status = 'success',
        $code = ResponseAlias::HTTP_OK
    ): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error(
        $data = null,
        $message = 'Error',
        $status = 'error',
        $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
