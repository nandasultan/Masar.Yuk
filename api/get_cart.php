<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['success'=>false]); exit; }
$stmt = $mysqli->prepare('SELECT c.product_id, c.quantity, p.name, p.price, p.image FROM carts c JOIN products p ON c.product_id=p.id WHERE c.user_id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
echo json_encode(['success'=>true,'data'=>$data]);
?>