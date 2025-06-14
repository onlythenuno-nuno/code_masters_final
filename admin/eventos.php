<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $data = mysqli_real_escape_string($conn, $_POST['data_evento']);
    $local = mysqli_real_escape_string($conn, $_POST['local_evento']);
    $ativo = 1;

    $conn->query("INSERT INTO eventos (titulo, descricao, data_evento, local_evento, ativo)
                  VALUES ('$titulo', '$descricao', '$data', '$local', '$ativo')");
    
    header("Location: eventos.php?sucesso=Evento+cadastrado+com+sucesso");
    exit();
}

// Remover evento
if (isset($_GET['remover'])) {
    $id = intval($_GET['remover']);
    $conn->query("UPDATE eventos_participantes SET ativo = 0 WHERE id_eventos = $id");
    $conn->query("UPDATE eventos SET ativo = 0 WHERE id_eventos = $id");
    
    header("Location: eventos.php?sucesso=Evento+removido+com+sucesso");
    exit();
}

$eventos = $conn->query("SELECT * FROM eventos WHERE ativo = 1 ORDER BY data_evento DESC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Eventos | Painel Administrativo</title>
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
            text-decoration: none;
            display: inline-block;
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
        
        .evento-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .evento-title {
            font-size: 1.2em;
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 5px;
        }
        
        .evento-meta {
            display: flex;
            gap: 15px;
            color: var(--text-light);
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .evento-descricao {
            color: var(--text);
            margin-bottom: 15px;
        }
        
        .evento-actions {
            display: flex;
            gap: 10px;
        }
        
        .delete-btn {
            background-color: #f1c0c0;
            color: #c0392b;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            text-decoration: none;
        }
        
        .delete-btn:hover {
            background-color: #e6b0b0;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
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
                <a href="alunos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Alunos</div></a>
                <a href="cursos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Cursos</div></a>
                <a href="certificado.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Certificados</div></a>
                <div class="menu-item active">Eventos</div>
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
            <?php if(isset($_GET['sucesso'])): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($_GET['sucesso']); ?>
                </div>
            <?php endif; ?>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Gerenciar Eventos</h1>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Adicionar Novo Evento</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="titulo">Título do Evento</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Digite o título do evento" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="4" placeholder="Descreva o evento"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_evento">Data e Hora</label>
                        <input type="datetime-local" id="data_evento" name="data_evento" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="local_evento">Local</label>
                        <input type="text" id="local_evento" name="local_evento" class="form-control" placeholder="Local do evento" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Cadastrar Evento</button>
                </form>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Eventos Cadastrados</h2>
                
                <?php if ($eventos->num_rows > 0): ?>
                    <?php while($ev = $eventos->fetch_assoc()): ?>
                    <div class="evento-card">
                        <h3 class="evento-title"><?php echo htmlspecialchars($ev['titulo']); ?></h3>
                        
                        <div class="evento-meta">
                            <span><strong>Data:</strong> <?php echo date("d/m/Y H:i", strtotime($ev['data_evento'])); ?></span>
                            <span><strong>Local:</strong> <?php echo htmlspecialchars($ev['local_evento']); ?></span>
                        </div>
                        
                        <?php if(!empty($ev['descricao'])): ?>
                        <div class="evento-descricao">
                            <?php echo nl2br(htmlspecialchars($ev['descricao'])); ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="evento-actions">
                            <a href="?remover=<?php echo $ev['id_eventos']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja remover este evento?')">Remover Evento</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhum evento cadastrado ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>