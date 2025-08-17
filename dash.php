<?php
// Connexion à la base de données
$host = 'localhost'; // hôte de votre base de données
$user = 'root';      // nom d'utilisateur MySQL
$pass = '';          // mot de passe MySQL
$db = 'messe';       // base de données à utiliser

$conn = new mysqli($host, $user, $pass, $db);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Ajouter un servant
if (isset($_POST['name']) && isset($_POST['role'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Insertion du servant dans la base de données
    $stmt = $conn->prepare("INSERT INTO servants (nom, niveau) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $role);
    if ($stmt->execute()) {
        echo "Servant ajouté avec succès!";
    } else {
        echo "Erreur lors de l'ajout du servant.";
    }
    $stmt->close();
}

// Modifier un servant
if (isset($_POST['update_id']) && isset($_POST['update_name']) && isset($_POST['update_role'])) {
    $update_id = $_POST['update_id'];
    $update_name = $_POST['update_name'];
    $update_role = $_POST['update_role'];

    // Mise à jour du servant dans la base de données
    $stmt = $conn->prepare("UPDATE servants SET nom = ?, niveau = ? WHERE id = ?");
    $stmt->bind_param("ssi", $update_name, $update_role, $update_id);
    if ($stmt->execute()) {
        echo "Servant mis à jour avec succès!";
    } else {
        echo "Erreur lors de la mise à jour du servant.";
    }
    $stmt->close();
}

// Supprimer un servant
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Suppression du servant dans la base de données
    $stmt = $conn->prepare("DELETE FROM servants WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Servant supprimé avec succès!";
    } else {
        echo "Erreur lors de la suppression du servant.";
    }
    $stmt->close();
}

// Récupérer tous les servants pour l'affichage
$servants_result = $conn->query("SELECT * FROM servants");

// Ajouter une assignation de rôle
if (isset($_POST['month'], $_POST['week'], $_POST['day_of_week'])) {
    $month = $_POST['month'];
    $week = $_POST['week'];
    $day_of_week = $_POST['day_of_week'];

    // Assignation des rôles pour chaque jour et rôle
    foreach ($_POST['servant_id'] as $role_day => $servant_id) {
        // Extraire jour et rôle depuis l'index du tableau (par exemple lundi_thuriferaire)
        list($day, $role) = explode('_', $role_day);

        // Insérer l'assignation dans la base de données
        $stmt = $conn->prepare("INSERT INTO assignments (month, week, day_of_week, servant_id, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisis", $month, $week, $day, $servant_id, $role);
        $stmt->execute();
    }
    echo "Rôles assignés avec succès!";
}

// Récupérer tous les rôles
$roles = [
    'thuriferaire', 'naviculaire', 'libre', 'porte-croix', 'acolyte1', 'acolyte2', 'ceroferaire1', 'ceroferaire2', 'familier1', 'familier2'
];

// Afficher les mois et les semaines possibles
$months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
$weeks = [1, 2, 3, 4];

// Récupérer tous les servants pour l'affichage
$servants_result = $conn->query("SELECT * FROM servants");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord - Administrateur</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Fonction pour afficher les rôles du jour sélectionné
    function showRoles(day) {
      // Masquer tous les jours
      let days = document.querySelectorAll('.roles-day');
      days.forEach(dayElement => {
        dayElement.style.display = 'none';
      });

      // Afficher les rôles pour le jour sélectionné
      let selectedDay = document.getElementById('roles-' + day);
      selectedDay.style.display = 'block';
    }

    // Fonction pour rechercher un servant
    function searchServant(input) {
      const filter = input.value.toUpperCase();
      const options = document.querySelectorAll('.servant-option');
      options.forEach(option => {
        const servantName = option.textContent || option.innerText;
        if (servantName.toUpperCase().indexOf(filter) > -1) {
          option.style.display = '';
        } else {
          option.style.display = 'none';
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
        <li><a href="?action=add" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter un servant</a></li>
        <li><a href="?action=edit" class="block py-2 text-gray-200 hover:bg-indigo-600">Modifier un servant</a></li>
        <li><a href="?action=delete" class="block py-2 text-gray-200 hover:bg-indigo-600">Supprimer un servant</a></li>
      </ul>
    </div>
    <div class="mb-6">
      <h2 class="text-lg font-semibold">Assignation des rôles</h2>
      <ul>
        <li><a href="?action=assign_roles" class="block py-2 text-gray-200 hover:bg-indigo-600">Assignation des rôles par semaine</a></li>
      </ul>
    </div>
  </div>

  <!-- Contenu Principal -->
  <div class="flex-1 p-6">
    <h1 class="text-4xl font-bold text-indigo-700 mb-6">Gestion des Servants de Messe</h1>

    <!-- Ajouter un servant -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
      <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ajouter un Servant</h2>
        <form action="" method="POST">
          <div class="mb-4">
            <label for="name" class="block text-sm text-gray-600">Nom du Servant</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded mt-2" required>
          </div>
          <div class="mb-4">
            <label for="role" class="block text-sm text-gray-600">Niveau (Rôle)</label>
            <select id="role" name="role" class="w-full p-2 border rounded mt-2" required>
              <option value="thuriferaire">Thuriféraire</option>
              <option value="naviculaire">Naviculaire</option>
              <option value="libre">Libre</option>
              <option value="porte-croix">Porte-Croix</option>
              <option value="acolyte1">Acolyte 1</option>
              <option value="acolyte2">Acolyte 2</option>
              <option value="ceroferaire1">Céroféraire 1</option>
              <option value="ceroferaire2">Céroféraire 2</option>
              <option value="familier1">Familier 1</option>
              <option value="familier2">Familier 2</option>
            </select>
          </div>
          <button type="submit" class="px-6 py-2 bg-indigo-700 text-white rounded-md">Ajouter le Servant</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- Modifier un servant -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'edit'): ?>
      <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Modifier un Servant</h2>
        <form action="" method="POST">
          <div class="mb-4">
            <label for="update_id" class="block text-sm text-gray-600">Sélectionner le Servant</label>
            <select id="update_id" name="update_id" class="w-full p-2 border rounded mt-2" required>
              <option value="">Sélectionner un servant</option>
              <?php while ($row = $servants_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nom']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-4">
            <label for="update_name" class="block text-sm text-gray-600">Nom du Servant</label>
            <input type="text" id="update_name" name="update_name" class="w-full p-2 border rounded mt-2" required>
          </div>
          <div class="mb-4">
            <label for="update_role" class="block text-sm text-gray-600">Niveau (Rôle)</label>
            <select id="update_role" name="update_role" class="w-full p-2 border rounded mt-2" required>
              <option value="thuriferaire">Thuriféraire</option>
              <option value="naviculaire">Naviculaire</option>
              <option value="libre">Libre</option>
              <option value="porte-croix">Porte-Croix</option>
              <option value="acolyte1">Acolyte 1</option>
              <option value="acolyte2">Acolyte 2</option>
              <option value="ceroferaire1">Céroféraire 1</option>
              <option value="ceroferaire2">Céroféraire 2</option>
              <option value="familier1">Familier 1</option>
              <option value="familier2">Familier 2</option>
            </select>
          </div>
          <button type="submit" class="px-6 py-2 bg-indigo-700 text-white rounded-md">Mettre à jour le Servant</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- Supprimer un servant -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'delete'): ?>
      <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Supprimer un Servant</h2>
        <form action="" method="POST">
          <div class="mb-4">
            <label for="delete_id" class="block text-sm text-gray-600">Sélectionner le Servant à supprimer</label>
            <select id="delete_id" name="delete_id" class="w-full p-2 border rounded mt-2" required>
              <option value="">Sélectionner un servant</option>
              <?php while ($row = $servants_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nom']; ?></option>
              <?php } ?>
            </select>
          </div>
          <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-md">Supprimer le Servant</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- Assigner des rôles -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'assign_roles'): ?>
      <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Assigner des Rôles</h2>
        <form action="" method="POST">
          <div class="mb-4">
            <label for="month" class="block text-sm text-gray-600">Mois</label>
            <select id="month" name="month" class="w-full p-2 border rounded mt-2" required>
              <?php foreach ($months as $month): ?>
                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-4">
            <label for="week" class="block text-sm text-gray-600">Semaine</label>
            <select id="week" name="week" class="w-full p-2 border rounded mt-2" required>
              <?php foreach ($weeks as $week): ?>
                <option value="<?php echo $week; ?>"><?php echo $week; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-4">
            <label for="day_of_week" class="block text-sm text-gray-600">Jour de la Semaine</label>
            <select id="day_of_week" name="day_of_week" class="w-full p-2 border rounded mt-2" required>
              <option value="lundi">Lundi</option>
              <option value="mardi">Mardi</option>
              <option value="mercredi">Mercredi</option>
              <option value="jeudi">Jeudi</option>
              <option value="vendredi">Vendredi</option>
              <option value="samedi">Samedi</option>
              <option value="dimanche">Dimanche</option>
            </select>
          </div>
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Assigner des rôles</h3>
          <?php foreach ($roles as $role): ?>
            <div class="mb-4">
              <label class="block text-sm text-gray-600"><?php echo ucfirst($role); ?></label>
              <select name="servant_id[<?php echo strtolower($role); ?>]" class="w-full p-2 border rounded mt-2" required>
                <option value="">Sélectionner un servant</option>
                <?php while ($row = $servants_result->fetch_assoc()) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['nom']; ?></option>
                <?php } ?>
              </select>
            </div>
          <?php endforeach; ?>
          <button type="submit" class="px-6 py-2 bg-indigo-700 text-white rounded-md">Assigner les rôles</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

<?php
$conn->close();
?>
