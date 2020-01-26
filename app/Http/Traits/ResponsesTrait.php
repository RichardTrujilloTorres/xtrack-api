<?php

namespace App\Http\Traits;

/**
 * Trait ResponsesTrait.
 */
trait ResponsesTrait
{
    /**
     * @param $data
     * @param int    $code
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(
        $data,
        $code = 200,
        $message = ''
    ) {
        return response()->json([
            'data'    => $data,
            'status'  => 'success',
            'message' => $message,
        ], $code);
    }

    /**
     * @param array  $data
     * @param int    $code
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(
        $data = [],
        $code = 400,
        $message = 'An error occurred'
    ) {
        return response()->json([
            'data'    => $data,
            'status'  => 'error',
            'message' => $message,
        ], $code);
    }
}
