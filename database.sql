CREATE DATABASE IF NOT EXISTS vitrine_cultural;
USE vitrine_cultural;

CREATE TABLE IF NOT EXISTS artistas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    area VARCHAR(255) NOT NULL,
    imagem VARCHAR(255) NULL,
    link_instagram VARCHAR(255) NULL,
    link_youtube VARCHAR(255) NULL,
    link_portfolio VARCHAR(255) NULL,
    aprovado TINYINT(1) DEFAULT 1,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    data_evento DATE NOT NULL,
    descricao TEXT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO artistas (nome, area, imagem, link_instagram, link_youtube) VALUES
('Leandro', 'Músico', 'images/Leandro.png', 'https://www.instagram.com/leandro.musico', 'https://www.youtube.com/@leandromusico'),
('Alexandre', 'Pintor', 'images/Alexandre.jpg', 'https://www.instagram.com/alexandre.pintor', 'https://www.youtube.com/@alexandrepintor');

INSERT INTO eventos (titulo, data_evento, descricao) VALUES
('Cuiabá por Dentro', '2026-03-15', 'Exposição de ilustrações retratando Cuiabá'),
('Apresentação de cavaquinho individual', '2026-03-23', 'Praça da quadra do Jardim Florianópolis');
