<?php
namespace LiteWebServiceLib;

require_once __DIR__.'/HTTPStatusCode.php';

use LiteWebServiceLib\HTTPStatusCode;


function request_methods($methods) {
    if (!is_array($methods)) {
        $methods = [$methods];
    }

    if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
        http_response_code(HTTPStatusCode::METHOD_NOT_ALLOWED);
        exit;
    }
}

function query_string_parameters(array $qsps) {
    foreach ($qsps as $qsp) {
        $qsp->validate();
    }
}

function run() {
    $function = ACTIONS_NAMESPACE.$_GET[ACTION].ACTION_SUFFIX;

    if (isset($_GET[ACTION]) && function_exists($function)) {
        call_user_func($function);
        exit;
    }

    http_response_code(HTTPStatusCode::NOT_FOUND);
    exit;
}
