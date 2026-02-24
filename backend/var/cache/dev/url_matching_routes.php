<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/all/perfumes' => [[['_route' => 'app_api_all_perfumes', '_controller' => 'App\\Controller\\ApiAllPerfumesController::index'], null, null, null, false, false, null]],
        '/api/books' => [[['_route' => 'api_books', '_controller' => 'App\\Controller\\ApiBookController::index'], null, ['GET' => 0], null, false, false, null]],
        '/api/perfumerias' => [[['_route' => 'app_api_perfumerias', '_controller' => 'App\\Controller\\ApiPerfumeriasController::index'], null, null, null, false, false, null]],
        '/api/perfumes/club' => [[['_route' => 'app_api_perfumes_club', '_controller' => 'App\\Controller\\ApiPerfumesClubController::index'], null, null, null, false, false, null]],
        '/api/perfumes' => [[['_route' => 'app_api_perfumes', '_controller' => 'App\\Controller\\ApiPerfumesController::index'], null, null, null, false, false, null]],
        '/api/products' => [[['_route' => 'api_products', '_controller' => 'App\\Controller\\ApiProductController::listProducts'], null, ['GET' => 0], null, false, false, null]],
        '/add-product' => [[['_route' => 'add_product', '_controller' => 'App\\Controller\\ProductController::addProduct'], null, null, null, false, false, null]],
        '/product/curl' => [[['_route' => 'app_product_curl', '_controller' => 'App\\Controller\\ProductCurlController::index'], null, null, null, false, false, null]],
        '/api/scrape-books' => [[['_route' => 'scrape_books', '_controller' => 'App\\Controller\\ScrapeController::scrapeBooks'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|books/([^/]++)(*:64)'
                    .'|perfume(?'
                        .'|rias/([^/]++)(*:94)'
                        .'|s/(?'
                            .'|club/([^/]++)(*:119)'
                            .'|([^/]++)(*:135)'
                        .')'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        64 => [[['_route' => 'api_book_detail', '_controller' => 'App\\Controller\\ApiBookController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        94 => [[['_route' => 'api_perfumerias_detail', '_controller' => 'App\\Controller\\ApiPerfumeriasController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        119 => [[['_route' => 'api_perfumes_club_detail', '_controller' => 'App\\Controller\\ApiPerfumesClubController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        135 => [
            [['_route' => 'api_perfumes_detail', '_controller' => 'App\\Controller\\ApiPerfumesController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
