<?php
require_once '../Models/Reservation.php';
class RservationController{

    // public function __constract(){

    // }

    public function afficherDetailReservation(){
        $res=new Reservation();
        $resDetail=$res->afficherDetailReser($coach_id);
    }

    public function annulerRser(){
        if (isset($_POST['annuler'])) {
            $idReservation = $_POST['id_reservation'];

        if ($resr->annulerReservation($idReservation, $coach_id)) {
            header("Location: Mes-reservations-coach.php");
            exit();
        }
    }

    }




}


?>