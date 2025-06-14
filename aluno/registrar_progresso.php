<?php
include 'conexao.php'; // Conexão com o banco
session_start();

$id_aluno = $_SESSION['aluno_id'];
$id_aula = $_POST['id_aula'];
$id_curso = $_POST['id_curso'];

// Verificar se já foi marcado
$verifica = mysqli_query($conn, "SELECT * FROM progresso WHERE id_aluno = $id_aluno AND id_aula = $id_aula");

if (mysqli_num_rows($verifica) == 0) {
    mysqli_query($conn, "INSERT INTO progresso (id_aluno, id_curso, id_aula, concluido) VALUES ($id_aluno, $id_curso, $id_aula, 1)");
}

header("Location: leitor_video.php?id_aula=$id_aula&id_curso=$id_curso&status=concluido");
exit;
?>
