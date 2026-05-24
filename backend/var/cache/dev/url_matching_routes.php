<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/favorites/variables/users' => [[['_route' => 'api_favorites_variable_users', '_controller' => 'App\\Controller\\ApiFavoritesController::favoritesVariableUsers'], null, ['GET' => 0], null, false, false, null]],
        '/api/perfumes' => [[['_route' => 'app_api_perfumes', '_controller' => 'App\\Controller\\ApiPerfumesController::index'], null, null, null, false, false, null]],
        '/users' => [[['_route' => 'app_api_users', '_controller' => 'App\\Controller\\ApiUsersController::index'], null, ['POST' => 0], null, false, false, null]],
        '/api/me' => [[['_route' => 'api_me', '_controller' => 'App\\Controller\\ApiUsersController::me'], null, ['GET' => 0], null, false, false, null]],
        '/api/all/users' => [[['_route' => 'api_all_users', '_controller' => 'App\\Controller\\ApiUsersController::users'], null, ['GET' => 0], null, false, false, null]],
        '/login' => [[['_route' => 'login', '_controller' => 'App\\Controller\\AuthController::login'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|favorites/(?'
                        .'|([^/]++)(?'
                            .'|(*:74)'
                        .')'
                        .'|users(*:87)'
                    .')'
                    .'|perfumes/([^/]++)(*:112)'
                    .'|user/([^/]++)(?'
                        .'|(*:136)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        74 => [
            [['_route' => 'app_api_favorites', '_controller' => 'App\\Controller\\ApiFavoritesController::favorites'], ['id'], ['POST' => 0], null, false, true, null],
            [['_route' => 'delete_api_favorites', '_controller' => 'App\\Controller\\ApiFavoritesController::deleteFavorites'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        87 => [[['_route' => 'api_favorites_users', '_controller' => 'App\\Controller\\ApiFavoritesController::favoritesUsers'], [], ['GET' => 0], null, false, false, null]],
        112 => [[['_route' => 'api_perfumes_detail', '_controller' => 'App\\Controller\\ApiPerfumesController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        136 => [
            [['_route' => 'api_edit_user', '_controller' => 'App\\Controller\\ApiUsersController::editUser'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'api_delete_user', '_controller' => 'App\\Controller\\ApiUsersController::deleteUser'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
