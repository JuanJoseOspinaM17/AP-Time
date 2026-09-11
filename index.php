<?php
require_once __DIR__.'/config/database.php';
require_once __DIR__.'/config/helpers.php';
$stmt=$pdo->query("SELECT p.id,p.name,p.price,p.availability,(SELECT image FROM product_images WHERE product_id=p.id ORDER BY is_main DESC,sort_order ASC,id ASC LIMIT 1) main_image FROM products p WHERE p.status='active' ORDER BY p.created_at DESC LIMIT 8");
$products=$stmt->fetchAll();

?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="AP TIME — Catálogo de relojes.">
<title>AP TIME | Relojes</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url('assets/css/style.css?v=20260910')
?>">
</head>
<body>
<header class="site-header">
<div class="container header-inner">
<a class="brand" href="<?=base_url()
?>">
<img src="<?=base_url('uploads/products/ap-time-logo.jpg')
?>" onerror="this.style.display='none'">
<span>
<b>AP</b> TIME</span>
</a>
<nav>
<a class="active" href="<?=base_url()
?>">Inicio</a>
<a href="<?=base_url('catalogo.php')
?>">Catálogo</a>
<a href="#nosotros">Nosotros</a>
<a href="#contacto">Contacto</a>
</nav>
<button class="menu-toggle" aria-label="Menú">☰</button>
</div>
</header>
<main>
<section class="hero">
<div class="hero-glow">
</div>
<div class="container hero-grid">
<div>
<span class="eyebrow">AP TIME · WATCH COLLECTION</span>
<h1>El tiempo se lleva <em>con estilo.</em>
</h1>
<p>Descubre relojes seleccionados para destacar en cada momento. Elige tu modelo y consulta disponibilidad directamente.</p>
<div class="hero-actions">
<a class="btn btn-primary" href="<?=base_url('catalogo.php')
?>">Explorar catálogo <span>→</span>
</a>
<a class="btn btn-ghost" href="#nosotros">Conocer AP TIME</a>
</div>
</div>
<div class="hero-mark">
<span>AP</span>
<strong>TIME</strong>
<small>PRECISIÓN · ESTILO · PRESENCIA</small>
</div>
</div>
</section>
<section class="trust">
<div class="container trust-grid">
<div>
<b>01</b>
<span>Selección cuidada</span>
</div>
<div>
<b>02</b>
<span>Compra contra entrega</span>
</div>
<div>
<b>03</b>
<span>Modelos bajo encargo</span>
</div>
<div>
<b>04</b>
<span>Atención personalizada</span>
</div>
</div>
</section>
<section class="catalog-section" id="catalogo">
<div class="container">
<div class="section-head">
<div>
<span class="eyebrow">COLECCIÓN</span>
<h2>Relojes destacados</h2>
</div>
<a class="text-link" href="<?=base_url('catalogo.php')
?>">Ver catálogo completo →</a>
</div>
<?php
if($products):

?>
<div class="products-grid">
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
<span>⌚</span>
<h3>La colección está por llegar</h3>
<p>Estamos preparando nuestros primeros relojes. Muy pronto podrás verlos aquí.</p>
<a class="btn btn-primary" href="<?=base_url('admin/login.php')
?>">Acceso administrador</a>
</div>
<?php
endif;

?>
</div>
</section>
<section class="about" id="nosotros">
<div class="container about-grid">
<div class="about-logo">
<b>AP</b>
<span>TIME</span>
</div>
<div>
<span class="eyebrow">SOBRE AP TIME</span>
<h2>Tu tiempo. <em>Tu estilo.</em>
</h2>
<p>AP TIME es un catálogo pensado para mostrar relojes con presencia, diseño y personalidad. Seleccionamos cada pieza para que encontrar tu próximo reloj sea sencillo.</p>
<p>Consulta cualquier modelo y te confirmaremos si está disponible contra entrega o si se maneja bajo encargo.</p>
</div>
</div>
</section>
<section class="contact" id="contacto">
<div class="container contact-card">
<div>
<span class="eyebrow">CONTACTO</span>
<h2>¿Encontraste tu reloj?</h2>
<p>Escríbenos para consultar disponibilidad, precio y detalles del modelo.</p>
</div>
<a class="btn btn-primary" target="_blank" href="https://wa.me/573052882471">Consultar por WhatsApp ↗</a>
</div>
</section>
</main>
<footer>
<div class="container">
<div class="footer-top">
<div class="brand footer-brand">
<span>
<b>AP</b> TIME</span>
<small>El tiempo se lleva con estilo.</small>
</div>
<div>
<b>AP TIME</b>
<a href="<?=base_url('catalogo.php')
?>">Catálogo</a>
<a href="#nosotros">Nosotros</a>
<a href="#contacto">Contacto</a>
</div>
</div>
<div class="footer-bottom">© <?=date('Y')
?> AP TIME <span>Hecho para destacar.</span>
</div>
</div>
</footer>
<script src="<?=base_url('assets/js/app.js')
?>">
</script>
</body>
</html>
