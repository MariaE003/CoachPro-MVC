<?php
require_once '../vendor/autoload.php';
require_once '../app/Controllers/UserController.php';
require_once '../app/Controllers/CoachController.php';
require_once '../app/Controllers/DisponibiliteController.php';
require_once '../app/Controllers/RservationController.php';
require_once '../core/Session.php';
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/../app/Views');
$twig = new Environment($loader);

// var global pour les template
$twig->addGlobal('isLogged', $isLogged);
$twig->addGlobal('role', $role);


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

// detail coach
if (preg_match("#^/coach/profile/([0-9]+)$#", $uri, $matches)) {
    $controller = new CoachController($twig);
    $controller->profileCoach((int)$matches[1]);
}


if ($uri === '/coach/disponibilites') {
    $controller = new DisponibiliteController($twig);
    $controller->disponibilite();
    exit;
}

if ($uri === '/coach/reserver') {
    if (!isset($_GET['idCoach'])) {
        echo "coach id n'existe pas !";
        exit;
    }

    $idCoach = (int) $_GET['idCoach'];
    
    $controller = new RservationController($twig);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
        $controller->reserver($idCoach);
    } else {
        $controller->reserverForm($idCoach);
    }
}



if ($uri === '/mes-reservations') {
    $controller = new RservationController($twig);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_res=$_POST["id_reservation"];
        $controller->annulerClient($id_res);
    } else {
        $controller->mesReservations();
    }
}


// les reservation pour coach
// public/index.php


if ($uri === '/coach/reservations') {
     $controller=new RservationController($twig);
        $controller->reservationsCoach();
    exit;
}

if ($uri === '/coach/reservation/accepter' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idReservation = $_POST['id_reservation'] ?? 0;
    $id_user = $_SESSION['user_id'];
    $coach = new Coach();
    $idCoach = $coach->leCoachConne($id_user);

    $controller=new RservationController($twig);
    $controller->accepterCoach($idReservation,$idCoach);

    exit;
}

if ($uri === '/coach/reservation/annuler' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idReservation = $_POST['id_reservation'] ?? 0;
    $id_user = $_SESSION['user_id'];
    $coach = new Coach();
    $idCoach = $coach->leCoachConne($id_user);

    $controller=new RservationController($twig);
    $controller->annulerCoach($idReservation,$idCoach);

    exit;
}





// echo $_SESSION["user_id"];


// http_response_code(404);
// echo "404 Not Found";

?>
