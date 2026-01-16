<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Disponibilité - SportCoach</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Navigation -->
<nav class="navbar bg-white shadow-md">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <a href="index.html" class="text-xl font-bold text-gray-800 flex items-center">
      <i class="fas fa-dumbbell mr-2"></i> SportCoach
    </a>
    <div>
      <a href="coach-dashboard.html" class="px-4 py-2 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition">Mon compte</a>
    </div>
  </div>
</nav>

<section class="py-10">
  <div class="container mx-auto px-4">
    <a href="coach-availability.html" class="inline-block mb-6 text-blue-600 font-semibold hover:underline">
      ← Retour aux disponibilités
    </a>

    <div class="bg-white rounded-xl shadow-lg p-6 max-w-3xl mx-auto">
      <h1 class="text-2xl font-bold mb-6 text-gray-800">Modifier la disponibilité</h1>

      <form id="editAvailForm" class="grid md:grid-cols-2 gap-4">
        <div>
          <label for="editAvailDate" class="block mb-2 font-semibold text-gray-700">Date</label>
          <input type="date" id="editAvailDate" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label for="editAvailTime" class="block mb-2 font-semibold text-gray-700">Heure</label>
          <input type="time" id="editAvailTime" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="md:col-span-2 flex justify-end space-x-4 mt-2">
          <a href="coach-availability.html" class="px-6 py-2 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition font-semibold">Annuler</a>
          <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-white shadow-inner mt-10 py-6">
  <div class="container mx-auto px-4 text-center text-gray-500">
    &copy; 2025 SportCoach. Tous droits réservés.
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const editDate = document.getElementById('editAvailDate');
  const editTime = document.getElementById('editAvailTime');

  // Récupérer les données depuis localStorage
  const date = localStorage.getItem('editDate');
  const time = localStorage.getItem('editTime');
  const index = localStorage.getItem('editIndex');

  editDate.value = date;
  editTime.value = time;

  document.getElementById('editAvailForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const availabilities = JSON.parse(localStorage.getItem('availabilities') || '[]');
    availabilities[index] = {date: editDate.value, time: editTime.value};
    localStorage.setItem('availabilities', JSON.stringify(availabilities));
    alert('Disponibilité modifiée !');
    window.location.href = 'coach-availability.html';
  });
});
</script>
</body>
</html>



























<!-- Modal / Formulaire d'édition de disponibilité -->
<div id="editAvailabilityModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md relative">
    <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
    <h2 class="text-xl font-bold mb-4 text-gray-800">Modifier le créneau</h2>
    
    <form id="editAvailabilityForm" class="space-y-4">
      <div>
        <label for="editDateInput" class="block font-semibold text-gray-700 mb-1">Date</label>
        <input type="date" id="editDateInput" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
      </div>
      <div>
        <label for="editTimeInput" class="block font-semibold text-gray-700 mb-1">Heure</label>
        <input type="time" id="editTimeInput" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
      </div>
      <div class="flex justify-end space-x-3 mt-4">
        <button type="button" id="cancelEdit" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Annuler</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">Enregistrer</button>
      </div>
    </form>
  </div>
</div>












<script>
    let editIndex = null;

function editAvailability(index) {
  editIndex = index;
  const a = availabilities[index];
  
  // Ouvrir modal
  const modal = document.getElementById('editAvailabilityModal');
  modal.classList.remove('hidden');

  // Remplir les inputs avec la valeur existante
  document.getElementById('editDateInput').value = formatForInput(a.date);
  document.getElementById('editTimeInput').value = a.time;
}

function formatForInput(dateStr) {
  // Convertit "dd/mm/yyyy" en "yyyy-mm-dd" pour input[type=date]
  const parts = dateStr.split('/');
  return `${parts[2]}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
}

// Fermer modal
document.getElementById('closeModal').addEventListener('click', () => document.getElementById('editAvailabilityModal').classList.add('hidden'));
document.getElementById('cancelEdit').addEventListener('click', () => document.getElementById('editAvailabilityModal').classList.add('hidden'));

// Sauvegarder modifications
document.getElementById('editAvailabilityForm').addEventListener('submit', (e) => {
  e.preventDefault();
  const newDate = document.getElementById('editDateInput').value.split('-').reverse().join('/');
  const newTime = document.getElementById('editTimeInput').value;

  availabilities[editIndex] = {date: newDate, time: newTime};
  renderAvailabilities();
  document.getElementById('editAvailabilityModal').classList.add('hidden');
});

</script>