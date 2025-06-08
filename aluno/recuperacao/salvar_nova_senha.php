<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['email_recuperacao']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: esqueci_senha.php');
    exit();
}

$email = $_SESSION['email_recuperacao'];
$codigo = $_POST['codigo'];
$nova_senha = $_POST['nova_senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Validações
if ($nova_senha !== $confirmar_senha) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    header('Location: restaurar_senha.php');
    exit();
}

if (strlen($nova_senha) < 8) {
    $_SESSION['erro'] = "A senha deve ter no mínimo 8 caracteres.";
    header('Location: restaurar_senha.php');
    exit();
}

// Verifica o código
$query = $conn->prepare("SELECT id FROM recuperacao_senha 
                        WHERE email = ? AND codigo = ? AND expiracao > NOW()");
$query->bind_param("ss", $email, $codigo);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    $_SESSION['erro'] = "Código inválido ou expirado.";
    header('Location: restaurar_senha.php');
    exit();
}

// Atualiza a senha
$senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE aluno SET senha_aluno = ? WHERE email_aluno = ?");
$update->bind_param("ss", $senha_hash, $email);
$update->execute();

// Limpa o código de recuperação
$delete = $conn->prepare("DELETE FROM recuperacao_senha WHERE email = ?");
$delete->bind_param("s", $email);
$delete->execute();

// Limpa a sessão e redireciona
unset($_SESSION['email_recuperacao']);
$_SESSION['sucesso'] = "Senha alterada com sucesso!";
header('Location: ../login.php');
exit();
?>