<?php
include_once __DIR__ . '/../common/conexao.php';

$id = (int)($_GET['id'] ?? 0);
$res = $mysqli->query("SELECT * FROM projetos WHERE id = $id");
$proj = $res->fetch_assoc();

if (!$proj) {
  echo "<p>Projeto não encontrado.</p>";
  exit;
}
?>

<?php
include_once __DIR__ . '/../common/conexao.php';

$id = (int)($_GET['id'] ?? 0);
$res = $mysqli->query("SELECT * FROM projetos WHERE id = $id");
$proj = $res->fetch_assoc();

if (!$proj) {
  echo "<p>Projeto não encontrado.</p>";
  exit;
}
?>

<form onsubmit="event.preventDefault(); salvarProjeto(<?= $proj['id'] ?>)">
  <input type="hidden" name="id" value="<?= $proj['id'] ?>">

  <div class="modal-form-columns">
    <div class="modal-column">
      <label>Nome</label>
      <input type="text" name="nome" value="<?= htmlspecialchars($proj['nome']) ?>" required>

      <label>Responsáveis</label>
      <input type="text" name="responsaveis" value="<?= htmlspecialchars($proj['responsaveis']) ?>">

      <label>Data de Início</label>
      <input type="date" name="data_inicio" value="<?= $proj['data_inicio'] ?>">

      <label>Previsão de Término</label>
      <input type="date" name="data_previsao" value="<?= $proj['data_previsao'] ?>">
    </div>

    <div class="modal-column">
      <label>Data de Término</label>
      <input type="date" name="data_fim" value="<?= $proj['data_fim'] ?>">

      <label>Status</label>
      <select name="status">
        <option value="planejamento" <?= $proj['status'] == 'planejamento' ? 'selected' : '' ?>>Planejamento</option>
        <option value="ativo" <?= $proj['status'] == 'ativo' ? 'selected' : '' ?>>Ativo</option>
        <option value="finalizado" <?= $proj['status'] == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
        <option value="cancelado" <?= $proj['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
      </select>

      <label>Progresso (%)</label>
      <input type="number" name="progresso" min="0" max="100" value="<?= $proj['progresso'] ?>">

      <label>Descrição</label>
      <textarea name="descricao"><?= htmlspecialchars($proj['descricao']) ?></textarea>

      <label>Relatório</label>
      <textarea name="relatorio"><?= htmlspecialchars($proj['relatorio']) ?></textarea>
    </div>
  </div>

  <div style="margin-top: 20px;">
    <button type="button" onclick="salvarProjeto(<?= $proj['id'] ?>)">💾 Salvar</button>
    <button type="button" onclick="excluirProjeto(<?= $proj['id'] ?>)">🗑️ Excluir</button>
  </div>
</form>

</form>
