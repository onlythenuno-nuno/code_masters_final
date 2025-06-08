<?php
session_start();
include 'conexao.php';

require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: esqueci_senha.php');
    exit();
}

$email = $_POST['email'];

// Verifica se o email existe no banco de dados
$query = $conn->prepare("SELECT id_aluno FROM aluno WHERE email_aluno = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    $_SESSION['erro'] = "Email não encontrado.";
    header('Location: esqueci_senha.php');
    exit();
}

// Gera código de 6 dígitos e data de expiração
$codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expiracao = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// Remove códigos antigos para este email
$delete = $conn->prepare("DELETE FROM recuperacao_senha WHERE email = ?");
$delete->bind_param("s", $email);
$delete->execute();

// Insere o novo código
$insert = $conn->prepare("INSERT INTO recuperacao_senha (email, codigo, expiracao) VALUES (?, ?, ?)");
$insert->bind_param("sss", $email, $codigo, $expiracao);
$insert->execute();

// Envio de email com PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nunocassule2004@gmail.com';         // 🔁 Substitua pelo seu e-mail Gmail
    $mail->Password   = 'bqvqktqkajupmbpz';    // 🔁 Substitua pela senha de aplicativo
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('seuemail@gmail.com', 'Suporte CodeMasters'); // 🔁 Pode personalizar
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Código de Recuperação - CodeMasters";
    $mail->Body    = "
        <p>Olá!</p>
        <p>Seu código de recuperação é: <strong>$codigo</strong></p>
        <p>Este código expira em 15 minutos.</p>
        <p>Se você não solicitou esta alteração, ignore este e-mail.</p>
        <br>
        <p>Equipe CodeMasters</p>
    ";

    $mail->send();
    $_SESSION['email_recuperacao'] = $email;
    $_SESSION['sucesso'] = "Código enviado para seu email!";
    header('Location: restaurar_senha.php');
} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao enviar email: {$mail->ErrorInfo}";
    header('Location: esqueci_senha.php');
}

exit();
