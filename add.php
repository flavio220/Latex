<?php
ob_start();

// Connexion à la BDD
$conn = new mysqli('localhost', 'root', '', 'messe');
if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

$successMessage = "";
if (isset($_POST['add_servant'])) {
    $nom = $_POST['nom'];
    $matricule = uniqid('SVT');
    $niveau = $_POST['niveau'];
    $stmt = $conn->prepare("INSERT INTO servants (nom, matricule, niveau) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $matricule, $niveau);
    $stmt->execute();
    $stmt->close();
    $successMessage = "<p class='text-green-600 font-semibold mb-4'>Servant ajouté avec succès !</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Servant</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<h1 class="text-3xl font-bold text-indigo-700 mb-6">Ajouter un Servant</h1>

<div class="bg-white shadow-lg rounded-xl p-6">
    <?= $successMessage ?>
    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-600">Nom du servant</label>
            <input name="nom" placeholder="Ex: Jean-Paul" required class="w-full border rounded px-4 py-2 mt-1" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Niveau</label>
            <select name="niveau" required class="w-full border rounded px-4 py-2 mt-1">
                <option value="">-- Sélectionner le niveau --</option>
                <option value="porte-croix">Porte-croix</option>
                <option value="thuriferaire">Thuriféraire</option>
                <option value="naviculaire">Naviculaire</option>
                <option value="acolyte">Acolyte</option>
                <option value="libre">Libre</option>
                <option value="ceroferaire">Céréféraire</option>
                <option value="familier">Familier</option>
            </select>
        </div>
        <button type="submit" name="add_servant" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Ajouter</button>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = "Modifier un Servant";
include('layout_admin.php');
?>

