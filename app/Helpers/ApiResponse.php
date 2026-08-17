<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /*
     * @param $data
     * @param $message
     * @param $status
     */
    public static function success($data = null, $message = 'success', $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /*
     * @param $data
     * @param $message
     * @param $status
     */
    public static function error($message = 'Error', $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
