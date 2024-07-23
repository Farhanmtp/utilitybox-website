<?php

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data, $message = '', $code = 200): JsonResponse
    {
        if (is_string($data)) {
            $message = $data;
            $data = [];
        }

        $pagination = [];
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $pagination['current_page'] = $data->currentPage();
            $pagination['last_page'] = $data->lastPage();
            $pagination['total_record'] = $data->total();
            $pagination['per_page'] = $data->perPage();
            $pagination['to'] = $data->lastItem();
            $data = $data->getCollection();
        }

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        $response = array_merge($response, $pagination);

        return response()->json($response, $code);
    }

    protected function errorResponse($message, $code, $errors = []): JsonResponse
    {
        if (empty($message) && $code == 422) {
            $message = 'An error occurred while validating the data.';
        }

        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
