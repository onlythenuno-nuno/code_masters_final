<?php
include 'conexao.php';


if (isset($_GET['id'])) {
    $curso_id = (int) $_GET['id']; 

    
    mysqli_query($conn, "DELETE FROM aula WHERE id_curso = $curso_id");

    mysqli_query($conn, "DELETE FROM inscricao WHERE id_curso = $curso_id");

    $query = "DELETE FROM curso WHERE id_curso = $curso_id";
    $resultado = mysqli_query($conn, $query);

    if ($resultado) {
        echo "Curso excluído com sucesso.";
        header("location: painel_admin.php");
    } else {
        echo "Erro ao excluir o curso: " . mysqli_error($conn);
    }
} else {
    echo "ID do curso não foi especificado.";
}
?>
