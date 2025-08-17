<?php
ob_start();
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "messe");
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Ajouter un divers
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $date_ajout = date("Y-m-d"); // Utilise la date actuelle

    // Insérer le divers dans la base de données
    $sql = "INSERT INTO divers (message, date) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $message, $date_ajout);

    if ($stmt->execute()) {
        echo "<p>Divers ajouté avec succès !</p>";
    } else {
        echo "<p>Erreur : " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un divers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ajouter un divers</h2>
        <form action="ajouter_divers.php" method="POST">
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea id="message" name="message" rows="4" class="mt-1 block w-full p-2 border-gray-300 rounded-md" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-600 text-white p-2 rounded">Ajouter le divers</button>
        </form>
    </div>
</body>
</html>
<?php
$content = ob_get_clean();
$title = "Modifier un Servant";
include('layout_admin.php');
?>