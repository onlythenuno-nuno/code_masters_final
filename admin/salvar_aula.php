<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';

// Exibir erros 
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_curso = $_POST['id_curso'];
    $titulo= $_POST['titulo'];
    $link_video = isset($_POST['link_video']) ? $_POST['link_video'] : '';
    $link_pdf = isset($_POST['link_pdf']) ? $_POST['link_pdf'] : '';
    $ordem = isset($_POST['ordem']) ? $_POST['ordem'] : 0;

    
    $id_curso = mysqli_real_escape_string($conn, $id_curso);
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $link_video = mysqli_real_escape_string($conn, $link_video);
    $link_pdf = mysqli_real_escape_string($conn, $link_pdf);
    $ordem = mysqli_real_escape_string($conn, $ordem);

    
    $sql = "INSERT INTO aula (id_curso, titulo, link_video, link_pdf, ordem)
            VALUES ('$id_curso', '$titulo', '$link_video', '$link_pdf', '$ordem')";

    if (mysqli_query($conn, $sql)) {
        header("Location: painel_admin.php?mensagem=Aula cadastrada com sucesso");
        exit(); 
    } else {
        echo "Erro ao salvar aula: " . mysqli_error($conn);
    }
} else {
    header("Location: painel_admin.php");
    exit();
}
?>
