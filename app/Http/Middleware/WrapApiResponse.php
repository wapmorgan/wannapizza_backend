<?php

namespace App\Http\Middleware;


use Closure;

class WrapApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the response
        $response = $next($request);

        // Calculate execution time
        $executionTime = microtime(true) - LARAVEL_START;

        // I assume you're using valid json in your responses
        // Then I manipulate them below
        $content = [
            'data' => json_decode($response->getContent(), true),
            'executionTime' => $executionTime,
        ];

        // Change the content of your response
        $response->setContent(json_encode($content));

        // Return the response
        return $response;
    }
}
