<?php // vendas/list.php
header('Content-Type: application/json');
require_once '../common/db_connect.php';
$pdo=getConnection();
$stmt=$pdo->query("
  SELECT v.id,v.data_venda,v.cliente_id,v.itens,v.total_liquido,v.forma_pagamento
  FROM vendas v
  LEFT JOIN clientes c ON v.cliente_id=c.id
  ORDER BY v.data_venda DESC
");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
