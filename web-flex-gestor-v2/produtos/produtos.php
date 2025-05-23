<?php
include_once __DIR__ . '/../common/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codigo         = $_POST['codigo'] ?? '';
  $nome           = $_POST['nome'] ?? '';
  $descricao      = $_POST['descricao'] ?? '';
  $categoria      = $_POST['categoria'] ?? '';
  $subcategoria   = $_POST['subcategoria'] ?? '';
  $fornecedor     = $_POST['fornecedor'] ?? '';
  $ncm_sh         = $_POST['ncm_sh'] ?? '';
  $modelo         = $_POST['modelo'] ?? '';
  $unidade        = $_POST['unidade'] ?? '';
  $peso_gramas    = $_POST['peso_gramas'] ?? 0;
  $estoqueInicial = $_POST['estoqueInicial'] ?? 0;
  $estoqueAtual   = $_POST['estoqueAtual'] ?? 0;
  $estoqueMinimo  = $_POST['estoqueMinimo'] ?? 0;
  $precoCusto     = $_POST['precoCusto'] ?? 0;
  $precoVenda     = $_POST['precoVenda'] ?? 0;
  $observacoes    = $_POST['observacoes'] ?? '';
  $atributos      = isset($_POST['atributos']) ? json_encode($_POST['atributos']) : '';

  $stmt = $mysqli->prepare("INSERT INTO produtos (
    codigo, nome, descricao, categoria, subcategoria, fornecedor,
    ncm_sh, modelo, unidade, peso_gramas, estoque_inicial, estoque_atual,
    estoque_minimo, preco_custo, preco_venda, observacoes, atributos
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param(
    "ssssssssdiiiddsss",
    $codigo, $nome, $descricao, $categoria, $subcategoria,
    $fornecedor, $ncm_sh, $modelo, $unidade, $peso_gramas,
    $estoqueInicial, $estoqueAtual, $estoqueMinimo,
    $precoCusto, $precoVenda, $observacoes, $atributos
  );

  $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Produtos – Web Flex Gestor</title>
  <link rel="stylesheet" href="../common/stylepadrao.css">
</head>
<body>
  <div class="sidebar">
    <h2>Web Flex Gestor</h2>
    <ul>
      <li><a href="../tela_inicial/telainicial.html">🏠 Dashboard</a></li>
      <li><a href="../clientes/clientes.php">👥 Clientes</a></li>
      <li><a href="produtos.php">📦 Produtos</a></li>
      <li><a href="../vendas/vendas.html">🛒 Vendas</a></li>
      <li><a href="../projetos/projetos.php">📂 Projetos</a></li>
      <li><a href="../financeiro/financeiro.php">💰 Financeiro</a></li>
      <li><a href="../funcionarios/funcionarios.php">👷 Funcionários</a></li>
    </ul>
  </div>

  <div class="main">
    <h1>📦 Cadastro de Produtos</h1>
    <form method="POST">
      <label>Código do Produto *</label>
      <input type="text" name="codigo" required>
      <label>Nome *</label>
      <input type="text" name="nome" required>
      <label>Descrição</label>
      <textarea name="descricao" rows="2"></textarea>
      <label>Categoria</label>
      <input type="text" name="categoria">
      <label>Subcategoria</label>
      <input type="text" name="subcategoria">
      <label>Fornecedor</label>
      <input type="text" name="fornecedor">
      <label>NCM/SH</label>
      <input type="text" name="ncm_sh">
      <label>Modelo</label>
      <input type="text" name="modelo">
      <label>Unidade</label>
      <input type="text" name="unidade" value="UN">
      <label>Peso (g)</label>
      <input type="number" name="peso_gramas" value="0">
      <label>Estoque Inicial *</label>
      <input type="number" name="estoqueInicial" required>
      <label>Estoque Atual *</label>
      <input type="number" name="estoqueAtual" required>
      <label>Estoque Mínimo</label>
      <input type="number" name="estoqueMinimo">
      <label>Preço de Custo</label>
      <input type="number" name="precoCusto" step="0.01">
      <label>Preço de Venda *</label>
      <input type="number" name="precoVenda" step="0.01" required>

      <h2>Atributos Avançados</h2>
      <label>Observações</label>
      <textarea name="observacoes" rows="2"></textarea>

      <!-- campo oculto para atributos JSON -->
      <input type="hidden" name="atributos" value="{}">

      <button type="submit">Salvar</button>
    </form>

    <h2>Lista de Produtos</h2>
    <table id="tbl-produtos">
      <thead>
        <tr>
          <th>Código</th><th>Nome</th><th>Categoria</th>
          <th>Estoque</th><th>Preço</th><th>Detalhes</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $res = $mysqli->query("SELECT * FROM produtos ORDER BY id DESC");
        while ($p = $res->fetch_assoc()) {
          echo "<tr>
                  <td>{$p['codigo']}</td>
                  <td>{$p['nome']}</td>
                  <td>{$p['categoria']}</td>
                  <td>{$p['estoque_atual']}</td>
                  <td>{$p['preco_venda']}</td>
                  <td><button onclick=\"alert('Produto: {$p['nome']}\\nDescrição: {$p['descricao']}')\">Ver</button></td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
