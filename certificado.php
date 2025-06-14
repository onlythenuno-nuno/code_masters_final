<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'gerenciamento/conexao.php';

$aluno_id = $_SESSION['id'];

// Buscar cursos concluídos (verificação de aulas assistidas)
$query = "SELECT c.id_curso, c.titulo 
          FROM curso c
          WHERE NOT EXISTS (
              SELECT a.id_aula 
              FROM aula a 
              WHERE a.id_curso = c.id_curso
              AND NOT EXISTS (
                  SELECT 1 FROM aulas_assistidas aa 
                  WHERE aa.id_aula = a.id_aula 
                  AND aa.id_aluno = $aluno_id
                  AND aa.completo = 1
              )
          )
          AND NOT EXISTS (
              SELECT 1 FROM certificados cert 
              WHERE cert.id_aluno = $aluno_id 
              AND cert.id_curso = c.id_curso
          )
          AND EXISTS (
              SELECT 1 FROM inscricao i
              WHERE i.id_aluno = $aluno_id
              AND i.id_curso = c.id_curso
          )";
$cursos_concluidos = mysqli_query($conn, $query);

// Processar geração do certificado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_curso'])) {
    $id_curso = $_POST['id_curso'];
    
    // Verificar novamente se o curso foi concluído
    $verifica_conclusao = mysqli_query($conn, "SELECT 1 FROM aula a 
        WHERE a.id_curso = $id_curso
        AND NOT EXISTS (
            SELECT 1 FROM aulas_assistidas aa 
            WHERE aa.id_aula = a.id_aula 
            AND aa.id_aluno = $aluno_id
            AND aa.completo = 1
        )");
    
    if (mysqli_num_rows($verifica_conclusao) == 0) {
        // Gerar código único
        $codigo_certificado = 'CERT-' . strtoupper(uniqid());
        
        // Inserir no banco de dados
        $insert = "INSERT INTO certificados (id_aluno, id_curso, codigo_certificado) 
                   VALUES ($aluno_id, $id_curso, '$codigo_certificado')";
        if (mysqli_query($conn, $insert)) {
            $sucesso = "Código do certificado gerado com sucesso!";
            $codigo_gerado = $codigo_certificado;
        } else {
            $erro = "Erro ao gerar certificado. Tente novamente.";
        }
    } else {
        $erro = "Curso não concluído. Complete todas as aulas primeiro.";
    }
}

// Buscar histórico de certificados gerados
$historico_query = "SELECT c.codigo_certificado, cr.titulo as curso_titulo, c.data_geracao 
                    FROM certificados c
                    JOIN curso cr ON c.id_curso = cr.id_curso
                    WHERE c.id_aluno = $aluno_id
                    ORDER BY c.data_geracao DESC";
$historico_certificados = mysqli_query($conn, $historico_query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificados | Plataforma de Cursos</title>
    <style>
        :root {
            --primary: #170448;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light:rgb(162, 150, 212);
            --esverdeado: #2fc10e;
            --background: #f5f7fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary);
            color: var(--text);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            color: #fff;
            font-size: 2em;
            margin: 0;
            font-weight: 600;
        }
        
        .back-btn {
            padding: 10px 20px;
            background-color: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn:hover {
            background-color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
            width: 100%;
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
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23170048' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }
        
        .submit-btn {
            background-color: var(--esverdeado);
            color: white;
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
            background-color: #26980c;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 1em;
            border-left: 4px solid transparent;
        }
        
        .alert-success {
            background-color: #edf7ed;
            color: #1e4620;
            border-left-color: var(--esverdeado);
        }
        
        .alert-error {
            background-color: #fde8e8;
            color: #611a15;
            border-left-color: var(--accent);
        }
        
        .code-display {
            background-color: #f8f9fa;
            border: 2px dashed #ddd;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 1.3em;
            text-align: center;
            margin: 25px 0;
            word-break: break-all;
            color: var(--primary);
            font-weight: 600;
        }
        
        .warning-box {
            background-color: #fff8e6;
            color: #5c3b00;
            padding: 18px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-top: 30px;
            font-size: 1em;
        }
        
        .warning-box strong {
            color: #5c3b00;
        }
        
        .empty-state {
            color: var(--text-light);
            font-style: italic;
            text-align: center;
            padding: 30px;
            font-size: 1.1em;
        }
        
        .course-list {
            display: grid;
            gap: 15px;
        }
        
        .course-option {
            padding: 16px;
            border: 1px solid #eee;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .course-option:hover {
            border-color: var(--secondary);
            background-color: #faf5ff;
        }
        
        .course-option input[type="radio"] {
            display: none;
        }
        
        .course-option input[type="radio"]:checked + label {
            color: var(--secondary);
            font-weight: 500;
        }
        
        .course-option label {
            cursor: pointer;
            display: block;
            font-size: 1.1em;
        }
    
        
        .history-section {
            margin-top: 40px;
        }
        
        .history-title {
            color: var(--primary);
            font-size: 1.3em;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .history-list {
            display: grid;
            gap: 12px;
        }
        
        .history-item {
            background-color: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border-left: 3px solid var(--secondary);
        }
        
        .history-course {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .history-code {
            font-family: 'Courier New', monospace;
            font-size: 0.95em;
            color: var(--text);
            margin-bottom: 5px;
            word-break: break-all;
        }
        
        .history-date {
            font-size: 0.85em;
            color: var(--text-light);
        }
        
        .copy-btn {
            background-color: var(--light);
            color: var(--secondary);
            border: 1px solid var(--secondary);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            cursor: pointer;
            margin-left: 8px;
            transition: all 0.2s;
        }
        
        .copy-btn:hover {
            background-color: var(--secondary);
            color: white;
        }
        
        .no-history {
            color: var(--text-light);
            font-style: italic;
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>


    <div class="container">
        <div class="header">
            <h1 class="page-title">Meus Certificados</h1>
            <a href="index.php" class="back-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>

        <div class="section">
            <div class="warning-box">
                <strong>⚠️ Aviso importante:</strong> O levantamento do certificado físico tem um custo de <span style="color: green;">10.000 Kwanzas</span> 
                 e para obter o certificado, o aluno deverá realizar um teste avaliativo.<br> No dia do teste, é obrigatório levar: o <span style="color: green;">Bilhete de Identidade</span>, <span style="color: green;">duas fotos tipo passe</span> e uma <span style="color: green;">cópia do BI</span> <br>
                <em style="color: red;">Obs:</em> Os testes são baseados nos tópicos apresentados durante o curso.
                
            </div>
        </div>
        
        <div class="section">
            <h2 class="section-title">Gerar Código de Certificado</h2>
            
            <?php if (isset($sucesso)): ?>
                <div class="alert alert-success">
                    <strong>✓ Sucesso!</strong> <?= $sucesso ?>
                </div>
                
                <div class="code-display">
                    <?= $codigo_gerado ?>
                </div>
                
                <p style="text-align: center; color: var(--text);">Guarde este código para emissão do seu certificado físico.</p>
            <?php elseif (isset($erro)): ?>
                <div class="alert alert-error">
                    <strong>✗ Erro:</strong> <?= $erro ?>
                </div>
            <?php endif; ?>
            
            <?php if (mysqli_num_rows($cursos_concluidos) > 0): ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Selecione o curso concluído:</label>
                        <div class="course-list">
                            <?php while($curso = mysqli_fetch_assoc($cursos_concluidos)): ?>
                                <div class="course-option">
                                    <input type="radio" name="id_curso" id="curso_<?= $curso['id_curso'] ?>" value="<?= $curso['id_curso'] ?>" required>
                                    <label for="curso_<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['titulo']) ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 19V5M5 12l7-7 7 7"/>
                        </svg>
                        Gerar Código
                    </button>
                </form>
            <?php else: ?>
                <p class="empty-state">Você não tem cursos concluídos disponíveis para certificação ou você já gerou o código, verifique o histórico abaixo.<br>
                <span style="color: red;">OBS:</span> Um curso é considerado concluído quando você se inscreve e assiste a todas as aulas.</p>
            <?php endif; ?>
            
            <div class="warning-box">
                <strong>⚠️ Aviso importante:</strong> O levantamento do certificado físico tem um custo de <span style="color: green;">10.000 Kwanzas</span> por curso.
            </div>
            
            <!-- Seção de Histórico de Certificados -->
            <div class="history-section">
                <h3 class="history-title">Histórico de Certificados Gerados</h3>
                
                <?php if (mysqli_num_rows($historico_certificados) > 0): ?>
                    <div class="history-list">
                        <?php while($cert = mysqli_fetch_assoc($historico_certificados)): ?>
                            <div class="history-item">
                                <div class="history-course"><?= htmlspecialchars($cert['curso_titulo']) ?></div>
                                <div class="history-code">
                                    <?= htmlspecialchars($cert['codigo_certificado']) ?>
                                    <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($cert['codigo_certificado']) ?>')">Copiar</button>
                                </div>
                                <div class="history-date">Gerado em: <?= date('d/m/Y H:i', strtotime($cert['data_geracao'])) ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-history">Nenhum certificado gerado anteriormente.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Código copiado para a área de transferência!');
            }, function() {
                alert('Erro ao copiar o código. Tente novamente.');
            });
        }
    </script>
</body>
</html>