<?php
$RolePage="coach";
require '../session.php';

require '../classes/Disponibilite.php';
require '../classes/coach.php';

$user_id=$_SESSION["user_id"];
$coach1=new Coach();
$idCoach=$coach1->leCoachConne($user_id);





$dispo = new Disponibilite();

if (isset($_POST["save"])) {

    if (!empty($_POST["date"]) && !empty($_POST["startTime"]) && !empty($_POST["endTime"])) {

        if (!$dispo->dispoExist($idCoach, $_POST["date"], $_POST["startTime"], $_POST["endTime"])) {

            $dispo->AjouterDispo($idCoach, $_POST["date"], $_POST["startTime"], $_POST["endTime"]);
            header("Location: coach-availability.php");
            exit();

        } else {
            $erreur = "Ce créneau existe déjà !";
        }

    } else {
        $erreur = "Tous les champs sont obligatoires";
    }
}

if (isset($_POST["annuler"])) {
    $dispo->supprimer((int)$_POST["annuler"]);
    header("Location: coach-availability.php");
    exit();
}



$disponibilite = $dispo->AfficherDispoCoach($idCoach);





$erreur=""; 


?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter disponibilité</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php
// C:\laragon\www\FitCoach-Pro\Pages\components\header.php
require('./components/header.php');
?>
<div class="bg-white rounded-xl shadow-lg p-6 max-w-md mx-auto my-8">
  <h2 class="text-xl font-bold mb-4 text-gray-800">Ajouter une disponibilité</h2>

  <form id="availabilityForm" class="space-y-4" method="POST">
    <?php if(!empty($erreur)){
              ?>
              <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $erreur ?>
              </div>
            <?php
            };?>
    <div>
      <label for="dateInput" class="block font-semibold text-gray-700 mb-1">Date</label>
      <input type="date" name="date" id="dateInput" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
      <label for="startTime" class="block font-semibold text-gray-700 mb-1">Heure de début</label>
      <input type="time" name="startTime" id="startTime" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
    </div>

    <div>
      <label for="endTime" class="block font-semibold text-gray-700 mb-1">Heure de fin</label>
      <input type="time" name="endTime" id="endTime" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
    </div>

    <div class="flex justify-end">
      <button type="submit" name="save" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">Enregistrer</button>
    </div>
  </form>
</div>
<h3 class="font-semibold text-gray-700 mt-6 mb-2 text-center">Disponibilités actuelles</h3>
  <!-- table -->
    <?php
    
    ?>
   <table class="divide-y divide-gray-200" style="margin:auto;min-width:54em;">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">heure_debut</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">heure_fin</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">action</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <!-- foreach -->
         <?php
         if (count($disponibilite)>0) {
          foreach($disponibilite as $dispo){
         ?>
        <tr>
          <td class="px-6 py-4"><?=$dispo["date"]?></td>
          <td class="px-6 py-4"><?=$dispo["heure_debut"]?></td>
          <td class="px-6 py-4"><?=$dispo["heure_fin"]?></td>
          <td class="px-6 py-4 text-center space-x-2">
            <!-- <button class="text-yellow-500 hover:text-yellow-700" title="Modifier">Modifier
              <i class="fas fa-pen"></i> -->
            </button>
            <form action="" method="POST">
              <button name="annuler"  value="<?=$dispo["id"]?>" class="text-red-500 hover:text-red-700" title="Annuler"> Annuler 
                <i class="fas fa-trash"></i>
              </button> 
            </form>
          </td>
        </tr>
         <?php            
          }
        }else{
          echo "<tr>
            <td colspan='7' class='px-4 py-6 text-center text-gray-500'>aucun disponibilite trouve</td>
          </tr>";
              
        }
        ?>

      </tbody>
    </table>
<?php
require('./components/footer.php');
?>
<script>

</script>


</body>
</html>
