<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();

$page_title = 'Hasil Perhitungan ARAS';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Hasil ARAS']
];

$content = base_path('dashboard/admin/hasil/home.php');
include base_path('layout/main.php');
