<?php
include_once __DIR__ . '/../common/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome           = $_POST['nome'] ?? '';
  $responsaveis   = $_POST['responsaveis'] ?? '';
  $dataInicio     = $_POST['dataInicio'] ?? '';
  $dataPrevisao   = $_POST['dataPrevisao'] ?? '';
  $dataFim        = $_POST['dataFim'] ?? '';
  $status         = $_POST['status'] ?? '';
  $progresso      = $_POST['progresso'] ?? 0;
  $descricao      = $_POST['descricao'] ?? '';
  $relatorio      = $_POST['relatorio'] ?? '';

  $stmt = $mysqli->prepare("INSERT INTO projetos (
    nome, responsaveis, data_inicio, data_previsao, data_fim,
    status, progresso, descricao, relatorio
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param("ssssssiss", 
    $nome, $responsaveis, $dataInicio, $dataPrevisao,
    $dataFim, $status, $progresso, $descricao, $relatorio
  );

  $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Projetos – Web Flex Gestor</title>
  <link rel="stylesheet" href="../common/stylepadrao.css">
  <link rel="stylesheet" href="projetos.css">
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
      <li><a href="../clientes/clientes.php">👥 Clientes</a></li>
      <li><a href="../produtos/produtos.php">📦 Produtos</a></li>
      <li><a href="../vendas/vendas.html">🛒 Vendas</a></li>
      <li><a href="projetos.php">📂 Projetos</a></li>
      <li><a href="../financeiro/financeiro.php">💰 Financeiro</a></li>
      <li><a href="../funcionarios/funcionarios.php">👷 Funcionários</a></li>
    </ul>
  </div>

  <div class="main">
    <h1>📂 Gestão de Projetos</h1>
    <form method="POST">
      <label>Nome *</label>
      <input type="text" name="nome" required>
      <label>Responsáveis</label>
      <input type="text" name="responsaveis">
      <label>Data de Início</label>
      <input type="date" name="dataInicio">
      <label>Previsão Término</label>
      <input type="date" name="dataPrevisao">
      <label>Data de Término</label>
      <input type="date" name="dataFim">
      <label>Status</label>
      <select name="status">
        <option value="planejamento">Planejamento</option>
        <option value="ativo">Ativo</option>
        <option value="finalizado">Finalizado</option>
        <option value="cancelado">Cancelado</option>
      </select>
      <label>Progresso (%)</label>
      <input type="number" name="progresso" min="0" max="100" value="0">
      <label>Descrição</label>
      <textarea name="descricao" rows="2"></textarea>
      <label>Relatório</label>
      <textarea name="relatorio" rows="2"></textarea>
      <button type="submit">Salvar Projeto</button>
    </form>

    <h2>Projetos</h2>
    <table>
      <thead>
        <tr><th>Nome</th><th>Status</th><th>Progresso</th><th>Detalhes</th></tr>
      </thead>
      <tbody>
        <?php
        $res = $mysqli->query("SELECT * FROM projetos ORDER BY id DESC");
        while ($p = $res->fetch_assoc()) {
          echo "<tr>
            <td>{$p['nome']}</td>
            <td>{$p['status']}</td>
            <td>{$p['progresso']}%</td>
            <td><button type='button' onclick='openProjetoModal({$p['id']})'>Ver</button></td>
          </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div id="projeto-modal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="fecharModal()">&times;</span>
      <div id="projeto-modal-body">Carregando...</div>
    </div>
  </div>

  <script>
  function openProjetoModal(id) {
    fetch("projeto_modal.php?id=" + id)
      .then(r => r.text())
      .then(html => {
        document.getElementById("projeto-modal-body").innerHTML = html;
        document.getElementById("projeto-modal").style.display = "flex";
      });
  }

  function fecharModal() {
    document.getElementById("projeto-modal").style.display = "none";
  }

  function excluirProjeto(id) {
    if (!confirm("Excluir este projeto?")) return;
    fetch("excluir_projeto.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.querySelector(`button[onclick="openProjetoModal(${id})"]`).closest("tr").remove();
        fecharModal();
        alert("Projeto excluído com sucesso.");
      } else {
        alert("Erro ao excluir.");
      }
    });
  }

 function salvarProjeto(id) {
  const form = document.querySelector('#projeto-modal-body form');
  const data = new FormData(form);

  fetch("salvar_projeto.php", {
    method: "POST",
    body: data
  })
  .then(r => r.json())
  .then(resp => {
    if (resp.success) {
      const row = document.querySelector(`button[onclick="openProjetoModal(${id})"]`).closest("tr");
      row.children[0].textContent = resp.nome;
      row.children[1].textContent = resp.status;
      row.children[2].textContent = resp.progresso + '%';
      alert("Projeto atualizado com sucesso!");
      fecharModal();
    } else {
      alert("Erro ao salvar.");
    }
  });
}


  </script>
</body>
</html>
