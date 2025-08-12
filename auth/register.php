<?php
require_once __DIR__ . '/../config.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';
    if(!$name || !$email || !$pass) $errors[]='Lengkapi semua field.';
    if($pass !== $pass2) $errors[]='Password tidak cocok.';
    if(empty($errors)){
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
        $stmt->bind_param('sss',$name,$email,$hash);
        if($stmt->execute()){ $_SESSION['user_id'] = $stmt->insert_id; header('Location: http://localhost/masar/index.php'); exit; }
        else { if($mysqli->errno===1062) $errors[]='Email sudah terdaftar.'; else $errors[]='Gagal mendaftar.'; }
    }
}
include __DIR__ . '/../header.php';
?>
<div class="container py-5">
  <h2>Daftar</h2>
  <?php foreach($errors as $e) echo '<div class="alert alert-danger">'.$e.'</div>'; ?>
  <form method="post" class="col-md-6 col-lg-4">
    <input name="name" class="form-control mb-2" placeholder="Nama" required>
    <input name="email" class="form-control mb-2" placeholder="Email" type="email" required>
    <input name="password" class="form-control mb-2" placeholder="Password" type="password" required>
    <input name="password2" class="form-control mb-2" placeholder="Ulangi Password" type="password" required>
    <button class="btn btn-primary">Daftar</button>
  </form>
  <p class="mt-3">Sudah punya akun? <a href="http://localhost/masar/auth/login.php">Login</a></p>
</div>
<?php include __DIR__ . '/../footer.php'; ?>