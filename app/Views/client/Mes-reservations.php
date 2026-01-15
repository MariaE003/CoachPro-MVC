<?php
require '../session.php';
require '../classes/Reservation.php';
require '../classes/Coach.php';
require '../classes/client.php';

$idUser = $_SESSION["user_id"];
$role = $_SESSION["role"];

// id client
$client = new Client();
$id_client = $client->leClientConne($idUser); 

$reservationObj = new Reservation();
$reservations = $reservationObj->affichierReservation($id_client);


if (isset($_POST['annuler'])) {
    $idReservation = $_POST['id_reservation'];

    if($reservationObj->annulerReservationClient($idReservation, $id_client)){
        header("Location: Mes-reservations.php");
        exit();
    } else {
        echo "Erreur lors de l'annulation.";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Réservations | SportCoach</title>
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
require '../Pages/components/header.php' ;
?>

<!-- CONTENT -->
<section class="max-w-6xl mx-auto px-6 py-16">
  <h1 class="text-3xl font-bold mb-8">Mes Réservations</h1>

  <div class="overflow-x-auto bg-white rounded-xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure Debut</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure Fin</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
          <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
         <?php
    
 
  if ($reservations && count($reservations) > 0) {
         foreach ($reservations as $reser) {
        
         ?>
        <tr>
          <td class="px-6 py-4"><?=$reser["date"]?></td>
          <td class="px-6 py-4"><?=$reser["heure_debut"]?></td>
          <td class="px-6 py-4"><?=$reser["heure_fin"]?></td>
          <td class="px-6 py-4"><?=$reser["prenom"]." ".$reser["nom"]?></td>
          <td class="px-6 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><?=$reser["status"]?></span>
          </td>
          <td class="px-6 py-4 text-center space-x-2">
            <button data-id="<?=$dispo['id']?> data-date="<?=$reser["date"]?>" data-name="<?=$reser["prenom"]." ".$reser["nom"] ?>" data-start="<?=$reser["heure_debut"]?>" data-end="<?=$reser["heure_fin"]?>" data-status="<?=$reser["status"]?>" data-prix="<?=$reser["prix"]?>"
            data-objectif="<?=$reser["objectif"]?>" data-photo="<?=$reser["photo"]?>" class="text-blue-500 hover:text-blue-700 open-modal" title="Détails">
            <i class="fas fa-eye"></i>
          </button>
          <form action="" method="POST">
            <input type="hidden" name="id_reservation" value="<?=$reser['id']?>">
            <button name="modifier" class="text-yellow-500 hover:text-yellow-700"  title="Modifier">
              <i class="fas fa-pen"></i>
            </button>
          </form>
            <!-- <button class="text-red-500 hover:text-red-700" title="Annuler">
              <i class="fas fa-trash"></i>
            </button> -->
            <form method="POST" class="inline">
            <input type="hidden" name="id_reservation" value="<?=$reser['id']?>">
            <button name="annuler"
              onclick="return confirm('vous voulez vraiment annuler la reservation ?')"
              class="text-red-500 hover:text-red-700"
              title="Annuler">
              <i class="fas fa-trash"></i>
            </button>
          </form>

          </td>
        </tr>
        <?php
         }
        }else{
          echo "<tr>
            <td colspan='7' class='px-4 py-6 text-center text-gray-500'>aucun reservation trouve</td>
          </tr>";
                                
        }
        ?>
        
      </tbody>
    </table>
  </div>
  
</section>  


<!--  -->

<?php
require('../Pages/components/footer.php');

?>
</body>
</html>

<!-- Modal Overlay -->
<div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <!-- le detail du reservation -->

  <div class="bg-white rounded-xl shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
    
    <div class="flex justify-between items-center border-b px-6 py-4">
      <h2 class="text-xl font-bold text-primary">Détails de la réservation</h2>
      <button id="closeModal" class="text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="px-6 py-4 space-y-4">
      <div class="flex items-center space-x-4">
        <img src="" id="photoModal" alt="Coach" class="rounded-full">
        <div>
          <strong class="text-lg" id="nameModal">Mohammed Benali</strong>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 text-gray-700">
        <div>
          <span class="font-semibold">Date :</span> <span id="modalDate">16 Décembre 2024</span>
        </div>
        <div>
          <span class="font-semibold">Heure Debut :</span> <span id="modalTimeStart">10:00</span>
        </div>
        <div>
          <span class="font-semibold">Heure Fin :</span> <span id="modalTimeend">10:00</span>
        </div>
        <!-- <div>
          <span class="font-semibold">Type :</span> <span id="modalType">Individuelle</span>
        </div> -->
        <div>
          <span class="font-semibold">Statut :</span> 
          <span class="px-2 py-1 rounded-full bg-green-100 text-green-800" id="modalStatus">Confirmée</span>
        </div>
        <div class="col-span-2">
          <span class="font-semibold">Prix :</span> <span id="modalPrice">200 DH</span>
        </div>
      </div>

      <div>
        <h3 class="font-semibold text-gray-800">Objectif</h3>
        <p class="text-gray-600" id="modalObjectif"></p>
      </div>
    </div>

    <div class="px-6 py-4 border-t flex justify-end">
      <button id="closeModalBtn" class="btn btn-outline px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition">
        Fermer
      </button>
    </div>
  </div>
</div>

<script>
 
  const modal = document.getElementById("reservationModal");
  const closeModal = document.getElementById("closeModal");
  const closeModalBtn = document.getElementById("closeModalBtn");

  
  const modalDate = document.getElementById("modalDate");
  const modalTimeStart = document.getElementById("modalTimeStart");
  const modalTimeEnd = document.getElementById("modalTimeend");
  const modalStatus = document.getElementById("modalStatus");
  const modalPrice = document.getElementById("modalPrice");
  const modalObjectif = document.getElementById("modalObjectif");
  const modalName = document.getElementById("nameModal");
  const modalPhoto = document.getElementById("photoModal");

  // buttons
  const buttonsOpen = document.querySelectorAll(".open-modal");

  buttonsOpen.forEach(btn => {
    btn.addEventListener("click", () => {

      // data attribut
      modalDate.textContent = btn.dataset.date;
      modalTimeStart.textContent = btn.dataset.start;
      modalTimeEnd.textContent = btn.dataset.end;
      modalStatus.textContent = btn.dataset.status;
      modalPrice.textContent = btn.dataset.prix + " DH";
      modalObjectif.textContent = btn.dataset.objectif;
      modalName.textContent = btn.dataset.name;

      // photo
      modalPhoto.src = btn.dataset.photo 
        ? btn.dataset.photo 
        : "https://via.placeholder.com/80";

      // show modal
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    });
  });

  // close modal
  [closeModal, closeModalBtn].forEach(btn => {
    btn.addEventListener("click", () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    });
  });

  
</script>

