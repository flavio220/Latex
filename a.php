<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard des Services de la Semaine</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans flex">

  <!-- Menu Latéral -->
  <div class="w-64 bg-indigo-700 text-white min-h-screen p-6">
    <h1 class="text-2xl font-semibold mb-6">Tableau de bord</h1>
    
    <!-- Sélection du mois -->
    <div class="mb-6">
      <h2 class="text-lg font-semibold">Mois</h2>
      <ul>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Janvier</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Février</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Mars</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Avril</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Mai</a></li>
      </ul>
    </div>

    <!-- Sélection de la semaine -->
    <div>
      <h2 class="text-lg font-semibold">Semaines</h2>
      <ul>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Semaine 1</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Semaine 2</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Semaine 3</a></li>
        <li><a href="#" class="block py-2 text-gray-200 hover:bg-indigo-600">Semaine 4</a></li>
      </ul>
    </div>
  </div>

  <!-- Contenu Principal -->
  <div class="flex-1 p-6">
    <!-- Message de bienvenue -->
    <div class="text-center mt-16 mb-10">
      <h1 class="text-4xl font-bold text-indigo-700 mb-4">Bienvenue sur l’espace des Servants de Messe</h1>
      <p class="text-lg text-gray-600">Que votre service à l’autel soit rempli de grâce et de joie ✨</p>
    </div>

    <!-- Classement des services de la semaine -->
    <div class="w-full bg-white rounded-xl shadow-lg p-6">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Classement des services de la semaine</h2>

      <!-- Sélection de la semaine -->
      <div class="mb-4">
        <label for="week" class="text-gray-600">Semaine :</label>
        <select id="week" class="mt-2 p-2 border rounded">
          <option value="1">Semaine 1</option>
          <option value="2">Semaine 2</option>
          <option value="3">Semaine 3</option>
          <option value="4">Semaine 4</option>
        </select>
      </div>

      <!-- Tableau des services pour chaque jour de la semaine -->
      <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Lundi</h3>
        <table class="w-full table-auto border-collapse">
          <thead>
            <tr class="bg-green-100 text-green-700">
              <th class="px-4 py-2 text-left">Rôle</th>
              <th class="px-4 py-2 text-left">Servants</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="px-4 py-2">Thuriféraire</td>
              <td class="px-4 py-2">AWONGBONON Giovanni, ZOHOUN Christelle</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Naviculaire</td>
              <td class="px-4 py-2">KOUASSI André</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Libre</td>
              <td class="px-4 py-2">HOUNTONDJI Richard</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Porte-Croix</td>
              <td class="px-4 py-2">DOSSOU Yvan</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Acolyte 1</td>
              <td class="px-4 py-2">BLOHOUNA Yannick</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Acolyte 2</td>
              <td class="px-4 py-2">TOHOU Angèle</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Céroféraire 1</td>
              <td class="px-4 py-2">KOFFI Théodore</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Céroféraire 2</td>
              <td class="px-4 py-2">YAO Marie</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Familier 1</td>
              <td class="px-4 py-2">SOSSOU Médard</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Familier 2</td>
              <td class="px-4 py-2">HOUSSOU Théodore</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Répétez pour les autres jours de la semaine -->
      <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Mardi</h3>
        <!-- Vous pouvez répéter le même tableau pour chaque jour -->
        <table class="w-full table-auto border-collapse">
          <thead>
            <tr class="bg-green-100 text-green-700">
              <th class="px-4 py-2 text-left">Rôle</th>
              <th class="px-4 py-2 text-left">Servants</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="px-4 py-2">Thuriféraire</td>
              <td class="px-4 py-2">AWONGBONON Giovanni</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Naviculaire</td>
              <td class="px-4 py-2">KOUASSI André</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Libre</td>
              <td class="px-4 py-2">HOUNTONDJI Richard</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Porte-Croix</td>
              <td class="px-4 py-2">DOSSOU Yvan</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Acolyte 1</td>
              <td class="px-4 py-2">BLOHOUNA Yannick</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Acolyte 2</td>
              <td class="px-4 py-2">TOHOU Angèle</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Céroféraire 1</td>
              <td class="px-4 py-2">KOFFI Théodore</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Céroféraire 2</td>
              <td class="px-4 py-2">YAO Marie</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Familier 1</td>
              <td class="px-4 py-2">SOSSOU Médard</td>
            </tr>
            <tr>
              <td class="px-4 py-2">Familier 2</td>
              <td class="px-4 py-2">HOUSSOU Théodore</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Répétez pour les autres jours de la semaine -->
    </div>
  </div>
</body>
</html>
