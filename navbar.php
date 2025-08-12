<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
$cart_count = 0;
if(is_logged_in()){
    $uid = $_SESSION['user_id'];
    $stmt = $mysqli->prepare('SELECT COALESCE(SUM(quantity),0) as qty FROM carts WHERE user_id = ?');
    $stmt->bind_param('i',$uid);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $cart_count = (int)($r['qty'] ?? 0);
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="http://localhost/masar/index.php"><strong>Masar</strong>.Yuk</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item" >
          <a class="nav-link" href="#produk" >Produk</a></li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center position-relative">
        <li class="nav-item me-3 position-relative">
          <a class="nav-link position-relative" href="http://localhost/masar/cart.php" id="cart-link" aria-label="Keranjang">
            <i class="bi bi-cart" style="font-size:1.5rem;"></i>
            <span id="cart-count" class="badge bg-danger rounded-pill" style="position:absolute;top:-8px;right:-8px;"><?php echo $cart_count; ?></span>
          </a>
        </li>
        <?php if(is_logged_in()): $u = get_user($mysqli, $_SESSION['user_id']); ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <?=htmlspecialchars($u['name'])?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="http://localhost/masar/profile.php">Profil</a></li>
            <li><a class="dropdown-item" href="http://localhost/masar/orders.php">Riwayat Pembelian</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="http://localhost/masar/auth/logout.php">Logout</a></li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item"><a class="btn btn-outline-primary me-2" href="http://localhost/masar/auth/login.php">Login</a></li>
        <li class="nav-item"><a class="btn btn-primary" href="http://localhost/masar/auth/register.php">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
