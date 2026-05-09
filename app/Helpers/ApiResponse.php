<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = [], $message = 'success')
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error($message = 'error', $code = 400)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ]);
    }
}