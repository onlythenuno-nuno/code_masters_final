<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include '../conexao.php';

// Buscar estatísticas
$total_cursos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM curso"))['total'];
$total_alunos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT id_aluno) as total FROM inscricao"))['total'];
$curso_popular = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT c.titulo, COUNT(i.id_inscricao) as inscritos 
     FROM curso c LEFT JOIN inscricao i ON c.id_curso = i.id_curso 
     GROUP BY c.id_curso ORDER BY inscritos DESC LIMIT 1"));

// Pegar 3 cursos com mais inscritos
$cursos_populares = mysqli_query($conn, 
    "SELECT c.*, COUNT(i.id_inscricao) as total_inscritos 
     FROM curso c 
     LEFT JOIN inscricao i ON c.id_curso = i.id_curso 
     GROUP BY c.id_curso 
     ORDER BY total_inscritos DESC 
     LIMIT 3");

// Pesquisa de cursos
$termo_pesquisa = '';
$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pesquisa'])) {
    $termo_pesquisa = mysqli_real_escape_string($conn, $_GET['pesquisa']);
    
    $query = "SELECT c.*, COUNT(i.id_inscricao) as total_inscritos 
              FROM curso c 
              LEFT JOIN inscricao i ON c.id_curso = i.id_curso 
              WHERE c.titulo LIKE '%$termo_pesquisa%' OR c.descricao LIKE '%$termo_pesquisa%' 
              GROUP BY c.id_curso 
              ORDER BY c.titulo";
    
    $resultados = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos | Painel Administrativo</title>
    <style>
        :root {
            --primary: #170448;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
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
            background-color: var(--primary);
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
        
        .course-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
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
        
        .course-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            justify-content: center;
        }

        .course-actions > a{
            padding: 10px 50px;
        }
        
        .course-btn {
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            font-size: 0.9em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .edit-btn {
            background-color: var(--light);
            color: var(--text);
            border: 1px solid #ddd;
        }
        
        .edit-btn:hover {
            background-color: #bdc3c7;
        }
        
        .delete-btn {
            background-color: #f1c0c0;
            color: #c0392b;
            border: 1px solid #e6b0b0;
        }
        
        .delete-btn:hover {
            background-color: #e6b0b0;
        }
        
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        
        .search-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .no-results {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
        }
        
        .popular-badge {
            background-color: var(--accent);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7em;
            margin-left: 10px;
            vertical-align: middle;
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
                <a href="painel_admin_comum.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Dashboard</div></a>
                <a href="administradores.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Administradores</div></a>
                <a href="cursos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Alunos</div></a>
                <div class="menu-item active">Cursos</div>
                <a href="certificado.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Certificados</div></a>
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
            <div class="dashboard-header">
                <h1 class="dashboard-title">Gerenciamento de Cursos</h1>
            </div>
            
            <!-- Cards de Estatísticas -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_cursos; ?></div>
                    <div class="stat-label">Total de Cursos</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_alunos; ?></div>
                    <div class="stat-label">Alunos Inscritos</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $curso_popular['inscritos'] ?? 0; ?></div>
                    <div class="stat-label"><?php echo htmlspecialchars($curso_popular['titulo'] ?? 'Nenhum curso'); ?></div>
                </div>
            </div>
            
            <!-- Cursos Mais Populares -->
            <div class="form-section">
                <h2 class="form-title">Cursos Mais Populares <span class="popular-badge">TOP 3</span></h2>
                
                <?php if (mysqli_num_rows($cursos_populares) > 0): ?>
                    <?php while ($curso = mysqli_fetch_assoc($cursos_populares)): ?>
                    <div class="course-card">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h3 class="course-title"><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                <p style="color: var(--text-light); margin-top: 5px;">
                                    <?php echo htmlspecialchars($curso['descricao']); ?>
                                </p>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 1.5em; color: var(--primary); font-weight: bold;">
                                    <?php echo $curso['total_inscritos']; ?>
                                </div>
                                <div style="font-size: 0.8em; color: var(--text-light);">alunos</div>
                            </div>
                        </div>
                        
                        <div class="course-actions">
                            <a href="editar_curso.php?id=<?php echo $curso['id_curso']; ?>" class="course-btn edit-btn">Editar Curso</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhum curso com inscrições ainda.</p>
                <?php endif; ?>
            </div>
            
            <!-- Barra de Pesquisa -->
            <div class="form-section">
                <h2 class="form-title">Pesquisar Cursos</h2>
                
                <div class="search-container">
                    <form method="GET" class="search-form">
                        <input type="text" name="pesquisa" class="search-input" 
                               placeholder="Digite o nome do curso ou descrição..." 
                               value="<?php echo htmlspecialchars($termo_pesquisa); ?>">
                        <button type="submit" class="search-btn">Pesquisar</button>
                    </form>
                </div>
                
                <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pesquisa'])): ?>
                    <?php if (mysqli_num_rows($resultados) > 0): ?>
                        <h3 style="margin-top: 20px;">Resultados para "<?php echo htmlspecialchars($termo_pesquisa); ?>"</h3>
                        
                        <?php while ($curso = mysqli_fetch_assoc($resultados)): ?>
                        <div class="course-card" style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 class="course-title"><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                    <p style="color: var(--text-light); margin-top: 5px;">
                                        <?php echo htmlspecialchars($curso['descricao']); ?>
                                    </p>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-size: 1.5em; color: var(--primary); font-weight: bold;">
                                        <?php echo $curso['total_inscritos']; ?>
                                    </div>
                                    <div style="font-size: 0.8em; color: var(--text-light);">alunos</div>
                                </div>
                            </div>
                            
                            <div class="course-actions">
                                <a href="editar_curso.php?id=<?php echo $curso['id_curso']; ?>" class="course-btn edit-btn">Editar Curso</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-results">
                            <p>Nenhum curso encontrado com o termo "<?php echo htmlspecialchars($termo_pesquisa); ?>"</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal de Exclusão -->
    <div id="delete-course-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 8px; width: 500px; max-width: 90%;">
            <h3 style="margin-top: 0;">Apagar Cursos</h3>
            <p>Tem certeza que deseja apagar os cursos selecionados? Esta ação não pode ser desfeita.</p>
            
            <form action="apagar_cursos.php" method="POST">
                <div style="margin-bottom: 20px;">
                    <?php
                    $cursos = mysqli_query($conn, "SELECT id_curso, titulo FROM curso");
                    while ($curso = mysqli_fetch_assoc($cursos)) {
                        echo '<div style="margin-bottom: 10px;">';
                        echo '<input type="checkbox" name="cursos[]" value="'.$curso['id_curso'].'" id="curso_'.$curso['id_curso'].'">';
                        echo '<label for="curso_'.$curso['id_curso'].'" style="margin-left: 10px;">'.htmlspecialchars($curso['titulo']).'</label>';
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="document.getElementById('delete-course-modal').style.display='none'" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn" style="background-color: var(--accent);">Confirmar Exclusão</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja excluir este curso? Todas as aulas e inscrições relacionadas também serão removidas.')) {
                window.location.href = 'excluir_curso.php?id=' + id;
            }
        }
    </script>
</body>
</html>