<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

$sql = "SELECT * FROM suporte ORDER BY data_envio DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens de Suporte | Painel Administrativo</title>
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
        
        .status-respondido {
            color: var(--esverdeado);
            font-weight: 500;
        }
        
        .status-pendente {
            color: orange;
            font-weight: 500;
        }
        
        .responder-btn {
            background-color: var(--esverdeado);
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.85em;
            transition: background-color 0.3s;
        }
        
        .responder-btn:hover {
            background-color: #26980c;
        }
        
        .empty-state {
            color: var(--text-light);
            font-style: italic;
            padding: 20px;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        
        <div class="main-content">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Mensagens de Suporte</h1>
                <div class="admin-actions">
                    <a href="painel_admin_comum.php" class="action-btn secondary">Voltar</a>
                </div>
            </div>
            
            <div class="form-section">
                <div class="table-container">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Assunto</th>
                                    <th>Mensagem</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nome']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['assunto']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['mensagem'])) ?></td>
                                        <td class="<?= $row['status'] === 'respondido' ? 'status-respondido' : 'status-pendente' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['data_envio'])) ?></td>
                                        <td>
                                            <a class="responder-btn" href="responder_suporte.php?id_suporte=<?= $row['id_suporte'] ?>">Responder</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="empty-state">Nenhuma mensagem de suporte encontrada.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>