<?php
include_once __DIR__ . '/../common/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id           = (int)($_POST['id'] ?? 0);
  $nome         = $_POST['nome'] ?? '';
  $responsaveis = $_POST['responsaveis'] ?? '';
  $dataInicio   = $_POST['data_inicio'] ?? '';
  $dataPrevisao = $_POST['data_previsao'] ?? '';
  $dataFim      = $_POST['data_fim'] ?? '';
  $status       = $_POST['status'] ?? '';
  $progresso    = (int)($_POST['progresso'] ?? 0);
  $descricao    = $_POST['descricao'] ?? '';
  $relatorio    = $_POST['relatorio'] ?? '';

  $stmt = $mysqli->prepare("UPDATE projetos SET nome=?, responsaveis=?, data_inicio=?, data_previsao=?, data_fim=?, status=?, progresso=?, descricao=?, relatorio=? WHERE id=?");
  $stmt->bind_param("ssssssissi", $nome, $responsaveis, $dataInicio, $dataPrevisao, $dataFim, $status, $progresso, $descricao, $relatorio, $id);
  $stmt->execute();

  echo json_encode(['success' => true, 'nome' => $nome, 'status' => $status, 'progresso' => $progresso]);
  exit;
}

echo json_encode(['success' => false]);
