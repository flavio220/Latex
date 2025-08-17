<?php
ob_start();

$conn = new mysqli('localhost', 'root', '', 'messe');
if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

$successMessage = "";

// Supprime le servant
if (isset($_POST['delete_servant'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM servants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $successMessage = "<p class='text-red-600 font-semibold mb-4'>Servant supprimé avec succès !</p>";
}

// Récupère les servants
$result = $conn->query("SELECT * FROM servants");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avertissements</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<h1 class="text-3xl font-bold text-indigo-700 mb-6">Supprimer un Servant</h1>

<?= $successMessage ?>

<div class="space-y-4">
    <?php while ($row = $result->fetch_assoc()): ?>
        <form method="POST" class="flex items-center justify-between bg-white shadow rounded-lg p-4">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <div>
                <p class="text-lg font-semibold"><?= htmlspecialchars($row['nom']) ?></p>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($row['matricule']) ?> — <?= $row['niveau'] ?></p>
            </div>
            <button type="submit" name="delete_servant" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Supprimer</button>
        </form>
    <?php endwhile; ?>
</div>

<?php
$content = ob_get_clean();
$title = "Supprimer un Servant";
include('layout_admin.php');
?>
