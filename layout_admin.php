
<!-- layout_admin.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Admin' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans flex">
  <!-- Menu Latéral -->
  <div class="w-64 bg-indigo-700 text-white min-h-screen p-6">
    <h1 class="text-2xl font-semibold mb-6">Tableau de bord Admin</h1>
    <div class="mb-6">
      <h2 class="text-lg font-semibold">Gestion des Servants</h2>
      <ul>
        <li><a href="?action=add.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter un servant</a></li>
        <li><a href="?action=update.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Modifier un servant</a></li>
        <li><a href="?action=delete.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Supprimer un servant</a></li>
      </ul>
    </div>
    <div class="mb-6">
      <h2 class="text-lg font-semibold">Assignation des rôles</h2>
      <ul>
        <li><a href="?action=assign_roles" class="block py-2 text-gray-200 hover:bg-indigo-600">Assignation des rôles par semaine</a></li>
      </ul>
    </div>
    <div class="mt-auto">
      <h2 class="text-lg font-semibold">Autres actions</h2>
      <ul>
        <li><a href="?action=stat.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Statistiques</a></li>
        <li><a href="?action=ajouter_avertissement.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter un avertissement</a></li>
        <li><a href="?action=ajouter_divers.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Ajouter divers</a></li>
        <li><a href="?action=logout.php" class="block py-2 text-gray-200 hover:bg-indigo-600">Déconnexion</a></li>
      </ul>
    </div>
  </div>

  <!-- Contenu Principal -->
  <div class="flex-1 p-6">
    <?= $content ?>
  </div>
</body>
</html>
