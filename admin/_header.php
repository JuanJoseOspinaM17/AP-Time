<?php
if(!isset($pageTitle))$pageTitle='AP TIME | Admin';

?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>
<?=e($pageTitle)
?>
</title>
<link rel="stylesheet" href="<?=base_url('assets/css/admin.css')
?>">
</head>
<body class="admin-body">
<aside class="sidebar">
<a class="admin-brand" href="<?=base_url('admin/dashboard.php')
?>">
<b>AP</b> TIME</a>
<span class="sidebar-kicker">ADMINISTRACIÓN</span>
<nav>
<a class="<?=$activePage==='dashboard'?'active':''
?>" href="<?=base_url('admin/dashboard.php')
?>">▣ <span>Dashboard</span>
</a>
<a class="<?=$activePage==='new'?'active':''
?>" href="<?=base_url('admin/product_create.php')
?>">＋ <span>Nuevo reloj</span>
</a>
<a target="_blank" href="<?=base_url()
?>">↗ <span>Ver tienda</span>
</a>
</nav>
<div class="sidebar-bottom">
<div class="admin-user">
<i>
<?=e(strtoupper(substr($_SESSION['admin_username']??'A',0,1)))
?>
</i>
<div>
<b>
<?=e($_SESSION['admin_username']??'admin')
?>
</b>
<small>Administrador</small>
</div>
</div>
<a class="logout" href="<?=base_url('admin/logout.php')
?>">Cerrar sesión</a>
</div>
</aside>
<main class="admin-main">
<header class="admin-topbar">
<div>
<span class="admin-kicker">AP TIME / CATÁLOGO</span>
<h1>
<?=e($pageHeading??'Dashboard')
?>
</h1>
</div>
<a class="store-link" href="<?=base_url()
?>" target="_blank">Ver tienda ↗</a>
</header>
