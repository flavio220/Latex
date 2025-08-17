<?php
ob_start();
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

// Ajouter un avertissement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécuriser et valider les données reçues
    $servant_id = filter_input(INPUT_POST, 'servant_id', FILTER_SANITIZE_NUMBER_INT);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $date = date("Y-m-d"); // Date du jour

    // Vérifier si les valeurs sont valides
    if (empty($servant_id) || empty($message)) {
        echo "<p class='text-red-500'>Erreur : L'ID du servant ou le message sont vides.</p>";
    } else {
        // Insérer l'avertissement dans la base de données
        $sql = "INSERT INTO warnings (servant_id, message, date) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iss", $servant_id, $message, $date);

            if ($stmt->execute()) {
                echo "<p class='text-green-500'>Avertissement ajouté avec succès !</p>";
            } else {
                echo "<p class='text-red-500'>Erreur : " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='text-red-500'>Erreur de préparation de la requête SQL.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avertissements</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <!-- Formulaire pour ajouter un avertissement -->
    <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ajouter un avertissement</h2>
        <form action="ajouter_avertissement.php" method="POST">
            <div class="mb-4">
                <label for="servant" class="block text-sm font-medium text-gray-700">Sélectionner le servant</label>
                <select id="servant" name="servant_id" class="mt-1 block w-full p-2 border-gray-300 rounded-md" required>
                    <?php
                    // Récupérer tous les servants
                    $result = $conn->query("SELECT id, nom FROM servants");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nom']}</option>";
                        }
                    } else {
                        echo "<option value=''>Aucun servant trouvé</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">Message d'avertissement</label>
                <textarea id="message" name="message" rows="4" class="mt-1 block w-full p-2 border-gray-300 rounded-md" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-600 text-white p-2 rounded">Ajouter l'avertissement</button>
        </form>
    </div>

    <!-- Liste des avertissements -->
    <div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Liste des avertissements</h2>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Servant</th>
                    <th class="py-2 px-4 border-b">Message</th>
                    <th class="py-2 px-4 border-b">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer tous les avertissements
                $result = $conn->query("SELECT s.nom, w.message, w.date FROM warnings w JOIN servants s ON w.servant_id = s.id ORDER BY w.date DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='py-2 px-4 border-b'>{$row['nom']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['message']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['date']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='py-2 px-4 border-b'>Aucun avertissement trouvé.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
$title = "Modifier un Servant";
include('layout_admin.php');
?>
