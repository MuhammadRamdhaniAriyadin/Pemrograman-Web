<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
} elseif (strlen($nama) < 3) {
    $errors[] = "Nama minimal 3 karakter.";
} elseif (!preg_match('/^[a-zA-Z\s\.\']+$/', $nama)) {
    $errors[] = "Nama hanya boleh berisi huruf, spasi, titik, dan petik.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} elseif (!preg_match('/^[a-zA-Z0-9-]+$/', $noAnggota)) {
    $errors[] = "No. Anggota hanya boleh berisi huruf, angka, dan tanda hubung (-).";
}
if ($noHp !== '' && !preg_match('/^\+?[0-9]{10,15}$/', $noHp)) {
    $errors[] = "No. HP harus berupa angka 10-15 digit (boleh diawali +).";
}
if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;               
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$_SESSION['anggota'][] = [
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;
