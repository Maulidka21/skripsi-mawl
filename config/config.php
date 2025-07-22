<?php
function base_url($path = '') {
    $base = 'http://localhost/spk_aras'; 
    return $base . '/' . ltrim($path, '/');
}

function base_path($path = '') {
    return $_SERVER['DOCUMENT_ROOT'] . '/spk_aras/' . $path;
}

function cek_admin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: ' . base_url('login/login.php'));
        exit();
    }
}