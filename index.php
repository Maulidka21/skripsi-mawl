<?php
session_start();
include 'config/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ' . base_url('login/login.php'));
    exit();
}
?>