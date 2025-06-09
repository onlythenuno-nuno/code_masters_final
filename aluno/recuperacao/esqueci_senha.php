<?php
session_start();
include 'conexao.php';

require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$erro = $_SESSION['erro'] ?? null;
unset($_SESSION['erro']);

if (isset($_POST['enviar'])) {
    $email = $_POST['email'];

    $query = $conn->prepare("SELECT id_aluno FROM aluno WHERE email_aluno = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        $erro = "Email não encontrado.";
    } else {
        $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiracao = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $delete = $conn->prepare("DELETE FROM recuperacao_senha WHERE email = ?");
        $delete->bind_param("s", $email);
        $delete->execute();

        $insert = $conn->prepare("INSERT INTO recuperacao_senha (email, codigo, expiracao) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $email, $codigo, $expiracao);
        $insert->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nunocassule2004@gmail.com';
            $mail->Password = 'bqvqktqkajupmbpz'; // senha de app
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('nunocassule2004@gmail.com', 'CodeMasters');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Código de Recuperação - CodeMasters";
            $mail->Body = "
                <p>Olá!</p>
                <p>Seu código de recuperação é: <strong>$codigo</strong></p>
                <p>Este código expira em 15 minutos.</p>
                <p>Se você não solicitou isso, apenas ignore.</p>
                <br>
                <p>Equipe CodeMasters</p>
            ";

            $mail->send();

            $_SESSION['email_recuperacao'] = $email;
            header('Location: restaurar_senha.php');
            exit();
        } catch (Exception $e) {
            $erro = "Erro ao enviar email: {$mail->ErrorInfo}";
        }
    }
}
?>
<!-- HTML continua abaixo normalmente (sem alterações no visual) -->

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Code Masters</title>
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
            padding: 50px;
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
            padding: 50px;
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
        
        .back-btn {
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
        
        .back-btn:hover {
            background-color: var(--secondary);
            color: white;
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
        
        .success-box {
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
            <h1>Problemas para entrar?</h1>
            <p>Digite seu email e enviaremos um código de verificação para você redefinir sua senha.</p>
            <a href="../login.php" class="back-btn">Voltar ao login</a>
        </div>
        
        <div class="login-section">
            <div class="logo">
                <a href="../index.php"><img src="../../logo-grande/logo.png" alt="CodeMasters"></a>
            </div>
            
            <h2 class="form-title">Recuperar senha</h2>
            <p class="form-subtitle">Digite seu email para receber o código de verificação</p>
            
            <?php if (isset($erro)) echo "<div class='erro-box'>$erro</div>"; ?>
            
            <form method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Seu e-mail cadastrado" required>
                </div>
                
                <button type="submit" name="enviar" class="login-btn">Enviar Código</button>
            </form>
        </div>
    </div>
</body>
</html>