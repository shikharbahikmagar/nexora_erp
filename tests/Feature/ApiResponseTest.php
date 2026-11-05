<?php

use App\Helpers\ApiResponse;

test('success responses have a consistent shape', function () {
    $response = ApiResponse::success(['id' => 1], 'Created', 201);

    expect($response->getStatusCode())->toBe(201)
        ->and($response->getData(true))->toBe([
            'success' => true,
            'data' => ['id' => 1],
            'message' => 'Created',
        ]);
});

test('success responses have sensible defaults', function () {
    $response = ApiResponse::success();

    expect($response->getStatusCode())->toBe(200)
        ->and($response->getData(true))->toBe([
            'success' => true,
            'data' => null,
            'message' => 'success',
        ]);
});

test('error responses have a consistent shape', function () {
    $response = ApiResponse::error('Not found', 404);

    expect($response->getStatusCode())->toBe(404)
        ->and($response->getData(true))->toBe([
            'success' => false,
            'message' => 'Not found',
        ]);
});
