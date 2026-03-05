<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($usuario === 'admin' && $senha === 'Admin@123#') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Vitrine Cultural</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #2e1d28;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #1b1b1b;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #e6c8a6;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #a78e63;
            border-radius: 5px;
            background: #333;
            color: white;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #a78e63;
            color: #1b1b1b;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #cfa77d;
        }

        .erro {
            color: #ff7b7b;
            margin-bottom: 10px;
        }

        a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-top: 20px;
            font-size: 0.9em;
        }

        a:hover {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Login Admin</h2>
        <?php if ($erro): ?>
            <p class="erro">
                <?= $erro ?>
            </p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <a href="../index.php">← Voltar ao site</a>
    </div>

</body>

</html>