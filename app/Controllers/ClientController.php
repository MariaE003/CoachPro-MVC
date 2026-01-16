<?php

require_once __DIR__.'/../../app/Models/Client.php';


class ClientController{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
            // $this->pdo = DataBase::connect(); 
    }
    public function ClientConnecter(){
        $idUser=$_SESSION["user_id"];
        $client=new Client();
        $id_client=$client->leClientConne($idUser);
        // donner id au view ?
    }

    public function profilClient(){
    $idUser=$_SESSION["user_id"];
    $client = new Client();
    $test=$client->leClientConne($idUser);
    $Info=$client->profilSportif($test);
    echo $this->twig->render('client/sportif-profil.twig', [
        'client' => $Info
    ]);
}

}

?>