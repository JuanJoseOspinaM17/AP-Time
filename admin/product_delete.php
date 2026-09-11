<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
session_start();
require_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if($id) {
    $st=$pdo->prepare('SELECT image FROM product_images WHERE product_id=?');
    $st->execute([$id]);
    foreach($st->fetchAll() as $im)@unlink(__DIR__.'/../uploads/products/'.$im['image']);
    $pdo->prepare('DELETE FROM products WHERE id=?')->execute([$id]);
}
redirect(base_url('admin/dashboard.php'));
