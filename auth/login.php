<?php
require_once __DIR__ . '/../config.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    if($email && $pass){
        $stmt = $mysqli->prepare('SELECT id,password FROM users WHERE email = ?');
        $stmt->bind_param('s',$email);
        $stmt->execute();
        if($row = $stmt->get_result()->fetch_assoc()){
            if(password_verify($pass, $row['password'])){ $_SESSION['user_id'] = $row['id']; header('Location: http://localhost/masar/index.php'); exit; }
            else $errors[]='Email atau password salah.';
        } else $errors[]='Email atau password salah.';
    } else $errors[]='Lengkapi semua field.';
}
include __DIR__ . '/../header.php';
?>
<div class="container py-5">
  <h2>Login</h2>
  <?php foreach($errors as $e) echo '<div class="alert alert-danger">'.$e.'</div>'; ?>
  <form method="post" class="col-md-6 col-lg-4">
    <input name="email" class="form-control mb-2" placeholder="Email" type="email" required>
    <input name="password" class="form-control mb-2" placeholder="Password" type="password" required>
    <button class="btn btn-primary">Login</button>
  </form>
  <p class="mt-3">Belum punya akun? <a href="http://localhost/masar/auth/register.php">Daftar</a></p>
</div>
<?php include __DIR__ . '/../footer.php'; ?>