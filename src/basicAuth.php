<?php

namespace src;

use Tuupola\Middleware\HttpBasicAuthentication;

function basicAuth(): HttpBasicAuthentication {
    return new HttpBasicAuthentication([
        "secure" => false,
        "users"=> [
            "admin" => "3210"
        ]
    ]);
}

function advAuth(): HttpBasicAuthentication {
    return new HttpBasicAuthentication([
        "secure" => false,
        "users"=> [
            "admin" => "0123"
        ]
    ]);
}