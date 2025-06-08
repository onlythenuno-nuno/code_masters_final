<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

include 'conexao.php';

if (!isset($_SESSION['id']) || !isset($_POST['id_evento'])) {
    die('Acesso inválido');
}

$id_evento = intval($_POST['id_evento']);
$id_aluno = $_SESSION['id'];

$query = "INSERT INTO eventos_participantes (id_aluno, id_eventos, data_confirmacao)
          VALUES (?, ?, NOW())";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_aluno, $id_evento);
$stmt->execute();

header('Location: eventos.php');
exit();
?>