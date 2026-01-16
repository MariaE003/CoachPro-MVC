<?php
require_once __DIR__.'/../../app/Models/Reservation.php';

require_once __DIR__.'/../../app/Models/Client.php';
require_once __DIR__.'/../../app/Models/Disponibilite.php';


class RservationController{

    private $twig;

    public function __construct($twig){
        $this->twig = $twig;
    }


    public function reserverForm($idCoach){

        $clientId = $_SESSION['user_id'];
        var_dump($clientId);

        $clientId = $_SESSION['user_id'];
        $client = new Client();
        $idClient = $client->leClientConne($clientId);

        $dispoObj = new Disponibilite();
        $dispos = $dispoObj->dispoDuCeCoach($idCoach);

        $dispoLignes = [];

        foreach ($dispos as $d) {
            $date = $d['date'];
            $dispoLignes[$date][] = [
                'start' => $d['heure_debut'],
                'end'   => $d['heure_fin'],
                'id'    => $d['id']
            ];
        }

        echo $this->twig->render('reservation/reserver.twig', [
            'idCoach' => $idCoach,
            'dispoLignes' => $dispoLignes  
        ]);
        

    }


    public function reserver($idCoach){

        if(session_status() === PHP_SESSION_NONE){
        session_start();
        }

        $clientId = $_SESSION['user_id'];
        var_dump($clientId);
        $client = new Client();
        $idClient = $client->leClientConne($clientId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])){

            $reservation = new Reservation();
            $reservation->setDate($_POST['date']);
            $reservation->setHeure_debut($_POST['Hdebut']);
            $reservation->setHeure_fin($_POST['HFin']);
            $reservation->setObjectif($_POST['objectif']);
            $reservation->AjouterReservation($idClient, $idCoach, $_POST['idDispo']);
            $dispoObj = new Disponibilite();
            $dispoObj->ModifierStatusDispo($_POST['idDispo']);
            header("Location: /mes-reservations");
            exit;
        }
    }

    public function annulerClient($idReservation){
        $client = new Client();
        $user = $_SESSION['user_id'];
        $id_client = $client->leClientConne($user);
        $reservation = new Reservation();
        $reservation->annulerReservationClient($idReservation, $id_client);
        
        
        $this->mesReservations();
    }

    
    public function mesReservations(){
        $user = $_SESSION['user_id'];
        // var_dump($clientId);
        
        $client = new Client();
        $id_client = $client->leClientConne($user);
        
        $reservation = new Reservation();
        $reservations = $reservation->affichierReservation($id_client);
        // var_dump($reservations);
        echo $this->twig->render('reservation/mes-reservations.twig', [
            'reservations' => $reservations
            ]);
            }
            
    public function reservationsCoach(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $id_user = $_SESSION['user_id'];
        $coach = new Coach();
        $idCoach = $coach->leCoachConne($id_user);

        $reservation = new Reservation();
        $reservations = $reservation->afficherDetailReser($idCoach);
        
        // var_dump($idCoach);
        // var_dump($reservations[0]['id']);

        // var_dump($reservations);

        echo $this->twig->render('reservation/Mes-reservations-coach.twig', [
            'reservations' => $reservations
        ]);
    }

    public function accepterCoach($idReservation, $idCoach){
        $result = $reservation->accepterReservation($idReservation, $idCoach);
        // var_dump($result); 
        exit;


        $reservation = new Reservation();
        $result =$reservation->accepterReservation($idReservation, $idCoach);


        header("Location: /coach/reservations");
        exit;
    }

    public function annulerCoach($idReservation, $idCoach){
        $reservation = new Reservation();
        $reservation->annulerReservation($idReservation, $idCoach);
        header("Location: /coach/reservations");
        exit;
    }

}









?>