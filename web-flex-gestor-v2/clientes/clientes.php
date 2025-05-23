<?php
include_once __DIR__ . '/../common/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
  $tipo      = $_POST['tipo'] ?? '';
  $nome      = $_POST['nome'] ?? '';
  $documento = $_POST['documento'] ?? '';
  $email     = $_POST['email'] ?? '';
  $telefone  = $_POST['telefone'] ?? '';
  $endereco  = $_POST['endereco'] ?? '';
  $cidade    = $_POST['cidade'] ?? '';
  $estado    = $_POST['estado'] ?? '';
  $obs       = $_POST['obs'] ?? '';

  $stmt = $mysqli->prepare("INSERT INTO clientes (tipo, nome, documento, email, telefone, endereco, cidade, estado, obs)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssssss", $tipo, $nome, $documento, $email, $telefone, $endereco, $cidade, $estado, $obs);
  $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Clientes – Web Flex Gestor</title>
  <link rel="stylesheet" href="../common/stylepadrao.css">
  <link rel="stylesheet" href="clientes.css">
  <style>
    .modal {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 999;
    }
    .modal-content {
      background: #1e1e1e;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 600px;
      color: white;
    }
    .modal .close {
      float: right;
      font-size: 24px;
      cursor: pointer;
    }
  </style>
</head>
<body>
<div class="sidebar">
  <h2>Web Flex Gestor</h2>
  <ul>
    <li><a href="../tela_inicial/telainicial.html">🏠 Dashboard</a></li>
    <li><a href="clientes.php">👥 Clientes</a></li>
    <li><a href="../produtos/produtos.php">📦 Produtos</a></li>
    <li><a href="../vendas/vendas.php">🛒 Vendas</a></li>
    <li><a href="../projetos/projetos.php">📂 Projetos</a></li>
    <li><a href="../financeiro/financeiro.php">💰 Financeiro</a></li>
    <li><a href="../funcionarios/funcionarios.php">👷 Funcionários</a></li>
  </ul>
</div>

<div class="main">
  <h1>👥 Cadastro de Clientes</h1>

  <form method="POST">
    <input type="hidden" name="cadastrar" value="1">
    <label>Tipo *</label>
    <select name="tipo" required>
      <option value="">Selecione</option>
      <option value="PF">Pessoa Física</option>
      <option value="PJ">Pessoa Jurídica</option>
    </select>

    <label>Nome Completo *</label>
    <input type="text" name="nome" required>

    <label>CPF / CNPJ *</label>
    <input type="text" name="documento" required>

    <label>E-mail</label>
    <input type="email" name="email">

    <label>Telefone</label>
    <input type="tel" name="telefone">

    <label>Endereço</label>
    <input type="text" name="endereco">

    <label>Cidade</label>
    <input type="text" name="cidade">

    <label>Estado</label>
    <input type="text" name="estado">

    <label>Observações</label>
    <textarea name="obs" rows="2"></textarea>

    <button type="submit">Salvar Cliente</button>
  </form>

  <h2>Lista de Clientes</h2>

  <form method="GET" style="margin-bottom: 20px;">
    <label>Buscar por CPF/CNPJ:</label>
    <input type="text" name="cpf" value="<?= htmlspecialchars($_GET['cpf'] ?? '') ?>" placeholder="Digite o CPF ou CNPJ">
    <button type="submit">Buscar</button>
    <?php if (isset($_GET['cpf'])): ?>
      <a href="clientes.php" style="margin-left: 10px;">Limpar</a>
    <?php endif; ?>
  </form>

  <table id="tbl-clientes">
    <thead>
      <tr>
        <th>Tipo</th><th>Nome</th><th>Documento</th>
        <th>Telefone</th><th>E-mail</th><th>Detalhes</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (!empty($_GET['cpf'])) {
        $cpf = '%' . $mysqli->real_escape_string($_GET['cpf']) . '%';
        $query = "SELECT * FROM clientes WHERE documento LIKE '$cpf' ORDER BY id DESC";
      } else {
        $query = "SELECT * FROM clientes ORDER BY id DESC";
      }
      $res = $mysqli->query($query);
      while ($c = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$c['tipo']}</td>
          <td>{$c['nome']}</td>
          <td>{$c['documento']}</td>
          <td>" . ($c['telefone'] ?: '–') . "</td>
          <td>" . ($c['email'] ?: '–') . "</td>
          <td><button type='button' onclick='openClienteModal({$c['id']})'>Ver</button></td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div id="cliente-modal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="fecharModal()">&times;</span>
    <div id="cliente-modal-body">Carregando...</div>
  </div>
</div>

<script>
function openClienteModal(id) {
  fetch("cliente_modal.php?id=" + id)
    .then(res => res.text())
    .then(html => {
      document.getElementById("cliente-modal-body").innerHTML = html;
      document.getElementById("cliente-modal").style.display = "flex";
    });
}
function fecharModal() {
  document.getElementById("cliente-modal").style.display = "none";
}

function excluirCliente(id) {
  if (!confirm("Tem certeza que deseja excluir este cliente?")) return;

  fetch("excluir_cliente.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: "id=" + encodeURIComponent(id)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Remove a linha da tabela
      const row = document.querySelector(`button[onclick="openClienteModal(${id})"]`).closest("tr");
      if (row) row.remove();
      alert("Cliente excluído com sucesso.");
      fecharModal();
    } else {
      alert("Erro ao excluir.");
    }
  });
}

function salvarCliente(id) {
  const form = document.querySelector('#cliente-modal-body form');
  const data = new FormData(form);

  fetch("salvar_cliente.php", {
    method: "POST",
    body: data
  })
  .then(r => r.json())
  .then(resp => {
    if (resp.success) {
      const row = document.querySelector(`button[onclick="openClienteModal(${id})"]`).closest("tr");
      row.children[1].textContent = resp.nome;
      row.children[2].textContent = resp.documento;
      row.children[3].textContent = resp.telefone || '–';
      row.children[4].textContent = resp.email || '–';
      alert("Cliente atualizado com sucesso!");
      fecharModal();
    } else {
      alert("Erro ao salvar.");
    }
  });
}


</script>


</body>
</html>
