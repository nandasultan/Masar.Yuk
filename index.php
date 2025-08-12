<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
include 'header.php';
include 'navbar.php';
$res = $mysqli->query('SELECT * FROM products');
?>
<!-- THUMBNAIL -->
<div class="container d-none d-md-block">
      <div class="thumbnail col-12 col-s-12">
        <div id="carouselExampleIndicators" class="container-fluid carousel slide" data-bs-ride="true">
          <div class="carousel-indicators">
            <button
              type="button"
              data-bs-target="#carouselExampleIndicators"
              data-bs-slide-to="0"
              class="active"
              aria-current="true"
              aria-label="Slide 1"
            ></button>
            <button
              type="button"
              data-bs-target="#carouselExampleIndicators"
              data-bs-slide-to="1"
              aria-label="Slide 2"
            ></button>
            <button
              type="button"
              data-bs-target="#carouselExampleIndicators"
              data-bs-slide-to="2"
              aria-label="Slide 3"
            ></button>
          </div>
          <div class="carousel-inner rounded-4">
            <div class="carousel-item active">
              <img src="./assets/img/tb4.jpg" class="d-block img-fluid" alt="..." />
            </div>
            <div class="carousel-item">
              <img src="./assets/img/tb3.jpg" class="d-block img-fluid" alt="..." />
            </div>
            <div class="carousel-item">
              <img src="./assets/img/tb2.jpg" class="d-block img-fluid" alt="..." />
            </div>
          </div>
          <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev"
          >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next"
          >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
<!-- AKHIR THUMBNAIL -->

    <!-- PERKENALAN -->
      <div class="container ">
        <div class="perkenalan rounded-3 border col-12 col-s-12 flex flex-wrap">
          <div class="gambarsambutan col-s-12 col-md-6">
            <img src="./assets/img/prk.jpg" class="d-block img-fluid rounded float-start" alt="..." />
          </div>
          <div class="sambutan col-s-12 col-md-6">
            <h2>Hallo Kak,</h2>
            <p>
              Selamat Berbelanja di toko kami, toko kami menyediakan beraneka ragam produk. Semoga kalian suka dan jika
              ada kritik maupun saran pergi ke "bantuan" Terimakasih!
            </p>
            <a href="./assets/html/bantuan.html"><button type="button" class="btn btn-success">Bantuan</button></a>
          </div>
        </div>
      </div>
    <!-- AKHIR PERKENALAN -->

<section  class="produk">
<div class="col-12" id="produk">
  <div class="container text-center my-5">
    <h1 class="">Produk</h1>
    <p class="col-12 ">Temukan berbagai produk menarik di sini.</p>
  </div>
<div class="produk container my-5">
  <div class="row" id="product-list">
    <?php while($p = $res->fetch_assoc()): ?>
    <div class="col-md-4 mb-4">
      <div class="card">
        <img src="<?php echo $p['image']; ?>" class="card-img-top product-img" data-id="<?php echo $p['id']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?php echo htmlspecialchars($p['name']); ?></h5>
          <p class="card-text mb-3"><strong>Rp <?php echo number_format($p['price']); ?></strong> / <small><?php echo $p['unit']; ?></small></p>
          <div class="mt-auto d-flex justify-content-between">
            <a class="btn btn-outline-secondary" href="http://localhost/masar/product.php?id=<?php echo $p['id']; ?>">Detail</a>
            <button class="btn btn-success btn-add" data-id="<?php echo $p['id']; ?>">Tambah</button>
          </div>
        </div>
      </div>
    </div>
    
    <?php endwhile; ?>
  </div>
</div>
</div>
</section>

<script>
// fly animation and AJAX add-to-cart
function flyToCart($img, $target){
    if(!$img.length || !$target.length) return;
    var $clone = $img.clone().addClass('fly-img').css({
        width: $img.width(),
        height: $img.height(),
        top: $img.offset().top,
        left: $img.offset().left,
        opacity: 0.95,
        position: 'absolute'
    }).appendTo('body');
    var to = $target.offset();
    $clone.animate({
        top: to.top + 8,
        left: to.left + 8,
        width: 28,
        height: 28,
        opacity: 0.5
    }, 700, function(){ $clone.remove(); });
}

function refreshCartCount(){
    $.getJSON('http://localhost/masar/api/get_cart_count.php', function(data){
        if(data && data.count !== undefined) $('#cart-count').text(data.count);
    });
}

$(document).on('click', '.btn-add', function(){
    var id = $(this).data('id');
    var $img = $('.product-img[data-id="'+id+'"]').first();
    var $target = $('#cart-link');
    $.post('http://localhost/masar/api/add_to_cart.php', { product_id: id }, function(res){
        if(res && res.count !== undefined){
            flyToCart($img, $target);
            $('#cart-count').text(res.count);
        } else if(res && res.error === 'unauth'){
            alert('Silakan login terlebih dahulu.');
            window.location.href = 'http://localhost/masar/auth/login.php';
        } else {
            alert('Gagal menambahkan ke keranjang.');
        }
    }, 'json').fail(function(){ alert('Request gagal. Cek network.'); });
});

$(function(){ refreshCartCount(); });
</script>

<?php include 'footer.php'; ?>