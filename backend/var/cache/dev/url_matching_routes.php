<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/perfumes' => [[['_route' => 'app_api_perfumes', '_controller' => 'App\\Controller\\ApiPerfumesController::index'], null, null, null, false, false, null]],
        '/users' => [[['_route' => 'app_api_users', '_controller' => 'App\\Controller\\ApiUsersController::index'], null, ['POST' => 0], null, false, false, null]],
        '/api/favorites' => [[['_route' => 'app_api_favorites', '_controller' => 'App\\Controller\\ApiUsersController::favorites'], null, ['POST' => 0], null, false, false, null]],
        '/api/headers' => [[['_route' => 'app_apiusers_headers', '_controller' => 'App\\Controller\\ApiUsersController::headers'], null, ['GET' => 0], null, false, false, null]],
        '/api/me' => [[['_route' => 'api_me', '_controller' => 'App\\Controller\\ApiUsersController::me'], null, ['GET' => 0], null, false, false, null]],
        '/login' => [[['_route' => 'login', '_controller' => 'App\\Controller\\AuthController::login'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/perfumes/([^/]++)(*:64)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        64 => [
            [['_route' => 'api_perfumes_detail', '_controller' => 'App\\Controller\\ApiPerfumesController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
