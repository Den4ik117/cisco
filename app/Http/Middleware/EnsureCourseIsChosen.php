<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseIsChosen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasCookie('guest')) {
            $token = $request->cookie('guest');

            $exists = Token::query()
                ->where('uuid', $token)
                ->whereNotNull('course_id')
                ->exists();

            if ($exists) return $next($request);
        }

        return to_route('courses.choose');
    }

    private function generateAndSetToken(Request $request): void
    {
        $token = Token::query()->create([
            'ip' => $request->ip(),
        ]);

        Cookie::queue('guest', $token->uuid, now()->addYear()->diffInMinutes());
    }
}
