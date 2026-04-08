<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        Log::info('API KEY MIDDLEWARE DEBUG', [
            'received_header' => $apiKey,
            'all_headers' => $request->headers->all(),
            'active_keys_count' => ApiKey::where('is_active', true)->count(),
            'active_keys' => ApiKey::where('is_active', true)->get(['id', 'name', 'key_prefix', 'is_active'])->toArray(),
        ]);

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

        Log::info('API KEY MATCH RESULT', [
            'received_header' => $apiKey,
            'matched' => $key ? true : false,
            'matched_key' => $key ? [
                'id' => $key->id,
                'name' => $key->name,
                'key_prefix' => $key->key_prefix,
            ] : null,
        ]);

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