<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasGuestToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasCookie('guest')) {
            $this->generateAndSetToken($request);
        } else {
            $token = $request->cookie('guest');

            if (Token::query()->where('uuid', $token)->count() === 0) {
                $this->generateAndSetToken($request);
            }
        }

        return $next($request);
    }

    private function generateAndSetToken(Request $request): void
    {
        $token = Token::query()->create([
            'ip' => $request->ip(),
        ]);

        Cookie::queue('guest', $token->uuid, now()->addYear()->diffInMinutes());
    }
}
