<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;

trait ExceptionResponse
{
    public function exceptionMessage($exception): JsonResponse
    {
        return response()->json(['errors' => "message: {$exception->getMessage()},
        line: {$exception->getLine()}, file: {$exception->getFile()}"], 500);
    }

    public function responseMessage($status, $message = 'success', $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    public function responseMessageNotFound(): JsonResponse
    {
        return $this->responseMessage('error', 'Recurso não encontrado.', 422);
    }
}
