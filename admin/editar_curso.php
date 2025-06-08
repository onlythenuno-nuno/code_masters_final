<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// Verificação e sanitização do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    header("Location: painel_admin.php");
    exit();
}

// Busca os dados do curso
$stmt_curso = $conn->prepare("SELECT * FROM curso WHERE id_curso = ?");
$stmt_curso->bind_param("i", $id);
$stmt_curso->execute();
$curso = $stmt_curso->get_result()->fetch_assoc();

if (!$curso) {
    header("Location: painel_admin.php");
    exit();
}

// Busca as aulas do curso
$stmt_aulas = $conn->prepare("SELECT * FROM aula WHERE id_curso = ? ORDER BY ordem");
$stmt_aulas->bind_param("i", $id);
$stmt_aulas->execute();
$aulas = $stmt_aulas->get_result();

// Processamento do formulário do curso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar_curso'])) {
        // Edição do curso
        $titulo = htmlspecialchars($_POST['titulo']);
        $descricao = htmlspecialchars($_POST['descricao']);

        $stmt = $conn->prepare("UPDATE curso SET titulo=?, descricao=? WHERE id_curso=?");
        $stmt->bind_param("ssi", $titulo, $descricao, $id);
        $stmt->execute();
        
        $_SESSION['mensagem'] = "Curso atualizado com sucesso!";
        header("Location: editar_curso.php?id=$id");
        exit();
    } elseif (isset($_POST['editar_aula'])) {
        // Edição de aula
        $aula_id = filter_input(INPUT_POST, 'aula_id', FILTER_VALIDATE_INT);
        $titulo_aula = htmlspecialchars($_POST['titulo_aula']);
        $link_video_aula = filter_var($_POST['link_video_aula'], FILTER_SANITIZE_URL);
        $ordem_aula = filter_input(INPUT_POST, 'ordem_aula', FILTER_VALIDATE_INT);

        $stmt = $conn->prepare("UPDATE aula SET titulo=?, link_video=?, ordem=? WHERE id_aula=?");
        $stmt->bind_param("ssii", $titulo_aula, $link_video_aula, $ordem_aula, $aula_id);
        $stmt->execute();
        
        $_SESSION['mensagem'] = "Aula atualizada com sucesso!";
        header("Location: editar_curso.php?id=$id");
        exit();
    } elseif (isset($_POST['adicionar_aula'])) {
        // Adição de nova aula
        $titulo_aula = htmlspecialchars($_POST['novo_titulo_aula']);
        $link_video_aula = filter_var($_POST['novo_link_video_aula'], FILTER_SANITIZE_URL);
        $ordem_aula = filter_input(INPUT_POST, 'nova_ordem_aula', FILTER_VALIDATE_INT);

        $stmt = $conn->prepare("INSERT INTO aula (titulo, link_video, ordem, id_curso) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $titulo_aula, $link_video_aula, $ordem_aula, $id);
        $stmt->execute();
        
        $_SESSION['mensagem'] = "Aula adicionada com sucesso!";
        header("Location: editar_curso.php?id=$id");
        exit();
    } elseif (isset($_POST['excluir_aula'])) {
        // Exclusão de aula
        $aula_id = filter_input(INPUT_POST, 'excluir_aula_id', FILTER_VALIDATE_INT);
        
        $stmt = $conn->prepare("DELETE FROM aula WHERE id_aula = ?");
        $stmt->bind_param("i", $aula_id);
        $stmt->execute();
        
        $_SESSION['mensagem'] = "Aula excluída com sucesso!";
        header("Location: editar_curso.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - Painel Admin</title>
    <style>
        :root {
            --primary: #170448;       /* Roxo escuro (sidebar) */
            --secondary: #8b5cf6;     /* Roxo claro (destaques/botões) */
            --accent: #f43f5e;        /* Coral/rosa (para hover ou alerta) */
            --light: #f3e8ff;         /* Lilás bem claro (usado no fundo dos cards) */
            --dark: #2e1065;          /* Roxo mais escuro (para sombreados profundos) */
            --text: #1e1b4b;          /* Roxo escuro mais neutro (para texto normal) */
            --text-light: #c4b5fd;    /* Lilás suave (para texto secundário) */
            --esverdeado: #2fc10e;
            --white: #fff;
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
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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
        
        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--dark);
        }
        
        .btn-danger {
            background-color: #f43f5e;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc3545;
        }
        
        .btn-success {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-success:hover {
            background-color: rgb(106, 70, 190);
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            margin: 0;
            color: var(--primary);
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .close {
            font-size: 1.5em;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #333;
        }

        /* Estilos para o accordion de aulas */
        .aula-accordion {
            margin-top: 20px;
        }
        
        .aula-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            margin-bottom: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 4px solid var(--secondary);
            transition: all 0.3s;
        }
        
        .aula-header:hover {
            background-color: #f9f5ff;
        }
        
        .aula-header h3 {
            margin: 0;
            font-size: 1.1em;
            color: var(--primary);
        }
        
        .aula-header .toggle-icon {
            transition: transform 0.3s;
        }
        
        .aula-header.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }
        
        .aula-content {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: white;
            border-radius: 0 0 8px 8px;
            margin-bottom: 15px;
        }
        
        .aula-content.show {
            max-height: 1000px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
    <div class="container">
        
        <div class="main-content">
            <?php if (isset($_SESSION['mensagem'])): ?>
                <div class="alert alert-<?php echo strpos($_SESSION['mensagem'], 'sucesso') !== false ? 'success' : 'danger'; ?>">
                    <?php echo $_SESSION['mensagem']; ?>
                </div>
                <?php unset($_SESSION['mensagem']); ?>
            <?php endif; ?>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">Editar Curso: <?php echo htmlspecialchars($curso['titulo']); ?></h1>
            <div class="navigation-buttons">
            <a href="painel_admin.php" class="btn btn-secondary">Voltar para o Painel</a>
            </div>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Informações do Curso</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="titulo">Título do Curso</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo htmlspecialchars($curso['titulo']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="4" required><?php echo htmlspecialchars($curso['descricao']); ?></textarea>
                    </div>
                    
                    <button type="submit" name="editar_curso" class="submit-btn">Salvar Alterações do Curso</button>
                </form>
            </div>
            
            <div class="form-section">
                <h2 class="form-title">Aulas do Curso</h2>
                
                <div class="aula-accordion">
                    <?php if ($aulas->num_rows > 0): ?>
                        <?php while ($aula = $aulas->fetch_assoc()): ?>
                            <div class="aula-item">
                                <div class="aula-header collapsed" onclick="toggleAula(this)">
                                    <h3 class="aula-title">Aula <?php echo $aula['ordem']; ?>: <?php echo htmlspecialchars($aula['titulo']); ?></h3>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="18 15 12 9 6 15"></polyline>
                                    </svg>
                                </div>
                                
                                <div class="aula-content">
                                    <form method="POST">
                                        <input type="hidden" name="aula_id" value="<?php echo $aula['id_aula']; ?>">
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Título da Aula</label>
                                                <input type="text" class="form-control" name="titulo_aula" value="<?php echo htmlspecialchars($aula['titulo']); ?>" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Ordem</label>
                                                <input type="number" class="form-control" name="ordem_aula" value="<?php echo $aula['ordem']; ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Link do Vídeo</label>
                                            <input type="text" class="form-control" name="link_video_aula" value="<?php echo htmlspecialchars($aula['link_video']); ?>" required>
                                        </div>
                                        
                                        <div class="form-actions">
                                            <button type="submit" name="editar_aula" class="btn btn-primary">Salvar Aula</button>
                                            <button type="button" class="btn btn-danger" onclick="confirmarExclusao(<?php echo $aula['id_aula']; ?>, '<?php echo addslashes(htmlspecialchars($aula['titulo'])); ?>')">Excluir Aula</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-info">Nenhuma aula cadastrada para este curso.</div>
                    <?php endif; ?>
                </div>
                
                <div style="margin-top: 30px;">
                    <h3>Adicionar Nova Aula</h3>
                    
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Título da Aula</label>
                                <input type="text" class="form-control" name="novo_titulo_aula" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Ordem</label>
                                <input type="number" class="form-control" name="nova_ordem_aula" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Link do Vídeo</label>
                            <input type="text" class="form-control" name="novo_link_video_aula" required>
                        </div>
                        
                        <button type="submit" name="adicionar_aula" class="btn btn-success">Adicionar Aula</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Confirmação -->
    <div id="modalExclusao" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmar Exclusão</h3>
                <span class="close" onclick="fecharModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="mensagemExclusao">Tem certeza que deseja excluir esta aula?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="formExclusao">
                    <input type="hidden" name="excluir_aula_id" id="excluirAulaId">
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" name="excluir_aula" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Função para alternar (minimizar/maximizar) a aula
        function toggleAula(header) {
            header.classList.toggle('collapsed');
            const content = header.nextElementSibling;
            content.classList.toggle('show');
        }
        
        // Função para confirmar exclusão
        function confirmarExclusao(id, titulo) {
            document.getElementById('excluirAulaId').value = id;
            document.getElementById('mensagemExclusao').textContent = `Tem certeza que deseja excluir a aula "${titulo}"?`;
            document.getElementById('modalExclusao').style.display = 'flex';
        }
        
        // Função para fechar o modal
        function fecharModal() {
            document.getElementById('modalExclusao').style.display = 'none';
        }
        
        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            if (event.target == document.getElementById('modalExclusao')) {
                fecharModal();
            }
        }
    </script>
</body>
</html>