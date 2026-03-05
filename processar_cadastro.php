<?php
require_once 'config.php';

$uploadDir = 'admin/upload/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $area = trim($_POST['area'] ?? '');
    $link = trim($_POST['link_portfolio'] ?? '');

    $caminho_imagem = null;

    if (!empty($nome) && !empty($area)) {

    
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($extensao, $permitidos)) {
                $novoNome = uniqid() . '.' . $extensao;
                $destino = $uploadDir . $novoNome;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                    $caminho_imagem = 'admin/upload/' . $novoNome;
                }
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO artistas (nome, area, imagem, link_portfolio) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nome, $area, $caminho_imagem, $link])) {
                header("Location: index.php?status=sucesso#participe");
                exit;
            } else {
                header("Location: index.php?status=erro#participe");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: index.php?status=erro#participe");
            exit;
        }
    } else {
        header("Location: index.php?status=erro#participe");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>