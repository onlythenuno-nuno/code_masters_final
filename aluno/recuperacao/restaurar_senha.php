<?php
session_start();
include 'conexao.php';

// Redireciona se não houver email na sessão
if (!isset($_SESSION['email_recuperacao'])) {
    header('Location: esqueci_senha.php');
    exit();
}

$email = $_SESSION['email_recuperacao'];

// Verifica se o email ainda existe no banco (caso o usuário tenha sido deletado)
$query = $conn->prepare("SELECT id_aluno FROM aluno WHERE email_aluno = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    unset($_SESSION['email_recuperacao']);
    $_SESSION['erro'] = "Email não encontrado. Por favor, solicite um novo código.";
    header('Location: esqueci_senha.php');
    exit();
}

// Mostra mensagens de erro/sucesso
$erro = isset($_SESSION['erro']) ? $_SESSION['erro'] : null;
unset($_SESSION['erro']);

$sucesso = isset($_SESSION['sucesso']) ? $_SESSION['sucesso'] : null;
unset($_SESSION['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Code Masters</title>
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
        
        .password-strength {
            margin-top: 5px;
            height: 5px;
            background-color: #eee;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s, background-color 0.3s;
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
            <h1>Redefina sua senha</h1>
            <p>Verifique seu email (<strong><?php echo htmlspecialchars($email); ?></strong>) e insira o código de 6 dígitos que enviamos, junto com sua nova senha.</p>
            <a href="login.php" class="back-btn">Voltar ao login</a>
        </div>
        
        <div class="login-section">
            <div class="logo">
                <a href="../index.php"><img src="../../logo-grande/logo.png" alt="CodeMasters"></a>
            </div>
            
            <h2 class="form-title">Redefinir senha</h2>
            <p class="form-subtitle">Digite o código e sua nova senha</p>
            
            <?php if ($erro): ?>
                <div class='erro-box'><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <?php if ($sucesso): ?>
                <div class='success-box'><?php echo $sucesso; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="salvar_nova_senha.php" onsubmit="return validarSenha()">
                <div class="input-group">
                    <input type="text" name="codigo" placeholder="Código de 6 dígitos" required maxlength="6" pattern="\d{6}" title="Digite exatamente 6 dígitos">
                </div>
                
                <div class="input-group">
                    <input type="password" name="nova_senha" id="nova_senha" placeholder="Nova senha (mínimo 8 caracteres)" required minlength="8">
                    <div class="password-strength">
                        <div class="strength-meter" id="strength-meter"></div>
                    </div>
                </div>
                
                <div class="input-group">
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar nova senha" required minlength="8">
                </div>
                
                <button type="submit" name="verificar" class="login-btn">Redefinir Senha</button>
            </form>
        </div>
    </div>

    <script>
        // Validação de força da senha
        const senhaInput = document.getElementById('nova_senha');
        const meter = document.getElementById('strength-meter');
        
        senhaInput.addEventListener('input', function() {
            const senha = this.value;
            let strength = 0;
            
            // Verifica o comprimento
            if (senha.length >= 8) strength += 1;
            if (senha.length >= 12) strength += 1;
            
            // Verifica caracteres especiais
            if (/[!@#$%^&*(),.?":{}|<>]/.test(senha)) strength += 1;
            
            // Verifica números
            if (/\d/.test(senha)) strength += 1;
            
            // Verifica letras maiúsculas e minúsculas
            if (/[a-z]/.test(senha) && /[A-Z]/.test(senha)) strength += 1;
            
            // Atualiza o medidor
            const width = strength * 20;
            let color = '#ff4444'; // Vermelho
            
            if (strength >= 3) color = '#ffbb33'; // Amarelo
            if (strength >= 4) color = '#00C851'; // Verde
            
            meter.style.width = width + '%';
            meter.style.backgroundColor = color;
        });
        
        // Validação do formulário
        function validarSenha() {
            const senha = document.getElementById('nova_senha').value;
            const confirmar = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmar) {
                alert('As senhas não coincidem!');
                return false;
            }
            
            if (senha.length < 8) {
                alert('A senha deve ter no mínimo 8 caracteres!');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>