<?php
include '../../../config/config.php';

$page_title = 'Edit Alternatif';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Manajemen Alternatif', 'link' => base_url('dashboard/admin/alternatif/index.php')],
    ['title' => 'Edit Alternatif']
];

$content = base_path('dashboard/admin/alternatif/edit_content.php');
include base_path('layout/main.php');
