<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once '../config.php';

$mensagem = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] == 'adicionar') {
        $titulo = $_POST['titulo'];
        $data_evento = $_POST['data_evento'];
        $descricao = $_POST['descricao'];
        
        $stmt = $pdo->prepare("INSERT INTO eventos (titulo, data_evento, descricao) VALUES (?, ?, ?)");
        if ($stmt->execute([$titulo, $data_evento, $descricao])) {
            $mensagem = "Evento adicionado com sucesso!";
        } else {
            $mensagem = "Erro ao adicionar evento.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
        if ($stmt->execute([$id])) {
            $mensagem = "Evento excluído com sucesso!";
        }
    }
}

$eventos = $pdo->query("SELECT * FROM eventos ORDER BY data_evento DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Eventos - Admin</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background: #f4f4f4; }
        .sidebar { width: 250px; background: #2e1d28; color: #fff; position: fixed; height: 100vh; padding-top: 20px; }
        .sidebar a { display: block; color: #fff; padding: 15px; text-decoration: none; border-bottom: 1px solid #423044; transition: 0.3s; }
        .sidebar a:hover { background: #423044; border-left: 5px solid #e6c8a6; padding-left: 10px; }
        .content { margin-left: 250px; padding: 30px; }
        
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; margin-bottom: 30px;}
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #333; color: white; }
        tr:hover { background-color: #f1f1f1; }
        
        form.adicionar-form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; max-width: 600px; }
        form.adicionar-form input, form.adicionar-form textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit;}
        form.adicionar-form button { padding: 10px 20px; background: #2e1d28; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;}
        form.adicionar-form button:hover { background: #423044; }
        
        .btn-excluir { background: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
        .btn-excluir:hover { background: #c9302c; }
        .msg { padding: 10px; background: #dff0d8; color: #3c763d; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="text-align: center; color: #e6c8a6; border-bottom: 1px solid #423044; padding-bottom: 15px;">Admin Vitrine</h3>
        <a href="index.php">Dashboard</a>
        <a href="gerenciar_artistas.php">Gerenciar Artistas</a>
        <a href="gerenciar_eventos.php" style="background:#423044; border-left: 5px solid #e6c8a6; padding-left: 10px;">Gerenciar Eventos</a>
        <a href="../index.php" target="_blank">Acessar Site Público ↗</a>
        <a href="logout.php" style="margin-top: 50px; color: #ff7b7b;">Sair do Sistema</a>
    </div>
    <div class="content">
        <h2>Gerenciar Eventos</h2>
        
        <?php if($mensagem): ?>
            <div class="msg"><?= $mensagem ?></div>
        <?php endif; ?>

        <h3>Adicionar Novo Evento</h3>
        <form class="adicionar-form" method="POST">
            <input type="hidden" name="acao" value="adicionar">
            <input type="text" name="titulo" placeholder="Título do Evento (ex: Apresentação de Coral)" required>
            <input type="date" name="data_evento" required>
            <textarea name="descricao" rows="4" placeholder="Descrição do Evento" required></textarea>
            <button type="submit">Salvar Evento</button>
        </form>

        <h3>Eventos Cadastrados</h3>
        <table>
            <tr>
                <th>Data</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($eventos as $evento): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($evento['data_evento'])) ?></td>
                <td><?= htmlspecialchars($evento['titulo']) ?></td>
                <td><?= htmlspecialchars($evento['descricao']) ?></td>
                <td>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                        <input type="hidden" name="acao" value="excluir">
                        <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                        <button type="submit" class="btn-excluir">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($eventos) == 0): ?>
                <tr><td colspan="4" style="text-align: center;">Nenhum evento cadastrado.</td></tr>
            <?php endif;?>
        </table>
    </div>
</body>
</html>
