<?php
include '../../../config/config.php';
$page_title = 'Tambah User';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'User Management', 'link' => base_url('dashboard/admin/user/index.php')],
    ['title' => 'Tambah User']
];
$content = base_path('dashboard/admin/user/create_content.php');
include base_path('layout/main.php');

