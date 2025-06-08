<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_aluno = $_SESSION['id'];
$sql = "SELECT * FROM suporte WHERE id_aluno = $id_aluno ORDER BY data_envio DESC";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens de Suporte | CodeMasters</title>
    <style>
        :root {
            --primary: #4c1d95;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
            color: var(--text);
        }
        
        .container {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: var(--light);
        }
        
        .dashboard-header {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        
        .dashboard-title {
            font-size: 1.8em;
            color: var(--primary);
            margin: 0 0 15px 0;
            font-weight: 600;
            text-align: center;
        }
        
        .botoes-voltar {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .support-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        
        .support-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 600px; /* Para garantir que a tabela não fique muito estreita */
        }
        
        .support-table th {
            background-color: var(--primary);
            color: var(--white);
            padding: 12px;
            text-align: left;
            font-size: 0.9em;
        }
        
        .support-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 0.9em;
        }
        
        .support-table tr:hover {
            background-color: #f9f5ff;
        }
        
        .status-pending {
            color: #f59e0b;
            font-weight: 500;
        }
        
        .status-resolved {
            color: #10b981;
            font-weight: 500;
        }
        
        .response-box {
            background-color: var(--light);
            border-left: 4px solid var(--secondary);
            padding: 10px;
            margin-top: 8px;
            border-radius: 0 4px 4px 0;
            font-size: 0.85em;
        }
        
        .no-messages {
            text-align: center;
            padding: 30px;
            color: var(--text-light);
        }
        
        .new-ticket-btn {
            background-color: var(--secondary);
            color: var(--white);
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.9em;
        }
        
        .new-ticket-btn:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
        }
        
        /* Estilos para telas maiores */
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
            }
            
            .main-content {
                padding: 30px;
            }
            
            .dashboard-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            
            .dashboard-title {
                font-size: 2em;
                text-align: left;
                margin: 0;
            }
            
            .botoes-voltar {
                flex-direction: row;
                gap: 15px;
                margin-bottom: 0;
            }
            
            .support-container {
                padding: 30px;
            }
            
            .support-table th {
                padding: 15px;
                font-size: 1em;
            }
            
            .support-table td {
                padding: 15px;
                font-size: 1em;
            }
            
            .new-ticket-btn {
                padding: 12px 20px;
                font-size: 1em;
            }
        }
        
        /* Estilo para a tabela em mobile */
        @media (max-width: 767px) {
            .support-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Mensagens de Suporte</h1>
                <div class="botoes-voltar">
                    <a href="dashboard.php" class="new-ticket-btn">Voltar para dashboard</a>
                    <a href="../suporte/suporte.php" class="new-ticket-btn">Voltar para o Suporte</a>
                </div>
            </div>
            
            <div class="support-container">
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <table class="support-table">
                        <thead>
                            <tr>
                                <th>Assunto</th>
                                <th>Mensagem</th>
                                <th>Status</th>
                                <th>Resposta</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['assunto']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></td>
                                <td class="<?php echo $row['status'] == 'Respondido' ? 'status-resolved' : 'status-pending'; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['resposta'])): ?>
                                        <div class="response-box">
                                            <?php echo nl2br(htmlspecialchars($row['resposta'])); ?>
                                        </div>
                                    <?php else: ?>
                                        <em>Aguardando resposta...</em>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['data_envio'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-messages">
                        <p>Você ainda não enviou nenhuma mensagem de suporte.</p>
                        <a href="suporte.php" class="new-ticket-btn">Enviar minha primeira mensagem</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>