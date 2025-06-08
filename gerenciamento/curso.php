<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id_aluno = $_SESSION['id'];
$id_curso = $_GET['id_curso'] ?? null;

if (!$id_curso) {
    echo "Curso não encontrado.";
    exit();
}

// Pega informações do curso
$sql_curso = "SELECT titulo FROM curso WHERE id_curso = ?";
$stmt = $conn->prepare($sql_curso);
$stmt->bind_param("i", $id_curso);
$stmt->execute();
$curso = $stmt->get_result()->fetch_assoc();

// Pega todas as aulas do curso
$sql = "SELECT * FROM aula WHERE id_curso = ? ORDER BY ordem";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_curso);
$stmt->execute();
$aulas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Pega IDs das aulas assistidas pelo aluno
$sql_assistidas = "SELECT id_aula FROM aulas_assistidas WHERE id_aluno = ?";
$stmt = $conn->prepare($sql_assistidas);
$stmt->bind_param("i", $id_aluno);
$stmt->execute();
$result_assistidas = $stmt->get_result();

$assistidas_ids = [];
while ($row = $result_assistidas->fetch_assoc()) {
    $assistidas_ids[] = $row['id_aula'];
}

// Cálculo da porcentagem de progresso
$total_aulas = count($aulas);
$total_assistidas = 0;

foreach ($aulas as $aula) {
    if (in_array($aula['id_aula'], $assistidas_ids)) {
        $total_assistidas++;
    }
}

$progresso = ($total_aulas > 0) ? ($total_assistidas / $total_aulas) * 100 : 0;
$progresso = round($progresso, 1);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($curso['titulo']) ?> - CodeMasters</title>
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
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }

        .botoes-baixo {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            display: flex;
            justify-content: center;
        }
        
        .header, .botoes-baixo h1 {
            color: var(--primary);
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .header, .botoes-baixo h2 {
            color: var(--secondary);
            font-size: 1.5rem;
            font-weight: 500;
        }
        
        .progress-section {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .progress-title {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .progress-container {
            width: 100%;
            background-color: #e2e8f0;
            border-radius: 10px;
            margin-bottom: 10px;
            height: 25px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            text-align: center;
            color: var(--white);
            font-weight: bold;
            line-height: 25px;
            font-size: 0.9rem;
            transition: width 0.5s ease;
        }
        
        .progress-stats {
            display: flex;
            justify-content: space-between;
            color: var(--text);
            font-size: 1rem;
            font-weight: bold;
        }
        
        .aulas-section {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .aulas-title {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .aula-list {
            list-style: none;
        }
        
        .aula-item {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            background-color: var(--light);
        }
        
        .aula-item:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .aula-link {
            flex: 1;
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .aula-status {
            margin-left: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .assistida {
            color: var(--success);
        }
        
        .nao-assistida {
            color: var(--warning);
        }
        
        .status-icon {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .navigation-buttons {
            display: flex;
            gap: 15px;
            padding: 30px 0;
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
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary);
            color: var(--white);
        }
        
        
        @media (max-width: 768px) {
            .header, .botoes-baixo h1 {
                font-size: 1.8rem;
            }
            
            .header, .botoes-baixo h2 {
                font-size: 1.2rem;
            }
            
            .progress-section, .aulas-section {
                padding: 20px;
            }

            .navigation-buttons {
                width: 100%;
                justify-content: center;
            }
            
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
            <h2>Continue seu aprendizado</h2>
        </div>
        



        
        <div class="progress-section">
            <h3 class="progress-title">Seu Progresso</h3>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?= $progresso ?>%;">
                    <?= $progresso ?>%
                </div>
            </div>
            <div class="progress-stats">
                <span><?= $total_assistidas ?> de <?= $total_aulas ?> aulas concluídas</span>
                <span><?= $progresso ?>% completo</span>
            </div>
        </div>
        
        <div class="aulas-section">
            <h3 class="aulas-title">Aulas do Curso</h3>
            <ul class="aula-list">
                <?php foreach ($aulas as $aula): ?>
                <li class="aula-item">
                    <a href="assistir.php?id=<?= $aula['id_aula'] ?>" class="aula-link">
                        <?php if (in_array($aula['id_aula'], $assistidas_ids)): ?>
                            <span class="status-icon">✅</span>
                        <?php else: ?>
                            <span class="status-icon">⏳</span>
                        <?php endif; ?>
                        <?= htmlspecialchars($aula['titulo']) ?>
                    </a>
                    <span class="aula-status <?= in_array($aula['id_aula'], $assistidas_ids) ? 'assistida' : 'nao-assistida' ?>">
                        <?= in_array($aula['id_aula'], $assistidas_ids) ? 'Assistida' : 'Não assistida' ?>
                    </span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="botoes-baixo">
        <div class="navigation-buttons">
            <a href="../index.php" class="btn btn-primary">  Página Inicial  </a>
            <a href="cursos.php" class="btn btn-secondary">  Cursos Disponíveis </a>
        </div>
    </div>
</body>
</html>