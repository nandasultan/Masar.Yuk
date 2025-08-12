<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/functions.php';
include 'header.php';
include 'navbar.php';
require_login();
$u = get_user($mysqli, $_SESSION['user_id']);
?>
<div class="container py-4">
  <div class="row">
    <div class="col-md-4">
      <div class="card p-3"><h5>Profil</h5>
      <p><strong><?php echo htmlspecialchars($u['name']); ?></strong><br><?php echo htmlspecialchars($u['email']); ?></p>
      </div>
    </div>
    <div class="col-md-8"><div class="card p-3"><h5>Informasi Akun</h5><p>Fitur edit profile bisa ditambah.</p></div></div>
  </div>
</div>
<?php include 'footer.php'; ?>