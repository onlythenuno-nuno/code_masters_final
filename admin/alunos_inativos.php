<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// -----------------------------
// Processar reativação de aluno
// -----------------------------
if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    $id_aluno = $_GET['remover'];

    $stmt = $conn->prepare("SELECT * FROM aluno WHERE id_aluno = ? AND ativo = 0");
    $stmt->bind_param("i", $id_aluno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE aluno SET ativo = 1 WHERE id_aluno = ?");
        $stmt->bind_param("i", $id_aluno);
        if ($stmt->execute()) {
            $mensagem = "Aluno reativado com sucesso!";
            header("Location: alunos_inativos.php?mensagem=" . urlencode($mensagem));
            exit();
        } else {
            $erro = "Erro ao reativar aluno.";
        }
    } else {
        $erro = "Aluno não encontrado ou já está ativo.";
    }
}

// -----------------------------
// Filtros de busca
// -----------------------------
$filtro_nome  = isset($_GET['nome'])  ? $_GET['nome']  : '';
$filtro_email = isset($_GET['email']) ? $_GET['email'] : '';

// -----------------------------
// Paginação
// -----------------------------
$registros_por_pagina = 40;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;

// WHERE para somente inativos
$where = "WHERE ativo = 0";
if (!empty($filtro_nome)) {
    $nome_filtrado = mysqli_real_escape_string($conn, $filtro_nome);
    $where .= " AND nome_aluno LIKE '%$nome_filtrado%'";
}
if (!empty($filtro_email)) {
    $email_filtrado = mysqli_real_escape_string($conn, $filtro_email);
    $where .= " AND email_aluno LIKE '%$email_filtrado%'";
}

// Contagem
$query_count = "SELECT COUNT(*) as total FROM aluno $where";
$result_count = mysqli_query($conn, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
if ($pagina_atual > $total_paginas && $total_paginas > 0) {
    $pagina_atual = $total_paginas;
}
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Consulta final
$query = "SELECT * FROM aluno $where ORDER BY criado_em DESC LIMIT $offset, $registros_por_pagina";
$alunos = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alunos | Painel Administrativo</title>
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

        .inativo{
            padding: 7px 60px;
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
            background-color:rgb(21, 179, 58);
            color:rgb(255, 255, 255);
        }
        
        .action-btn.delete-btn:hover {
            background-color: #e6b0b0;
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
            padding: 12px 9px;
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
        
    
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: var(--primary);
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination .current {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination .disabled {
            color: #ddd;
            pointer-events: none;
        }
        
        .pagination-info {
            text-align: center;
            margin-top: 10px;
            color: var(--text);
            font-size: 0.9em;
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
                <a href="painel_admin.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Dashboard</div></a>
                <a href="administradores.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Administradores</div></a>
                <a href="alunos.php" style="text-decoration: none; color: inherit;"><div class="menu-item">Alunos</div></a>
                <div class="menu-item inativo active">Alunos Desativados</div>
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
            <?php 
            if (isset($_GET['mensagem'])) {
                echo "<div class='alert-message'>" . htmlspecialchars($_GET['mensagem']) . "</div>";
            }
            if (isset($erro)) {
                echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0;'>$erro</div>";
            }
            ?>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Gerenciar Alunos</h1>
            </div>
            
            <div class="search-filters">
                <h2 class="form-title">Pesquisar Alunos</h2>
                <form method="GET" action="alunos_inativos.php">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="nome">Nome do Aluno</label>
                            <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do aluno" value="<?php echo htmlspecialchars($filtro_nome); ?>">
                        </div>
                        <div class="filter-group">
                            <label for="email">Email do Aluno</label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="Digite o email do aluno" value="<?php echo htmlspecialchars($filtro_email); ?>">
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Pesquisar</button>
                    <a href="alunos_inativos.php" class="action-btn secondary" style="margin-left: 10px;">Limpar Filtros</a>
                </form>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Lista de Alunos</h2>
                
                <div class="pagination-info">
                    Mostrando <?php echo ($total_registros > 0 ? ($offset + 1) : 0); ?> a <?php echo min($offset + $registros_por_pagina, $total_registros); ?> de <?php echo $total_registros; ?> alunos
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($alunos) > 0) {
                                while ($aluno = mysqli_fetch_assoc($alunos)) {
                                    echo '<tr>';
                                    echo '<td>' . $aluno['id_aluno'] . '</td>';
                                    echo '<td>' . htmlspecialchars($aluno['nome_aluno']) . '</td>';
                                    echo '<td>' . htmlspecialchars($aluno['email_aluno']) . '</td>';
                                    echo '<td>' . $aluno['criado_em'] . '</td>';
                                    echo '<td class="actions-cell">';
                                    echo '<a href="perfil_aluno_desativado.php?id=' . $aluno['id_aluno'] . '" class="action-btn view-btn">Ver Perfil</a>';
                                    echo '<a href="#" onclick="confirmarExclusao(' . $aluno['id_aluno'] . ')" class="action-btn delete-btn small">Reactivar</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5">Nenhum aluno encontrado.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <?php if ($total_paginas > 1): ?>
                <div class="pagination">
                    <?php if ($pagina_atual > 1): ?>
                        <a href="alunos.php?pagina=1<?php echo !empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : ''; ?><?php echo !empty($filtro_email) ? '&email='.urlencode($filtro_email) : ''; ?>">&laquo;</a>
                        <a href="alunos.php?pagina=<?php echo $pagina_atual - 1; ?><?php echo !empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : ''; ?><?php echo !empty($filtro_email) ? '&email='.urlencode($filtro_email) : ''; ?>">&lsaquo;</a>
                    <?php else: ?>
                        <span class="disabled">&laquo;</span>
                        <span class="disabled">&lsaquo;</span>
                    <?php endif; ?>
                    
                    <?php
                    // Mostrar links para páginas próximas
                    $inicio = max(1, $pagina_atual - 2);
                    $fim = min($total_paginas, $pagina_atual + 2);
                    
                    if ($inicio > 1) {
                        echo '<a href="alunos_inativos.php?pagina=1'.(!empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : '').(!empty($filtro_email) ? '&email='.urlencode($filtro_email) : '').'">1</a>';
                        if ($inicio > 2) echo '<span>...</span>';
                    }
                    
                    for ($i = $inicio; $i <= $fim; $i++) {
                        if ($i == $pagina_atual) {
                            echo '<span class="current">'.$i.'</span>';
                        } else {
                            echo '<a href="alunos_inativos.php?pagina='.$i.(!empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : '').(!empty($filtro_email) ? '&email='.urlencode($filtro_email) : '').'">'.$i.'</a>';
                        }
                    }
                    
                    if ($fim < $total_paginas) {
                        if ($fim < $total_paginas - 1) echo '<span>...</span>';
                        echo '<a href="alunos_inativos.php?pagina='.$total_paginas.(!empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : '').(!empty($filtro_email) ? '&email='.urlencode($filtro_email) : '').'">'.$total_paginas.'</a>';
                    }
                    ?>
                    
                    <?php if ($pagina_atual < $total_paginas): ?>
                        <a href="alunos_inativos.php?pagina=<?php echo $pagina_atual + 1; ?><?php echo !empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : ''; ?><?php echo !empty($filtro_email) ? '&email='.urlencode($filtro_email) : ''; ?>">&rsaquo;</a>
                        <a href="alunos.php?pagina=<?php echo $total_paginas; ?><?php echo !empty($filtro_nome) ? '&nome='.urlencode($filtro_nome) : ''; ?><?php echo !empty($filtro_email) ? '&email='.urlencode($filtro_email) : ''; ?>">&raquo;</a>
                    <?php else: ?>
                        <span class="disabled">&rsaquo;</span>
                        <span class="disabled">&raquo;</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmação -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3>Confirmar Reativação</h3>
            <p>Tem certeza que deseja reativar este aluno?</p>
            <div class="modal-actions">
                <button onclick="fecharModal()" class="action-btn cancel-btn">Cancelar</button>
                <button id="confirmDeleteBtn" class="action-btn confirm-btn">Confirmar</button>
            </div>
        </div>
    </div>
    
    <script>
        function confirmarExclusao(idAluno) {
            const modal = document.getElementById('confirmModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            // Mostrar o modal
            modal.style.display = 'block';
            
            // Configurar o botão de confirmação
            confirmBtn.onclick = function() {
                window.location.href = 'alunos_inativos.php?remover=' + idAluno;
            };
        }
        
        function fecharModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
        
        // Fechar o modal se clicar fora dele
        window.onclick = function(event) {
            const modal = document.getElementById('confirmModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>