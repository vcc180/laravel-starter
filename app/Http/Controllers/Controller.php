<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function ok($data = null, $message = null, int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function fail($message = null, int $code = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function validateOrFail(array $rules, $request = null)
    {
        $request = $request ?: \Illuminate\Support\Facades\Request::instance();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }
}
