<?php
require_once '../Models/Client.php';

class ClientController{

    public function ClientConnecter(){
        $idUser=$_SESSION["user_id"];
        $client=new Client();
        $id_client=$client->leClientConne($idUser);
        // donner id au view ?
    }


}

?>