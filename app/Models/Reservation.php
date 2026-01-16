<?php

class Reservation{
    
    private $id; 
    private $id_client;
    private $id_coach;
    private $id_disponibilite;

    private $date;
    private $heure_debut;
    private $heure_fin;
    private $objectif;
    private $status;

    private $pdo;

    public function __construct(){
        $this->pdo=DataBase::connect();
    }

    public function getIdcoach(){return $this->id_coach;}
    // public function setidcoach(int $idcoach){$this->idcoach=$idcoach;}

    public function getId(){return $this->id;}

    public function getDate(){return $this->date;}
    public function setDate($date){$this->date=$date;}

    public function getHeure_debut(){return $this->heure_debut;}
    public function setHeure_debut($heure_debut){$this->heure_debut=$heure_debut;}

    public function getHeure_fin(){return $this->heure_fin;}
    public function setHeure_fin($heure_fin){$this->heure_fin=$heure_fin;}

    public function getDisponible(){return $this->disponible;}
    public function setDisponible($disponible){$this->disponible=$disponible;}

    public function getObjectif(){return $this->objectif;}
    public function setObjectif($objectif){$this->objectif=$objectif;}

    public function getStatus(){return $this->status;}
    public function setStatus($status){$this->status=$status;}

    // id du coach
    public function AjouterReservation($id_client,$id_coach,$id_disponibilite){
        $req1 = $this->pdo->prepare("INSERT INTO reservation (id_client, id_coach, id_disponibilite, heure_debut, heure_fin, objectif, date,status) 
        VALUES(?,?,?,?,?,?,?,?)");
        $req1->execute([
            $id_client,
            $id_coach,
            $id_disponibilite,

            $this->heure_debut,
            $this->heure_fin,
            $this->objectif,
            $this->date,
            "en_attente"

            // $this->status,
        ]);
        $idDispo = $this->pdo->lastInsertId();
        return $idDispo;

    }

    public function ModifierReservation(int $idReser){
        $req2 = $this->pdo->prepare("UPDATE reservation set  heure_debut=?, heure_fin=?, objectif=?,status=?, date=?  where id_client=?");
        $req2->execute([

            $this->heure_debut,
            $this->heure_fin,
            $this->objectif,
            $this->date,

            'accepter',
            $idReser
        ]);

    }
    public function affichierReservation($id_client){
        // $reqSelect=$this->pdo->prepare("SELECT * from reservation");
        $statusEnAttente='en_attente';
        $reqSelect = $this->pdo->prepare("SELECT c.nom, c.prenom, c.prix, c.photo, r.* FROM reservation r 
            INNER JOIN coach c ON r.id_coach=c.id 
            WHERE r.id_client=? AND r.status=? "
            );
        $reqSelect->execute([
            $id_client, $statusEnAttente
        ]);
        $MesRe=$reqSelect->fetchAll(PDO::FETCH_ASSOC);
        if ($MesRe) {
            return $MesRe;
            
        }else{
            echo "reservation non trouve.";
            return false;
        }
    }


    public function supprimerReservation($id){
        $reqDelete=$this->pdo->prepare("DELETE from reservation where id=?");
        $reqDelete->execute([
            $id
        ]);
    }


    public function afficherDetailReser($id_coach1){
        $statusEnAttent='en_attente';

        $req=$this->pdo->prepare("SELECT c.nom,c.prenom,c.prix,c.photo,cl.nom as nomClient,cl.prenom as prenomClient,r.* FROM reservation r 
        inner join coach c on  r.id_coach=c.id
        inner join client cl on  r.id_client=cl.id
        where c.id=? and r.status=?");
        $req->execute([$id_coach1,$statusEnAttent]);
        return $req->fetchAll(PDO::FETCH_ASSOC);


    }





    // annuler
    public function annulerReservationClient($idReservation, $id_client){
    
    $status = "annuler";
    $req = $this->pdo->prepare("UPDATE reservation SET status = ? WHERE id = ? AND id_client = ?");
    $success = $req->execute([$status, $idReservation, $id_client]);

    if($success){
        
        $reqInfo = $this->pdo->prepare("SELECT id_coach, date, heure_debut, heure_fin FROM reservation WHERE id = ?");
        $reqInfo->execute([$idReservation]);
        $info = $reqInfo->fetch(PDO::FETCH_ASSOC);
        if($info){
            $reqDispo = $this->pdo->prepare("UPDATE disponibilite SET disponible = true WHERE id_coach = ? AND date = ? AND heure_debut = ? AND heure_fin = ?");
            $reqDispo->execute([$info['id_coach'], $info['date'], $info['heure_debut'], $info['heure_fin']]);
        }
    }

    return $success;
}

    public function annulerReservation($idReservation,$idCoach) {

        $status = "annuler";
        $req = $this->pdo->prepare("UPDATE reservation SET status = ?  WHERE id = ? AND id_coach = ?");

        if (!$req->execute([$status, $idReservation, $idCoach])) {
            return false;
        }

        $reqInfo = $this->pdo->prepare("SELECT id_coach, date, heure_debut, heure_fin FROM reservation  WHERE id = ?");
        $reqInfo->execute([$idReservation]);
        $info = $reqInfo->fetch(PDO::FETCH_ASSOC);

        if (!$info) {
            return false;
        }

        // update disponible 1
        $disponible = 1;
        $reqDispo = $this->pdo->prepare("UPDATE disponibilite  SET disponible = ? WHERE id_coach = ? AND date = ? AND heure_debut = ? AND heure_fin = ?");

        return $reqDispo->execute([
            $disponible,
            $info['id_coach'],
            $info['date'],
            $info['heure_debut'],
            $info['heure_fin']
        ]);
    }

    // accepter
    public function accepterReservation($idReservation, $idCoach) {
        $status = "accepter";
        $req = $this->pdo->prepare(
            "UPDATE reservation SET status = ? WHERE id = ? AND id_coach = ?"
        );

        return $req->execute([$status, $idReservation, $idCoach]);
    }


}



?>