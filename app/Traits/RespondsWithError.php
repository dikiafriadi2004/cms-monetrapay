<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

trait RespondsWithError
{
    /**
     * Handle errors consistently across controllers
     *
     * @param \Throwable $e
     * @param string $userMessage
     * @param string $redirectRoute
     * @param array $context
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleError(
        \Throwable $e,
        string $userMessage = 'Terjadi kesalahan. Silakan coba lagi.',
        string $redirectRoute = null,
        array $context = []
    ): JsonResponse|RedirectResponse {
        // Log the error with context
        Log::error($e->getMessage(), array_merge([
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], $context));

        // Check if request expects JSON
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $userMessage,
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        // Return redirect with error message
        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : back();
        
        return $redirect->with('error', $userMessage);
    }

    /**
     * Handle validation errors
     *
     * @param array $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function validationError(
        array $errors,
        string $message = 'Data yang Anda masukkan tidak valid.'
    ): JsonResponse|RedirectResponse {
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], 422);
        }

        return back()->withErrors($errors)->withInput()->with('error', $message);
    }

    /**
     * Handle success responses
     *
     * @param string $message
     * @param mixed $data
     * @param string $redirectRoute
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function successResponse(
        string $message,
        mixed $data = null,
        string $redirectRoute = null
    ): JsonResponse|RedirectResponse {
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ]);
        }

        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : back();
        
        return $redirect->with('success', $message);
    }

    /**
     * Handle not found errors
     *
     * @param string $message
     * @param string $redirectRoute
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function notFoundError(
        string $message = 'Data tidak ditemukan.',
        string $redirectRoute = null
    ): JsonResponse|RedirectResponse {
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        }

        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : back();
        
        return $redirect->with('error', $message);
    }

    /**
     * Handle unauthorized access
     *
     * @param string $message
     * @param string $redirectRoute
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthorizedError(
        string $message = 'Anda tidak memiliki akses untuk melakukan tindakan ini.',
        string $redirectRoute = null
    ): JsonResponse|RedirectResponse {
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : back();
        
        return $redirect->with('error', $message);
    }
}
