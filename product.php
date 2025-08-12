<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
include 'header.php';
include 'navbar.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare('SELECT * FROM products WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if(!$p){ echo '<div class="container py-4"><div class="alert alert-warning">Produk tidak ditemukan.</div></div>'; include 'footer.php'; exit; }
?>
<div class="container py-4">
  <div class="row">
    <div class="col-md-6"><img src="<?php echo $p['image']; ?>" class="img-fluid" alt=""></div>
    <div class="col-md-6">
      <h2><?php echo htmlspecialchars($p['name']); ?></h2>
      <p class="lead">Rp <?php echo number_format($p['price']); ?> / <?php echo $p['unit']; ?></p>
      <p>Stok: <?php echo $p['stock']; ?></p>
      <div class="mb-3"><label>Jumlah</label><input id="qty" type="number" value="1" min="1" max="<?php echo $p['stock']; ?>" class="form-control" style="width:120px;"></div>
      <button id="buy" class="btn btn-success">Tambah ke Keranjang</button>
      <a class="btn btn-link" href="http://localhost/masar/index.php">Kembali</a>
    </div>
  </div>
</div>
<script>
$('#buy').on('click', function(){
    var q = parseInt($('#qty').val()) || 1;
    $.post('http://localhost/masar/api/add_to_cart.php', { product_id: <?php echo $p['id']; ?>, quantity: q }, function(res){
        if(res && res.count !== undefined){
            $('#cart-count').text(res.count);
            alert('Ditambahkan ke keranjang');
            window.location.href = 'http://localhost/masar/cart.php';
        } else if(res && res.error==='unauth'){
            window.location.href = 'http://localhost/masar/auth/login.php';
        } else alert('Gagal');
    }, 'json');
});
</script>
<?php include 'footer.php'; ?>