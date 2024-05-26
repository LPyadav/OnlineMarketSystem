<?php

namespace App\Helpers;

class APIResponse
{
    public static function success($data = [], $message = 'Operation successful', $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);

    }

    public static function error($message = 'An error occurred', $status = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
