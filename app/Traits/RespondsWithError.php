<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait RespondsWithError
{
    /**
     * Return an error response.
     *
     * Automatically detects if the request expects JSON and returns
     * the appropriate response type. For web requests, redirects back
     * with the error message in session and preserves old input.
     *
     * @param  string  $message  The error message to display
     * @param  int  $status  HTTP status code (default: 400)
     */
    protected function respondError(string $message, int $status = 400): RedirectResponse|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['error' => $message], $status);
        }

        return back()->with('error', $message)->withInput();
    }

    /**
     * Return a success response.
     *
     * Automatically detects if the request expects JSON and returns
     * the appropriate response type. For web requests, redirects back
     * or to a named route with the success message in session.
     *
     * @param  string  $message  The success message to display
     * @param  string|null  $redirectRoute  Optional route name to redirect to (defaults to back)
     */
    protected function respondSuccess(string $message, ?string $redirectRoute = null): RedirectResponse|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['success' => $message]);
        }

        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : back();

        return $redirect->with('success', $message);
    }
}
