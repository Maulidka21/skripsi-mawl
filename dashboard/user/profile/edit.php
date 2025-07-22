<?php
session_start();
include '../../../config/koneksi.php';
include '../../../config/config.php';

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $umur = $_POST['umur'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Update users
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $queryUser = "UPDATE users SET username='$username', password='$hashed' WHERE id='$userId'";
    } else {
        $queryUser = "UPDATE users SET username='$username' WHERE id='$userId'";
    }
    mysqli_query($koneksidb, $queryUser);

    // Cek apakah data alternatif ada
    $cekAlt = mysqli_query($koneksidb, "SELECT id FROM alternatives WHERE user_id = '$userId'");
    if (mysqli_num_rows($cekAlt) > 0) {
        $queryAlt = "UPDATE alternatives SET name='$name', code='$code', alamat='$alamat', no_hp='$no_hp', umur='$umur' WHERE user_id='$userId'";
    } else {
        $queryAlt = "INSERT INTO alternatives (user_id, name, code, alamat, no_hp, umur) VALUES ('$userId', '$name', '$code', '$alamat', '$no_hp', '$umur')";
    }
    mysqli_query($koneksidb, $queryAlt);

    header('Location: ' . base_url('dashboard/user/profile/index.php?success=update'));
    exit;
}

// Ambil data sekarang
$query = "SELECT u.username, a.name, a.code, a.alamat, a.no_hp, a.umur
          FROM users u
          LEFT JOIN alternatives a ON a.user_id = u.id
          WHERE u.id = '$userId'";
$result = mysqli_query($koneksidb, $query);
$data = mysqli_fetch_assoc($result);

$page_title = 'Halaman Edit Profile';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/user')],
    ['title' => 'Profile', 'link' => base_url('dashboard/user/profile')],
    ['title' => 'Edit Profile']
];

$content = base_path('dashboard/user/profile/edit_content.php');
include base_path('layout/main.php');