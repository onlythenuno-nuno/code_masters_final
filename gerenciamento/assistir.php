<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'conexao.php'; // conexão usando MySQLi

if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

$id_aluno = $_SESSION['id'];
$id_aula = $_GET['id'] ?? 0;

// Verifica se aula existe
$aula_stmt = $conn->prepare("SELECT a.*, c.id_curso FROM aula a JOIN curso c ON a.id_curso = c.id_curso WHERE a.id_aula = ?");
$aula_stmt->bind_param("i", $id_aula);
$aula_stmt->execute();
$aula_result = $aula_stmt->get_result();
$aula = $aula_result->fetch_assoc();

if (!$aula) {
    echo "Aula não encontrada!";
    exit();
}

// Verifica se já foi assistida
$check_stmt = $conn->prepare("SELECT * FROM aulas_assistidas WHERE id_aluno = ? AND id_aula = ?");
$check_stmt->bind_param("ii", $id_aluno, $id_aula);
$check_stmt->execute();
$ja_assistida = $check_stmt->get_result()->num_rows > 0;

// Extrai URL do iframe (caso necessário)
function extrairUrlEmbed($iframe) {
    if (preg_match('/src="([^"]+)"/', $iframe, $matches)) {
        return $matches[1];
    }
    return $iframe;
}

$link_video = strpos($aula['link_video'], '<iframe') !== false
    ? extrairUrlEmbed($aula['link_video'])
    : $aula['link_video'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula <?= htmlspecialchars($aula['titulo']) ?> - CodeMasters</title>
    <style>
        :root {
            --primary: #4c1d95;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --roxo_menu: #1f0660;
            --esverdeado: #39ff14;
            --background: #170448;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header h1 {
            color: var(--secondary);
            font-size: 1.8rem;
        }
        
        .header h2 {
            color: var(--light);
            font-size: 1.2rem;
            font-weight: 500;
        }
        
        .navigation-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            border: none;
            font-size: 1em;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: #7c3aed;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--white);
            color: var(--secondary);
            border: 2px solid var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary);
            color: var(--white);
        }
        
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background-color: var(--dark);
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-group {
            display: flex;
            gap: 15px;
        }
        
        .btn-concluir {
            background-color: var(--success);
            color: var(--white);
            padding: 12px 25px;
        }
        
        .btn-concluir[disabled] {
            background-color: #9ca3af;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .btn-concluir:hover:not([disabled]) {
            background-color: #059669;
        }
        
        .anotacoes-container {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        
        .anotacoes-title {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        #anotacao {
            width: 100%;
            height: 150px;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            resize: vertical;
            font-family: inherit;
            font-size: 1em;
            margin-bottom: 15px;
            transition: border 0.3s;
        }
        
        #anotacao:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            .navigation-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .actions-container {
                flex-direction: column;
            }
            
            .action-group {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><?= htmlspecialchars($aula['titulo']) ?></h1>
                <h2>Aula do curso <?= htmlspecialchars($aula['titulo'] ?? '') ?></h2>
            </div>
            <div class="navigation-buttons">
                <a href="curso.php?id_curso=<?= $aula['id_curso'] ?>" class="btn btn-secondary">Voltar ao módulo</a>
                <a href="cursos.php" class="btn btn-primary">Todos os Cursos</a>
            </div>
        </div>
        
        <div class="video-container">
            <iframe src="<?= htmlspecialchars($link_video) ?>" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
        </div>
        
        <div class="actions-container">
            <?php if (!empty($aula['link_pdf'])): ?>
            <a href="<?= htmlspecialchars($aula['link_pdf']) ?>" target="_blank" class="btn btn-primary">Baixar Material PDF</a>
            <?php endif; ?>
            
            <div class="action-group">
                <button id="btnConcluir" class="btn btn-concluir" onclick="marcarConcluida()" <?= $ja_assistida ? 'disabled' : '' ?>>
                    <?= $ja_assistida ? '✅ Aula Concluída' : 'Marcar como Concluída' ?>
                </button>
            </div>
        </div>
        
        <div class="anotacoes-container">
            <h3 class="anotacoes-title">Anotações da Aula</h3>
            <textarea id="anotacao" placeholder="Escreva suas anotações aqui..."></textarea>
            <button class="btn btn-primary" onclick="salvarAnotacao()">Salvar Anotação</button>
        </div>
    </div>

    <script>
        const aulaId = <?= (int)$id_aula ?>;

        function marcarConcluida() {
            const btn = document.getElementById("btnConcluir");
            const originalText = btn.innerText;
            btn.innerText = "Salvando...";
            btn.disabled = true;

            fetch("marcar_assistida.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_aula=${aulaId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btn.innerText = "✅ Aula Concluída";
                } else {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    alert("Erro ao marcar aula como concluída");
                }
            })
            .catch(() => {
                btn.innerText = originalText;
                btn.disabled = false;
                alert("Erro de conexão");
            });
        }

        function salvarAnotacao() {
            const texto = document.getElementById('anotacao').value;
            localStorage.setItem('anotacao_' + aulaId, texto);
            
            const btn = document.querySelector('.anotacoes-container button');
            const originalText = btn.innerText;
            btn.innerText = "Anotação Salva!";
            
            setTimeout(() => {
                btn.innerText = originalText;
            }, 2000);
        }

        function carregarAnotacao() {
            const texto = localStorage.getItem('anotacao_' + aulaId);
            if (texto) {
                document.getElementById('anotacao').value = texto;
            }
        }

        window.addEventListener('load', carregarAnotacao);
    </script>
</body>
</html>