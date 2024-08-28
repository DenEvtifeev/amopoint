<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$config = require 'config.php';

try {
    $dsn = "{$config['db_type']}:host={$config['db_host']};dbname={$config['db_name']};charset=utf8";
    $db = new PDO($dsn, $config['db_user'], $config['db_pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $hourlyData = $db->query("SELECT DATE_FORMAT(visit_time, '%H:00') as hour, COUNT(*) as visits FROM visits GROUP BY hour")->fetchAll(PDO::FETCH_ASSOC);
    $cityData = $db->query("SELECT city, COUNT(*) as visits FROM visits GROUP BY city")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка при подключении к базе данных: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика посещений</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Статистика посещений</h1>

<div style="width: 250px; height: 250px;">
    <canvas id="hourlyChart" width="250" height="250"></canvas>
</div>

<div style="width: 250px; height: 250px;">
    <canvas id="cityChart" width="250" height="250"></canvas>
</div>

<canvas id="hourlyChart"></canvas>
<canvas id="cityChart"></canvas>


<script>
    // Данные для графика посещений по часам
    var hourlyLabels = <?= json_encode(array_column($hourlyData, 'hour')) ?>;
    var hourlyData = <?= json_encode(array_column($hourlyData, 'visits')) ?>;

    var hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(hourlyCtx, {
        type: 'line',
        data: {
            labels: hourlyLabels,
            datasets: [{
                label: 'Посещения по часам',
                data: hourlyData,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Данные для круговой диаграммы по городам
    var cityLabels = <?= json_encode(array_column($cityData, 'city')) ?>;
    var cityData = <?= json_encode(array_column($cityData, 'visits')) ?>;

    var cityCtx = document.getElementById('cityChart').getContext('2d');
    new Chart(cityCtx, {
        type: 'pie',
        data: {
            labels: cityLabels,
            datasets: [{
                data: cityData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
</script>
</body>
</html>
