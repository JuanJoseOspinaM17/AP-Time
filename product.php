<?php
require_once __DIR__.'/config/database.php';
require_once __DIR__.'/config/helpers.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id) redirect(base_url('catalogo.php'));
$st=$pdo->prepare("SELECT id,name,price,availability FROM products WHERE id=? AND status='active' LIMIT 1");
$st->execute([$id]);
$product=$st->fetch();
if(!$product) {
    http_response_code(404);
    die('Producto no encontrado. <a href="'.e(base_url('catalogo.php')).'">Volver</a>');
}
$im=$pdo->prepare("SELECT image FROM product_images WHERE product_id=? ORDER BY is_main DESC,sort_order ASC,id ASC");
$im->execute([$id]);
$images=$im->fetchAll();
$msg=urlencode('Hola AP TIME, estoy interesado en el reloj '.$product['name'].' ('.money($product['price']).').');

?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>
<?=e($product['name'])
?> | AP TIME</title>
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
</nav>
</div>
</header>
<main class="product-page">
<div class="container product-detail">
<div class="gallery">
<div class="gallery-main">
<span class="availability <?=availability_class($product['availability'])
?>">
<?=e(availability_label($product['availability']))
?>
</span>
<img id="mainProductImage" src="<?=product_main_image($images[0]['image']??null)
?>" alt="<?=e($product['name'])
?>">
</div>
<?php
if(count($images)>1):

?>
<div class="thumbs">
<?php
foreach($images as $i=>$img):

?>
<button type="button" class="thumb <?=$i===0?'selected':''
?>" data-image="<?=product_main_image($img['image'])
?>">
<img src="<?=product_main_image($img['image'])
?>" alt="Vista <?=($i+1)
?>">
</button>
<?php
endforeach;

?>
</div>
<?php
endif;

?>
</div>
<div class="product-copy">
<span class="eyebrow">AP TIME / RELOJ</span>
<h1>
<?=e($product['name'])
?>
</h1>
<div class="detail-price">
<?=money($product['price'])
?>
</div>
<div class="availability-box">
<span class="dot">
</span>
<div>
<b>
<?=e(availability_label($product['availability']))
?>
</b>
<small>
<?= $product['availability']==='encargo'?'Este modelo se solicita bajo encargo. Consulta tiempos y disponibilidad.':'Modelo disponible para compra contra entrega, sujeto a disponibilidad.' 
?>
</small>
</div>
</div>
<a class="btn btn-primary full" target="_blank" href="https://wa.me/573052882471?text=<?=$msg
?>">Consultar este reloj por WhatsApp ↗</a>
<a class="back-link" href="<?=base_url('catalogo.php')
?>">← Volver al catálogo</a>
</div>
</div>
</main>
<script src="<?=base_url('assets/js/app.js')
?>">
</script>
</body>
</html>
