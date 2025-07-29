<?php include("conexao.php"); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pokémons Perdidos - Caçapava</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Registro de Pokémons Perdidos</h1>

  <!-- FORMULÁRIO DE CADASTRO -->
  <h2>Cadastrar Pokémon</h2>
  <form method="POST">
    <label>Nome*: <input type="text" name="nome" required></label><br>
    <label>Tipo: <input type="text" name="tipo"></label><br>
    <label>Localização Encontrada: <input type="text" name="local"></label><br>
    <label>Data do Registro: <input type="date" name="data"></label><br>
    <label>HP: <input type="number" name="hp"></label><br>
    <label>Ataque: <input type="number" name="ataque"></label><br>
    <label>Defesa: <input type="number" name="defesa"></label><br>
    <label>Observações: <textarea name="obs"></textarea></label><br>
    <input type="submit" name="cadastrar" value="Cadastrar Pokémon">
  </form>

  <?php
  // CADASTRO
  if (isset($_POST['cadastrar'])) {
    $nome = trim($_POST['nome']);
    $tipo = $_POST['tipo'];
    $local = $_POST['local'];
    $data = $_POST['data'];
    $hp = $_POST['hp'];
    $ataque = $_POST['ataque'];
    $defesa = $_POST['defesa'];
    $obs = $_POST['obs'];

    if (!empty($nome)) {
      $stmt = $conexao->prepare("INSERT INTO pokemons (nome, tipo, localizacao, data_registro, hp, ataque, defesa, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssiiis", $nome, $tipo, $local, $data, $hp, $ataque, $defesa, $obs);
      $stmt->execute();
      echo "<p style='color: green;'>Pokémon cadastrado com sucesso!</p>";
    } else {
      echo "<p style='color: red;'>O nome é obrigatório!</p>";
    }
  }
  ?>

  <!-- FORMULÁRIO DE PESQUISA -->
  <h2>Pesquisar Pokémon por nome</h2>
  <form method="GET">
    <input type="text" name="busca" placeholder="Digite o nome">
    <input type="submit" value="Pesquisar">
  </form>

  <!-- LISTAGEM -->
  <h2>Pokémons Encontrados</h2>
  <?php
  if (isset($_GET['busca'])) {
    $busca = "%" . $_GET['busca'] . "%";
    $stmt = $conexao->prepare("SELECT * FROM pokemons WHERE nome LIKE ?");
    $stmt->bind_param("s", $busca);
    $stmt->execute();
    $resultado = $stmt->get_result();
  } else {
    $resultado = $conexao->query("SELECT * FROM pokemons ORDER BY data_registro DESC");
  }

  if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
      echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
      echo "<strong>Nome:</strong> " . htmlspecialchars($row['nome']) . "<br>";
      echo "<strong>Tipo:</strong> " . htmlspecialchars($row['tipo']) . "<br>";
      echo "<strong>Local:</strong> " . htmlspecialchars($row['localizacao']) . "<br>";
      echo "<strong>Data:</strong> " . $row['data_registro'] . "<br>";
      echo "<strong>HP:</strong> " . $row['hp'] . " | ";
      echo "<strong>Ataque:</strong> " . $row['ataque'] . " | ";
      echo "<strong>Defesa:</strong> " . $row['defesa'] . "<br>";
      echo "<strong>Observações:</strong> " . htmlspecialchars($row['observacoes']) . "<br>";
      echo "</div>";
    }
  } else {
    echo "<p>Nenhum pokémon encontrado.</p>";
  }
  ?>
</body>
</html>
