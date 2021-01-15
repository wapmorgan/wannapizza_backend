<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
    public function handle(Request $request, Closure $next)
    {
        // Get the response
        $response = $next($request);

        // Calculate execution time
        $executionTime = microtime(true) - LARAVEL_START;

        $content = $response->getContent();

        // I assume you're using valid json in your responses
        // Then I manipulate them below
        $content = [
            'data' => $content === null ? null : json_decode($content, true),
            'executionTime' => $executionTime,
        ];

        // Change the content of your response
        $response->setContent(json_encode($content));

        // Return the response
        return $response;
    }
}
