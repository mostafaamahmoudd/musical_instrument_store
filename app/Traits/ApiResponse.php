<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function successResponse(
        string $message = 'Request completed successfully.',
        mixed $data = null,
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return $this->respondWithSuccess($message, $data, $status, $meta);
    }

    protected function createdResponse(
        string $message = 'Resource created successfully.',
        mixed $data = null,
        array $meta = []
    ): JsonResponse {
        return $this->respondWithSuccess($message, $data, Response::HTTP_CREATED, $meta);
    }

    protected function noContentResponse(): JsonResponse
    {
        return $this->respondNoContent();
    }

    protected function errorResponse(
        string $message = 'Something went wrong.',
        int $status = Response::HTTP_BAD_REQUEST,
        mixed $errors = null,
        array $meta = []
    ): JsonResponse {
        return $this->respondWithError($message, $status, $errors, $meta);
    }

    protected function paginatedResponse(
        LengthAwarePaginator $paginator,
        JsonResource $resourceClass,
        string $message = 'Request completed successfully.',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return $this->respondWithPaginator($paginator, $resourceClass, $message, $status, $meta);
    }

    protected function resourceResponse(
        JsonResource $resource,
        string $message = 'Request completed successfully.',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return $this->respondWithResource($resource, $message, $status, $meta);
    }

    protected function respondWithArray(
        array $data = [],
        string $message = 'Request completed successfully.',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
            'meta' => empty($meta) ? null : $meta,
        ], $status);
    }

    protected function respondWithResource(
        JsonResource $resource,
        string $message = 'Request completed successfully.',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource->resolve(),
            'errors' => null,
            'meta' => empty($meta) ? null : $meta,
        ], $status);
    }

    protected function respondWithCollection(
        ResourceCollection|JsonResource $resource,
        string $message = 'Request completed successfully.',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        $resolved = $resource->response()->getData(true);

        $data = $resolved['data'] ?? $resolved;

        $resourceMeta = [];
        if (isset($resolved['meta'])) {
            $resourceMeta['pagination'] = $resolved['meta'];
        }

        if (isset($resolved['links'])) {
            $resourceMeta['links'] = $resolved['links'];
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
            'meta' => array_merge($resourceMeta, $meta) ?: null,
        ], $status);
    }

    protected function respondWithPaginator(
        LengthAwarePaginator $paginator,
        JsonResource $resourceClass,
        string $message = 'Request completed successfully.',
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        $collection = $resourceClass::collection($paginator);
        $resolved = $collection->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resolved['data'] ?? [],
            'errors' => null,
            'meta' => array_merge([
                'pagination' => $resolved['meta'] ?? [],
                'links' => $resolved['links'] ?? [],
            ], $meta) ?: null,
        ], $status);
    }

    protected function respondWithSuccess(
        string $message = 'Request completed successfully.',
        mixed $data = null,
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
            'meta' => empty($meta) ? null : $meta,
        ], $status);
    }

    protected function respondNoContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function respondWithError(
        string $message = 'Something went wrong.',
        int $status = Response::HTTP_BAD_REQUEST,
        mixed $errors = null,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'meta' => empty($meta) ? null : $meta,
        ], $status);
    }

    protected function respondValidationErrors(
        mixed $errors,
        string $message = 'Validation failed.'
    ): JsonResponse {
        return $this->respondWithError(
            $message,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $errors
        );
    }

    protected function respondUnauthorized(
        string $message = 'Unauthenticated.'
    ): JsonResponse {
        return $this->respondWithError(
            $message,
            Response::HTTP_UNAUTHORIZED
        );
    }

    protected function respondForbidden(
        string $message = 'This action is forbidden.'
    ): JsonResponse {
        return $this->respondWithError(
            $message,
            Response::HTTP_FORBIDDEN
        );
    }

    protected function respondNotFound(
        string $message = 'Resource not found.'
    ): JsonResponse {
        return $this->respondWithError(
            $message,
            Response::HTTP_NOT_FOUND
        );
    }
}
