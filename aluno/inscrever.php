<?php
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para se inscrever.";
    exit();
}

include 'conexao.php';

$id_aluno = $_SESSION['id'];
$id_curso = $_POST['curso_id']; 
$verifica = mysqli_query($conn, "SELECT * FROM inscricao WHERE id_aluno = '$id_aluno' AND id_curso = '$id_curso'");
if (mysqli_num_rows($verifica) == 0) {
    $data = date("Y-m-d");
    mysqli_query($conn, "INSERT INTO inscricao (id_aluno, id_curso, data_inscricao) VALUES ('$id_aluno', '$id_curso', '$data')");
}

header("Location: dashboar.php"); 
exit();
?>
