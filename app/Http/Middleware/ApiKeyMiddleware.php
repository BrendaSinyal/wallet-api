<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Missing API Key',
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'details' => []
                ]
            ], 401);
        }

        $key = ApiKey::where('is_active', true)
            ->get()
            ->first(function ($item) use ($apiKey) {
                return Hash::check($apiKey, $item->key_hash);
            });

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key',
                'error' => [
                    'code' => 'INVALID_API_KEY',
                    'details' => []
                ]
            ], 401);
        }

        return $next($request);
    }
}