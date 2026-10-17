<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * If maintenance mode is active, show "Coming Soon" page
     * to unauthenticated visitors. Authenticated admins bypass.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $maintenanceMode = Setting::get('maintenance_mode', 'maintenance');

            if ($maintenanceMode === '1' && ! auth()->check()) {
                return response()->view('frontend.maintenance', [], 503);
            }
        } catch (\Exception $e) {
            // If settings table doesn't exist yet, skip maintenance check
        }

        return $next($request);
    }
}
