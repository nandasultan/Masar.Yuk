<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
include 'header.php';
include 'navbar.php';
require_login();
$stmt = $mysqli->prepare('SELECT id,total,status,created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="container py-4">
  <h2>Riwayat Pembelian</h2>
  <?php if(empty($orders)) echo '<div class="alert alert-info">Belum ada pesanan.</div>'; ?>
  <?php foreach($orders as $o): ?>
    <div class="card mb-2"><div class="card-body d-flex justify-content-between">
      <div>Order #<?php echo $o['id']; ?><br><small><?php echo $o['created_at']; ?></small></div>
      <div><strong>Rp <?php echo number_format($o['total']); ?></strong><br>Status: <?php echo $o['status']; ?><br><a href="http://localhost/masar/order_detail.php?id=<?php echo $o['id']; ?>">Detail</a></div>
    </div></div>
  <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>