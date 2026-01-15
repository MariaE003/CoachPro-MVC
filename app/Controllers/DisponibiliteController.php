<?php
require_once '../Models/Disponibilite.php';

class DisponibiliteController{

    // public function __contruct(){

    // }

    //
    public function ajouterDisponibilite(){
        $dispo=new Disponibilite();//une seulou plusieur?
        if(!$dispo->dispoExist($idCoach, $_POST["date"], $_POST["startTime"], $_POST["endTime"])){
            $dispo->AjouterDispo($idCoach,$_POST["date"],$_POST["startTime"],$_POST["endTime"]);
            // header("Location: coach-availability.php");
            // exit();

        }else{
            $erreur="Ce créneau existe déjà !";
        }
    }
    public function supprimerDispo(){
        $dispo->supprimer((int)$_POST["annuler"]);
        // header("Location: coach-availability.php");
        // exit();
    }
    public function AfficherDisponibiliter(){
        $disponibilite = $dispo->AfficherDispoCoach($idCoach);
        return $disponibilite;
    }
}


?>