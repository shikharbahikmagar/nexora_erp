<?php

use App\Http\Middleware\AuthenticateFromCookie;
use Illuminate\Http\Request;

function runCookieMiddleware(Request $request): array
{
    $middleware = new AuthenticateFromCookie;

    $response = $middleware->handle($request, function (Request $request) {
        return response()->json(['bearer' => $request->bearerToken()]);
    });

    return $response->getData(true);
}

test('the auth_token cookie is converted into a bearer token', function () {
    $request = Request::create('/api/v1/auth/me', 'GET', cookies: [
        'auth_token' => '1|plain-text-token',
    ]);

    expect(runCookieMiddleware($request)['bearer'])->toBe('1|plain-text-token');
});

test('the auth_token cookie is url decoded before use', function () {
    $request = Request::create('/api/v1/auth/me', 'GET', cookies: [
        'auth_token' => urlencode('1|plain-text-token'),
    ]);

    expect(runCookieMiddleware($request)['bearer'])->toBe('1|plain-text-token');
});

test('an existing bearer token is not overwritten by the cookie', function () {
    $request = Request::create('/api/v1/auth/me', 'GET', cookies: [
        'auth_token' => '1|cookie-token',
    ]);
    $request->headers->set('Authorization', 'Bearer bearer-token');

    expect(runCookieMiddleware($request)['bearer'])->toBe('bearer-token');
});

test('no cookie or bearer token leaves the request without a bearer token', function () {
    $request = Request::create('/api/v1/auth/me', 'GET');

    expect(runCookieMiddleware($request)['bearer'])->toBeNull();
});
