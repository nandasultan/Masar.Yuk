<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
include 'header.php';
include 'navbar.php';
require_login();
$oid = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare('SELECT id,total,status,created_at FROM orders WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $oid, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if(!$order){ echo '<div class="container py-4"><div class="alert alert-warning">Order tidak ditemukan.</div></div>'; include 'footer.php'; exit; }
$stmt2 = $mysqli->prepare('SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id = ?');
$stmt2->bind_param('i', $oid);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="container py-4">
  <h3>Order #<?php echo $order['id']; ?></h3>
  <p>Waktu: <?php echo $order['created_at']; ?> | Status: <?php echo $order['status']; ?></p>
  <ul class="list-group mb-3">
    <?php foreach($items as $it): ?>
      <li class="list-group-item"><?php echo $it['name']; ?> — <?php echo $it['quantity']; ?> x Rp <?php echo number_format($it['price']); ?></li>
    <?php endforeach; ?>
  </ul>
  <h4>Total: Rp <?php echo number_format($order['total']); ?></h4>
  <a href="http://localhost/masar/orders.php" class="btn btn-link">Kembali</a>
</div>
<?php include 'footer.php'; ?>