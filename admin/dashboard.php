<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
session_start();
require_admin();
$activePage='dashboard';
$pageHeading='Dashboard';
$pageTitle='AP TIME | Dashboard';
$total=(int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$active=(int)$pdo->query("SELECT COUNT(*) FROM products WHERE status='active'")->fetchColumn();
$delivery=(int)$pdo->query("SELECT COUNT(*) FROM products WHERE availability='contra_entrega' AND status='active'")->fetchColumn();
$order=(int)$pdo->query("SELECT COUNT(*) FROM products WHERE availability='encargo' AND status='active'")->fetchColumn();
$products=$pdo->query("SELECT p.*, (SELECT image FROM product_images WHERE product_id=p.id ORDER BY is_main DESC,sort_order,id LIMIT 1) main_image, (SELECT COUNT(*) FROM product_images WHERE product_id=p.id) image_count FROM products p ORDER BY p.created_at DESC")->fetchAll();
require __DIR__.'/_header.php';

?>
<section class="stats">
<article>
<span>RELOJES</span>
<b>
<?=$total
?>
</b>
<small>Total registrados</small>
</article>
<article>
<span>PUBLICADOS</span>
<b>
<?=$active
?>
</b>
<small>Visibles en la tienda</small>
</article>
<article>
<span>CONTRA ENTREGA</span>
<b>
<?=$delivery
?>
</b>
<small>Modelos disponibles</small>
</article>
<article>
<span>BAJO ENCARGO</span>
<b>
<?=$order
?>
</b>
<small>Modelos por solicitud</small>
</article>
</section>
<section class="panel">
<div class="panel-head">
<div>
<span class="admin-kicker">CATÁLOGO</span>
<h2>Tus relojes</h2>
</div>
<a class="admin-btn small" href="<?=base_url('admin/product_create.php')
?>">＋ Agregar reloj</a>
</div>
<?php
if(!$products):

?>
<div class="admin-empty">
<span>⌚</span>
<h3>Aún no tienes relojes</h3>
<p>Agrega tu primer modelo con sus fotografías, precio y modalidad de venta.</p>
<a class="admin-btn" href="<?=base_url('admin/product_create.php')
?>">Agregar primer reloj →</a>
</div>
<?php
else:

?>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Reloj</th>
<th>Precio</th>
<th>Modalidad</th>
<th>Fotos</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php
foreach($products as $p):

?>
<tr>
<td>
<div class="product-cell">
<img src="<?=product_main_image($p['main_image'])
?>" alt="">
<div>
<b>
<?=e($p['name'])
?>
</b>
<small>Agregado <?=date('d/m/Y',strtotime($p['created_at']))
?>
</small>
</div>
</div>
</td>
<td>
<b>
<?=money($p['price'])
?>
</b>
</td>
<td>
<span class="pill <?=availability_class($p['availability'])
?>">
<?=e(availability_label($p['availability']))
?>
</span>
</td>
<td>
<?=((int)$p['image_count'])
?> foto<?=((int)$p['image_count'])===1?'':'s'
?>
</td>
<td>
<span class="pill status-<?=e($p['status'])
?>">
<?= $p['status']==='active'?'Publicado':'Oculto'
?>
</span>
</td>
<td>
<div class="actions">
<a href="<?=base_url('admin/product_edit.php?id='.$p['id'])
?>">Editar</a>
<a class="danger" data-confirm="¿Eliminar este reloj y todas sus fotografías?" href="<?=base_url('admin/product_delete.php?id='.$p['id'])
?>">Eliminar</a>
</div>
</td>
</tr>
<?php
endforeach;

?>
</tbody>
</table>
</div>
<?php
endif;

?>
</div>
<?php
require __DIR__.'/_footer.php';
