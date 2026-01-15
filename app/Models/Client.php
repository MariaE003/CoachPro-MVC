<?php
require_once __DIR__ .'/User.php';
require_once __DIR__ .'/../../config/DataBase.php';

class Client extends User{
    // private string $id;
    private string $nom;
    private string $prenom;
    private string $telephone;
    // private string $email;

    // private $pdo;
        public function __construct(){
            parent::__construct();
            $this->pdo = DataBase::connect();
    }


    //  public function getId(){
    //     return $this->id;
    // }

    // getter & setter
    public function setId(string $id){$this->id=$id;}//pour eviter erreur du pdo null
    public function getNom(){return $this->nom;}
    public function setNom(string $nom){$this->nom=$nom;}
    public function getPrenom(){return $this->prenom;}
    public function setPrenom(string $prenom){$this->prenom=$prenom;}
    public function getTelephone(){return $this->telephone;}
    public function setTelephone(string $telephone){$this->telephone=$telephone;}
    // public function setEmail(string $email){$this->email=$email;}

    public function registerClient(){
        $this->role='client';
        // if ($this->register()) {
        if ($this->id) {
            $req=$this->pdo->prepare("INSERT into client(id_user, nom, prenom, telephone) values(?,?,?,?)");
            return $req->execute([
                $this->id,
                $this->nom,
                $this->prenom,
                $this->telephone
            ]);
        }
        return false;
        
    }

    public function leClientConne(int $id){
        $req=$this->pdo->prepare("SELECT * FROM client  WHERE id_user=?");
        
        $req->execute([
            $id
        ]);
        $test=$req->fetch(PDO::FETCH_ASSOC);
        // var_dump($test);
        // echo "dxcf";
        if ($test) {
            // echo $test["nom"];
            return $test["id"];
        }else{
            echo "non";
        }
        }
}

?>