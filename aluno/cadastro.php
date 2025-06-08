<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config/db.php'; 

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id_aluno FROM aluno WHERE email_aluno = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $erro = "Este email já está associado a uma conta.";
    } else {
        $stmt = $conn->prepare("INSERT INTO aluno (nome_aluno, email_aluno, senha_aluno) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            $sucesso = "Cadastro realizado com sucesso! <a href='login.php' style='color: var(--secondary);'>Fazer login</a>";
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - CodeMasters</title>
    <style>
        :root {
            --primary: #4c1d95;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
            --white: #ffffff;         /* Branco */
            --roxo_menu: #1f0660;
            --esverdeado: #39ff14;
            --background: #170448;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(180deg, var(--background) 0%, var(--primary) 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .register-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .welcome-section {
            flex: 1;
            padding: 10px 50px;
            color: var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .welcome-section p {
            font-size: 1rem;
            margin-bottom: 30px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .register-section {
            background: linear-gradient(135deg, var(--dark) 0%, var(--secondary) 100%);
            flex: 1;
            padding: 10px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .logo img {
            max-width: 180px;
            height: auto;
        }
        
        .form-title {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 10px;
            font-weight: 600;
            text-align: center;
        }
        
        .form-subtitle {
            color: var(--text-light);
            text-align: center;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        .input-group input:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        
        .input-group input::placeholder {
            color: #aaa;
        }
        
        .register-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .register-btn:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text);
        }
        
        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .erro-box {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
            border-left: 4px solid #c62828;
        }
        
        .sucesso-box {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
            border-left: 4px solid #2e7d32;
        }
        
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }
            
            .welcome-section, .register-section {
                padding: 30px;
            }
            
            .welcome-section {
                order: 2;
            }
            
            .register-section {
                order: 1;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="welcome-section">
            <h1>Junte-se à CodeMasters!</h1>
            <p>Comece sua jornada como desenvolvedor hoje mesmo. Crie sua conta e tenha acesso aos melhores cursos de programação.</p>
            <div class="login-link">
                Já tem uma conta? <a href="login.php">Faça login</a>
            </div>
        </div>
        
        <div class="register-section">
            <div class="logo">
                <a href="../index.php"><img src="../logo-grande/logo.png" alt="CodeMasters"></a>
            </div>
            
            <h2 class="form-title">Criar nova conta</h2>
            <p class="form-subtitle">Preencha seus dados para se registrar</p>
            
            <?php if (isset($erro)) echo "<div class='erro-box'>$erro</div>"; ?>
            <?php if (isset($sucesso)) echo "<div class='sucesso-box'>$sucesso</div>"; ?>
            
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="nome" placeholder="Nome completo" required>
                </div>
                
                <div class="input-group">
                    <input type="email" name="email" placeholder="Seu e-mail" required>
                </div>
                
                <div class="input-group">
                    <input type="password" name="senha" placeholder="Crie uma senha" required>
                </div>
                
                <button type="submit" name="cadastrar" class="register-btn">Cadastrar</button>
            </form>
            
        </div>
        
    </div>
</body>
</html>