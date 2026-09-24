<?php
session_start();

// 1. Kosongkan variabel 
session_unset();

// 2. Hapus data
session_destroy();

// 3. Mulai session baru 
session_start();
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Seluruh data session berhasil dikosongkan.'
];

// 4. Redirect kembali ke beranda 
header('Location: index.php');
exit;