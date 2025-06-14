<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_nivel'] != 'super') {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// Processar adição de novo administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_admin'])) {
    $nome = trim($_POST['nome_admin']);
    $email = trim($_POST['email_admin']);
    $senha = trim($_POST['senha_admin']);
    $nivel = $_POST['nivel_admin'];

    // Verificar se email já existe
    $stmt = $conn->prepare("SELECT id_admin FROM administrador WHERE email_admin = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mensagem = "<div class='alert alert-error'>Este email já está cadastrado!</div>";
    } else {
        // Hash da senha (exceto para super admin)
        $senha_hash = ($nivel == 'super') ? $senha : password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO administrador (nome_admin, email_admin, senha_admin, nivel, ativo) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $nivel);

        if ($stmt->execute()) {
            $mensagem = "<div class='alert alert-success'>Administrador adicionado com sucesso!</div>";
        } else {
            $mensagem = "<div class='alert alert-error'>Erro ao adicionar administrador.</div>";
        }
    }
}

// Processar a reativação do admin
if (isset($_GET['remover_admin'])) {
    $id_remover = $_GET['remover_admin'];

    
    if ($id_remover != $_SESSION['admin_id']) {
        $stmt = $conn->prepare("UPDATE administrador SET ativo = 1 WHERE id_admin = ?");
        $stmt->bind_param("i", $id_remover);

        if ($stmt->execute()) {
            $mensagem = "<div class='alert alert-success'>Administrador reactivado com sucesso!</div>";
        } else {
            $mensagem = "<div class='alert alert-error'>Erro ao remover administrador.</div>";
        }
    } else {
        $mensagem = "<div class='alert alert-error'>Você não pode remover a si mesmo.</div>";
    }
}

// Obter lista de administradores ativos
$admins = $conn->query("SELECT * FROM administrador WHERE ativo = 0 ORDER BY id_admin DESC");

// Obter estatísticas
$total_admins = $admins->num_rows;
$total_cursos = $conn->query("SELECT COUNT(*) as total FROM curso WHERE ativo = 1")->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Administradores | Painel Administrativo</title>
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
            font-size: 1.1rem;
        }
        
        .logo-section {
            padding: 25px;
            background-color: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
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
            padding-left: 60px;
            color: var(--secondary);
        }
        
        .logout-section {
            padding: 20px;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
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
            background-color: var(--dark);
            transform: translateY(-2px);
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
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            color: var(--text-light);
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .admin-table th, .admin-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .admin-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }
        
        .admin-table tr:hover {
            background-color: #f9f5ff;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 500;
        }
        
        .badge-super {
            background-color: var(--accent);
            color: white;
        }
        
        .badge-admin {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-danger {
            background-color: #f1c0c0;
            color: #c0392b;
            border: 1px solid #e6b0b0;
        }
        
        .btn-danger:hover {
            background-color: #e6b0b0;
        }
        
        .curso-row {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .curso-row:hover {
            background-color: #f3e8ff;
        }
        
        .aulas-list {
            display: none;
            padding: 10px;
            background-color: #f9f9f9;
        }
        
        .aulas-list.active {
            display: table-row;
        }
        
        .aula-item {
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Estilos para os modais */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            overflow: auto;
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1001;
        }
        
        .modal-container {
            position: relative;
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 1002;
            animation: modalFadeIn 0.3s;
        }
        
        .modal-title {
            margin-top: 0;
            color: var(--primary);
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            font-size: 1.5em;
        }
        
        #modal-message {
            margin: 20px 0;
            font-size: 1.1em;
            line-height: 1.5;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .modal-btn-cancel {
            background-color: #f3f3f3;
            color: #333;
        }
        
        .modal-btn-confirm {
            background-color: var(--primary);
            color: white;
        }
        
        .modal-btn-confirm:hover {
            background-color: var(--dark);
        }
        
        @keyframes modalFadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .inativo{
            padding: 7px 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="user-profile">
                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?></h2>
            </div>
            
            <div class="menu-section">
                <h3>Administração</h3>
                <a href="painel_admin.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Dashboard</div></a>
                <a href="administradores.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Administradores </div></a>
                <a href="administradores_inativos.php" style="text-decoration: none; color: inherit;"><div class="menu-item inativo active">Administradores Inativos</div></a>
                <a href="alunos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Alunos</div></a>
                <a href="cursos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Cursos</div></a>
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
            <?php if(isset($mensagem)) echo $mensagem; ?>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Gerenciar Administradores</h1>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_admins; ?></div>
                    <div class="stat-label">Administradores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_cursos; ?></div>
                    <div class="stat-label">Cursos Cadastrados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo date('d/m/Y'); ?></div>
                    <div class="stat-label">Data Atual</div>
                </div>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Lista de Administradores Desativados</h2>
                
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Nível</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($admin = $admins->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $admin['id_admin']; ?></td>
                                    <td><?php echo htmlspecialchars($admin['nome_admin']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['email_admin']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $admin['nivel'] == 'super' ? 'badge-super' : 'badge-admin'; ?>">
                                            <?php echo ucfirst($admin['nivel']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($admin['id_admin'] != $_SESSION['admin_id']): ?>
                                            <a href="administradores_inativos.php?remover_admin=<?php echo $admin['id_admin']; ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirmarExclusao(this.href)">
                                                Reactivar
                                            </a>
                                        <?php else: ?>
                                            <span style="color: var(--text-light);">Você</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
    
        </div>
    </div>
    
    
    <!-- Modal de Confirmação para Exclusão -->
    <div id="modal-confirmacao" class="modal" style="display:none;">
        <div class="modal-overlay" onclick="document.getElementById('modal-confirmacao').style.display='none'"></div>
        <div class="modal-container">
            <h2 class="modal-title">Confirmar Reativação</h2>
            <p id="modal-message">Tem certeza que deseja Reactivar este administrador?</p>
            
            <div class="modal-actions">
                <button onclick="document.getElementById('modal-confirmacao').style.display='none'" class="modal-btn modal-btn-cancel">
                    Cancelar
                </button>
                <button id="confirmar-exclusao" class="modal-btn modal-btn-confirm">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Função para mostrar/ocultar a lista de aulas
        function toggleAulas(id_curso) {
            const aulasList = document.getElementById(`aulas-${id_curso}`);
            aulasList.classList.toggle('active');
        }
        
        // Funções para os modais
        let urlExclusao = '';
        
        function confirmarExclusao(url) {
            urlExclusao = url;
            document.getElementById('modal-confirmacao').style.display = 'block';
            return false;
        }
        
        document.getElementById('confirmar-exclusao').addEventListener('click', function() {
            window.location.href = urlExclusao;
        });
        
        // Fechar modais ao clicar no overlay ou pressionar ESC
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        };
        
        document.onkeydown = function(evt) {
            evt = evt || window.event;
            if (evt.keyCode == 27) {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        };
        
        // Função para mostrar o modal de adicionar administrador
        function showAddAdminModal() {
            document.getElementById('modal-adicionar').style.display = 'block';
        }
    </script>
</body>
</html>