<?php

namespace src;

function slimConfiguration(): \Slim\Container
{
    $configuration = [
        'settings' => [
        'displayErrorDetails' => getenv('DISPLAY_ERRORS_DETAILS'),
        //'determineRouteBeforeAppMiddleware' => true,
        //'addContentLengthHeader' => false,
        ],
    ];
    return new \Slim\Container($configuration);
}