<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../common/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['usuario'], $data['senha'])) {
  echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
  exit;
}

try {
  $pdo = getConnection();

  $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE usuario = :usuario AND ativo = 1");
  $stmt->execute([':usuario' => $data['usuario']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($data['senha'], $user['senha'])) {
    session_start();
    $_SESSION['usuario'] = $data['usuario'];
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos']);
  }

} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
