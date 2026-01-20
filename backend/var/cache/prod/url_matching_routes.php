<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/products' => [[['_route' => 'api_products', '_controller' => 'App\\Controller\\ApiProductController::listProducts'], null, ['GET' => 0], null, false, false, null]],
        '/add-product' => [[['_route' => 'add_product', '_controller' => 'App\\Controller\\ProductController::addProduct'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
    ],
    [ // $dynamicRoutes
    ],
    null, // $checkCondition
];
