<?php

namespace Middlewares;

use Src\Middleware;
use Src\Request;
use Exception;

class TokenMiddleware extends Middleware
{
    // Полные URI маршрутов, не требующих авторизации
    protected array $skipRoutes = [
        '/pop-it-mvc/api/login',
        '/pop-it-mvc/api/register',
        '/'
    ];

    public function handle(Request $request): Request
    {
//        $path = $this->normalizePath($request->uri());
//
//        // Пропускаем публичные маршруты
//        if (in_array($path, $this->skipRoutes)) {
//            return $request;
//        }
//
//        // Проверяем авторизацию для защищенных маршрутов
//        $token = $this->getTokenFromRequest($request);
//        $user = $this->authenticate($token);
//
//        $request->set('auth_user', $user);
        return $request;
    }

    protected function getTokenFromRequest(Request $request): string
    {
        $header = $request->header('Authorization');

        if (empty($header)) {
            throw new Exception('Authorization header is missing', 401);
        }

        if (!str_starts_with($header, 'Bearer ')) {
            throw new Exception('Invalid token format. Expected: Bearer <token>', 401);
        }

        return substr($header, 7);
    }

    protected function authenticate(string $token)
    {
        $user = app()->auth::checkByToken($token);

        if (!$user) {
            throw new Exception('Invalid or expired token', 401);
        }

        return $user;
    }

    protected function normalizePath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        // Удаляем возможные слеши в начале и конце
        return trim($path, '/');
    }
}