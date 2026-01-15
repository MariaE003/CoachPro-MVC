<?php
$RolePage="coach";
require '../session.php';
require '../classes/coach.php';

$idUser=$_SESSION["user_id"];


$coach1=new Coach();
$id_coach=$coach1->leCoachConne($idUser);
// echo $id_coach;

// le nombre des reservation 
$nbrResAtt=$coach1->nbrReservationEnAttente($id_coach);

$nbrResAcc=$coach1->nrbReseValide($id_coach);
// echo $nbrRes["nbr"];

$nbrdmain = $coach1->nbrResDemain($id_coach);
$nextSeance = $coach1->prochaineRese($id_coach);



// $res=$coach1->detailCoach($id_coach);
// echo $res;
// var_dump ($res)



?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Coach - SportCoach</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

  <!-- Navigation -->
 <?php
require('../Pages/components/header.php');

?>

  <!-- Dashboard Content -->
  <section class="flex-1 py-10">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">

      <!-- Sidebar -->
      <?php   require '../Pages/components/aside.php';   ?>

      <!-- Main Content -->
      <div class="md:col-span-3 flex flex-col space-y-6">

        <!-- Statistiques -->
        <section id="statistiques" class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
            <span class="text-gray-500">Demandes en attente</span>
            <span class="text-3xl font-bold text-green-600 mt-2"><?=$nbrResAtt["nbr"]?></span>
          </div>
          <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
            <span class="text-gray-500">Séances validées aujourd'hui</span>
            <span class="text-3xl font-bold text-green-600 mt-2"><?=$nbrResAcc["nbr"]?></span>
          </div>
          <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
            <span class="text-gray-500">Séances validées demain</span>
            <span class="text-3xl font-bold text-green-600 mt-2"><?=$nbrdmain["nbr"]?></span>
          </div>
        </section>

        <!-- Prochain sportif -->
         <?php
         if ($nextSeance) {
          
         ?>
        <section class="bg-white p-6 rounded-xl shadow flex flex-col md:flex-row justify-between items-center">
          <div>
            <h3 class="font-bold text-gray-800">Prochaine séance</h3>
            <p class="text-gray-600 mt-1"><?=$nextSeance["prenom"]." ".$nextSeance["nom"]?>Mohammed Benali - 16 Décembre 2024 à 10:00</p>
            <!-- <p class="text-gray-600">Type : Individuelle</p> -->
          </div>
          <a href="./Mes-reservations-coach.php" class="mt-4 md:mt-0 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Voir Détails</a>
        </section>
        <?php
         }else{
        ?>
        <section class="bg-white p-6 rounded-xl shadow flex flex-col md:flex-row justify-between items-center">
          <div>
            <h3 class="font-bold text-gray-800">Prochaine séance</h3>
            <p class="text-gray-600 mt-1">aucun seance pour le moment</p>
          </div>
        </section>
        <?php
         }
        ?>

        </section>

      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php
require('../Pages/components/footer.php');

?>

</body>
</html>
