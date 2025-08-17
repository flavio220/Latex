<?php
ob_start();

$conn = new mysqli('localhost', 'root', '', 'messe');
if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

$successMessage = "";

// Met à jour les infos du servant
if (isset($_POST['update_servant'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $niveau = $_POST['niveau'];
    $stmt = $conn->prepare("UPDATE servants SET nom = ?, niveau = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nom, $niveau, $id);
    $stmt->execute();
    $stmt->close();
    $successMessage = "<p class='text-green-600 font-semibold mb-4'>Servant mis à jour avec succès !</p>";
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


<h1 class="text-3xl font-bold text-indigo-700 mb-6">Modifier un Servant</h1>

<?= $successMessage ?>

<div class="space-y-6">
    <?php while ($row = $result->fetch_assoc()): ?>
        <form method="POST" class="bg-white shadow-md rounded-lg p-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <div>
                <label class="block text-sm font-medium text-gray-600">Nom</label>
                <input name="nom" value="<?= htmlspecialchars($row['nom']) ?>" class="w-full border px-4 py-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Niveau</label>
                <select name="niveau" class="w-full border px-4 py-2 rounded" required>
                    <?php
                    $niveaux = ['porte-croix', 'thuriferaire', 'naviculaire', 'acolyte', 'libre', 'ceroferaire', 'familier'];
                    foreach ($niveaux as $niveau) {
                        $selected = $row['niveau'] == $niveau ? 'selected' : '';
                        echo "<option value=\"$niveau\" $selected>$niveau</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="update_servant" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Enregistrer</button>
        </form>
    <?php endwhile; ?>
</div>

<?php
$content = ob_get_clean();
$title = "Modifier un Servant";
include('layout_admin.php');
?>
