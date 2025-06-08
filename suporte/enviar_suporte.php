<?php
include 'conexao.php';
session_start();

$id_aluno = $_SESSION['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

$sql = "INSERT INTO suporte (id_aluno, nome, email, assunto, mensagem)
        VALUES ('$id_aluno', '$nome', '$email', '$assunto', '$mensagem')";

if (mysqli_query($conn, $sql)) {
    echo "Mensagem enviada com sucesso!";
    
    echo "<script>
        alert('Mensagem Enviada com SUCESSO!');
    </script>";
    header("Location: suporte.php");
} else {
    echo "Erro: " . mysqli_error($conexao);
}
?>
