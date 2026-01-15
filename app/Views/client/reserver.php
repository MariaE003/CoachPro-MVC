<?php
$RolePage="client";
require '../session.php';
require '../classes/client.php';
require '../classes/Disponibilite.php';
require '../classes/Reservation.php';

$id_user=$_SESSION["user_id"];
// echo $id_user;
if (isset($_GET["idProfilCoach"])) {
  $idcoach=$_GET["idProfilCoach"];
}

$client=new client();
$id_client=$client->leClientConne($id_user);


$dispoObj = new Disponibilite();
$dispoRows = $dispoObj->dispoDuCeCoach($idcoach);
 



$dispoLignes = [];
foreach ($dispoRows as $dispo) {
    $date = $dispo['date'];
    
    $dispoLignes[$date][] = [
        'start' => $dispo['heure_debut'],
        'end'   => $dispo['heure_fin'],
        'id'    => $dispo['id']
    ];
}



// virifier 

if (isset($_POST["reserver"])) {

    $date=$_POST["date"];
    $Hdebut=$_POST["Hdebut"];
    $HFin=$_POST["HFin"];
    $objectif=$_POST["objectif"];
    $idDispo=$_POST["idDispo"];
    var_dump($idDispo);

    $reservation = new Reservation();
    $reservation->setDate($date);
    $reservation->setHeure_debut($Hdebut);
    $reservation->setHeure_fin($HFin);
    $reservation->setObjectif($objectif);

    $reservation->AjouterReservation(
        $id_client,
        $idcoach,
        $idDispo
    );
    $dispoObj->ModifierStatusDispo($idDispo); 

    header("Location: Mes-reservations.php");
    exit;
}


if(isset($_POST['annuler'])){
    $reser = new Reservation();
    $reser->annulerReservation($_POST['idReser'], $id_coach);
    header("Location: Mes-reservations.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réserver une séance | SportCoach</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

<!-- NAV -->
<?php
require('../Pages/components/header.php');

?>

<!-- CONTENT -->
<section class="max-w-4xl mx-auto px-6 py-16">

  <a href="./coach-profile.php?idProfilCoach=<?=$idcoach?>" class="text-sm text-gray-500 hover:text-accent flex items-center gap-2 mb-6">
    <i class="fas fa-arrow-left"></i> Retour au profil
  </a>

  <div class="bg-white rounded-xl shadow p-8 grid md:grid-cols-3 gap-8">

    <!-- FORM -->
    <div class="md:col-span-2">
      <h1 class="text-3xl font-bold mb-6">
        <i class="fas fa-calendar-plus text-accent"></i> les moments disponible
      </h1>
      
      <!-- Date Card -->
      <?php
    if (count($dispoLignes)>0) {
      
     foreach ($dispoLignes as $date => $times){ ?>
        <div class="max-w-3xl mx-auto p-6">
            <div class="mb-6 bg-white shadow rounded-xl p-5">
                <h3 class="text-lg font-semibold text-gray-700 mb-4"><?= $date ?></h3>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($times as $oneTime){ ?>
                        <button class="time px-4 py-2 rounded-lg border border-green-500 text-green-600 hover:bg-green-500 hover:text-white transition"
                            data-date="<?= $date ?>"
                            data-start="<?= $oneTime['start'] ?>"
                            data-end="<?= $oneTime['end'] ?>"
                            data-id="<?= $oneTime['id'] ?>">
                            <?= $oneTime['start'] ?>-<?= $oneTime['end'] ?>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php };
        }
    else{
    ?>

    <div class="max-w-md mx-auto mt-6 bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl text-center shadow">
      Aucun temps disponible pour ce coach
    </div>

    <?php
      }
      ?>

      <h2 class="text-3xl font-bold mb-6">
        <i class="fas fa-calendar-plus text-accent"></i> Réserver une séance
      </h2>

      <!-- les dispo de ce coach -->
       

      <!-- ALERT -->
      <div id="availabilityAlert" class="hidden mb-6 p-4 rounded-lg text-sm"></div>
      <form class="space-y-6" method="POST">
        <input type="hidden" name="idDispo" id="idDispo" value="">
        <div>
          <label class="block mb-1 font-medium">Date</label>
          <input type="date" id="date" readonly name="date"
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent">
        </div>

        <div>
          <label class="block mb-1 font-medium">Heure Debut</label>
          <input type="time" id="timeStart" name="Hdebut" readonly
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent">
        </div>
        <div>
          <label class="block mb-1 font-medium">Heure Fin</label>
          <input type="time" id="timeEnd" name="HFin" readonly
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent">
        </div>

        <div>
          <label class="block mb-1 font-medium">Objectifs</label>
          <textarea rows="3" name="objectif" id="objectif"
            class="w-full border rounded-lg px-4 py-2"
            placeholder="Perte de poids, technique, endurance..."></textarea>
        </div>

        <div class="flex gap-4">

          <button type="submit"
            id="confirmBtn"
            name="reserver"
            disabled
            class="px-6 py-3 bg-accent text-white rounded-lg font-semibold  ">
            Confirmer réservation
          </button>
        </div>

      </form>
    </div>
  </div>
</section>

<?php
require('../Pages/components/footer.php');
?>
<script>
  let dateF=document.querySelector("#date"); 
  let timeStart=document.querySelector("#timeStart"); 
  let timeEnd=document.querySelector("#timeEnd"); 
  let objectif=document.querySelector("#objectif"); 
  let allInputs=document.querySelectorAll("#date,#timeStart,#timeEnd,#objectif");
  let time=document.querySelectorAll("#time");
  // btn
  let confirmBtn=document.querySelector("#confirmBtn"); 
  // input hidden
  let input=document.querySelector("#idDispo"); 


  let times=document.querySelectorAll(".time"); 
  times.forEach(btn=>{
    btn.addEventListener("click",()=>{
      let date=btn.dataset.date;
      let startTime=btn.dataset.start
      let endTime=btn.dataset.end
       let idDispo = btn.dataset.id; 

      dateF.value=date;
      timeStart.value=startTime;
      timeEnd.value=endTime;
      confirmBtn.disabled = objectif.value.trim() === "";

      input.value = idDispo;
    })
  })
  objectif.addEventListener("click",()=>{
    confirmBtn.disabled = !(dateF.value && timeStart.value && timeEnd.value && objectif.value.trim());

  });

  
</script>
</body>
</html>









