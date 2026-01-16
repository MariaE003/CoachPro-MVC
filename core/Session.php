<?php
//  session_start();

// if(!isset($_SESSION['user_id'])) {
//     header("Location: /FitCoach-Pro/auth/login.twig");
//     exit();
// }
// // echo "<a href='./auth/login.php'></a>"

// // logout

// if(isset($_POST["logout"])){
//     session_unset();
//     session_destroy();
//     header("Location: /FitCoach-Pro/auth/login.twig");
//     // C:\laragon\www\FitCoach-Pro\auth\login.php
//     exit();
// }



// if (isset($RolePage) && $_SESSION["role"]!==$RolePage){
//     // echo $_SESSION["role"];
//     header("Location: /FitCoach-Pro/index.php");
//     exit();
// }



session_start();


$isLogged = isset($_SESSION['user_id']);
$role = $isLogged ? $_SESSION['role'] : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: /CoachPro-MVC/public/login");
    exit();
}
?>


