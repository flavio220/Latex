<?php
session_start();
session_destroy(); // Détruit la session
header("Location: login_servant.php"); // Redirige vers la page de connexion
exit();
