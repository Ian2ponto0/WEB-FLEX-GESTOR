<?php
include_once __DIR__ . '/../common/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome        = $_POST['nome'] ?? '';
  $cargo       = $_POST['cargo'] ?? '';
  $departamento= $_POST['departamento'] ?? '';
  $salario     = $_POST['salario'] ?? 0;
  $email       = $_POST['email'] ?? '';
  $telefone    = $_POST['telefone'] ?? '';
  $obs         = $_POST['obs'] ?? '';

  $stmt = $mysqli->prepare("INSERT INTO funcionarios 
    (nome, cargo, departamento, salario, email, telefone, obs)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param("sssisss", 
    $nome, $cargo, $departamento, $salario, $email, $telefone, $obs
  );

  $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Funcionários – Web Flex Gestor</title>
  <link rel="stylesheet" href="../common/stylepadrao.css">
</head>
<body>
  <div class="sidebar">
    <h2>Web Flex Gestor</h2>
    <ul>
      <li><a href="../tela_inicial/telainicial.html">🏠 Dashboard</a></li>
      <li><a href="../clientes/clientes.php">👥 Clientes</a></li>
      <li><a href="../produtos/produtos.php">📦 Produtos</a></li>
      <li><a href="../vendas/vendas.html">🛒 Vendas</a></li>
      <li><a href="../projetos/projetos.php">📂 Projetos</a></li>
      <li><a href="../financeiro/financeiro.php">💰 Financeiro</a></li>
      <li><a href="funcionarios.php">👷 Funcionários</a></li>
    </ul>
  </div>
  <div class="main">
    <h1>👷 Cadastro de Funcionários</h1>
    <form method="POST">
      <label>Nome *</label>
      <input type="text" name="nome" required>

      <label>Cargo *</label>
      <input type="text" name="cargo" required>

      <label>Departamento</label>
      <input type="text" name="departamento">

      <label>Salário</label>
      <input type="number" name="salario" step="0.01">

      <label>E-mail</label>
      <input type="email" name="email">

      <label>Telefone</label>
      <input type="tel" name="telefone">

      <label>Observações</label>
      <textarea name="obs" rows="2"></textarea>

      <button type="submit">Salvar Funcionário</button>
    </form>

    <h2>Lista de Funcionários</h2>
    <table>
      <thead>
        <tr><th>Nome</th><th>Cargo</th><th>Dept.</th><th>Salário</th><th>Detalhes</th></tr>
      </thead>
      <tbody>
        <?php
        $res = $mysqli->query("SELECT * FROM funcionarios ORDER BY id DESC");
        while ($f = $res->fetch_assoc()) {
          echo "<tr>
                  <td>{$f['nome']}</td>
                  <td>{$f['cargo']}</td>
                  <td>{$f['departamento']}</td>
                  <td>R$ " . number_format($f['salario'], 2, ',', '.') . "</td>
                  <td>
                    <button onclick=\"alert('Funcionário: {$f['nome']}\\nEmail: {$f['email']}\\nTelefone: {$f['telefone']}\\nObservações: {$f['obs']}')\">Ver</button>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
