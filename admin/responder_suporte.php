<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// Verifica se o id_suporte foi enviado na URL
$id = isset($_GET['id_suporte']) ? $_GET['id_suporte'] : null;
if (!$id) {
    header("Location: suporte.php?erro=ID de suporte inválido");
    exit;
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resposta = mysqli_real_escape_string($conn, $_POST['resposta']);
    $sql = "UPDATE suporte SET resposta='$resposta', status='respondido' WHERE id_suporte=$id";
    mysqli_query($conn, $sql);
    header("Location: suporte.php?sucesso=Resposta enviada com sucesso");
    exit;
}

// Busca os dados da mensagem
$result = mysqli_query($conn, "SELECT * FROM suporte WHERE id_suporte = $id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Suporte | Painel Administrativo</title>
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
        
        .form-section {
            background-color: var(--primary);
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .form-title {
            margin-top: 0;
            color: var(--primary);
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            font-size: 1.5em;
        }
        
        .message-box {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            border-left: 4px solid var(--secondary);
        }
        
        .message-box p {
            margin: 8px 0;
            color: var(--text);
        }
        
        .message-box strong {
            color: var(--primary);
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
            font-family: inherit;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        
        .submit-btn {
            background-color: var(--esverdeado);
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
            background-color:rgb(187, 141, 247);
        }
        
        .alert-message {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="main-content">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Responder Mensagem de Suporte</h1>
                <div class="admin-actions">
                    <a href="suporte.php" class="action-btn secondary">Voltar</a>
                </div>
            </div>
            
            <?php if (isset($_GET['erro'])): ?>
                <div class="alert-message alert-error">
                    <?php echo htmlspecialchars($_GET['erro']); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-section">
                <div class="message-box">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($row['nome']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                    <p><strong>Assunto:</strong> <?= htmlspecialchars($row['assunto']) ?></p>
                    <p><strong>Mensagem:</strong></p>
                    <p><?= nl2br(htmlspecialchars($row['mensagem'])) ?></p>
                </div>
                
                <form method="post">
                    <div class="form-group">
                        <label for="resposta"><strong>Sua Resposta:</strong></label>
                        <textarea class="form-control" name="resposta" required placeholder="Escreva sua resposta aqui..."><?= htmlspecialchars($row['resposta'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Enviar Resposta</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>