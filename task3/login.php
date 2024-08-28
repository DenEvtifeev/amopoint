<?php
session_start();
$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $config['auth']['username'] && $password === $config['auth']['password']) {
        $_SESSION['logged_in'] = true;
        header('Location: stats.php');
        exit;
    } else {
        echo 'Неправильные учетные данные';
    }
}
?>

<form method="post">
    <input type="text" name="username" placeholder="Имя пользователя">
    <input type="password" name="password" placeholder="Пароль">
    <button type="submit">Войти</button>
</form>
