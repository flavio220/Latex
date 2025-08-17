<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "messe");
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifie que le servant est connecté
if (!isset($_SESSION['matricule'])) {
    header("Location: login_servant.php");
    exit();
}

// Récupère les informations du servant
$matricule = $_SESSION['matricule'];
$stmt = $conn->prepare("SELECT id, nom, niveau FROM servants WHERE matricule = ?");
$stmt->bind_param("s", $matricule);
$stmt->execute();
$result = $stmt->get_result();
$servant = $result->fetch_assoc();

$servant_id = $servant['id'];
$nom = $servant['nom'];
$niveau = $servant['niveau'];

// Services effectués
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM assignments WHERE servant_id = ?");
$stmt->bind_param("i", $servant_id);
$stmt->execute();
$total_services = $stmt->get_result()->fetch_assoc()['total'];

// Services ce mois
$mois_actuel = date("F");
$stmt = $conn->prepare("SELECT COUNT(*) as mois_total FROM assignments WHERE servant_id = ? AND month = ?");
$stmt->bind_param("is", $servant_id, $mois_actuel);
$stmt->execute();
$services_mois = $stmt->get_result()->fetch_assoc()['mois_total'];

// Prochain service
$stmt = $conn->prepare("SELECT date_serving, mass_time FROM assignments WHERE servant_id = ? AND date_serving >= CURDATE() ORDER BY date_serving ASC LIMIT 1");
$stmt->bind_param("i", $servant_id);
$stmt->execute();
$prochain = $stmt->get_result()->fetch_assoc();
$prochain_service = $prochain ? date("l d F", strtotime($prochain['date_serving'])) . " à " . $prochain['mass_time'] : "Aucun service prévu";

// Services à venir
$stmt = $conn->prepare("SELECT date_serving, mass_time FROM assignments WHERE servant_id = ? AND date_serving >= CURDATE() ORDER BY date_serving ASC LIMIT 3");
$stmt->bind_param("i", $servant_id);
$stmt->execute();
$services_venir = $stmt->get_result();

// Services passés
$stmt = $conn->prepare("SELECT date_serving, mass_time FROM assignments WHERE servant_id = ? AND date_serving < CURDATE() ORDER BY date_serving DESC LIMIT 3");
$stmt->bind_param("i", $servant_id);
$stmt->execute();
$services_passes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tableau de bord - Servant</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="min-h-screen p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Bienvenue, <?= htmlspecialchars($nom) ?></h1>
      <p class="text-lg text-gray-600">Niveau : <?= htmlspecialchars($niveau) ?></p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-2xl shadow-md p-4 hover:shadow-xl transition duration-300">
        <h2 class="text-xl font-semibold text-gray-700">Services effectués</h2>
        <p class="text-4xl font-bold text-indigo-600 mt-2"><?= $total_services ?></p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-4 hover:shadow-xl transition duration-300">
        <h2 class="text-xl font-semibold text-gray-700">Ce mois</h2>
        <p class="text-4xl font-bold text-indigo-600 mt-2"><?= $services_mois ?></p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-4 hover:shadow-xl transition duration-300">
        <h2 class="text-xl font-semibold text-gray-700">Prochain service</h2>
        <p class="text-lg text-gray-600 mt-2"><?= $prochain_service ?></p>
      </div>
    </div>

    <!-- Liste des services -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="bg-white rounded-2xl shadow-md p-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Services à venir</h3>
        <ul class="space-y-2">
          <?php while ($row = $services_venir->fetch_assoc()): ?>
            <li class="text-gray-600">- <?= date("l d F", strtotime($row['date_serving'])) ?> à <?= $row['mass_time'] ?></li>
          <?php endwhile; ?>
        </ul>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Services passés</h3>
        <ul class="space-y-2">
          <?php while ($row = $services_passes->fetch_assoc()): ?>
            <li class="text-gray-600">- <?= date("l d F", strtotime($row['date_serving'])) ?> à <?= $row['mass_time'] ?></li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>

    <!-- Evolution -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Évolution</h3>
      <div class="w-full bg-gray-200 rounded-full h-4">
        <div class="bg-indigo-600 h-4 rounded-full" style="width: 65%;"></div>
      </div>
      <p class="mt-2 text-sm text-gray-500">Progrès vers le niveau suivant</p>
    </div>

    <?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "messe");
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les avertissements du servant
$stmt = $conn->prepare("SELECT message, date FROM warnings WHERE servant_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $servant_id);
$stmt->execute();
$avertissements = $stmt->get_result();
$stmt->close();

// Récupérer les divers
$stmt = $conn->prepare("SELECT message, date FROM divers ORDER BY date DESC");
$stmt->execute();
$divers = $stmt->get_result();
$stmt->close();
?>

<!-- Carte contenant Avertissements & Divers côte à côte -->
<div class="bg-white rounded-2xl shadow-md p-6 mt-8">
  <h2 class="text-2xl font-bold text-gray-800 mb-6">Avertissements & Divers</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- Avertissements -->
    <div>
      <h3 class="text-xl font-semibold text-red-600 mb-4">Avertissements</h3>
      <?php if ($avertissements->num_rows > 0): ?>
        <ul class="space-y-2">
          <?php while ($row = $avertissements->fetch_assoc()): ?>
            <li class="text-red-600 flex justify-between">
              <span><?= htmlspecialchars($row['message']) ?></span>
              <span class="text-sm italic"><?= date("d/m/Y", strtotime($row['date'])) ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-600">Aucun avertissement pour le moment.</p>
      <?php endif; ?>
    </div>

    <!-- Divers -->
    <div>
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Divers</h3>
      <?php if ($divers->num_rows > 0): ?>
        <ul class="space-y-2">
          <?php while ($row = $divers->fetch_assoc()): ?>
            <li class="text-gray-700 flex justify-between">
              <span><?= htmlspecialchars($row['message']) ?></span>
              <span class="text-sm italic"><?= date("d/m/Y", strtotime($row['date'])) ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-600">Aucun divers pour le moment.</p>
      <?php endif; ?>
    </div>

  </div>
</div>



  <!-- Animation script -->
  <script>
    anime({
      targets: 'h1',
      translateY: [-30, 0],
      opacity: [0, 1],
      duration: 1000,
      easing: 'easeOutExpo'
    });
  </script>
</body>
</html>
