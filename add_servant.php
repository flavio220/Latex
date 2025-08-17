<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'messe';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Ajouter un servant
if (isset($_POST['add_servant'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $matricule = uniqid('SVT');
    $stmt = $conn->prepare("INSERT INTO servants (nom, prenom, matricule) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $prenom, $matricule);
    $stmt->execute();
    $stmt->close();
    echo "<p class='text-green-600'>Servant ajouté avec succès !</p>";
}

// Modifier un servant
if (isset($_POST['edit_servant'])) {
    $id = $_POST['servant_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $stmt = $conn->prepare("UPDATE servants SET nom = ?, prenom = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nom, $prenom, $id);
    $stmt->execute();
    $stmt->close();
    echo "<p class='text-blue-600'>Servant modifié avec succès !</p>";
}

// Supprimer un servant
if (isset($_POST['delete_servant'])) {
    $id = $_POST['servant_id'];
    $stmt = $conn->prepare("DELETE FROM servants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<p class='text-red-600'>Servant supprimé avec succès !</p>";
}

// Ajouter un avertissement
if (isset($_POST['add_warning'])) {
    $servant_id = $_POST['servant_id'];
    $contenu = $_POST['contenu'];
    $stmt = $conn->prepare("INSERT INTO avertissements (servant_id, contenu, date) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $servant_id, $contenu);
    $stmt->execute();
    $stmt->close();
    echo "<p class='text-yellow-600'>Avertissement ajouté avec succès !</p>";
}

// Ajouter un divers
if (isset($_POST['add_divers'])) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $stmt = $conn->prepare("INSERT INTO divers (titre, contenu, date_creation) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $titre, $contenu);
    $stmt->execute();
    $stmt->close();
    echo "<p class='text-indigo-600'>Divers ajouté avec succès !</p>";
}

// Récupérer tous les servants
$servants_result = $conn->query("SELECT * FROM servants");
$servants = [];
while ($row = $servants_result->fetch_assoc()) {
    $servants[] = $row;
}

?>

<!-- HTML début -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex min-h-screen">
    <!-- Menu latéral -->
    <aside class="w-64 bg-indigo-700 text-white p-6">
      <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
      <nav class="space-y-4">
        <a href="?action=add" class="block hover:bg-indigo-600 p-2 rounded">Ajouter un servant</a>
        <a href="?action=edit" class="block hover:bg-indigo-600 p-2 rounded">Modifier un servant</a>
        <a href="?action=delete" class="block hover:bg-indigo-600 p-2 rounded">Supprimer un servant</a>
        <a href="?action=add_warning" class="block hover:bg-indigo-600 p-2 rounded">Ajouter un avertissement</a>
        <a href="?action=add_divers" class="block hover:bg-indigo-600 p-2 rounded">Ajouter divers</a>
      </nav>
    </aside>

    <!-- Contenu principal -->
    <main class="flex-1 p-8">
      <h2 class="text-3xl font-semibold text-indigo-700 mb-6">Gestion Admin</h2>

      <!-- Ajouter un servant -->
      <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
        <form method="POST" class="bg-white p-6 rounded shadow-md">
          <h3 class="text-xl font-bold mb-4">Ajouter un servant</h3>
          <input type="text" name="nom" placeholder="Nom" required class="w-full p-2 border rounded mb-4">
          <input type="text" name="prenom" placeholder="Prénom" required class="w-full p-2 border rounded mb-4">
          <button name="add_servant" class="bg-indigo-700 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
      <?php endif; ?>

      <!-- Modifier un servant -->
      <?php if (isset($_GET['action']) && $_GET['action'] == 'edit'): ?>
        <form method="POST" class="bg-white p-6 rounded shadow-md">
          <h3 class="text-xl font-bold mb-4">Modifier un servant</h3>
          <select name="servant_id" required class="w-full p-2 border rounded mb-4">
            <option value="">-- Choisir un servant --</option>
            <?php foreach ($servants as $s): ?>
              <option value="<?= $s['id'] ?>"><?= $s['nom'] . ' ' . $s['prenom'] ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" name="nom" placeholder="Nouveau nom" required class="w-full p-2 border rounded mb-4">
          <input type="text" name="prenom" placeholder="Nouveau prénom" required class="w-full p-2 border rounded mb-4">
          <button name="edit_servant" class="bg-blue-600 text-white px-4 py-2 rounded">Modifier</button>
        </form>
      <?php endif; ?>

      <!-- Supprimer un servant -->
      <?php if (isset($_GET['action']) && $_GET['action'] == 'delete'): ?>
        <form method="POST" class="bg-white p-6 rounded shadow-md">
          <h3 class="text-xl font-bold mb-4">Supprimer un servant</h3>
          <select name="servant_id" required class="w-full p-2 border rounded mb-4">
            <option value="">-- Choisir un servant --</option>
            <?php foreach ($servants as $s): ?>
              <option value="<?= $s['id'] ?>"><?= $s['nom'] ?></option>
            <?php endforeach; ?>
          </select>
          <button name="delete_servant" class="bg-red-600 text-white px-4 py-2 rounded">Supprimer</button>
        </form>
      <?php endif; ?>

      <!-- Ajouter un avertissement -->
      <?php if (isset($_GET['action']) && $_GET['action'] == 'add_warning'): ?>
        <form method="POST" class="bg-white p-6 rounded shadow-md">
          <h3 class="text-xl font-bold mb-4">Ajouter un avertissement</h3>
          <select name="servant_id" required class="w-full p-2 border rounded mb-4">
            <option value="">-- Choisir un servant --</option>
            <?php foreach ($servants as $s): ?>
              <option value="<?= $s['id'] ?>"><?= $s['nom']  ?></option>
            <?php endforeach; ?>
          </select>
          <textarea name="contenu" placeholder="Contenu de l'avertissement" required class="w-full p-2 border rounded mb-4"></textarea>
          <button name="add_warning" class="bg-yellow-500 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
      <?php endif; ?>

      <!-- Ajouter un divers -->
      <?php if (isset($_GET['action']) && $_GET['action'] == 'add_divers'): ?>
        <form method="POST" class="bg-white p-6 rounded shadow-md">
          <h3 class="text-xl font-bold mb-4">Ajouter un divers</h3>
          <input type="text" name="titre" placeholder="Titre" required class="w-full p-2 border rounded mb-4">
          <textarea name="contenu" placeholder="Contenu" required class="w-full p-2 border rounded mb-4"></textarea>
          <button name="add_divers" class="bg-indigo-700 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
