<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'code_masters';
$conn = mysqli_connect($host, $usuario, $senha, $banco);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>