<?php
require_once __DIR__.'/config/database.php';
require_once __DIR__.'/config/helpers.php';
$q=trim($_GET['q']??'');
$availability=$_GET['availability']??'';
$sql="SELECT p.id,p.name,p.price,p.availability,(SELECT image FROM product_images WHERE product_id=p.id ORDER BY is_main DESC,sort_order ASC,id ASC LIMIT 1) main_image FROM products p WHERE p.status='active'";
$params=[];
if($q!=='') {
    $sql.=' AND p.name LIKE ?';
    $params[]='%'.$q.'%';
}
if(in_array($availability,['contra_entrega','encargo'],true)) {
    $sql.=' AND p.availability=?';
    $params[]=$availability;
}
$sql.=' ORDER BY p.created_at DESC';
$st=$pdo->prepare($sql);
$st->execute($params);
$products=$st->fetchAll();

?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>AP TIME | Catálogo</title>
<link rel="stylesheet" href="<?=base_url('assets/css/style.css?v=20260910')
?>">
</head>
<body>
<header class="site-header">
<div class="container header-inner">
<a class="brand" href="<?=base_url()
?>">
<span>
<b>AP</b> TIME</span>
</a>
<nav>
<a href="<?=base_url()
?>">Inicio</a>
<a class="active" href="<?=base_url('catalogo.php')
?>">Catálogo</a>
<a href="<?=base_url()
?>#nosotros">Nosotros</a>
<a href="<?=base_url()
?>#contacto">Contacto</a>
</nav>
<button class="menu-toggle">☰</button>
</div>
</header>
<main class="catalog-page">
<div class="container">
<div class="page-heading">
<span class="eyebrow">AP TIME / COLECCIÓN</span>
<h1>Todos los relojes</h1>
<p>Explora nuestra selección y revisa cómo puedes adquirir cada modelo.</p>
</div>
<form class="filters" method="get">
<input type="search" name="q" value="<?=e($q)
?>" placeholder="Buscar reloj...">
<select name="availability">
<option value="">Todos</option>
<option value="contra_entrega" <?=$availability==='contra_entrega'?'selected':''
?>>Contra entrega</option>
<option value="encargo" <?=$availability==='encargo'?'selected':''
?>>Bajo encargo</option>
</select>
<button class="btn btn-primary" type="submit">Buscar</button>
</form>
<?php
if($products):

?>
<div class="products-grid catalog-grid">
<?php
foreach($products as $p):

?>
<a class="product-card" href="<?=base_url('product.php?id='.(int)$p['id'])
?>">
<div class="product-image">
<span class="availability <?=availability_class($p['availability'])
?>">
<?=e(availability_label($p['availability']))
?>
</span>
<img src="<?=product_main_image($p['main_image'])
?>" alt="<?=e($p['name'])
?>" loading="lazy">
</div>
<div class="product-info">
<div>
<small>AP TIME</small>
<h3>
<?=e($p['name'])
?>
</h3>
</div>
<strong>
<?=money($p['price'])
?>
</strong>
</div>
</a>
<?php
endforeach;

?>
</div>
<?php
else:

?>
<div class="empty-store">
<span>⌕</span>
<h3>No encontramos relojes</h3>
<p>Prueba otra búsqueda o revisa todos los modelos.</p>
<a class="btn btn-ghost" href="<?=base_url('catalogo.php')
?>">Ver todos</a>
</div>
<?php
endif;

?>
</div>
</main>
<footer>
<div class="container footer-bottom">© <?=date('Y')
?> AP TIME <span>El tiempo se lleva con estilo.</span>
</div>
</footer>
<script src="<?=base_url('assets/js/app.js')
?>">
</script>
</body>
</html>
