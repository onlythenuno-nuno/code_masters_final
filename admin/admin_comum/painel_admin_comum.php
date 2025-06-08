<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_nivel'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../conexao.php';

// Buscar estatísticas
$admin_id = $_SESSION['admin_id'];
$total_cursos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM curso WHERE admin_id = $admin_id"))['total'];
$total_alunos = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(DISTINCT i.id_aluno) as total 
     FROM inscricao i
     JOIN curso c ON i.id_curso = c.id_curso
     WHERE c.admin_id= $admin_id"))['total'];
$total_certificados = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total 
     FROM certificados cert
     JOIN curso c ON cert.id_curso = c.id_curso
     WHERE c.admin_id= $admin_id"))['total'];

// Processar adição de curso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_curso'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    
    $stmt = $conn->prepare("INSERT INTO curso (titulo, descricao, admin_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $titulo, $descricao, $admin_id);
    
    if ($stmt->execute()) {
        $mensagem = "Curso adicionado com sucesso!";
    } else {
        $erro = "Erro ao adicionar curso.";
    }
}

// Processar adição de aula
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_aula'])) {
    $id_curso = $_POST['id_curso'];
    $titulo = trim($_POST['titulo_aula']);
    $link_video = trim($_POST['link_video']);
    $link_pdf = trim($_POST['link_pdf'] ?? '');
    $ordem = $_POST['ordem'];
    
    $stmt = $conn->prepare("INSERT INTO aula (id_curso, titulo, link_video, link_pdf, ordem) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $id_curso, $titulo, $link_video, $link_pdf, $ordem);
    
    if ($stmt->execute()) {
        $mensagem = "Aula adicionada com sucesso!";
    } else {
        $erro = "Erro ao adicionar aula.";
    }
}

// Buscar cursos do admin
$cursos = mysqli_query($conn, "SELECT * FROM curso WHERE admin_id = $admin_id ORDER BY titulo");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <style>
        :root {
            --primary: #170448;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
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
            color: white;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.05);
            border-left: 3px solid var(--secondary);
            padding-left: 30px;
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
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .stat-value {
            font-size: 2.5em;
            color: var(--primary);
            margin: 10px 0;
            font-weight: 700;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 1em;
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
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
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
            background-color: var(--dark);
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
            border: 1px solid #ddd;
        }
        
        .edit-btn:hover {
            background-color: #bdc3c7;
        }
        
        .alert-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
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
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?></h2>
                <p class="user-email">Administrador</p>
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
            <?php 
            if (isset($mensagem)) {
                echo "<div class='alert-message'>$mensagem</div>";
            }
            if (isset($erro)) {
                echo "<div class='error-message'>$erro</div>";
            }
            ?>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Painel do Administrador</h1>
            </div>
            
            <!-- Estatísticas -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_cursos; ?></div>
                    <div class="stat-label">Meus Cursos</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_alunos; ?></div>
                    <div class="stat-label">Alunos Inscritos</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_certificados; ?></div>
                    <div class="stat-label">Certificados Emitidos</div>
                </div>
            </div>
            
            <!-- Adicionar Novo Curso -->
            <div class="form-section">
                <h2 class="form-title">Adicionar Novo Curso</h2>
                
                <form method="POST" action="painel_admin_comum.php">
                    <div class="form-group">
                        <label for="titulo">Título do Curso</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Digite o título do curso" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="4" placeholder="Descreva o conteúdo do curso" required></textarea>
                    </div>
                    
                    <input type="hidden" name="adicionar_curso" value="1">
                    <button type="submit" class="submit-btn">Salvar Curso</button>
                </form>
            </div>
            
            <!-- Adicionar Nova Aula -->
            <div class="form-section">
                <h2 class="form-title">Adicionar Nova Aula</h2>
                
                <form method="POST" action="painel_admin_comum.php">
                    <div class="form-group">
                        <label for="id_curso">Selecione o Curso</label>
                        <select id="id_curso" name="id_curso" class="select-control" required>
                            <option value="">-- Selecione --</option>
                            <?php
                            while ($curso = mysqli_fetch_assoc($cursos)) {
                                echo "<option value='{$curso['id_curso']}'>" . htmlspecialchars($curso['titulo']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="titulo_aula">Título da Aula</label>
                        <input type="text" id="titulo_aula" name="titulo_aula" class="form-control" placeholder="Digite o título da aula" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="link_video">Link do Vídeo</label>
                        <input type="text" id="link_video" name="link_video" class="form-control" placeholder="Cole o link do vídeo (YouTube, Vimeo, etc.)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="link_pdf">Link do PDF (opcional)</label>
                        <input type="text" id="link_pdf" name="link_pdf" class="form-control" placeholder="Cole o link do PDF">
                    </div>
                    
                    <div class="form-group">
                        <label for="ordem">Ordem da Aula</label>
                        <input type="number" id="ordem" name="ordem" class="form-control" placeholder="Número da ordem da aula" min="1" required>
                    </div>
                    
                    <input type="hidden" name="adicionar_aula" value="1">
                    <button type="submit" class="submit-btn">Adicionar Aula</button>
                </form>
            </div>
            
            <!-- Meus Cursos -->
            <div class="form-section">
                <h2 class="form-title">Meus Cursos</h2>
                
                <div class="courses-grid">
                    <?php
                    mysqli_data_seek($cursos, 0); // Resetar o ponteiro do resultado
                    if (mysqli_num_rows($cursos) > 0) {
                        while ($curso = mysqli_fetch_assoc($cursos)) {
                            // Contar aulas do curso
                            $aulas = mysqli_fetch_assoc(mysqli_query($conn, 
                                "SELECT COUNT(*) as total FROM aula WHERE id_curso = {$curso['id_curso']}"))['total'];
                            
                            // Contar alunos inscritos
                            $alunos = mysqli_fetch_assoc(mysqli_query($conn, 
                                "SELECT COUNT(*) as total FROM inscricao WHERE id_curso = {$curso['id_curso']}"))['total'];
                            
                            echo '<div class="course-card">';
                            echo '<h3 class="course-title">' . htmlspecialchars($curso['titulo']) . '</h3>';
                            echo '<div class="course-meta">';
                            echo '<span>' . $aulas . ' Aulas</span>';
                            echo '<span>' . $alunos . ' Alunos</span>';
                            echo '</div>';
                            echo '<div class="course-actions">';
                            echo '<a href="editar_curso.php?id=' . $curso['id_curso'] . '" class="course-btn edit-btn">Editar</a>';
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