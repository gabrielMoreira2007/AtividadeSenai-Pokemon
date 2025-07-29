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

  <!-- RELATÓRIO DE ESTATÍSTICAS -->
  <h2>Relatório de Estatísticas</h2>
  <?php
  $resumo = $conexao->query("SELECT tipo, COUNT(*) as total FROM pokemons GROUP BY tipo");
  if ($resumo->num_rows > 0) {
    echo "<ul>";
    while ($r = $resumo->fetch_assoc()) {
      echo "<li><strong>" . htmlspecialchars($r['tipo']) . ":</strong> " . $r['total'] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Nenhum Pokémon cadastrado ainda.</p>";
  }
  ?>

  <!-- FORMULÁRIO DE CADASTRO -->
  <h2><?php echo isset($_GET['editar']) ? "Editar Pokémon" : "Cadastrar Pokémon"; ?></h2>
  <form method="POST" enctype="multipart/form-data">
    <label>Nome*: <input type="text" name="nome" required value="<?php echo isset($editRow) ? htmlspecialchars($editRow['nome']) : ''; ?>"></label><br>
    <label>Tipo: <input type="text" name="tipo" value="<?php echo isset($editRow) ? htmlspecialchars($editRow['tipo']) : ''; ?>"></label><br>
    <label>Localização Encontrada: <input type="text" name="local" value="<?php echo isset($editRow) ? htmlspecialchars($editRow['localizacao']) : ''; ?>"></label><br>
    <label>Data do Registro: <input type="date" name="data" value="<?php echo isset($editRow) ? $editRow['data_registro'] : ''; ?>"></label><br>
    <label>HP: <input type="number" name="hp" value="<?php echo isset($editRow) ? $editRow['hp'] : ''; ?>"></label><br>
    <label>Ataque: <input type="number" name="ataque" value="<?php echo isset($editRow) ? $editRow['ataque'] : ''; ?>"></label><br>
    <label>Defesa: <input type="number" name="defesa" value="<?php echo isset($editRow) ? $editRow['defesa'] : ''; ?>"></label><br>
    <label>Observações: <textarea name="obs"><?php echo isset($editRow) ? htmlspecialchars($editRow['observacoes']) : ''; ?></textarea></label><br>
    <label>Foto (upload): <input type="file" name="foto"></label><br>
    <label>Ou link da foto: <input type="text" name="foto_link" value="<?php echo isset($editRow) ? htmlspecialchars($editRow['foto']) : ''; ?>"></label><br>
    <?php if (isset($_GET['editar'])): ?>
      <input type="hidden" name="id_editar" value="<?php echo intval($_GET['editar']); ?>">
      <input type="submit" name="atualizar" value="Atualizar Pokémon">
      <a href="index.php">Cancelar</a>
    <?php else: ?>
      <input type="submit" name="cadastrar" value="Cadastrar Pokémon">
    <?php endif; ?>
  </form>

  <?php
  // EXCLUSÃO
  if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $conexao->query("DELETE FROM pokemons WHERE id=$id");
    echo "<p style='color: green;'>Pokémon excluído com sucesso!</p>";
  }

  // EDIÇÃO - BUSCA DADOS
  if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $res = $conexao->query("SELECT * FROM pokemons WHERE id=$id");
    $editRow = $res->fetch_assoc();
  }

  // ATUALIZAÇÃO
  if (isset($_POST['atualizar'])) {
    $id = intval($_POST['id_editar']);
    $nome = trim($_POST['nome']);
    $tipo = $_POST['tipo'];
    $local = $_POST['local'];
    $data = $_POST['data'];
    $hp = $_POST['hp'];
    $ataque = $_POST['ataque'];
    $defesa = $_POST['defesa'];
    $obs = $_POST['obs'];
    $foto = "";

    // Upload de foto
    if (!empty($_FILES['foto']['name'])) {
      $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
      $foto = "fotos/" . uniqid() . "." . $ext;
      move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    } elseif (!empty($_POST['foto_link'])) {
      $foto = $_POST['foto_link'];
    } else {
      $foto = $conexao->query("SELECT foto FROM pokemons WHERE id=$id")->fetch_assoc()['foto'];
    }

    $stmt = $conexao->prepare("UPDATE pokemons SET nome=?, tipo=?, localizacao=?, data_registro=?, hp=?, ataque=?, defesa=?, observacoes=?, foto=? WHERE id=?");
    $stmt->bind_param("ssssiiissi", $nome, $tipo, $local, $data, $hp, $ataque, $defesa, $obs, $foto, $id);
    $stmt->execute();
    echo "<p style='color: green;'>Pokémon atualizado com sucesso!</p>";
  }

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
    $foto = "";

    // Upload de foto
    if (!empty($_FILES['foto']['name'])) {
      if (!is_dir("fotos")) mkdir("fotos");
      $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
      $foto = "fotos/" . uniqid() . "." . $ext;
      move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    } elseif (!empty($_POST['foto_link'])) {
      $foto = $_POST['foto_link'];
    }

    if (!empty($nome)) {
      $stmt = $conexao->prepare("INSERT INTO pokemons (nome, tipo, localizacao, data_registro, hp, ataque, defesa, observacoes, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssiiiss", $nome, $tipo, $local, $data, $hp, $ataque, $defesa, $obs, $foto);
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
      if (!empty($row['foto'])) {
        if (filter_var($row['foto'], FILTER_VALIDATE_URL)) {
          echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto' style='max-width:120px;'><br>";
        } else {
          echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto' style='max-width:120px;'><br>";
        }
      }
      echo "<strong>Nome:</strong> " . htmlspecialchars($row['nome']) . "<br>";
      echo "<strong>Tipo:</strong> " . htmlspecialchars($row['tipo']) . "<br>";
      echo "<strong>Local:</strong> " . htmlspecialchars($row['localizacao']) . "<br>";
      echo "<strong>Data:</strong> " . $row['data_registro'] . "<br>";
      echo "<strong>HP:</strong> " . $row['hp'] . " | ";
      echo "<strong>Ataque:</strong> " . $row['ataque'] . " | ";
      echo "<strong>Defesa:</strong> " . $row['defesa'] . "<br>";
      echo "<strong>Observações:</strong> " . htmlspecialchars($row['observacoes']) . "<br>";
      echo "<a href='?editar=" . $row['id'] . "' style='color:blue;'>Editar</a> | ";
      echo "<a href='?excluir=" . $row['id'] . "' style='color:red;' onclick=\"return confirm('Tem certeza que deseja excluir?');\">Excluir</a>";
      echo "</div>";
    }
  } else {
    echo "<p>Nenhum pokémon encontrado.</p>";
  }
  ?>
</body>
</html>
