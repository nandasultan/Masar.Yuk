<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
include 'header.php';
include 'navbar.php';
require_login();
?>
<div class="container py-4">
  <h2>Keranjang</h2>
  <div id="cart-list"></div>
  <div id="cart-actions" class="mt-3 d-none">
    <h4>Total: <span id="cart-total"></span></h4>
    <button id="checkout" class="btn btn-success">Checkout</button>
  </div>
</div>

<script>
function loadCart(){
    $.getJSON('http://localhost/masar/api/get_cart.php', function(res){
        if(!res || !res.success){ $('#cart-list').html('<div class="alert alert-info">Keranjang kosong.</div>'); $('#cart-actions').addClass('d-none'); return; }
        var html=''; var total=0;
        html += '<div class="list-group">';
        res.data.forEach(function(it){
            total += it.price * it.quantity;
            html += '<div class="list-group-item d-flex align-items-center justify-content-between">';
            html += '<div class="d-flex align-items-center">';
            html += '<img src="'+it.image+'" style="width:100px;height:70px;object-fit:cover;margin-right:12px;">';
            html += '<div><strong>'+it.name+'</strong><br>Rp '+it.price.toLocaleString()+'</div></div>';
            html += '<div class="text-end">';
            html += '<div class="input-group input-group-sm mb-2" style="width:120px; margin-left:auto;">';
            html += '<button class="btn btn-outline-secondary btn-decrease" data-id="'+it.product_id+'">−</button>';
            html += '<input type="text" readonly class="form-control text-center qty-field" data-id="'+it.product_id+'" value="'+it.quantity+'">';
            html += '<button class="btn btn-outline-secondary btn-increase" data-id="'+it.product_id+'">+</button>';
            html += '</div>';
            html += '<div><small>Subtotal: Rp '+(it.price*it.quantity).toLocaleString()+'</small></div>';
            html += '</div></div>';
        });
        html += '</div>';
        $('#cart-list').html(html);
        $('#cart-total').text('Rp '+total.toLocaleString());
        $('#cart-actions').removeClass('d-none');
    });
}

$(document).on('click', '.btn-increase, .btn-decrease', function(){
    var pid = $(this).data('id');
    var action = $(this).hasClass('btn-increase') ? 'plus' : 'minus';
    $.post('http://localhost/masar/api/update_quantity.php', { product_id: pid, action: action }, function(res){
        if(res && res.success){
            $('#cart-count').text(res.count);
            loadCart();
        } else if(res && res.error==='unauth'){
            window.location.href = 'http://localhost/masar/auth/login.php';
        } else {
            alert('Gagal update keranjang');
        }
    }, 'json').fail(function(){ alert('Request gagal'); });
});

$('#checkout').on('click', function(){
    $.post('http://localhost/masar/api/checkout.php', {}, function(r){
        if(r && r.success){ alert('Checkout berhasil. Order ID: '+r.order_id); window.location='http://localhost/masar/orders.php'; } else alert('Gagal: '+r.message);
    }, 'json');
});

$(function(){ loadCart(); });
</script>
<?php include 'footer.php'; ?>