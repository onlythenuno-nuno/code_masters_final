<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

if (!isset($_POST['id_aula'])) {
    echo json_encode(['success' => false, 'message' => 'ID da aula não fornecido.']);
    exit();
}

$id_aula = intval($_POST['id_aula']);
$id_aluno = intval($_SESSION['id']);
$data = date('Y-m-d H:i:s');

require 'conexao.php'; // ou 'db.php', dependendo do seu projeto

// Verifica se a aula já foi marcada como assistida
$query = "SELECT * FROM aulas_assistidas WHERE id_aluno = ? AND id_aula = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_aluno, $id_aula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Aula já foi marcada anteriormente.']);
    exit();
}

// Insere como assistida
$query = "INSERT INTO aulas_assistidas (id_aluno, id_aula, data_assistida, completo) VALUES (?, ?, ?, 1)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $id_aluno, $id_aula, $data);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Aula marcada como assistida.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao marcar aula: ' . $stmt->error]);
}
?>
