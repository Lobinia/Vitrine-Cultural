<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once '../config.php';

$totalArtistas = $pdo->query("SELECT COUNT(*) FROM artistas")->fetchColumn();
$totalEventos = $pdo->query("SELECT COUNT(*) FROM eventos")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Painel Admin - Vitrine Cultural</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background: #2e1d28;
            color: #fff;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 15px;
            text-decoration: none;
            border-bottom: 1px solid #423044;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #423044;
            border-left: 5px solid #e6c8a6;
            padding-left: 10px;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: inline-block;
            width: 300px;
            margin-right: 20px;
            vertical-align: top;
        }

        h2 {
            color: #333;
        }

        h3 {
            margin-top: 0;
            color: #555;
        }

        .stat {
            font-size: 36px;
            font-weight: bold;
            color: #a78e63;
            margin: 10px 0 0 0;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3 style="text-align: center; color: #e6c8a6; border-bottom: 1px solid #423044; padding-bottom: 15px;">Admin
            Vitrine</h3>
        <a href="index.php"
            style="background:#423044; border-left: 5px solid #e6c8a6; padding-left: 10px;">Dashboard</a>
        <a href="gerenciar_artistas.php">Gerenciar Artistas</a>
        <a href="gerenciar_eventos.php">Gerenciar Eventos</a>
        <a href="../index.php" target="_blank">Acessar Site Público ↗</a>
        <a href="logout.php" style="margin-top: 50px; color: #ff7b7b;">Sair do Sistema</a>
    </div>
    <div class="content">
        <h2>Dashboard Geral</h2>
        <div class="card">
            <h3>Total de Artistas 👤</h3>
            <p class="stat">
                <?= $totalArtistas ?>
            </p>
        </div>
        <div class="card">
            <h3>Eventos Agendados 📅</h3>
            <p class="stat">
                <?= $totalEventos ?>
            </p>
        </div>

        <p style="margin-top: 40px; color: #666;">Bem-vindo ao painel de administração da Vitrine Cultural. Use o menu
            lateral para gerenciar as informações exibidas no site.</p>
    </div>
</body>

</html>