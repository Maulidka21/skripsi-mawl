<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

$page_title = 'Penilaian Alternatif';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Penilaian Alternatif']
];

$content = base_path('dashboard/admin/penilaian/home.php');
include base_path('layout/main.php');
