<?php
session_start(); 
include '../config/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = $conn->query("SELECT * FROM aluno WHERE email_aluno='$email'");
    
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        
        if (password_verify($senha, $row['senha_aluno'])) {
            $_SESSION['id'] = $row['id_aluno'];  
            $_SESSION['nome_aluno'] = $row['nome_aluno'];
            header('Location: dashboard.php');
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Email não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Code Masters</title>
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
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
        
        .login-section {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--dark) 100%);
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
        
        .login-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .register-btn {
            background-color: var(--light);
            color: var(--text);
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-top: 10px;
            border: 2px solid var(--secondary);
        }
        
        .register-btn:hover {
            background-color: var(--secondary);
            color: white;
        }
        
        .social-login {
            margin-top: 30px;
            text-align: center;
        }
        
        .social-title {
            color: var(--text-light);
            margin-bottom: 15px;
            font-size: 0.9rem;
            position: relative;
        }
        
        .social-title::before,
        .social-title::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #eee;
            margin: auto;
        }
        
        .social-title span {
            padding: 0 10px;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-icons a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }
        
        .social-icons img {
            width: 20px;
            height: auto;
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
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .welcome-section, .login-section {
                padding: 30px;
            }
            
            .welcome-section {
                order: 2;
            }
            
            .login-section {
                order: 1;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="welcome-section">
            <h1>Bem-vindo à CodeMasters!</h1>
            <p>Parabéns por chegar até aqui! Seu interesse em se tornar um desenvolvedor já mostra que você está no caminho certo.</p>
            <a href="./cadastro.php" class="register-btn">Criar nova conta</a>
            <a href="recuperacao/esqueci_senha.php" class="register-btn">Esqueceu Sua Senha?</a>
        </div>
        
        <div class="login-section">
            <div class="logo">
                <a href="../index.php"><img src="../logo-grande/logo.png" alt="CodeMasters"></a>
            </div>
            
            <h2 class="form-title">Faça seu login</h2>
            <p class="form-subtitle">Entre com suas credenciais para acessar sua conta</p>
            
            <?php if (isset($erro)) echo "<div class='erro-box'>$erro</div>"; ?>
            
            <form method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Seu e-mail" required>
                </div>
                
                <div class="input-group">
                    <input type="password" name="senha" placeholder="Sua senha" required>
                </div>
                
                <button type="submit" name="login" class="login-btn">Entrar</button>
            </form>
            
            <div class="social-login">
                
            </div>
        </div>
    </div>
</body>
</html>