<?php
require '../session.php';
$RolePage="coach";

require '../classes/Coach.php';



$id_user=$_SESSION["user_id"];

$coach= new Coach();
$coach_id = $coach->leCoachConne($id_user);

$profil = $coach->afficherProfil($id_user);

// to  array
$specialiteName=isset($profil['specialite'])?explode(',', $profil['specialite']):[];
$certifNom=isset($profil["nom_certif"])?explode(",",$profil["nom_certif"]):[];
$certifAnnee=isset($profil["annee"])?explode(",",$profil["annee"]):[];
$certifEtabli=isset($profil["etablissement"])?explode(",",$profil["etablissement"]):[];


?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Coach - SportCoach</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
<?php require('./components/header.php'); ?>
<section class="flex-1 py-10">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- Sidebar dashboard -->
   <?php   require('../Pages/components/aside.php');   ?>

    <!-- Profil Main Content -->
    <div class="md:col-span-3 flex flex-col space-y-6">

      <section class="bg-white p-6 rounded-xl shadow grid gap-6 w-full">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Modifier Profil Coach</h3>

        <form class="space-y-4" method="POST" novalidate>
        <?php if(!empty($erreur)){
              ?>
              <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $erreur ?>
              </div>
            <?php
            };?>
          <!-- Photo -->
          <div>
            <label class="block text-gray-700 mb-1">Photo</label>
            <input type="url" name="photo" placeholder="URL de votre photo" value="<?=$res["photo"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>
          <!-- phone -->
          <div>
            <label class="block text-gray-700 mb-1">telephone</label>
            <input type="text" name="phone" placeholder="Numero du telephone" value="<?=$res["telephone"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>

          <!-- Biographie -->
          <div>
            <label class="block text-gray-700 mb-1">Bio</label>
            <textarea name="bio" rows="3" placeholder="Présentez-vous..." 
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"><?=$res["bio"]?></textarea>
          </div>

          <!-- Années d'expérience -->
          <div>
            <label class="block text-gray-700 mb-1">Années d'expérience</label>
            <input type="number" name="experience" min="0" placeholder="Ex: 5"  value="<?=$res["experience_en_annee"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>

          <!-- Prix -->
          <div>
            <label class="block text-gray-700 mb-1">Prix (DH)</label>
            <input type="text" name="prix" placeholder="Ex: 200" value="<?=$res["prix"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>

          <!-- Spécialités dynamiques -->
          <div>
            <label class="block text-gray-700 mb-1">Spécialités</label>
            <div id="specialitesContainer" class="space-y-2">
              <div class="flex space-x-2">
                <?php
                foreach($specialiteName as $spe){
                ?>
                <input type="text" name="specialites[]" placeholder="Nom de spécialité"  value="<?=$spe?>"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                       <?php
              }
              ?>
              <button type="button" onclick="addSpecialite()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">+</button>
              </div>
            </div>
          </div>

          <!-- Certifications dynamiques -->
          <div>
            <label class="block text-gray-700 mb-1">Certifications</label>
            <div id="certificationsContainer" class="space-y-2">
              <?php
                for ($i=0; $i < count($certifNom); $i++) { 
                  // echo $certifNom[$i];
                  ?>
                  <div class="flex flex-wrap space-x-2 space-y-2">
                <input type="text" name="certifications[nom][]" placeholder="Nom certification"  value="<?= $certifNom[$i]?>"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <input type="text" name="certifications[annee][]" placeholder="Année"  value="<?= $certifAnnee[$i]?>"
                       class="w-24 border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <input type="text" name="certifications[etablissement][]" placeholder="Établissement"  value="<?= $certifEtabli[$i]?>"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <!-- <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button> -->
                </div>
                <?php
                }
                
                ?>
            </div>
            <button type="button" onclick="addCertification()" 
                    class="mt-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Ajouter Certification</button>
          </div>

          <button type="submit" name="save"
                  class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center space-x-2">
            <i class="fas fa-save"></i>
            <span>Enregistrer Profil</span>
          </button>
        </form>
      </section>
    </div>
  </div>
</section>
<?php require('../Pages/components/footer.php'); ?>

<script>
function addSpecialite(){
  const container = document.getElementById('specialitesContainer');
  const div = document.createElement('div');
  div.className = 'flex space-x-2';
  div.innerHTML = `<input type="text" name="specialites[]" placeholder="Nom de spécialité" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                   <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button>`;
  container.appendChild(div);
}

function addCertification(){
  const container = document.getElementById('certificationsContainer');
  const div = document.createElement('div');
  div.className = 'flex flex-wrap space-x-2 space-y-2';
  div.innerHTML = `
    <input type="text" name="certifications[nom][]" placeholder="Nom certification" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <input type="text" name="certifications[annee][]" placeholder="Année" class="w-24 border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <input type="text" name="certifications[etablissement][]" placeholder="Établissement" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button>
  `;
  container.appendChild(div);
}
</script>

