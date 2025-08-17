<?php
// Traitement de l'assignation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['month'], $_POST['week'])) {
    $month = $_POST['month'];
    $week = $_POST['week'];

    $valid_days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    $valid_roles = ['thuriferaire', 'naviculaire', 'libre', 'porte-croix', 'acolyte1', 'acolyte2', 'ceroferaire1', 'ceroferaire2', 'familier1', 'familier2'];

    foreach ($_POST['servant_id'] as $role_day => $servant_id) {
        list($day, $role) = explode('_', $role_day);

        if (!in_array($day, $valid_days) || !in_array($role, $valid_roles)) {
            echo "<p class='text-red-600'>Jour ou rôle invalide ($day - $role).</p>";
            continue;
        }

        $check_servant = $conn->prepare("SELECT COUNT(*) FROM servants WHERE id = ?");
        $check_servant->bind_param("i", $servant_id);
        $check_servant->execute();
        $check_servant->bind_result($count);
        $check_servant->fetch();
        $check_servant->close();

        if ($count == 0) {
            echo "<p class='text-red-600'>Le servant avec l'ID $servant_id n'existe pas.</p>";
            continue;
        }

        $stmt = $conn->prepare("INSERT INTO assignments (month, week, day_of_week, servant_id, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisis", $month, $week, $day, $servant_id, $role);
        $stmt->execute();
        $stmt->close();
    }

    echo "<p class='text-green-600 font-semibold'>Rôles assignés avec succès !</p>";
}

// Liste des servants
$servants_result = $conn->query("SELECT * FROM servants");
$servants = [];
while ($row = $servants_result->fetch_assoc()) {
    $servants[] = $row;
}

$months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
$weeks = [1, 2, 3, 4];
$roles = ['thuriferaire', 'naviculaire', 'libre', 'porte-croix', 'acolyte1', 'acolyte2', 'ceroferaire1', 'ceroferaire2', 'familier1', 'familier2'];
$days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
?>

<div class="w-full bg-white rounded-xl shadow-lg p-6 mb-12">
  <h2 class="text-2xl font-semibold text-gray-800 mb-4">Assignation des rôles pour la semaine</h2>

  <form action="" method="POST">
    <div class="mb-4">
      <label for="month" class="block text-sm text-gray-600">Mois</label>
      <select id="month" name="month" class="w-full p-2 border rounded mt-2" required>
        <?php foreach ($months as $month): ?>
          <option value="<?= $month ?>"><?= $month ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-4">
      <label for="week" class="block text-sm text-gray-600">Semaine</label>
      <select id="week" name="week" class="w-full p-2 border rounded mt-2" required>
        <?php foreach ($weeks as $week): ?>
          <option value="<?= $week ?>">Semaine <?= $week ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Choisir un jour</h3>
      <div class="flex flex-wrap gap-2">
        <?php foreach ($days as $day): ?>
          <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md" onclick="showRoles('<?= strtolower($day) ?>')"><?= $day ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <?php foreach ($days as $day): ?>
      <div id="roles-<?= strtolower($day) ?>" class="roles-day mb-6" style="display:none;">
        <h3 class="text-xl font-semibold text-gray-800 mb-4"><?= $day ?></h3>
        <?php foreach ($roles as $role): ?>
          <div class="mb-4">
            <label class="block text-sm text-gray-600"><?= ucfirst($role) ?></label>
            <input type="text" placeholder="Rechercher un servant" class="w-full p-2 border rounded mt-2 mb-2" onkeyup="searchServant(this)" />
            <select name="servant_id[<?= $day . '_' . $role ?>]" class="w-full p-2 border rounded mt-2" required>
              <option value="">Sélectionner un servant</option>
              <?php foreach ($servants as $row): ?>
                <option value="<?= $row['id'] ?>"><?= $row['nom'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>

    <button type="submit" class="px-6 py-2 bg-indigo-700 text-white rounded-md">Assigner les rôles</button>
  </form>
</div>
