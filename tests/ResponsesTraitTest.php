<?php

namespace Tests;

use App\Http\Traits\ResponsesTrait;
use Illuminate\Http\JsonResponse;

class ResponsesTraitTest extends TestCase
{
    use ResponsesTrait;

    /**
     * @test
     */
    public function successResponse()
    {
        $message = 'All good';
        $data = [
            'sample' => 'data',
        ];

        $response = $this->success($data);

        $this->assertTrue($response instanceof JsonResponse);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($response->getContent(), json_encode([
            'data'    => $data,
            'status'  => 'success',
            'message' => '',
        ]));

        $response = $this->success($data, 200, $message);

        $this->assertTrue($response instanceof JsonResponse);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($response->getContent(), json_encode([
            'data'    => $data,
            'status'  => 'success',
            'message' => $message,
        ]));

        $response = $this->success($message, 201);
        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function errorResponse()
    {
        $message = 'An error occurred';
        $data = [
            'sample' => 'went wrong',
        ];
        $response = $this->error($data);

        $this->assertTrue($response instanceof JsonResponse);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($response->getContent(), json_encode([
            'data'    => $data,
            'status'  => 'error',
            'message' => $message,
        ]));

        $response = $this->error($data, 400, $message);

        $this->assertTrue($response instanceof JsonResponse);

        $response = $this->error($data, 500);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
