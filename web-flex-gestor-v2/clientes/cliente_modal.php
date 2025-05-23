<?php
include_once __DIR__ . '/../common/conexao.php';

$id = (int)($_GET['id'] ?? 0);
$res = $mysqli->query("SELECT * FROM clientes WHERE id = $id");
$cli = $res->fetch_assoc();

if (!$cli) {
  echo "<p>Cliente não encontrado.</p>";
  exit;
}
?>

<form onsubmit="event.preventDefault(); salvarCliente(<?= $cli['id'] ?>)">
  <input type="hidden" name="id" value="<?= $cli['id'] ?>">

  <div class="modal-form-columns">
    <div class="modal-column">
      <label>Nome</label>
      <input type="text" name="nome" value="<?= htmlspecialchars($cli['nome']) ?>" required>

      <label>Documento</label>
      <input type="text" name="documento" value="<?= htmlspecialchars($cli['documento']) ?>" required>

      <label>Telefone</label>
      <input type="text" name="telefone" value="<?= htmlspecialchars($cli['telefone']) ?>">

      <label>E-mail</label>
      <input type="email" name="email" value="<?= htmlspecialchars($cli['email']) ?>">
    </div>

    <div class="modal-column">
      <label>Endereço</label>
      <input type="text" name="endereco" value="<?= htmlspecialchars($cli['endereco']) ?>">

      <label>Cidade</label>
      <input type="text" name="cidade" value="<?= htmlspecialchars($cli['cidade']) ?>">

      <label>Estado</label>
      <input type="text" name="estado" value="<?= htmlspecialchars($cli['estado']) ?>">

      <label>Observações</label>
      <textarea name="obs" rows="3"><?= htmlspecialchars($cli['obs']) ?></textarea>
    </div>
  </div>

  <div style="margin-top: 20px;">
    <button type="button" onclick="salvarCliente(<?= $cli['id'] ?>)">💾 Salvar</button>
    <button type="button" onclick="excluirCliente(<?= $cli['id'] ?>)">🗑️ Excluir</button>
  </div>
</form>

</form>

