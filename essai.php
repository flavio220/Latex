<?php
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

// Ajouter une assignation de rôle
if (isset($_POST['month'], $_POST['week'], $_POST['service_type'])) {
    $month = $_POST['month'];
    $week = $_POST['week'];
    $service_type = $_POST['service_type'];

    // Logique selon le type de service
    switch ($service_type) {
        case 'messe_domicile':
            // Assignation pour la messe domicale (6 servants nécessaires)
            for ($i = 1; $i <= 6; $i++) {
                $role = "role" . $i;  // Par exemple: role1, role2...
                $servant_id = $_POST[$role]; // Récupère l'ID du servant pour ce rôle

                // Insérer l'assignation dans la base de données
                $stmt = $conn->prepare("INSERT INTO assignments (month, week, service_type, servant_id, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sisis", $month, $week, $service_type, $servant_id, $role);
                $stmt->execute();
                $stmt->close();
            }
            break;

        case 'messe_semaine':
            // Assignation pour la messe de la semaine (2 servants par jour)
            foreach ($_POST['day_of_week'] as $day => $servants) {
                foreach ($servants as $servant_id) {
                    $role = 'acolyte';  // Assignation spécifique aux acolytes

                    // Insérer l'assignation dans la base de données
                    $stmt = $conn->prepare("INSERT INTO assignments (month, week, service_type, day_of_week, servant_id, role) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sissis", $month, $week, $service_type, $day, $servant_id, $role);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            break;

        case 'messe_speciale':
            // Assignation pour la messe spéciale (tous les rôles nécessaires)
            foreach ($roles as $role) {
                foreach ($_POST['servants'] as $servant_id) {
                    // Insérer l'assignation dans la base de données
                    $stmt = $conn->prepare("INSERT INTO assignments (month, week, service_type, servant_id, role) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sisis", $month, $week, $service_type, $servant_id, $role);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            break;

        default:
            echo "Type de service inconnu.";
            break;
    }

    echo "Assignation des rôles réussie!";
}

// Récupérer tous les servants dans un tableau
$servants_result = $conn->query("SELECT * FROM servants");
$servants = [];
while ($row = $servants_result->fetch_assoc()) {
    $servants[] = $row;
}

// Définir les variables nécessaires pour le formulaire
$months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
$weeks = [1, 2, 3, 4];
$roles = [
    'thuriferaire', 'naviculaire', 'libre', 'porte-croix',
    'acolyte1', 'acolyte2', 'ceroferaire1', 'ceroferaire2',
    'familier1', 'familier2'
];
$days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Assignation des rôles - Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Fonction pour afficher les jours selon le type de messe
        function showDaysByServiceType() {
            let serviceType = document.getElementById("service_type").value;
            let daysContainer = document.getElementById("days_container");

            daysContainer.innerHTML = ""; // Réinitialiser l'affichage

            // Afficher les jours en fonction du type de service
            if (serviceType === "messe_semaine") {
                for (let i = 0; i < 6; i++) {
                    let day = document.createElement("div");
                    day.innerHTML = `
                        <label for="servants[${i}]">${days[i]}</label>
                        <select name="servants[${i}]">
                            <option value="">Sélectionner un acolyte</option>
                            ${servants.map(servant => `<option value="${servant.id}">${servant.name}</option>`).join("")}
                        </select>`;
                    daysContainer.appendChild(day);
                }
            } else if (serviceType === "messe_domicile") {
                let day = document.createElement("div");
                day.innerHTML = `
                    <label for="servants[0]">Dimanche (Messe Domicale)</label>
                    <select name="servants[0]">
                        <option value="">Sélectionner un servant</option>
                        ${servants.filter(servant => !['ceroferaire1', 'ceroferaire2', 'familier1', 'familier2'].includes(servant.role)).map(servant => `<option value="${servant.id}">${servant.name}</option>`).join("")}
                    </select>`;
                daysContainer.appendChild(day);
            } else if (serviceType === "messe_speciale") {
                let day = document.createElement("div");
                day.innerHTML = `
                    <label for="servants[0]">Sélectionner un jour pour la messe spéciale</label>
                    <input type="date" name="special_day" required>
                    <select name="servants[0]">
                        <option value="">Sélectionner un servant</option>
                        ${servants.map(servant => `<option value="${servant.id}">${servant.name}</option>`).join("")}
                    </select>`;
                daysContainer.appendChild(day);
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="w-64 bg-indigo-700 text-white min-h-screen p-6">
        <h1 class="text-2xl font-semibold mb-6">Tableau de bord Admin</h1>
        <form action="assign_roles.php" method="POST">
            <label for="month">Mois</label>
            <select name="month" id="month">
                <?php foreach ($months as $month): ?>
                    <option value="<?= $month ?>"><?= $month ?></option>
                <?php endforeach; ?>
            </select>
            <br>

            <label for="week">Semaine</label>
            <select name="week" id="week">
                <?php foreach ($weeks as $week): ?>
                    <option value="<?= $week ?>">Semaine <?= $week ?></option>
                <?php endforeach; ?>
            </select>
            <br>

            <label for="service_type">Type de service</label>
            <select name="service_type" id="service_type" onchange="showDaysByServiceType()">
                <option value="messe_semaine">Messe de la semaine</option>
                <option value="messe_domicile">Messe Domicale</option>
                <option value="messe_speciale">Messe Spéciale</option>
            </select>
            <br>

            <div id="days_container"></div>

            <button type="submit">Assigner les rôles</button>
        </form>
    </div>
</body>
</html>
