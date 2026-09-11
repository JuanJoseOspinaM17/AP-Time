<?php
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function base_url(string $path=''): string {
    $base='/AP_TIME';
    return $path===''?$base:$base.'/'.ltrim($path,'/');
}
function redirect(string $url): never {
    header('Location: '.$url);
    exit;
}
function money($amount): string {
    return '$'.number_format((float)$amount,0,',','.');
}
function require_admin(): void {
    if (empty($_SESSION['admin_id'])) redirect(base_url('admin/login.php'));
}
function product_main_image(?string $image): string {
    return $image ? base_url('uploads/products/'.rawurlencode($image)) : base_url('assets/img/no-watch.svg');
}
function availability_label(string $availability): string {
    return $availability==='encargo' ? 'Bajo encargo' : 'Contra entrega';
}
function availability_class(string $availability): string {
    return $availability==='encargo' ? 'order' : 'delivery';
}
