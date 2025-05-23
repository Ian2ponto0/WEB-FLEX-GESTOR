<?php
include_once __DIR__ . '/../common/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);
  if ($id > 0) {
    $stmt = $mysqli->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true, 'id' => $id]);
    exit;
  }
}

echo json_encode(['success' => false]);
