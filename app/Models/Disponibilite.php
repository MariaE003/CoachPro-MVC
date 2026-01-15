<?php
require_once __DIR__ . '/config/DataBase.php';

class Disponibilite{
    private $id; 
    private $id_coach;
    private $date;
    private $heure_debut;
    private $heure_fin;
    private $disponible;

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

 

    public function AjouterDispo($idCoach, $date, $debut, $fin): bool {
    $req = $this->pdo->prepare(
        "INSERT INTO disponibilite (id_coach, date, heure_debut, heure_fin, disponible)
         VALUES (?,?,?,?,1)"
    );
    return $req->execute([$idCoach, $date, $debut, $fin]);
}



    public function ModifierDispo(int $idDispo){
      $disponible = 0;
        $req2 = $this->pdo->prepare("UPDATE disponibilite set disponible=? where id_coach=? and date=? and heure_debut=? and heure_fin=?");
        // 
        $req2->execute([
            $disponible,
            $this->idcoach,
            $this->date,
            $this->heure_debut,
            $this->heure_fin,
        ]);

    }
    // les seance disponible
    public function affichierDispo(){
        $reqSelect=$this->pdo->prepare("SELECT * from disponibilite where disponible=1");
        $reqSelect->execute([]);
        return $reqSelect->fetchAll(PDO::FETCH_ASSOC);

    }
   
    // supprimer dispo + reservations
    public function supprimer($idDispo) {
        $req1=$this->pdo->prepare(
            "DELETE FROM reservation WHERE id_disponibilite=?"
        );
        $req1->execute([$idDispo]);

        $req2=$this->pdo->prepare(
            "DELETE FROM disponibilite WHERE id=?"
        );
        $req2->execute([$idDispo]);
    }
    // 
    public function ModifierStatusDispo($id){
        $reqDelete=$this->pdo->prepare("UPDATE disponibilite set disponible = 0 where id=?");
        $reqDelete->execute([
            $id
        ]);
    }
    


    public function dispoDuCeCoach(int $idCoach) {
        $req = $this->pdo->prepare("SELECT * FROM disponibilite WHERE id_coach=? AND disponible=1");
        $req->execute([$idCoach]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function reserverDispo(int $idDispo) {
        $req = $this->pdo->prepare("UPDATE disponibilite SET disponible=0 WHERE id=?");
        return $req->execute([$idDispo]);
    }





    // verifier si dispo exist
    public function dispoExist($idCoach,$date,$debut,$fin){
        $req = $this->pdo->prepare("SELECT id FROM disponibilite 
             WHERE id_coach=? AND date=? AND heure_debut=? AND heure_fin=?"
        );
        $req->execute([$idCoach, $date, $debut, $fin]);
        return $req->fetch() !== false;
    }


    // afficher dispo coach
    public function AfficherDispoCoach( $idCoach) {
        $req = $this->pdo->prepare(
            "SELECT * FROM disponibilite WHERE id_coach=?"
        );
        $req->execute([$idCoach]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
 


}



?>