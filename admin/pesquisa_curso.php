<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

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
    <!-- [Manter todos os estilos anteriores] -->
    <style>
        :root {
            --primary: #4c1d95;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
        }
        
        /* [Manter todos os estilos anteriores do painel] */
        
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
            font-size: 1.8em;
            font-weight: 800;
            color: var(--light);
            letter-spacing: 1px;
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
            background-color: #f5f7fa;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-title {
            font-size: 2em;
            color: var(--primary);
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
            width: 100%;
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
            background-color: var(--secondary);
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
        /* Novos estilos específicos para cursos */
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
            font-size: 0.9em;
        }
        
        .course-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .lesson-list {
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .lesson-item {
            padding: 10px;
            margin-bottom: 8px;
            background: var(--light);
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
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
            background-color: var(--secondary);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <!-- [Manter sidebar similar ao cursos.php] -->
             <div class="sidebar">
            <div class="logo-section">
                <div class="logo">CODE<span>MASTERS</span></div>
            </div>
            
            <div class="user-profile">
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?>!</h2>
            </div>
            
            <div class="menu-section">
                <h3>Administração</h3>
                <a href="painel_admin.php" style="text-decoration: none; color: inherit;">
                    <div class="menu-item">Dashboard</div>
                </a>
                <div class="menu-item active">Cursos</div>
                <a href="alunos.php" style="text-decoration: none; color: inherit;">
                    <div class="menu-item">Alunos</div>
                </a>
                
                <h3 style="margin-top: 30px;">Ações</h3>
                <div class="menu-item" onclick="document.getElementById('delete-course-modal').style.display='block'">
                    Apagar Cursos
                </div>
                <a href="adicionar_curso.php" style="text-decoration: none; color: inherit;">
                    <div class="menu-item">Adicionar Novo Curso</div>
                </a>
            </div>
        </div>
        </div>
        
        <div class="main-content">
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Pesquisar Cursos</h1>
                <div class="admin-actions">
                    <a href="cursos.php" class="action-btn secondary">Voltar</a>
                </div>
            </div>
            
            <div class="search-container">
                <form method="GET" class="search-form">
                    <input type="text" name="pesquisa" class="search-input" 
                           placeholder="Digite o nome do curso ou descrição..." 
                           value="<?php echo htmlspecialchars($termo_pesquisa); ?>">
                    <button type="submit" class="search-btn">Pesquisar</button>
                </form>
            </div>
            
            <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pesquisa'])): ?>
                <div class="form-section">
                    <h2 class="form-title">Resultados para "<?php echo htmlspecialchars($termo_pesquisa); ?>"</h2>
                    
                    <?php if (mysqli_num_rows($resultados) > 0): ?>
                        <?php while ($curso = mysqli_fetch_assoc($resultados)): ?>
                        <div class="course-card" style="margin-bottom: 20px;">
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
                                <a href="gerenciar_aulas.php?id=<?php echo $curso['id_curso']; ?>" class="course-btn edit-btn">Gerenciar Aulas</a>
                                <a href="#" onclick="confirmarExclusao(<?php echo $curso['id_curso']; ?>)" class="course-btn delete-btn">Excluir</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-results">
                            <p>Nenhum curso encontrado com o termo "<?php echo htmlspecialchars($termo_pesquisa); ?>"</p>
                            <a href="cursos.php" class="action-btn secondary">Ver Todos os Cursos</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="form-section">
                    <div class="no-results">
                        <p>Digite um termo de pesquisa para encontrar cursos</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- [Manter modal de exclusão] -->
</body>
</html>