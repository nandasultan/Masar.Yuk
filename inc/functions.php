<?php
require_once __DIR__ . '/../config.php';
function is_logged_in(){ return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']); }
function require_login(){ if(!is_logged_in()){ header('Location: http://localhost/masar/auth/login.php'); exit; } }
function get_user($mysqli, $user_id){
    $stmt = $mysqli->prepare('SELECT id,name,email,created_at FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>