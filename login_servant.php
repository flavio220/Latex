<?php
session_start();
$conn = new mysqli("localhost", "root", "", "messe");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = $_POST['matricule'];

    $stmt = $conn->prepare("SELECT * FROM servants WHERE matricule = ?");
    $stmt->bind_param("s", $matricule);
    $stmt->execute();
    $result = $stmt->get_result();
    $servant = $result->fetch_assoc();

    if ($servant) {
        $_SESSION['matricule'] = $servant['matricule'];
        $_SESSION['servant_id'] = $servant['id'];
        $_SESSION['nom'] = $servant['nom'];
        $_SESSION['niveau'] = $servant['niveau'];
        header("Location: dashboard_servant.php");
        exit();
    } else {
        $error = "Matricule incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Servant</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
  <form method="POST" class="bg-white p-6 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4 text-center">Connexion Servant</h1>
    <?php if (isset($error)) echo "<p class='text-red-500 text-sm mb-4'>$error</p>"; ?>
    <label class="block mb-2">Matricule :</label>
    <input type="text" name="matricule" required class="w-full border border-gray-300 p-2 rounded mb-4">
    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Se connecter</button>
  </form>
</body>
</html>
