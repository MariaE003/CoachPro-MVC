<?php
class Router {

    public static function dispatch($uri, $twig) {

        // if ($uri === '/' || $uri === '/register') {
        //     $controller = new UserController();
        //     $controller->showRegister($twig);
        // }

        // if ($uri === '/register/store') {
        //     $controller = new UserController();
        //     $controller->register();
        // }
    // }

    if ($uri === '/register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new UserController())->registerForm();
    exit;
}

if ($uri === '/register/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    (new UserController())->register();
    exit;
}

}}
?>