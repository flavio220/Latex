<?php
ob_start();
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'messe';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// 1. Nombre total de servants
$result = $conn->query("SELECT COUNT(*) AS total FROM servants");
$total_servants = $result->fetch_assoc()['total'] ?? 0;

// 2. Servant ayant le plus servi ce mois
$query = "
    SELECT s.nom, COUNT(*) AS service_count 
    FROM assignments a
    JOIN servants s ON a.servant_id = s.id
    WHERE MONTH(a.date) = MONTH(CURRENT_DATE) AND YEAR(a.date) = YEAR(CURRENT_DATE)
    GROUP BY s.id
    ORDER BY service_count DESC
    LIMIT 1
";

$top_result = $conn->query($query);
$top_servant = $top_result->fetch_assoc() ?? ['nom' => 'Aucun', 'total_services' => 0];

// 3. Graphique : Nombre de services par jour
$jours = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
$labels_fr = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
$servants_per_day = [];

foreach ($jours as $jour) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM assignments WHERE LOWER(date) = ?");
    $lower_day = strtolower($jour);
    $stmt->bind_param('s', $lower_day);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $servants_per_day[] = (int)$res['count'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex">


<!-- Contenu Principal -->
<div class="flex-1 p-6">
    <h2 class="text-3xl font-semibold text-indigo-700 mb-6">Statistiques générales</h2>

    <div class="mb-6 bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-2">Nombre total de servants</h3>
        <p>Il y a <strong><?= $total_servants ?></strong> servants inscrits.</p>
    </div>

    <div class="mb-6 bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-2">Servant le plus actif ce mois</h3>
        <p><?= htmlspecialchars($top_servant['nom']) ?> avec <strong><?= $top_servant['total_services'] ?></strong> services.</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Nombre de services par jour</h3>
        <canvas id="barChart" height="100"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels_fr) ?>,
            datasets: [{
                label: 'Nombre de services',
                data: <?= json_encode($servants_per_day) ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.5)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>
<?php
$content = ob_get_clean();
$title = "Modifier un Servant";
include('layout_admin.php');
?>