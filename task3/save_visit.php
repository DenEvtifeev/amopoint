<?php
// Подключение конфигурационного файла
$config = require 'config.php';

// Декодирование полученных данных
$data = json_decode(file_get_contents('php://input'), true);

$ip = $data['ip'];
$city = $data['city'];
$device = $data['device'];

// Подключение к БД
try {
    $dsn = "{$config['db_type']}:host={$config['db_host']};dbname={$config['db_name']};charset=utf8";
    $db = new PDO($dsn, $config['db_user'], $config['db_pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка, существует ли запись с этим IP в текущем часу
    $query = $db->prepare("SELECT COUNT(*) FROM visits WHERE ip_address = :ip AND DATE_FORMAT(visit_time, '%Y-%m-%d %H') = DATE_FORMAT(NOW(), '%Y-%m-%d %H')");
    $query->execute([':ip' => $ip]);

    if ($query->fetchColumn() == 0) {
        $insert = $db->prepare("INSERT INTO visits (ip_address, city, device) VALUES (:ip, :city, :device)");
        $insert->execute([':ip' => $ip, ':city' => $city, ':device' => $device]);
    }
} catch (PDOException $e) {
    echo "Ошибка при подключении к базе данных: " . $e->getMessage();
}
?>
