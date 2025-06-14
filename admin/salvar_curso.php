<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];


$stmt = $conn->prepare("INSERT INTO curso (titulo, descricao) VALUES (?, ?)");
if ($stmt === false) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("ss", $titulo, $descricao);

if ($stmt->execute()) {
    header("Location: painel_admin.php");
    exit();
} else {
    echo "Erro ao salvar curso: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
