<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
session_start();
require_admin();
$activePage='new';
$pageHeading='Nuevo reloj';
$pageTitle='AP TIME | Nuevo reloj';
$error='';
$old=['name'=>'','price'=>'','availability'=>'contra_entrega','status'=>'active'];
if($_SERVER['REQUEST_METHOD']==='POST') {
    $old['name']=trim($_POST['name']??'');
    $old['price']=$_POST['price']??'';
    $old['availability']=$_POST['availability']??'contra_entrega';
    $old['status']=$_POST['status']??'active';
    $files=$_FILES['images']??null;
    $valid=[];
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if($old['name']==='')$error='El nombre del reloj es obligatorio.';
    elseif(!is_numeric($old['price'])||(float)$old['price']<0)$error='Ingresa un precio válido.';
    elseif(!in_array($old['availability'],['contra_entrega','encargo'],true))$error='Selecciona una modalidad válida.';
    elseif(!in_array($old['status'],['active','inactive'],true))$error='Estado inválido.';
    elseif(!$files||!isset($files['name'])||!is_array($files['name']))$error='Selecciona al menos una fotografía.';
    if(!$error) {
        foreach($files['name'] as $i=>$name) {
            if(($files['error'][$i]??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE)continue;
            if(($files['error'][$i]??0)!==UPLOAD_ERR_OK) {
                $error='Una de las fotografías no pudo subirse.';
                break;
            }
            $tmp=$files['tmp_name'][$i];
            $mime=(new finfo(FILEINFO_MIME_TYPE))->file($tmp);
            $size=(int)$files['size'][$i];
            if(!isset($allowed[$mime])) {
                $error='Solo se permiten JPG, PNG o WEBP.';
                break;
            }
            if($size>8*1024*1024) {
                $error='Cada imagen debe pesar máximo 8 MB.';
                break;
            }
            $valid[]=['tmp'=>$tmp,'ext'=>$allowed[$mime]];
        }
    }
    if(!$error&&!$valid)$error='Selecciona al menos una fotografía.';
    if(!$error) {
        try {
            $pdo->beginTransaction();
            $st=$pdo->prepare("INSERT INTO products(name,price,availability,status) VALUES(?,?,?,?)");
            $st->execute([$old['name'],(float)$old['price'],$old['availability'],$old['status']]);
            $id=(int)$pdo->lastInsertId();
            $dir=__DIR__.'/../uploads/products/';
            if(!is_dir($dir)&&!mkdir($dir,0755,true))throw new Exception('No se pudo crear la carpeta de imágenes.');
            $is=$pdo->prepare("INSERT INTO product_images(product_id,image,is_main,sort_order) VALUES(?,?,?,?)");
            foreach($valid as $i=>$f) {
                $filename='product_'.$id.'_'.bin2hex(random_bytes(8)).'.'.$f['ext'];
                if(!move_uploaded_file($f['tmp'],$dir.$filename))throw new Exception('No se pudo guardar una fotografía.');
                $is->execute([$id,$filename,$i===0?1:0,$i]);
            }
            $pdo->commit();
            redirect(base_url('admin/dashboard.php'));
        } catch(Throwable $e) {
            if($pdo->inTransaction())$pdo->rollBack();
            $error='No se pudo guardar el reloj. '.$e->getMessage();
        }
    }
}
require __DIR__.'/_header.php';

?>
<div class="form-card">
<div class="form-intro">
<span class="admin-kicker">NUEVO PRODUCTO</span>
<h2>Agrega un reloj a AP TIME</h2>
<p>Solo necesitas nombre, precio, modalidad y sus fotografías.</p>
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
<input name="name" maxlength="160" placeholder="Ej. Technomarine Aqua" value="<?=e($old['name'])
?>" required>
</div>
<div class="form-row">
<div class="field">
<label>Precio *</label>
<input type="number" name="price" min="0" step="100" placeholder="250000" value="<?=e($old['price'])
?>" required>
</div>
<div class="field">
<label>Modalidad *</label>
<select name="availability">
<option value="contra_entrega" <?=$old['availability']==='contra_entrega'?'selected':''
?>>Contra entrega</option>
<option value="encargo" <?=$old['availability']==='encargo'?'selected':''
?>>Bajo encargo</option>
</select>
</div>
</div>
<div class="field">
<label>Visibilidad</label>
<select name="status">
<option value="active">Publicado en catálogo</option>
<option value="inactive">Oculto por ahora</option>
</select>
</div>
<div class="field">
<label>Fotografías *</label>
<div class="upload-box">
<input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required>
<label for="images">
<strong>＋ Seleccionar fotografías</strong>
<span>JPG, PNG o WEBP · hasta 8 MB cada una</span>
<small>La primera fotografía seleccionada será la principal.</small>
</label>
</div>
<div id="filePreview" class="file-preview">
</div>
</div>
<div class="form-actions">
<a class="back-button" href="<?=base_url('admin/dashboard.php')
?>">Cancelar</a>
<button class="admin-btn" type="submit">Guardar reloj →</button>
</div>
</form>
</div>
<script>const input=document.getElementById('images'),preview=document.getElementById('filePreview');input?.addEventListener('change',()=>{preview.innerHTML='';[...input.files].forEach((f,i)=>{const r=new FileReader();r.onload=()=>{preview.insertAdjacentHTML('beforeend',`<div>
<img src="${r.result}" alt="">
<span>${i===0?'Principal · ':''}${f.name}</span>
</div>`)};r.readAsDataURL(f)})});</script>
<?php
require __DIR__.'/_footer.php';
