<?php
session_start();
$conn = new mysqli("localhost", "root", "", "messe");
if (!isset($_SESSION['servant_id'])) {
    header("Location: login_servant.php");
    exit();
}
$id = $_SESSION['servant_id'];
$servant = $conn->query("SELECT * FROM servants WHERE id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Servant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-3xl font-bold text-indigo-700 mb-4">Bienvenue <?php echo htmlspecialchars($servant['nom']); ?> 👋</h1>
        
        <div class="mb-4">
            <a href="mes_services.php" class="block bg-indigo-600 text-white px-4 py-2 rounded mb-2 hover:bg-indigo-700">Voir mes services</a>
            <a href="mes_avertissements.php" class="block bg-red-500 text-white px-4 py-2 rounded mb-2 hover:bg-red-600">Mes avertissements</a>
            <a href="divers.php" class="block bg-yellow-400 text-white px-4 py-2 rounded mb-2 hover:bg-yellow-500">Informations diverses</a>
            <a href="logout.php" class="block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Déconnexion</a>
        </div>
    </div>
</body>
</html>
