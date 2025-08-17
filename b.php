<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'messe';
$conn = new mysqli($host, $user, $pass, $db);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Ajouter une assignation de rôle
if (isset($_POST['month'], $_POST['week'])) {
    $month = $_POST['month'];
    $week = $_POST['week'];

    // Vérifier et insérer les assignations de rôles pour chaque servant
    foreach ($_POST['servant_id'] as $role_day => $servant_id) {
        list($day, $role) = explode('_', $role_day);

        // Vérifier si le servant existe dans la base de données
        $check_servant = $conn->prepare("SELECT COUNT(*) FROM servants WHERE id = ?");
        $check_servant->bind_param("i", $servant_id);
        $check_servant->execute();
        $check_servant->bind_result($count);
        $check_servant->fetch();
        $check_servant->close(); // Fermer la requête après utilisation

        if ($count == 0) {
            echo "Le servant avec l'ID $servant_id n'existe pas.<br>";
            continue; // Passer à l'itération suivante
        }

        // Insérer l'assignation dans la base de données
        $stmt = $conn->prepare("INSERT INTO assignments (month, week, day_of_week, servant_id, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisis", $month, $week, $day, $servant_id, $role); // Assurez-vous que le paramètre "role" correspond bien à la variable $role
        $stmt->execute();
        $stmt->close(); // Fermer la requête après utilisation
    }
    echo "Rôles assignés avec succès!";
}

// Récupérer tous les servants dans un tableau
$servants_result = $conn->query("SELECT * FROM servants");
$servants = [];
while ($row = $servants_result->fetch_assoc()) {
    $servants[] = $row;
}

// Définir les variables nécessaires pour le formulaire
$months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
$weeks = [1, 2, 3, 4];
$roles = [
    'thuriferaire', 'naviculaire', 'libre', 'porte-croix',
    'acolyte1', 'acolyte2', 'ceroferaire1', 'ceroferaire2',
    'familier1', 'familier2'
];
$days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];  // Assurez-vous que cette ligne est incluse

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Assignation des rôles - Administrateur</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function showRoles(day) {
      let days = document.querySelectorAll('.roles-day');
      days.forEach(dayElement => {
        dayElement.style.display = 'none';
      });
      let selectedDay = document.getElementById('roles-' + day);
      selectedDay.style.display = 'block';
    }

    function searchServant(input) {
      const filter = input.value.toUpperCase();
      const select = input.nextElementSibling;
      const options = select.querySelectorAll('option');
      options.forEach(option => {
        if (option.text.toUpperCase().indexOf(filter) > -1 || option.value === "") {
          option.style.display = "";
        } else {
          option.style.display = "none";
        }
      });
    }
  </script>
</head>
<body class="bg-gray-50 font-sans flex">
  <!-- Menu Latéral -->
  <div class="w-64 bg-indigo-700 text-white min-h-screen p-6">
    <h1 class="text-2xl font-semibold mb-6">Tableau de bord Admin</h1>
    <div class="mb-6">
      <h2 class="text-lg font-semibold">Gestion des Servants</h2>
      <ul>
        <li><a href="add.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter un servant</a></li>
        <li><a href="update.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Modifier un servant</a></li>
        <li><a href="delete.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Supprimer un servant</a></li>
      </ul>
    </div>
    <div class="mb-6">
      
      <h2 class="text-lg font-semibold">Assignation des rôles</h2>
      <ul>
        <li><a href="?action=assign_roles" class="block py-2 text-gray-200 hover:bg-indigo-600">Assignation des rôles par semaine</a></li>
      </ul>
    </div>

    <!-- Autres actions -->
    <div class="mt-auto">
      <h2 class="text-lg font-semibold">Autres actions</h2>
      <ul>
        <li><a href="stat.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Statistiques</a></li>
        <li><a href="ajouter_avertissement.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter un avertissement</a></li>
        <li><a href="ajouter_divers.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter divers</a></li>
        <li><a href="logout.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Déconnexion</a></li>
      </ul>
    </div>
  </div>

  <!-- Contenu Principal -->
  <?php
// Chemin vers les fichiers de gestion des servants
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'add':
            include 'add.php';
            break;
        case 'update':
            include 'update.php';
            break;
        case 'delete':
            include 'delete.php';
            break;
        case 'assign_roles':
            include 'assign_roles.php';
            break;
        case 'stat':
            include 'stat.php';
            break;
        case 'ajouter_avertissement':
            include 'ajouter_avertissement.php';
            break;
        case 'ajouter_divers':
            include 'ajouter_divers.php';
            break;
        default:
            echo "<p class='text-red-600'>Action non reconnue.</p>";
            break;
    }
} else {
    echo "<div class='p-6'><h2 class='text-2xl font-bold mb-4'>Bienvenue 👋</h2><p>Sélectionnez une option dans le menu à gauche.</p></div>";
}
?>
</body>
</html>


