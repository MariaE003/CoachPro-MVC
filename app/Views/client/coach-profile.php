<?php
$RolePage="client";
require '../session.php';
require '../classes/Coach.php';

// echo $_SESSION["role"];

$idcoach=$_GET["idProfilCoach"];
// echo $idcoach;
if (isset($idcoach)) {
 // les info du profil
 
$coachObj = new Coach();

$idcoach = $_GET['idProfilCoach'];

if ($idcoach) {
    $profil = $coachObj->detailCoach($idcoach);

    if (!$profil) {
        echo "Coach non trouvé.";
        exit();
    }

    $specialites = explode(',', $profil['specialites']);
   
    $Certif=$coachObj->CertifCoach($idcoach);
    // var_dump($certif);
} else {
    header("Location: ./index.php");
    exit();
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Coach | SportCoach</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0f172a',
            accent: '#22c55e',
            soft: '#f8fafc'
          }
        }
      }
    }
  </script>
</head>

<body class="bg-soft text-gray-800">

<!-- NAVBAR -->
<?php
require('../Pages/components/header.php');


// foreach($profil as $prof){
?>

<!-- HEADER -->
<section class="bg-primary text-white">
  <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-3 gap-10 items-center">

    <!-- Avatar -->
    <div class="flex justify-center">
      <div class="relative">
        <img src="<?=$profil["photo"];?>"
             class="w-48 h-48 rounded-full border-4 border-accent object-cover">
        <span class="absolute bottom-2 right-2 bg-accent text-white p-2 rounded-full">
          <i class="fas fa-check"></i>
        </span>
      </div>
    </div>

    <!-- Infos -->
    <div class="md:col-span-2">
      <h1 class="text-4xl font-extrabold mb-2"><?=$profil["nom"]." ".$profil["prenom"]?></h1>
      <p class="text-gray-300 mb-4">
        <i class="fas fa-futbol text-accent"></i>
        <?= $profil['specialites'];?>
      </p>

      <div class="flex items-center gap-3 text-yellow-400 mb-4">
        ★★★★★ <span class="text-gray-300">(124 avis)</span>
      </div>

      <div class="grid sm:grid-cols-3 gap-4 text-sm text-gray-200">
        <div><i class="fas fa-clock text-accent"></i> <?=$profil["experience_en_annee"]?> ans d'expérience</div>
        <div><i class="fas fa-users text-accent"></i> 250+ sportifs</div>
        <div><i class="fas fa-certificate text-accent"></i> <?= $Certif["nbrCertif"]?> certifications</div>
      </div>
    </div>

  </div>
</section>

<!-- CONTENT -->
<section class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-3 gap-10">

  <!-- MAIN -->
  <div class="lg:col-span-2 space-y-10">

    <!-- BIO -->
    <div class="bg-white rounded-xl shadow p-8">
      <h2 class="text-2xl font-bold mb-4">
        <i class="fas fa-user text-accent"></i> À propos
      </h2>
      <p class="text-gray-600 leading-relaxed">
        <?=$profil["bio"];?>
        
      </p>
    </div>

    <!-- SPECIALITES -->
    <div class="bg-white rounded-xl shadow p-8">
      <h2 class="text-2xl font-bold mb-4">
        <i class="fas fa-star text-accent"></i> Spécialités
      </h2>
      <div class="flex flex-wrap gap-3">
        <?php
        foreach ($specialites as $specialite) {
          // echo $specialite."<br>";
                
        ?>
        <span class="px-4 py-2 bg-accent/10 text-accent rounded-full"><?= $specialite ?></span>
        <?php
        }
        ?>
      </div>
    </div>

    <!-- CERTIFICATIONS -->
    <div class="bg-white rounded-xl shadow p-8">
      <h2 class="text-2xl font-bold mb-6">
        <i class="fas fa-certificate text-accent"></i> Certifications
      </h2>
      <ul class="space-y-4">
        <!-- // -->
          <?php
          // les certifs du coach

          $cerName=$Certif["nomCertif"];
          $certificationName=explode(",",$cerName);

          $cerEtab=$Certif["etablissement"];
          $certificationEtabli=explode(",",$cerEtab);

          $cerAnnee=$Certif["anneeCertif"];
          $certificationAnnee=explode(",",$cerAnnee);


          for ($i=0; $i <count($certificationName) ; $i++) { 
          // echo $certificationName[$i] ."-". $certificationEtabli[$i] ."-". $certificationAnnee[$i] ."<br>" ;
          
           ?>
           <li class="flex gap-4">
          <i class="fas fa-check-circle text-accent text-xl"></i>
          <div>
            <strong><?=$certificationName[$i]?></strong>
            <p class="text-gray-500 text-sm"><?=$certificationEtabli[$i] ?> – <?=$certificationAnnee[$i]?></p>
          </div>
          </li>
          <?php
          }
          ?>
      </ul>
    </div>

  </div>

  <!-- SIDEBAR -->
  <div class="space-y-6">

    <!-- BOOKING -->
    <div class="bg-white rounded-xl shadow p-6 sticky top-24">
      <div class="text-center mb-6">
        <span class="text-3xl font-extrabold text-primary"><?=$profil["prix"];?> DH</span>
        <p class="text-gray-500">/ séance</p>
      </div>

      <a href="./reserver.php?idProfilCoach=<?=$idcoach?>"
         class="block w-full text-center bg-accent text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition">
        <i class="fas fa-calendar-check"></i> Choisir un créneau
      </a>

      <hr class="my-6">

      <ul class="space-y-3 text-sm text-gray-600">
        <li><i class="fas fa-phone text-accent"></i> <?=$profil["telephone"];?></li>
        <li><i class="fas fa-clock text-accent"></i> Séance : 1 heurs</li>
        <li><i class="fas fa-map-marker-alt text-accent"></i> Safi</li>
        <li><i class="fas fa-language text-accent"></i> FR / AR</li>
      </ul>
    </div>

  </div>
</section>

<!-- FOOTER -->
<?php
}else{
  header("Location: ./index.php");
}
require('../Pages/components/footer.php');

?>


</body>
</html>
