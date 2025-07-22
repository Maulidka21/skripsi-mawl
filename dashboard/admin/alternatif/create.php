<?php
include '../../../config/config.php';

$page_title = 'Tambah Alternatif';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Manajemen Alternatif', 'link' => base_url('dashboard/admin/alternatif/index.php')],
    ['title' => 'Tambah Alternatif']
];

$content = base_path('dashboard/admin/alternatif/create_content.php');
include base_path('layout/main.php');
