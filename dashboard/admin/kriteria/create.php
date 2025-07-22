<?php
include '../../../config/config.php';

$page_title = 'Tambah Kriteria';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Manajemen Kriteria', 'link' => base_url('dashboard/admin/kriteria/index.php')],
    ['title' => 'Tambah Kriteria']
];

$content = base_path('dashboard/admin/kriteria/create_content.php');
include base_path('layout/main.php');
