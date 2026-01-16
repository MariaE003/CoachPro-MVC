<?php

// require_once __DIR__. '/../../app/Models/Disponibilite.php';
require_once __DIR__.'/../../app/Models/Disponibilite.php';

// require '../Models/Coach.php';
require_once __DIR__.'/../../app/Models/Coach.php';


class DisponibiliteController {

    private $twig;

    public function __construct($twig){
        $this->twig = $twig;
    }

    public function disponibilite(){
        $coachModel = new Coach();
        $dispo = new Disponibilite();

        $userId  = $_SESSION['user_id'];
        $idCoach = $coachModel->leCoachConne($userId);

        $error = null;

        // ajou
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
            if (empty($_POST['date']) ||empty($_POST['startTime']) ||empty($_POST['endTime'])) {
                $error = "tous les champs sont obligatoire";
            } elseif ($dispo->dispoExist($idCoach,$_POST['date'], $_POST['startTime'],$_POST['endTime'])) {
                $error = "Ce creneau est  existe deja";
            } else {
                $dispo->ajouterDispo($idCoach, $_POST['date'],$_POST['startTime'],$_POST['endTime']);
                header("Location: /CoachPro-MVC/public/coach/disponibilites");
                exit;
            }
        }

        // supprimer
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['annuler'])) {
            $dispo->supprimer((int)$_POST['annuler']);
            header("Location: /CoachPro-MVC/public/coach/disponibilites");
            exit;
        }

        $disponibilites = $dispo->dispoDuCeCoach($idCoach);

        echo $this->twig->render('coach/coach-availability.twig', [
            'disponibilites' => $disponibilites,
            'error'          => $error
        ]);
    }
}

?>