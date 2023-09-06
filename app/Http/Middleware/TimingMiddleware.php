<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class TimingMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = Carbon::now();

        $response = $next($request);

        $end = Carbon::now();
        $duration = $end->diffInMilliseconds($start);

        $routeName = Route::currentRouteName() ?: 'Unnamed Route';
        $method = $request->method();
        $url = $request->fullUrl();

        Log::info("Request: $method $url | Route: $routeName | Processing Time: {$duration}ms");

        return $response;
    }
}
