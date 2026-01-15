
<?php
$RolePage="coach";
$idUser=$_SESSION["user_id"];
// echo $idUser;
$coach1=new Coach();
$id_coach=$coach1->leCoachConne($idUser);
// echo $id_coach;
$res=$coach1->detailCoach($id_coach);
// var_dump($res);
?>
<aside class="md:col-span-1 bg-white rounded-xl shadow-lg p-6 flex flex-col space-y-4">
        <div class="text-center">
          <img src="<?=$res["photo"]?$res["photo"]:"./images.png" ?>" alt="Photo Coach" class="w-24 h-24 rounded-full mx-auto mb-2">
          <h2 class="font-bold text-lg text-gray-800"><?=$res["prenom"]." ".$res["nom"]?></h2>
          <p class="text-gray-500 text-sm"><?=$res["specialites"]?></p>
        </div>
        <nav class="mt-4 flex flex-col space-y-2">
          <a href="./Mes-reservations-coach.php" class="text-green-600 font-semibold hover:underline">Gestion des Réservations</a>
          <a href="./profil-du-coach.php" class="text-green-600 font-semibold hover:underline">Mon Profil</a>
          <a href="./coach-availability.php" class="text-green-600 font-semibold hover:underline">Disponibilités</a>
          <!-- <a href="#statistiques" class="text-green-600 font-semibold hover:underline">Statistiques</a> -->
        </nav>
      </aside>