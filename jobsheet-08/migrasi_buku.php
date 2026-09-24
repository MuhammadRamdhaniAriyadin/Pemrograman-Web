<?php
require __DIR__ . '/includes/koneksi.php';

// 1. Tentukan path file JSON data lama
$jsonFile = __DIR__ . '/../jobsheet-06/data/buku.json';

// Check file availability
if (!file_exists($jsonFile)) {
    die("File $jsonFile tidak ditemukan. Pastikan path file sudah benar.");
}

// 2. Baca file JSON dan decode menjadi array PHP
$jsonData = file_get_contents($jsonFile);
$daftarBuku = json_decode($jsonData, true);

if (!is_array($daftarBuku)) {
    die("Gagal membaca data JSON atau format JSON tidak valid.");
}

// 3. Siapkan query INSERT (menggunakan prepared statement agar aman)
$stmt = $pdo->prepare("
    INSERT INTO buku (judul, pengarang, tahun, stok)
    VALUES (:judul, :pengarang, :tahun, :stok)
");

$berhasil = 0;
$gagal = 0;

// 4. Iterasi seluruh data JSON dan masukkan ke database PostgreSQL
foreach ($daftarBuku as $buku) {
    try {
        $stmt->execute([
            'judul'     => $buku['judul'] ?? '',
            'pengarang' => $buku['pengarang'] ?? '',
            'tahun'     => (int)($buku['tahun'] ?? 0),
            'stok'      => (int)($buku['stok'] ?? 0),
        ]);
        $berhasil++;
    } catch (PDOException $e) {
        $gagal++;
        echo "Gagal migrasi buku '{$buku['judul']}': " . $e->getMessage() . "<br>";
    }
}

echo "<h3>Migrasi Selesai!</h3>";
echo "Berhasil diimpor: $berhasil data.<br>";
echo "Gagal diimpor: $gagal data.<br>";
