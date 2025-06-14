<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $curso_id = (int) $_GET['id'];

    // Soft delete das aulas relacionadas (se tiver campo 'ativo')
    mysqli_query($conn, "UPDATE aula SET ativo = 0 WHERE id_curso = $curso_id");

    // Soft delete das inscrições relacionadas (se tiver campo 'ativo')
    mysqli_query($conn, "UPDATE inscricao SET ativo = 0 WHERE id_curso = $curso_id");

    // Soft delete do curso
    $query = "UPDATE curso SET ativo = 0 WHERE id_curso = $curso_id";
    $resultado = mysqli_query($conn, $query);

    if ($resultado) {
        header("Location: painel_admin.php?mensagem=Curso+removido+com+sucesso");
        exit();
    } else {
        echo "Erro ao remover o curso: " . mysqli_error($conn);
    }
} else {
    echo "ID do curso não foi especificado.";
}
?>
