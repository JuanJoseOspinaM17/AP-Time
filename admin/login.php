<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
session_start();
if(!empty($_SESSION['admin_id'])) redirect(base_url('admin/dashboard.php'));
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $u=trim($_POST['username']??'');
    $p=$_POST['password']??'';
    if($u===''||$p==='')$error='Completa usuario y contraseña.';
    else {
        $st=$pdo->prepare('SELECT id,username,password FROM admins WHERE username=? LIMIT 1');
        $st->execute([$u]);
        $a=$st->fetch();
        if($a&&password_verify($p,$a['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']=$a['id'];
            $_SESSION['admin_username']=$a['username'];
            redirect(base_url('admin/dashboard.php'));
        } else $error='Usuario o contraseña incorrectos.';
    }
}

?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>AP TIME | Admin</title>
<link rel="stylesheet" href="<?=base_url('assets/css/admin.css')
?>">
</head>
<body class="login-page">
<main class="login-wrap">
<div class="login-card">
<div class="admin-logo">
<b>AP</b> TIME</div>
<span class="admin-kicker">PANEL DE ADMINISTRACIÓN</span>
<h1>Bienvenido</h1>
<p>Administra tu catálogo de relojes desde un solo lugar.</p>
<?php
if($error):

?>
<div class="alert error">
<?=e($error)
?>
</div>
<?php
endif;

?>
<form method="post">
<label>Usuario<input name="username" autocomplete="username" required>
</label>
<label>Contraseña<input type="password" name="password" autocomplete="current-password" required>
</label>
<button class="admin-btn" type="submit">Entrar →</button>
</form>
<a class="back-store" href="<?=base_url()
?>">← Volver a la tienda</a>
<small class="login-hint">Instalación inicial: usuario <b>admin</b> · contraseña <b>APtime2026!</b>
</small>
</div>
</main>
</body>
</html>
