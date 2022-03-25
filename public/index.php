<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    if ($_SERVER['APP_DEBUG'] && isset($_SERVER['HTTP_REFERER'])) {
        header('Access-Control-Allow-Origin:'.rtrim($_SERVER['HTTP_REFERER'], '/'));
    } else {
        header('Access-Control-Allow-Origin:server');
    }
    header('Access-Control-Allow-Headers:*');
    header('Access-Control-Allow-Credentials:true');
    header('Access-Control-Allow-Headers:X-Requested-With, Content-Type, withCredentials');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        die();
    }
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
