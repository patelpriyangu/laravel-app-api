<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 */
trait ApiResponseTrait
{
    /**
     * responseSuccessJson
     *
     * @param JsonResource $resource
     * @param integer $statusCode
     * @return JsonResponse
     */
    protected function responseSuccessJson(JsonResource $resource = null, $statusCode = 200)
    {
        return response()->json(
            [
                'data' => !empty($resource) ? $resource->toArray(request()) : null,
            ], $statusCode
        );
    }

    /**
     * responseFailJson
     *
     * @param mixed $message
     * @param mixed $errors
     * @param mixed $statusCode
     * @return void
     */
    protected function responseFailJson($message = null, $errors = null, $statusCode = 404)
    {
        return response()->json(
            [
                'message' => $message,
                'errors' => $errors,
            ], $statusCode
        );
    }
}
