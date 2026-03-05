<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once '../config.php';

$mensagem = '';
$uploadDir = 'upload/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] == 'adicionar') {
        $nome = trim($_POST['nome']);
        $area = trim($_POST['area']);
        $link_instagram = trim($_POST['link_instagram']);
        $link_youtube = trim($_POST['link_youtube']);
        $link_portfolio = trim($_POST['link_portfolio']);
        
        $caminho_imagem = null;

    
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($extensao, $permitidos)) {
                $novoNome = uniqid() . '.' . $extensao;
                $destino = $uploadDir . $novoNome;
                
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                
                
                    $caminho_imagem = 'admin/' . $destino; 
                } else {
                    $mensagem = "Erro ao fazer upload da imagem.";
                }
            } else {
                $mensagem = "Formato de imagem não permitido.";
            }
        }

        if (empty($mensagem)) {
            $stmt = $pdo->prepare("INSERT INTO artistas (nome, area, imagem, link_instagram, link_youtube, link_portfolio) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nome, $area, $caminho_imagem, $link_instagram, $link_youtube, $link_portfolio])) {
                $mensagem = "Artista adicionado com sucesso!";
            } else {
                $mensagem = "Erro ao adicionar artista no banco.";
            }
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $id = $_POST['id'];
        
    
        $stmt_busca = $pdo->prepare("SELECT imagem FROM artistas WHERE id = ?");
        $stmt_busca->execute([$id]);
        $artista = $stmt_busca->fetch();
        
        if ($artista && !empty($artista['imagem'])) {
        
            $arquivo_local = str_replace('admin/', '', $artista['imagem']);
            if (file_exists($arquivo_local)) {
                unlink($arquivo_local);
            }
        }

    
        $stmt = $pdo->prepare("DELETE FROM artistas WHERE id = ?");
        if ($stmt->execute([$id])) {
            $mensagem = "Artista excluído com sucesso!";
        }
    }
}

$artistas = $pdo->query("SELECT * FROM artistas ORDER BY data_cadastro DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Artistas - Admin</title>
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
        form.adicionar-form input, form.adicionar-form select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        form.adicionar-form button { padding: 10px 20px; background: #2e1d28; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;}
        form.adicionar-form button:hover { background: #423044; }
        
        .btn-excluir { background: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
        .btn-excluir:hover { background: #c9302c; }
        .msg { padding: 10px; background: #dff0d8; color: #3c763d; margin-bottom: 15px; border-radius: 4px; }
        .img-preview { max-width: 60px; max-height: 60px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="text-align: center; color: #e6c8a6; border-bottom: 1px solid #423044; padding-bottom: 15px;">Admin Vitrine</h3>
        <a href="index.php">Dashboard</a>
        <a href="gerenciar_artistas.php" style="background:#423044; border-left: 5px solid #e6c8a6; padding-left: 10px;">Gerenciar Artistas</a>
        <a href="gerenciar_eventos.php">Gerenciar Eventos</a>
        <a href="../index.php" target="_blank">Acessar Site Público ↗</a>
        <a href="logout.php" style="margin-top: 50px; color: #ff7b7b;">Sair do Sistema</a>
    </div>
    <div class="content">
        <h2>Gerenciar Artistas</h2>
        
        <?php if($mensagem): ?>
            <div class="msg"><?= $mensagem ?></div>
        <?php endif; ?>

        <h3>Adicionar Novo Artista</h3>
        <!-- enctype="multipart/form-data" é obrigatório para envio de arquivos -->
        <form class="adicionar-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="adicionar">
            
            <label>Nome Artístico *</label>
            <input type="text" name="nome" required>
            
            <label>Área Artística (ex: Música, Pintura) *</label>
            <input type="text" name="area" required>
            
            <label>Foto do Artista</label>
            <input type="file" name="imagem" accept="image/*">
            
            <label>Link Instagram (opcional)</label>
            <input type="text" name="link_instagram" placeholder="Ex: instagram.com/seu_perfil ou @seu_perfil">
            
            <label>Link YouTube (opcional)</label>
            <input type="text" name="link_youtube" placeholder="Ex: youtube.com/seu_canal">
            
            <label>Link Portfólio (opcional)</label>
            <input type="text" name="link_portfolio" placeholder="Ex: seu-site.com">
            
            <button type="submit">Salvar Artista</button>
        </form>

        <h3>Artistas Cadastrados</h3>
        <table>
            <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Área</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($artistas as $artista): ?>
            <tr>
                <td>
                    <?php if(!empty($artista['imagem'])): ?>
                        <img src="../<?= htmlspecialchars($artista['imagem']) ?>" class="img-preview" alt="Foto">
                    <?php else: ?>
                        <span style="color:#999;font-size:12px;">Sem foto</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($artista['nome']) ?></td>
                <td><?= htmlspecialchars($artista['area']) ?></td>
                <td>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o artista <?= htmlspecialchars($artista['nome']) ?>?');">
                        <input type="hidden" name="acao" value="excluir">
                        <input type="hidden" name="id" value="<?= $artista['id'] ?>">
                        <button type="submit" class="btn-excluir">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($artistas) == 0): ?>
                <tr><td colspan="4" style="text-align: center;">Nenhum artista cadastrado.</td></tr>
            <?php endif;?>
        </table>
    </div>
</body>
</html>
