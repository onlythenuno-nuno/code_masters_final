
CREATE DATABASE IF NOT EXISTS code_masters;
USE code_masters;

CREATE TABLE administrador (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nome_admin VARCHAR(100) NOT NULL,
    email_admin VARCHAR(100) NOT NULL UNIQUE,
    senha_admin VARCHAR(255) NOT NULL
);

CREATE TABLE alunos (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome_aluno VARCHAR(100) NOT NULL,
    email_aluno VARCHAR(100) NOT NULL UNIQUE,
    senha_aluno VARCHAR(255) NOT NULL
);



-- Cadastro inicial do administrador
INSERT INTO administrador (nome_admin, email_admin, senha_admin)
VALUES ('Admin Principal', 'admin@codemaster.com', '$2y$10$XV2fGvSnjVepD6UgJ5O1HOkzpzDJnqE/EsuFzLXEXUYxwbEJ0km/C');
-- Senha: admin123
