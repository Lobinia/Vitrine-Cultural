<?php
// config.php
$db_host = 'localhost';
$db_name = 'vitrine_cultural';
$db_user = 'root'; // Ajuste no HPanel
$db_pass = 'jcsdtt';     // Ajuste no HPanel

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>