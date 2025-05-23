<?php // vendas/create.php
header('Content-Type: application/json');
require_once '../common/db_connect.php';
$data=json_decode(file_get_contents('php://input'),true);
if(!$data){ http_response_code(400); echo json_encode(['error'=>'JSON inválido']); exit; }
$sql="INSERT INTO vendas
  (data_venda,cliente_id,itens,total_bruto,descontos,total_liquido,forma_pagamento,obs)
 VALUES
  (:data_venda,:cliente_id,:itens,:total_bruto,:descontos,:total_liquido,:forma_pagamento,:obs)";
$pdo=getConnection();
$stmt=$pdo->prepare($sql);
$stmt->execute([
  ':data_venda'=>$data['dataVenda'],
  ':cliente_id'=>$data['clienteId']??null,
  ':itens'=>json_encode($data['itens']),
  ':total_bruto'=>$data['totalBruto'],
  ':descontos'=>$data['descontos']??0,
  ':total_liquido'=>$data['totalLiquido'],
  ':forma_pagamento'=>$data['formaPagamento']??'',
  ':obs'=>$data['obs']??''
]);
echo json_encode(['id'=>$pdo->lastInsertId()]);
