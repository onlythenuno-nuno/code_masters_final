<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

if (isset($_GET['mensagem'])) {
    $mensagem = "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0;'>
                    {$_GET['mensagem']}
                </div>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo CodeMasters</title>
    <style>
        :root {
            --primary: #170448;       /* Roxo escuro (sidebar) */
            --secondary: #8b5cf6;     /* Roxo claro (destaques/botões) */
            --accent: #f43f5e;        /* Coral/rosa (para hover ou alerta) */
            --light: #f3e8ff;         /* Lilás bem claro (usado no fundo dos cards) */
            --dark: #2e1065;          /* Roxo mais escuro (para sombreados profundos) */
            --text: #1e1b4b;          /* Roxo escuro mais neutro (para texto normal) */
            --text-light: #c4b5fd;    /* Lilás suave (para texto secundário) */
            --white: #fff;
            --roxo_menu: #1f0660;
            --esverdeado: #39ff14;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: var(--text);
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--dark) 100%);
            color: white;
            padding: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            font-size: 1.1rem;
        }
        
        .logo-section {
            padding: 25px;
            background-color: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .logo {
            padding: 0px 10px;
            width: 230px;
        }
        
        .logo span {
            color: var(--secondary);
        }
        
        .user-profile {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-profile h2 {
            margin: 15px 0 5px;
            font-size: 1.4em;
            color: white;
        }
        
        .user-email {
            font-size: 0.85em;
            color: var(--secondary);
        }
        
        .menu-section {
            padding: 15px 0;
        }
        
        .menu-section h3 {
            padding: 12px 25px;
            margin: 0;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--secondary);
            background-color: rgba(0,0,0,0.2);
        }
        
        .menu-item {
            padding: 14px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background-color: rgba(255,255,255,0.05);
            border-left: 3px solid var(--secondary);
            padding-left: 30px;
            color: var(--secondary);
        }
        
        .menu-item.active {
            background-color: rgba(52, 152, 219, 0.1);
            border-left: 3px solid var(--secondary);
            color: var(--secondary);
        }
        
        .logout-section {
            padding: 20px;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .logout-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: var(--light);
            transition: color 0.3s;
        }
        
        .logout-item:hover {
            color: var(--accent);
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: var(--roxo_menu);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-title {
            font-size: 2em;
            color: var(--white);
            margin: 0;
            font-weight: 600;
        }
        
        .admin-actions {
            display: flex;
            gap: 15px;
        }
        
        .action-btn {
            padding: 10px 20px;
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .action-btn.secondary {
            background-color: var(--light);
            color: var(--text);
        }
        
        .action-btn.secondary:hover {
            background-color: #bdc3c7;
        }
        
        .form-section {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .form-title {
            margin-top: 0;
            color: var(--primary);
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            font-size: 1.5em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary);
        }
        
        .form-control {
            width: 95%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .select-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            font-size: 1em;
        }
        
        .submit-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #2980b9;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .course-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
        }
        
        .course-title {
            font-size: 1.2em;
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            color: var(--text-light);
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        
        .course-actions {
            display: flex;
            gap: 10px;
        }
        
        .course-btn {
            flex: 1;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            font-size: 0.9em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .edit-btn {
            background-color: var(--light);
            color: var(--text);
        }
        
        .edit-btn:hover {
            background-color: #bdc3c7;
        }
        
        .delete-btn {
            background-color: #f1c0c0;
            color: #c0392b;
        }
        
        .delete-btn:hover {
            background-color: #e6b0b0;
        }
        
        /* Estilos para a tabela de alunos */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .data-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }
        
        .data-table tr:hover {
            background-color: #f9f5ff;
        }
        
        /* Mensagem de sucesso */
        .alert-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            
            <div class="user-profile">
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?>!</h2>
            </div>
            
            <div class="menu-section">
                <h3>Administração</h3>
                <div class="menu-item active">Dashboard</div>
                <a href="administradores.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Administradores</div></a>
                <a href="alunos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Alunos</div></a>
                <a href="cursos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Cursos</div></a>
                <a href="certificado.php" style="text-decoration: none; color: inherit;"><div class="menu-item">certificados</div></a>
                <a href="eventos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Eventos</div></a>
                <a href="suporte.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Suporte</div></a>
            </div>
            
            <div class="logout-section">
                <a href="logout.php" style="text-decoration: none; color: inherit;">
                    <div class="logout-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Terminar Sessão
                    </div>
                </a>
            </div>
        </div>
        
        <div class="main-content">
            <?php if(isset($mensagem)) echo $mensagem; ?>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Adicionar Novo Curso</h1>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Informações do Curso</h2>
                
                <form method="POST" action="salvar_curso.php" id="form-curso">
                    <div class="form-group">
                        <label for="course-title">Título</label>
                        <input type="text" id="course-title" name="titulo" class="form-control" placeholder="Digite o título do curso" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="course-description">Descrição</label>
                        <textarea id="course-description" name="descricao" class="form-control" rows="4" placeholder="Descreva o conteúdo do curso" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Salvar Curso</button>
                </form>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Adicionar Nova Aula</h2>
                
                <form method="POST" action="salvar_aula.php">
                    <div class="form-group">
                        <label for="course-select">Selecione o Curso</label>
                        <select id="course-select" name="id_curso" class="select-control" required>
                            <option value="">-- Selecione --</option>
                            <?php
                            $cursos = mysqli_query($conn, "SELECT id_curso, titulo FROM curso");
                            while ($curso = mysqli_fetch_assoc($cursos)) {
                                echo "<option value='{$curso['id_curso']}'>" . htmlspecialchars($curso['titulo']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="lesson-title">Título da Aula</label>
                        <input type="text" id="lesson-title" name="titulo" class="form-control" placeholder="Digite o título da aula" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="video-link">Link do Vídeo</label>
                        <input type="text" id="video-link" name="link_video" class="form-control" placeholder="Cole o link do vídeo (YouTube, Vimeo, etc.)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pdf-link">Link do PDF (opcional)</label>
                        <input type="text" id="pdf-link" name="link_pdf" class="form-control" placeholder="Cole o link do PDF">
                    </div>
                    
                    <div class="form-group">
                        <label for="lesson-order">Ordem da Aula</label>
                        <input type="number" id="lesson-order" name="ordem" class="form-control" placeholder="Número da ordem da aula" min="1">
                    </div>
                    
                    <button type="submit" class="submit-btn">Adicionar Aula</button>
                </form>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Cursos Existentes</h2>
                
                <div class="courses-grid">
                    <?php
                    $cursos = mysqli_query($conn, "SELECT c.id_curso, c.titulo, c.descricao, 
                                                 COUNT(DISTINCT a.id_aula) as num_aulas, 
                                                 COUNT(DISTINCT i.id_inscricao) as num_inscritos
                                          FROM curso c
                                          LEFT JOIN aula a ON c.id_curso = a.id_curso
                                          LEFT JOIN inscricao i ON c.id_curso = i.id_curso
                                          GROUP BY c.id_curso");
                    
                    if (mysqli_num_rows($cursos) > 0) {
                        while ($curso = mysqli_fetch_assoc($cursos)) {
                            echo '<div class="course-card">';
                            echo '<h3 class="course-title">' . htmlspecialchars($curso['titulo']) . '</h3>';
                            echo '<div class="course-meta">';
                            echo '<span>' . $curso['num_aulas'] . ' Aulas</span>';
                            echo '<span>' . $curso['num_inscritos'] . ' Inscritos</span>';
                            echo '</div>';
                            echo '<div class="course-actions">';
                            echo '<a href="editar_curso.php?id=' . $curso['id_curso'] . '" class="course-btn edit-btn">Editar</a>';
                            echo '<a href="excluir_curso.php?id=' . $curso['id_curso'] . '" class="course-btn delete-btn">Remover</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Nenhum curso cadastrado.</p>';
                    }
                    ?>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>