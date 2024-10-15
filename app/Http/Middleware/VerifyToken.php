<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        Log::info('TOKEN => '.$token);
        // Si no hay token, devolver una respuesta de error
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $url = env('API_AUTH_URL').'/verify-token';

        Log::info('URL => '.$url);
        // Hacer la solicitud POST a Laravel B para validar el token
        $response = Http::withToken($token)->post($url, []);
        Log::info('JSON => '. json_decode($response->body())->is_valid);

        // Log para depuración (opcional)
        Log::info('IS VALID ? => '.$response->body());

        // Si el token no es válido, denegar el acceso
        if ($response->failed() || !$response->json('is_valid')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
