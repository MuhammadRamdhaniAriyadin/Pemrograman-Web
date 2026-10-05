<?php
$host = "localhost";
$port = "5432";
$db   = "simpus_mini";
$user = "postgres";
$pass = "Arleb123#";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Catat detail error teknis/database ke log server secara internal
    error_log("Database Connection Error: " . $e->getMessage());

    // Tampilkan pesan generik yang aman kepada pengguna tanpa membocorkan detail sensitif
    die("Koneksi database gagal. Silakan hubungi administrator.");
}
