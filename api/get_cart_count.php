<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
$count = 0;
if(isset($_SESSION['user_id'])){
    $stmt = $mysqli->prepare('SELECT COALESCE(SUM(quantity),0) as qty FROM carts WHERE user_id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $count = (int)($r['qty'] ?? 0);
}
echo json_encode(['count'=>$count]);
?>