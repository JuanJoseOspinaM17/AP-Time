<?php
require_once __DIR__.'/config/helpers.php';

?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>AP TIME | Nosotros</title>
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
<a href="<?=base_url('catalogo.php')
?>">Catálogo</a>
<a class="active" href="<?=base_url('nosotros.php')
?>">Nosotros</a>
<a href="<?=base_url('contacto.php')
?>">Contacto</a>
</nav>
</div>
</header>
<main class="simple-page">
<div class="container">
<span class="eyebrow">AP TIME</span>
<h1>Tu tiempo. Tu estilo.</h1>
<p>Somos una tienda enfocada en relojes con diseño y presencia. Nuestro catálogo combina modelos disponibles contra entrega con referencias que manejamos bajo encargo.</p>
<p>Queremos que consultar y elegir tu próximo reloj sea una experiencia sencilla, clara y profesional.</p>
<a class="btn btn-primary" href="<?=base_url('catalogo.php')
?>">Ver catálogo →</a>
</div>
</main>
</body>
</html>
