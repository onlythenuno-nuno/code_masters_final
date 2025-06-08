<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = mysqli_prepare($conn, "SELECT * FROM administrador WHERE email_admin = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        $senha_valida = false;

        if ($admin['nivel'] == 'super') {
            if ($password === $admin['senha_admin']) {
                $senha_valida = true;
            }
        } else {
            if (password_verify($password, $admin['senha_admin'])) {
                $senha_valida = true;
            }
        }

        if ($senha_valida) {
            $_SESSION['admin_nivel'] = $admin['nivel']; 
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_nome'] = $admin['nome_admin'];
            $_SESSION['admin_logado'] = true;

            if ($admin['nivel'] == 'super') {
                header("Location: painel_admin.php");
            } else {
                header("Location: admin_comum/painel_admin_comum.php");
            }
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
    <title>Login Admin - Code Masters</title>
    <style>
        :root {
            --primary: #170448;
            --secondary: #39ff14; /* Verde neon para destaque admin */
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #0f0235; /* Tom mais escuro que o original */
            --text: #ffffff;
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
            background-color: var(--dark); /* Fundo mais escuro */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid var(--secondary); /* Borda neon */
        }
        
        .welcome-section {
            flex: 1;
            padding: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, rgba(57, 255, 20, 0.1) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid rgba(57, 255, 20, 0.2);
        }
        
        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            color: var(--secondary); /* Título em verde neon */
        }
        
        .welcome-section p {
            font-size: 1rem;
            margin-bottom: 30px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .login-section {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: rgba(15, 2, 53, 0.9); /* Tom ainda mais escuro */
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            max-width: 180px;
            height: auto;
            filter: brightness(0) invert(1); /* Garante que o logo fique branco */
        }
        
        .form-title {
            font-size: 1.8rem;
            color: var(--secondary); /* Verde neon */
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
            border: 2px solid rgba(57, 255, 20, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: rgba(15, 2, 53, 0.7);
            color: white;
        }
        
        .input-group input:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(57, 255, 20, 0.2);
        }
        
        .input-group input::placeholder {
            color: #aaa;
        }
        
        .login-btn {
            background-color: var(--secondary);
            color: var(--dark);
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background-color: transparent;
            color: var(--secondary);
            border: 2px solid var(--secondary);
            transform: translateY(-2px);
        }
        
        .erro-box {
            background-color: rgba(244, 63, 94, 0.2);
            color: var(--accent);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
            border-left: 4px solid var(--accent);
        }
        
        .back-btn {
            background-color: transparent;
            color: var(--secondary);
            border: 2px solid var(--secondary);
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
        }
        
        .back-btn:hover {
            background-color: var(--secondary);
            color: var(--dark);
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
            <h1>Área Administrativa</h1>
            <p>Acesso restrito à equipe Code Masters. Por favor, autentique-se com suas credenciais de administrador.</p>
            <a href="../index.php" class="back-btn">Voltar ao Site</a>
        </div>
        
        <div class="login-section">
            <div class="logo">
                <a href="../index.php"><img src="../logo-grande/logo.png" alt="CodeMasters"></a>
            </div>
            
            <h2 class="form-title">Login Admin</h2>
            <p class="form-subtitle">Use suas credenciais administrativas</p>
            
            <?php if (isset($erro)) echo "<div class='erro-box'>$erro</div>"; ?>
            
            <form method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Seu e-mail administrativo" required>
                </div>
                
                <div class="input-group">
                    <input type="password" name="password" placeholder="Sua senha" required>
                </div>
                
                <button type="submit" class="login-btn">Acessar Painel</button>
            </form>
        </div>
    </div>
</body>
</html>