<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Servants de Messe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">
  <div class="min-h-screen flex flex-col items-center justify-start p-6">
    <!-- Message de bienvenue -->
    <div class="text-center mt-16 mb-10">
      <h1 id="welcome" class="text-4xl font-bold text-indigo-700 mb-4">Bienvenue sur l’espace des Servants de Messe de la Paroisse Saint Michel</h1>
      <p class="text-lg text-gray-600">Que votre service à l’autel soit rempli de grâce et de joie ✨</p>
    </div>

    <!-- Tableau des servants -->
    <div class="w-full bg-white rounded-xl shadow-lg p-6">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Liste des Servants</h2>
      <table class="w-full table-auto border-collapse">
        <thead>
          <tr class="bg-indigo-100 text-indigo-700">
            <th class="px-4 py-2 text-left">Nom</th>
            <th class="px-4 py-2 text-left">Niveau</th>
            <th class="px-4 py-2 text-left">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">AWONGBONON Giovanni</td>
            <td class="px-4 py-2">Thuriféraire</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">KOUASSI André</td>
            <td class="px-4 py-2">Porte-croix</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">AGOSSOU Mireille</td>
            <td class="px-4 py-2">Naviculaire</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">HOUNTONDJI Richard</td>
            <td class="px-4 py-2">Céroféraire</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">DOSSOU Yvan</td>
            <td class="px-4 py-2">Acolyte</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">ZOHOUN Christelle</td>
            <td class="px-4 py-2">Libre</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">TOKPONYON Daniel</td>
            <td class="px-4 py-2">Familiers</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">AHOUANDJINOU Laetitia</td>
            <td class="px-4 py-2">Naviculaire</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">FAGNISSO Hugues</td>
            <td class="px-4 py-2">Thuriféraire</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-100 transition">
            <td class="px-4 py-2">AHOUANGBEDE Reine</td>
            <td class="px-4 py-2">Céroféraire</td>
            <td class="px-4 py-2">
              <a href="#" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Voir le tableau de bord</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Animation du message de bienvenue -->
  <script>
    anime({
      targets: '#welcome',
      translateY: [-50, 0],
      opacity: [0, 1],
      duration: 1200,
      easing: 'easeOutExpo'
    });
  </script>
</body>
</html>
