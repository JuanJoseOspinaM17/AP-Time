<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
session_start();
require_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id)redirect(base_url('admin/dashboard.php'));
$st=$pdo->prepare('SELECT * FROM products WHERE id=?');
$st->execute([$id]);
$p=$st->fetch();
if(!$p)redirect(base_url('admin/dashboard.php'));
$activePage='';
$pageHeading='Editar reloj';
$pageTitle='AP TIME | Editar reloj';
$error='';
$imgs=$pdo->prepare('SELECT * FROM product_images WHERE product_id=? ORDER BY is_main DESC,sort_order,id');
$imgs->execute([$id]);
$images=$imgs->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']??'');
    $price=$_POST['price']??'';
    $availability=$_POST['availability']??'';
    $status=$_POST['status']??'';
    if($name===''||!is_numeric($price)||(float)$price<0)$error='Revisa el nombre y el precio.';
    elseif(!in_array($availability,['contra_entrega','encargo'],true)||!in_array($status,['active','inactive'],true))$error='Selecciona opciones válidas.';
    else {
        try {
            $pdo->beginTransaction();
            $u=$pdo->prepare('UPDATE products SET name=?,price=?,availability=?,status=? WHERE id=?');
            $u->execute([$name,(float)$price,$availability,$status,$id]);
            if(!empty($_POST['delete_images'])&&is_array($_POST['delete_images'])) {
                foreach($_POST['delete_images'] as $imageId) {
                    $q=$pdo->prepare('SELECT image FROM product_images WHERE id=? AND product_id=?');
                    $q->execute([(int)$imageId,$id]);
                    if($row=$q->fetch()) {
                        @unlink(__DIR__.'/../uploads/products/'.$row['image']);
                        $pdo->prepare('DELETE FROM product_images WHERE id=? AND product_id=?')->execute([(int)$imageId,$id]);
                    }
                }
            }
            $new=$_FILES['images']??null;
            if($new&&isset($new['name'])&&is_array($new['name'])) {
                $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
                $count=(int)$pdo->query('SELECT COUNT(*) FROM product_images WHERE product_id='.(int)$id)->fetchColumn();
                $ins=$pdo->prepare('INSERT INTO product_images(product_id,image,is_main,sort_order) VALUES(?,?,?,?)');
                foreach($new['name'] as $i=>$n) {
                    if(($new['error'][$i]??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE)continue;
                    if(($new['error'][$i]??0)!==UPLOAD_ERR_OK)throw new Exception('Una nueva imagen no pudo subirse.');
                    $tmp=$new['tmp_name'][$i];
                    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($tmp);
                    if(!isset($allowed[$mime]))throw new Exception('Solo JPG, PNG o WEBP.');
                    if((int)$new['size'][$i]>8*1024*1024)throw new Exception('Cada imagen debe pesar máximo 8 MB.');
                    $filename='product_'.$id.'_'.bin2hex(random_bytes(8)).'.'.$allowed[$mime];
                    if(!move_uploaded_file($tmp,__DIR__.'/../uploads/products/'.$filename))throw new Exception('No se pudo guardar una nueva imagen.');
                    $ins->execute([$id,$filename,$count===0&&$i===0?1:0,$count+$i]);
                }
            }
            $left=(int)$pdo->query('SELECT COUNT(*) FROM product_images WHERE product_id='.(int)$id)->fetchColumn();
            if($left===0)throw new Exception('El reloj debe conservar al menos una fotografía.');
            if(!empty($_POST['main_image'])) {
                $pdo->prepare('UPDATE product_images SET is_main=0 WHERE product_id=?')->execute([$id]);
                $pdo->prepare('UPDATE product_images SET is_main=1 WHERE id=? AND product_id=?')->execute([(int)$_POST['main_image'],$id]);
            }
            $pdo->commit();
            redirect(base_url('admin/dashboard.php'));
        } catch(Throwable $e) {
            if($pdo->inTransaction())$pdo->rollBack();
            $error='No se pudo actualizar. '.$e->getMessage();
        }
        $imgs->execute([$id]);
        $images=$imgs->fetchAll();
        $p['name']=$name;
        $p['price']=$price;
        $p['availability']=$availability;
        $p['status']=$status;
    }
}
require __DIR__.'/_header.php';

?>
<div class="form-card">
<div class="form-intro">
<span class="admin-kicker">EDITAR PRODUCTO</span>
<h2>
<?=e($p['name'])
?>
</h2>
<p>Actualiza los datos, cambia la foto principal o añade nuevas fotografías.</p>
</div>
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
<form method="post" enctype="multipart/form-data" class="product-form">
<div class="field">
<label>Nombre del reloj *</label>
<input name="name" maxlength="160" value="<?=e($p['name'])
?>" required>
</div>
<div class="form-row">
<div class="field">
<label>Precio *</label>
<input type="number" name="price" min="0" step="100" value="<?=e($p['price'])
?>" required>
</div>
<div class="field">
<label>Modalidad *</label>
<select name="availability">
<option value="contra_entrega" <?=$p['availability']==='contra_entrega'?'selected':''
?>>Contra entrega</option>
<option value="encargo" <?=$p['availability']==='encargo'?'selected':''
?>>Bajo encargo</option>
</select>
</div>
</div>
<div class="field">
<label>Visibilidad</label>
<select name="status">
<option value="active" <?=$p['status']==='active'?'selected':''
?>>Publicado en catálogo</option>
<option value="inactive" <?=$p['status']==='inactive'?'selected':''
?>>Oculto por ahora</option>
</select>
</div>
<div class="field">
<label>Fotografías actuales</label>
<div class="edit-gallery">
<?php
foreach($images as $im):

?>
<label class="edit-image">
<img src="<?=product_main_image($im['image'])
?>" alt="">
<span>
<input type="radio" name="main_image" value="<?=$im['id']
?>" <?=$im['is_main']?'checked':''
?>> Principal</span>
<span class="delete-check">
<input type="checkbox" name="delete_images[]" value="<?=$im['id']
?>"> Eliminar</span>
</label>
<?php
endforeach;

?>
</div>
</div>
<div class="field">
<label>Añadir fotografías</label>
<input class="native-file" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
<small class="field-help">Puedes añadir varias. Si eliminas todas, debes conservar al menos una.</small>
</div>
<div class="form-actions">
<a class="back-button" href="<?=base_url('admin/dashboard.php')
?>">Cancelar</a>
<button class="admin-btn" type="submit">Guardar cambios →</button>
</div>
</form>
</div>
<?php
require __DIR__.'/_footer.php';
