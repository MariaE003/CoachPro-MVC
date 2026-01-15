<?php 
// require_once '../Models/Coach.php';
require_once __DIR__.'/../../app/Models/Coach.php';
// require_once __DIR__.'/../../Models/User.php';

class CoachController{

    private $twig;
    public function __construct($twig){
        $this->twig=$twig;
    }
    public function addProfilCoachForm(){
        echo $this->twig->render('auth/addProfilCoach.twig');
    }

    public function addProfilCoach($id_user){
        // $id_user=$_SESSION["user_id"];
        // echo $id_user;
        $experience=$_POST["experience"];
        $photo=$_POST['photo'];
        $bio=$_POST["bio"];

        $specialites=$_POST["specialites"];

        $cert=$_POST["certifications"];
        $prix= (float)$_POST["prix"];

        $coach=new Coach();

        $coach->updateProfilCoach($id_user,$experience,$bio,$prix,$photo);
        
        $coach_id = $coach->leCoachConne($id_user);
        if (!$coach_id) {
            die("Erreur : Coach introuvable");
        }

        $coach->setCoachId($coach_id);
        // echo $tes;
        
        foreach($specialites as $spe){
            $coach->setSpecialite($spe);
        }
            
        for ($i=0; $i <count($cert["nom"]) ; $i++) { 
            
            $coach->setCertif([
                'nom_certif'=>$cert["nom"][$i],
                'annee'=>$cert["annee"][$i],
                'etablissement'=>$cert["etablissement"][$i],
            ]);

        }
        $coach->saveSpecialite();
        $coach->saveCertif();

        header('Location:  /CoachPro-MVC/index.php');

        }

    public function coachPage(){
        $coach = new Coach();
        $coach = $coach->tousCoach();
        echo $this->twig->render('coach/coaches.twig',[
        'coachs'=>$coach,
        ]);
    }





}

?>