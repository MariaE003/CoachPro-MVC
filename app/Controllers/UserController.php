<?php 
require_once __DIR__ .'/../../app/Models/User.php';
require_once __DIR__.'/../../app/Models/Client.php';
require_once __DIR__.'/../../app/Models/Coach.php';
require_once __DIR__.'/../../config/DataBase.php';


class UserController {

     private $twig;
     protected $pdo; // <-- ajoute ça en haut de la classe

        public function __construct($twig) {
            $this->twig = $twig;
            // $this->pdo = DataBase::connect(); 
    }
    public function registerForm() {
    echo $this->twig->render('auth/register.twig', [
        'isLogged' => isset($_SESSION['user_id']),
        'role' => $_SESSION['role'] ?? null
    ]);
}


    public function register() {
        // $pdo = DataBase::connect();
        // var_dump($this->pdo);
        
        // var_dump($_POST);
        // exit;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $user = new User();
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setRole($_POST['role']);

        $id = $user->register();

        if ($id) {

            if ($_POST['role'] === 'client') {
                $client = new Client();
                $client->setId($id);
                $client->setNom($_POST['lastName']);
                $client->setPrenom($_POST['firstName']);
                $client->setTelephone($_POST['phone']);
                $client->registerClient();
            }

            if ($_POST['role'] === 'coach') {
                $coach = new Coach();
                $coach->setUserId($id);
                $coach->setNom($_POST['lastName']);
                $coach->setPrenom($_POST['firstName']);
                $coach->setTelephone($_POST['phone']);
                $coach->registerCoach();
            }

            header("Location: /CoachPro-MVC/public/login");
            exit();
        }
    }
    public function loginForm(){
        echo $this->twig->render('auth/login.twig');
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }


        $User=new User();
        if ($User->login($_POST['email'],$_POST['password'])) {

        $_SESSION["user_id"] = $User->getId();
        $_SESSION["role"]    = $User->getRole();

        if ($_SESSION["role"]==="coach"){
                $coach=new Coach();
                if ($coach->virifierSiCoachCompleterProfil($_SESSION["user_id"])) {
                header("Location: /CoachPro-MVC/index.php");
                exit();
            }
                // echo 'hi my coach';
                header("Location: /CoachPro-MVC/public/addProfilCoach");
                exit();
            }

            header("Location: /CoachPro-MVC/index.php");
            exit();
        }
        }

    
}



?>