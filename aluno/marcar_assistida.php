<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

if (!isset($_POST['id_aula'])) {
    echo json_encode(['success' => false, 'message' => 'Aula não especificada']);
    exit();
}

$id_aluno = $_SESSION['id'];
$id_aula = $_POST['id_aula'];
$data_atual = date('Y-m-d H:i:s');

// esse mambo vai ver se o aluno já marcou a aula como assistida
$verifica = mysqli_query($conn, "SELECT * FROM aulas_assistidas WHERE id_aluno = '$id_aluno' AND id_aula = '$id_aula'");

if (mysqli_num_rows($verifica) > 0) {
    echo json_encode(['success' => true, 'message' => 'Aula já estava marcada como assistida']);
    exit();
}

$query = "INSERT INTO aulas_assistidas (id_aluno, id_aula, data_assistida, completo) 
          VALUES ('$id_aluno', '$id_aula', '$data_atual', 1)";

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Aula marcada como assistida']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . mysqli_error($conn)]);
}