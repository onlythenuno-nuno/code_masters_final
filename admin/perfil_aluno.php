<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: alunos.php");
    exit();
}

$id_aluno = $_GET['id'];

// Buscar dados básicos do aluno
$stmt = $conn->prepare("SELECT * FROM aluno WHERE id_aluno = ?");
$stmt->bind_param("i", $id_aluno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: alunos.php?erro=Aluno não encontrado");
    exit();
}

$aluno = $result->fetch_assoc();

// Buscar cursos inscritos
$cursos_inscritos = $conn->query("
    SELECT c.id_curso, c.titulo, i.data_inscricao 
    FROM inscricao i
    JOIN curso c ON i.id_curso = c.id_curso
    WHERE i.id_aluno = $id_aluno
    ORDER BY i.data_inscricao DESC
");

// Buscar aulas assistidas
$aulas_assistidas = $conn->query("
    SELECT a.id_aula, a.titulo, a.link_video, aa.data_assistida
    FROM aulas_assistidas aa
    JOIN aula a ON aa.id_aula = a.id_aula
    JOIN curso c ON a.id_curso = c.id_curso
    WHERE aa.id_aluno = $id_aluno
    ORDER BY aa.data_assistida DESC
    LIMIT 10
");

// Buscar mensagens de suporte
$mensagens_suporte = $conn->query("
    SELECT id_suporte, assunto, mensagem, data_envio, status
    FROM suporte
    WHERE id_aluno = $id_aluno
    ORDER BY data_envio DESC
    LIMIT 5
");

// Buscar eventos confirmados
$eventos_confirmados = $conn->query("
    SELECT e.id_eventos, e.titulo, e.data_evento, e.local_evento, ep.data_confirmacao
    FROM eventos_participantes ep
    JOIN eventos e ON ep.id_eventos = e.id_eventos
    WHERE ep.id_aluno = $id_aluno
    ORDER BY e.data_evento DESC
    LIMIT 5
");


?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Aluno | Painel Administrativo</title>
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
            --roxo_menu: #1f0660;
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
            color: white;
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
            text-decoration: none;
            font-size: 0.9em;
        }
        
        .action-btn:hover {
            background-color: var(--dark);
            transform: translateY(-2px);
        }
        
        .action-btn.secondary {
            background-color: var(--light);
            color: var(--text);
        }
        
        .action-btn.delete-btn {
            background-color: #f1c0c0;
            color: #c0392b;
        }
        
        .action-btn.delete-btn:hover {
            background-color: #e6b0b0;
        }
        
        .form-section {
            background-color:rgb(229, 218, 255);
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
        
        .search-filters {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .filter-group {
            flex: 1;
        }
        
        .filter-group label {
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
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
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
        
        .actions-cell {
            display: flex;
            gap: 8px;
        }
        
        .action-btn.small {
            padding: 6px 12px;
            font-size: 0.85em;
        }
        
        .view-btn {
            background-color: var(--light);
            color: var(--text);
            border: 1px solid #ddd;
        }
        
        .view-btn:hover {
            background-color: #e0e0e0;
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
        
        /* Modal de confirmação */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .cancel-btn {
            background-color: #f3f3f3;
            color: #333;
        }
        
        .confirm-btn {
            background-color: var(--accent);
            color: white;
        }

        /* Estilos específicos para o perfil */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            font-size: 2em;
            color: var(--primary);
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .profile-info h2 {
            margin: 0 0 5px 0;
            color: var(--primary);
            font-size: 1.5em;
        }
        
        .profile-info p {
            margin: 5px 0;
            color: var(--text);
            font-size: 0.95em;
        }
        
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.8em;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.85em;
            color: var(--text-light);
        }
        
        .profile-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .profile-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .section-title {
            margin-top: 0;
            color: var(--primary);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            font-size: 1.2em;
        }
        
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-title {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--text);
            font-size: 0.95em;
        }
        
        .activity-meta {
            font-size: 0.85em;
            color: var(--text-light);
            display: flex;
            justify-content: space-between;
        }
        
        .activity-meta a {
            color: var(--secondary);
            text-decoration: none;
        }
        
        .activity-meta a:hover {
            text-decoration: underline;
        }
        
        .view-all {
            text-align: right;
            margin-top: 15px;
        }
        
        .view-all a {
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.9em;
        }
        
        .view-all a:hover {
            text-decoration: underline;
        }
        
        .empty-state {
            color: var(--text-light);
            font-style: italic;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo-section">
               <img class="logo" src="../logo.png" alt="">
            </div>
            
            <div class="user-profile">
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?>!</h2>
            </div>
            
            <div class="menu-section">
                <h3>Administração</h3>
                <a href="painel_admin.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Dashboard</div></a>
                <div class="menu-item">Administradores</div>
                <a href="cursos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Cursos</div></a>
                <a href="alunos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Alunos</div></a>
                <a href="eventos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Eventos</div></a>
                <a href="suporte.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Perguntas</div></a>
                
            </div>
            
            <div class="logout-section">
                <a href="login.php" style="text-decoration: none; color: inherit;">
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
                <h1 class="dashboard-title">Perfil do Aluno</h1>
                <div class="admin-actions">
                    <a href="alunos.php" class="action-btn secondary">Voltar</a>
                    <a href="alunos.php?remover=<?php echo $id_aluno; ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja remover este aluno?')">Remover Aluno</a>
                </div>
            </div>
            
            <div class="form-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($aluno['nome_aluno'], 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($aluno['nome_aluno']); ?></h2>
                        <p><?php echo htmlspecialchars($aluno['email_aluno']); ?></p>
                        <p>Cadastrado em: <?php echo date('d/m/Y H:i', strtotime($aluno['criado_em'])); ?></p>
                    </div>
                </div>
                
                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $cursos_inscritos->num_rows; ?></div>
                        <div class="stat-label">Cursos Inscritos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $aulas_assistidas->num_rows; ?></div>
                        <div class="stat-label">Aulas Assistidas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $mensagens_suporte->num_rows; ?></div>
                        <div class="stat-label">Mensagens de Suporte</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $eventos_confirmados->num_rows; ?></div>
                        <div class="stat-label">Eventos Confirmados</div>
                    </div>
                </div>
                
                <div class="profile-sections">
                    <div class="profile-section">
                        <h3 class="section-title">Cursos Inscritos</h3>
                        <?php
                        if ($cursos_inscritos->num_rows > 0) {
                            while ($curso = $cursos_inscritos->fetch_assoc()) {
                                echo '<div class="activity-item">';
                                echo '<div class="activity-title">' . htmlspecialchars($curso['titulo']) . '</div>';
                                echo '<div class="activity-meta">';
                                echo '<span>Inscrito em: ' . date('d/m/Y', strtotime($curso['data_inscricao'])) . '</span>';
                                echo '<a href="cursos.php?id=' . $curso['id_curso'] . '">Ver curso</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>O aluno não está inscrito em nenhum curso.</p>';
                        }
                        ?>
                    </div>
                    
                    <div class="profile-section">
                        <h3 class="section-title">Mensagens de Suporte</h3>
                        <?php
                        if ($mensagens_suporte->num_rows > 0) {
                            while ($mensagem = $mensagens_suporte->fetch_assoc()) {
                                echo '<div class="activity-item">';
                                echo '<div class="activity-title">' . htmlspecialchars($mensagem['assunto']) . '</div>';
                                echo '<div class="activity-meta">';
                                echo '<span>' . date('d/m/Y H:i', strtotime($mensagem['data_envio'])) . '</span>';
                                echo '<span>Status: ' . htmlspecialchars($mensagem['status']) . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Nenhuma mensagem de suporte enviada.</p>';
                        }
                        ?>
                    </div>
                    
                    <div class="profile-section">
                        <h3 class="section-title">Eventos Confirmados</h3>
                        <?php
                        if ($eventos_confirmados->num_rows > 0) {
                            while ($evento = $eventos_confirmados->fetch_assoc()) {
                                echo '<div class="activity-item">';
                                echo '<div class="activity-title">' . htmlspecialchars($evento['titulo']) . '</div>';
                                echo '<div class="activity-meta">';
                                echo '<span>' . date('d/m/Y H:i', strtotime($evento['data_evento'])) . '</span>';
                                echo '<span>' . htmlspecialchars($evento['local_evento']) . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Nenhum evento confirmado.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>