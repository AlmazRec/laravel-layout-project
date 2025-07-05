<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait ResponseTrait
{
    protected function success(
        string $message = 'success',
        mixed  $data = null,
        int    $statusCode = 200
    ): JsonResponse
    {
        $response = ['message' => $message];

        if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
            $response['data'] = $data->items();

            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ];

            $response['links'] = [
                'first' => $data->url(1),
                'last' => $data->url($data->lastPage()),
                'prev' => $data->previousPageUrl(),
                'next' => $data->nextPageUrl(),
            ];

        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }


    protected function error(
        string $message = 'error',
        mixed  $errors = null,
        int    $statusCode = 500
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
