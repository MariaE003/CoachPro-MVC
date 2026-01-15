<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    protected $pdo;
    protected $id;
    protected $email;
    protected $password;
    protected $role;

    public function __construct() {
        $this->pdo = DataBase::connect();
    }

    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function setRole($role) { $this->role = $role; }
    public function getRole() { return $this->role; }
    public function getId() { return $this->id; }

    public function emailExist() {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email=?");
        $stmt->execute([$this->email]);
        return $stmt->fetch() !== false;
    }

    public function register() {
        if ($this->emailExist()) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO users(email,password,role) VALUES(?,?,?)"
        );

        $stmt->execute([
            $this->email,
            $this->password,
            $this->role
        ]);

        $this->id = $this->pdo->lastInsertId();
        return $this->id;
    }

    public function login($email,$password){
        $req=$this->pdo->prepare("SELECT * from users where email = ?");
        $req->execute([$email]);

        $user=$req->fetch(PDO::FETCH_ASSOC);
        if (!$user){
            echo "non email";
            return false;
            
        }
       
        if (!password_verify($password,$user["password"])) {
            // password incorrect
            echo "non pw";

            return false;
        }
        
        // remplir lobjet from db
        $this->id=$user["id"];
        $this->email=$user["email"];
        $this->role=$user["role"];

        // echo "la connexion est donnnnne";
        return true;

    }
    




}
