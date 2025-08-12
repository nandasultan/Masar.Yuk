<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['success'=>false,'message'=>'unauth']); exit; }
$uid = $_SESSION['user_id'];
$mysqli->begin_transaction();
try{
    $stmt = $mysqli->prepare('SELECT c.product_id, c.quantity, p.price, p.stock FROM carts c JOIN products p ON c.product_id=p.id WHERE c.user_id = ?');
    $stmt->bind_param('i',$uid); $stmt->execute();
    $cart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    if(empty($cart)) throw new Exception('Keranjang kosong.');
    $total = 0;
    foreach($cart as $it){
        if($it['quantity'] > $it['stock']) throw new Exception('Stok tidak cukup');
        $total += $it['quantity'] * $it['price'];
    }
    $stmt = $mysqli->prepare('INSERT INTO orders (user_id, total, status) VALUES (?, ?, "paid")');
    $stmt->bind_param('ii',$uid,$total); $stmt->execute(); $oid = $stmt->insert_id;
    $stmt_item = $mysqli->prepare('INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)');
    $stmt_stock = $mysqli->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
    foreach($cart as $it){
        $stmt_item->bind_param('iiii', $oid, $it['product_id'], $it['price'], $it['quantity']); $stmt_item->execute();
        $stmt_stock->bind_param('ii', $it['quantity'], $it['product_id']); $stmt_stock->execute();
    }
    $stmt = $mysqli->prepare('DELETE FROM carts WHERE user_id = ?'); $stmt->bind_param('i',$uid); $stmt->execute();
    $mysqli->commit();
    echo json_encode(['success'=>true,'order_id'=>$oid,'total'=>$total]);
} catch(Exception $e){
    $mysqli->rollback();
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
?>