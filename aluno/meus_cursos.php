<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'conexao.php';
$aluno_id = $_SESSION['id']; // ID do aluno que está logado

// Consulta para pegar os cursos que o aluno está inscrito
$cursos_inscritos = mysqli_query($conn, "
    SELECT c.titulo, c.descricao, i.data_inscricao
    FROM inscricao i
    JOIN curso c ON i.id_curso = c.id_curso
    WHERE i.id_aluno = '$aluno_id'
");

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Cursos - Code Masters</title>
    <link rel="stylesheet" href="estilos.css"> <!-- link para o seu arquivo de estilos -->
</head>

<body>
    <div class="container">
        <h1>Meus Cursos</h1>

        <?php if (mysqli_num_rows($cursos_inscritos) > 0) { ?>
            <table border="1" cellpadding="8">
                <tr><th>Título do Curso</th><th>Descrição</th><th>Data de Inscrição</th></tr>
                <?php
                while ($curso = mysqli_fetch_assoc($cursos_inscritos)) {
                    echo "<tr>
                        <td>{$curso['titulo']}</td>
                        <td>{$curso['descricao']}</td>
                        <td>{$curso['data_inscricao']}</td>
                    </tr>";
                }
                ?>
            </table>
        <?php } else { ?>
            <p>Você não está inscrito em nenhum curso.</p>
        <?php } ?>

        <p><a href="dashboar.php">Voltar ao painel</a></p>
    </div>
</body>
<style>
  body {
    font-family: Arial, sans-serif;
  
  background-color: #1f0660;
    color: #333;
    padding: 20px;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background-color: #1f0660;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    color: white;
}

h1 {
    color: white;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid greenyellow;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #4CAF50;
    color: white;
}

a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: white;
    font-weight: bold;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

</style>
</html>
