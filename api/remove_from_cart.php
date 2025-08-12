<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['success'=>false]); exit; }
$pid = intval($_POST['product_id'] ?? 0);
$stmt = $mysqli->prepare('DELETE FROM carts WHERE user_id = ? AND product_id = ?');
$stmt->bind_param('ii', $_SESSION['user_id'], $pid);
$stmt->execute();
$stmt2 = $mysqli->prepare('SELECT COALESCE(SUM(quantity),0) as qty FROM carts WHERE user_id = ?');
$stmt2->bind_param('i', $_SESSION['user_id']);
$stmt2->execute();
$r = $stmt2->get_result()->fetch_assoc();
echo json_encode(['success'=>true,'count'=> (int)($r['qty'] ?? 0) ]);
?>