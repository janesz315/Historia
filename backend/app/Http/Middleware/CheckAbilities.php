<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbilities
{
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        // Ellenőrizzük, hogy van-e bejelentkezett user (Sanctum authentikáció)
        $user = $request->user();

        if (!$user || !$request->user()->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Megkapjuk a tokenhez tartozó képességeket (abilities)
        $tokenAbilities = $request->user()->currentAccessToken()->abilities;

        // Ellenőrizzük, hogy legalább egy szükséges képesség megvan
        foreach ($abilities as $ability) {
            if (in_array($ability, $tokenAbilities) || in_array('*', $tokenAbilities)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized.'], 403);
    }
}
