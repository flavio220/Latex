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
?>
