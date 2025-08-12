<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['error'=>'unauth']); exit; }
$uid = $_SESSION['user_id'];
$pid = intval($_POST['product_id'] ?? 0);
$qty = max(1, intval($_POST['quantity'] ?? 1));
if($pid){
    $stmt = $mysqli->prepare('INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)');
    $stmt->bind_param('iii', $uid, $pid, $qty);
    $stmt->execute();
    if($stmt->error){ echo json_encode(['error'=>'db','msg'=>$stmt->error]); exit; }
}
$stmt2 = $mysqli->prepare('SELECT COALESCE(SUM(quantity),0) as qty FROM carts WHERE user_id = ?');
$stmt2->bind_param('i',$uid);
$stmt2->execute();
$r = $stmt2->get_result()->fetch_assoc();
echo json_encode(['count'=>(int)($r['qty'] ?? 0)]);
?>