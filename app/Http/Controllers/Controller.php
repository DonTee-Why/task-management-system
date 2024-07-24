<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function jsonResponse(mixed $data = [], string $message = '', int $responseCode = 200, bool $status = true): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $responseCode);
    }
}
