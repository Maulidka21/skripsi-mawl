<?php
include '../../../config/config.php';
$page_title = 'Edit User';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'User Management', 'link' => base_url('dashboard/admin/user/index.php')],
    ['title' => 'Edit User']
];
$content = base_path('dashboard/admin/user/edit_content.php');
include base_path('layout/main.php');
?>
