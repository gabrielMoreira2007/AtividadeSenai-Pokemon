<!-- Gabriel Moreira e Rian -->
<?php
$host = "localhost";
$user = "root";
$senha = "";
$banco = "pokemon_db";

$conexao = new mysqli($host, $user, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
