<?php
require_once 'config.php';

$stmtArtistas = $pdo->query("SELECT * FROM artistas ORDER BY data_cadastro DESC LIMIT 10");
$artistas = $stmtArtistas->fetchAll();

$stmtEventos = $pdo->query("SELECT * FROM eventos ORDER BY data_evento ASC");
$eventos = $stmtEventos->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitrine Cultural – Jardim Florianópolis</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <header>
        <h1 class="header-title"><img src="./images/cavaquinho.png" width="80px" />Vitrine Cultural</h1>
        <nav>
            <a href="#artistas">Artistas</a>
            <a href="#eventos">Eventos</a>
            <a href="#contato">Contato</a>
            <a href="#participe">Participe</a>
            <a href="admin/login.php" style="background: #444; color: #fff;">Admin</a>
        </nav>
    </header>

    <div class="container">
        <section id="sobre">
            <h2>Sobre o Projeto</h2>
            <p>Esta plataforma foi criada para apoiar artistas independentes da Igreja Evangélica Assembleia de Deus -
                Jardim Florianópolis 2, promovendo sua arte e conectando a comunidade local.</p>
        </section>

        <section id="artistas">
            <h2>Galeria de Artistas</h2>
            <div class="cards-container">
                <?php if (count($artistas) > 0): ?>
                    <?php foreach ($artistas as $artista): ?>
                        <div class="card">
                            <?php if (!empty($artista['imagem'])): ?>
                                <img src="<?= htmlspecialchars($artista['imagem']) ?>"
                                    alt="Foto de <?= htmlspecialchars($artista['nome']) ?>" width="300">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x300?text=Sem+Foto" alt="Sem Foto">
                            <?php endif; ?>

                            <h2>
                                <?= htmlspecialchars($artista['nome']) ?>
                            </h2>
                            <p class="highlight">
                                <?= htmlspecialchars($artista['area']) ?>
                            </p>

                            <div class="social-button-container">
                                <?php if (!empty($artista['link_instagram']) && $artista['link_instagram'] != '#'): ?>
                                    <a href="<?= htmlspecialchars($artista['link_instagram']) ?>" class="social-button instagram"
                                        target="_blank">
                                        <span class="icon"><i class="fab fa-instagram"></i></span>
                                        Instagram
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($artista['link_youtube']) && $artista['link_youtube'] != '#'): ?>
                                    <a href="<?= htmlspecialchars($artista['link_youtube']) ?>" class="social-button youtube"
                                        target="_blank">
                                        <span class="icon"><i class="fab fa-youtube"></i></span>
                                        YouTube
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($artista['link_portfolio']) && $artista['link_portfolio'] != '#'): ?>
                                    <a href="<?= htmlspecialchars($artista['link_portfolio']) ?>" class="social-button"
                                        target="_blank" style="background:#555; color:#fff;">
                                        Portfólio
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum artista cadastrado ainda.</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="eventos" class="eventos">
            <h2>Agenda Cultural</h2>
            <ul id="lista-eventos">
                <?php if (count($eventos) > 0): ?>
                    <?php foreach ($eventos as $evento): ?>
                        <li>
                            <strong>
                                <?= htmlspecialchars($evento['titulo']) ?>
                            </strong> –
                            <span class="data-evento">
                                <?= date('d/m/Y', strtotime($evento['data_evento'])) ?>
                            </span> –
                            <?= htmlspecialchars($evento['descricao']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Nenhum evento agendado no momento.</li>
                <?php endif; ?>
            </ul>
        </section>

        <section id="participe" class="eventos">
            <h2>Cadastre-se como Artista</h2>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'sucesso'): ?>
                    <div
                        style="background: rgba(75, 181, 67, 0.2); border: 1px solid #4bb543; color: #4bb543; padding: 12px; border-radius: 5px; margin-bottom: 20px; width: 100%; max-width: 500px; text-align: center; font-family: 'Segoe UI', sans-serif;">
                        Cadastro recebido com sucesso! Você já está na galeria.
                    </div>
                <?php else: ?>
                    <div
                        style="background: rgba(255, 76, 76, 0.2); border: 1px solid #ff4c4c; color: #ffbcbc; padding: 12px; border-radius: 5px; margin-bottom: 20px; width: 100%; max-width: 500px; text-align: center; font-family: 'Segoe UI', sans-serif;">
                        Ocorreu um erro ao processar o formulário. Verifique os campos e tente novamente.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form id="form-artista" action="processar_cadastro.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="nome" placeholder="Seu nome artístico" required>
                <input type="text" name="area" placeholder="Área artística (ex: música, pintura)" required>
                <label style="color: #ccc; align-self: flex-start; margin-bottom: -5px; font-size: 0.9em;">Foto
                    (opcional):</label>
                <input type="file" name="imagem" accept="image/*" style="background: #333; color: white;">
                <input type="text" name="link_portfolio" placeholder="Link para portfólio, Instagram, YouTube...">
                <button type="submit">Enviar</button>
            </form>
        </section>




    </div>

    <footer>
        <div id="contato">
            <h2>Contato</h2>
            <p>Dúvidas, sugestões ou apoio cultural? Fale com a gente:</p>
            <p>Email: <a href="#" class="email">vitrinecultural@gmail.com</a></p>
        </div>
        <p>Projeto de Extensão II – Análise e Desenvolvimento de Sistemas</p>
        <p>Desenvolvido por Maya - Aluna de ADS - Unic Pantanal</p>
    </footer>
    <script src="script.js"></script>
</body>

</html>