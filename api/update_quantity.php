<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['error'=>'unauth']); exit; }
$uid = $_SESSION['user_id'];
$pid = intval($_POST['product_id'] ?? 0);
$action = $_POST['action'] ?? '';
if(!$pid){ echo json_encode(['success'=>false,'msg'=>'no product']); exit; }
if($action === 'plus'){
    $stmt = $mysqli->prepare('UPDATE carts SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?');
    $stmt->bind_param('ii',$uid,$pid); $stmt->execute();
} elseif($action === 'minus'){
    $stmt = $mysqli->prepare('UPDATE carts SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?');
    $stmt->bind_param('ii',$uid,$pid); $stmt->execute();
    // remove any zero or negative
    $stmt2 = $mysqli->prepare('DELETE FROM carts WHERE user_id = ? AND product_id = ? AND quantity <= 0');
    $stmt2->bind_param('ii',$uid,$pid); $stmt2->execute();
} else {
    echo json_encode(['success'=>false,'msg'=>'invalid action']); exit;
}
$stmt3 = $mysqli->prepare('SELECT COALESCE(SUM(quantity),0) as qty FROM carts WHERE user_id = ?');
$stmt3->bind_param('i',$uid); $stmt3->execute();
$r = $stmt3->get_result()->fetch_assoc();
echo json_encode(['success'=>true,'count'=>(int)$r['qty']]);
?>