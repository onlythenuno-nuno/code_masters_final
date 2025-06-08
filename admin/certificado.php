<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// Processar pesquisa
$resultado = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo'])) {
    $codigo = mysqli_real_escape_string($conn, $_POST['codigo']);
    
    $query = "SELECT c.*, a.nome_aluno, a.email_aluno, cr.titulo as curso_titulo, 
                     cr.descricao as curso_descricao
              FROM certificados c
              JOIN aluno a ON c.id_aluno = a.id_aluno
              JOIN curso cr ON c.id_curso = cr.id_curso
              WHERE c.codigo_certificado = '$codigo'";
    $resultado = mysqli_query($conn, $query);
    $certificado = $resultado ? mysqli_fetch_assoc($resultado) : null;
}

// Estatísticas
$query_stats = "SELECT 
    COUNT(*) as total_certificados,
    COUNT(DISTINCT id_aluno) as alunos_unicos,
    COUNT(DISTINCT id_curso) as cursos_certificados
    FROM certificados";
$stats = mysqli_query($conn, $query_stats);
$estatisticas = mysqli_fetch_assoc($stats);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificados | Painel Admin</title>
    <style>
        :root {
            --primary: #170448;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
            --esverdeado: #2fc10e;
            --background: #f5f7fa;
            --white: #fff;
            --roxo_menu: #1f0660;
            --esverdeado: #39ff14;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--roxo_menu);
            color: var(--text);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            flex-wrap: wrap;
            gap: 20px;
            border-bottom: 2px solid var(--secondary);
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
            background-color: var(--secondary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: #7c3aed;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--roxo_menu);
            color: var(--secondary);
            border: 2px solid var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary);
            color: white;
        }
        
        .page-title {
            color: var(--secondary);
            font-size: 2em;
            margin: 0;
            font-weight: 600;
        }
        
        .section {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .section-title {
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 1.5em;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--primary);
            font-size: 1.1em;
        }
        
        .form-control {
            width: 95%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s;
            background-color: #fafafa;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
            background-color: white;
        }
        
        .submit-btn {
            background-color: var(--roxo_menu);
            color: var(--secondary);
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .submit-btn:hover {
            background-color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .certificado-info {
            margin-top: 30px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            margin-bottom: 20px;
        }
        
        .info-label {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 0.95em;
        }
        
        .info-value {
            padding: 14px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid var(--secondary);
            font-size: 1em;
            color: var(--text);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 4px solid var(--roxo_menu);
        }
        
        .stat-number {
            font-size: 2.2em;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 1em;
            color: var(--text);
            font-weight: 500;
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 1em;
            border-left: 4px solid transparent;
        }
        
        .alert-error {
            background-color: #fde8e8;
            color: #611a15;
            border-left-color: var(--accent);
        }
        
        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .form-search {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="header">
            <h1 class="page-title">Verificação de Certificados</h1>
            <div class="navigation-buttons">
            <a href="painel_admin.php" class="btn btn-secondary">Voltar para o Painel</a>
            </div>
        </div>

        
        <div class="section">
            <h2 class="section-title">Pesquisar Certificado</h2>
            
            <form method="POST" class="form-search">
                <div class="form-group">
                    <label for="codigo">Digite o código do certificado:</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" 
                           placeholder="Ex: CERT-5F3A9B2C4D" required>
                </div>
                
                <button type="submit" class="submit-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Verificar Certificado
                </button>
            </form>
            
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <?php if ($certificado): ?>
                    <div class="certificado-info">
                        <h3 style="color: var(--primary); margin-bottom: 20px;">Detalhes do Certificado</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Código do Certificado:</div>
                                <div class="info-value"><?= htmlspecialchars($certificado['codigo_certificado']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Data de Emissão:</div>
                                <div class="info-value"><?= date('d/m/Y H:i', strtotime($certificado['data_geracao'])) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nome do Aluno:</div>
                                <div class="info-value"><?= htmlspecialchars($certificado['nome_aluno']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email do Aluno:</div>
                                <div class="info-value"><?= htmlspecialchars($certificado['email_aluno']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Curso Concluído:</div>
                                <div class="info-value"><?= htmlspecialchars($certificado['curso_titulo']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Descrição do Curso:</div>
                                <div class="info-value"><?= htmlspecialchars($certificado['curso_descricao']) ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-error">
                        <strong>✗ Certificado não encontrado</strong><br>
                        Verifique se o código foi digitado corretamente.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2 class="section-title">Estatísticas de Certificados</h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $estatisticas['total_certificados'] ?></div>
                    <div class="stat-label">Certificados Emitidos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $estatisticas['alunos_unicos'] ?></div>
                    <div class="stat-label">Alunos Certificados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $estatisticas['cursos_certificados'] ?></div>
                    <div class="stat-label">Cursos com Certificados</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

