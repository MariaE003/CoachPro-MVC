<?php
require_once '../vendor/autoload.php';
require_once '../app/Controllers/UserController.php';
require_once '../app/Controllers/CoachController.php';
require_once '../core/Session.php';
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/../app/Views');
$twig = new Environment($loader);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/CoachPro-MVC/public';
$uri = str_replace($basePath, '', $uri);

if ($uri === '/register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new UserController($twig);
    $controller->registerForm();
    exit;
}

if ($uri === '/register/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController($twig);
    $controller->register();
    exit;
}
if ($uri=="/login" && $_SERVER['REQUEST_METHOD']==='GET') {
  $controller=new UserController($twig);
  $controller->loginForm();

}

if ($uri === '/login/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController($twig);
    $controller->login();
    exit;
}



if ($uri=="/addProfilCoach" && $_SERVER['REQUEST_METHOD']==='GET') {
  $controller=new CoachController($twig);
  $controller->addProfilCoachForm();

}

if ($uri === '/addProfilCoach/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller = new CoachController($twig);
    $id_user=$_SESSION["user_id"]; //session
    $controller->addProfilCoach($id_user);
    exit;
}

if ($uri=="/coachs" && $_SERVER['REQUEST_METHOD']==='GET') {
  $controller=new CoachController($twig);
  $controller->coachPage();
}





// echo $_SESSION["user_id"];


// http_response_code(404);
// echo "404 Not Found";

?>
