<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

class PaymentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secretKey = 'your_secret_key';
        $token = $request->header('Authorization');
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        $request->user = $decoded;
        return $next($request);
    }
}
