<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseHelpers
{
    private ?array $_api_helpers_defaultSuccessData = ['success' => true];


    public function respondNoContent($data = null)
    {
        $data ??= [];

        return $this->apiResponse($data, Response::HTTP_NO_CONTENT);
    }

    public function respondWithSuccess($contents = null): JsonResponse
    {
        return $this->apiResponse($contents);
    }

    public function respondOk(string $message): JsonResponse
    {
        return $this->respondWithSuccess(['success' => $message]);
    }

    public function respondError($message = null, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Error'],
            $code
        );
    }

    public function respondForbidden(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Forbidden'],
            Response::HTTP_FORBIDDEN
        );
    }

    public function respondUnAuthenticated(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Unauthenticated'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function respondNotFound($message, ?string $key = 'error'): JsonResponse {

        return $this->apiResponse(
            [$key => $message],
            Response::HTTP_NOT_FOUND
        );
    }

    private function apiResponse($data, int $code = 200)
    {
        return response()->json($data, $code);
    }

}
