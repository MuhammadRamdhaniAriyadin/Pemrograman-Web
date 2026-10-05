<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Cek status pemblokiran akibat brute-force
$isBlocked = false;
$timeRemaining = 0;

if (isset($_SESSION['lockout_time'])) {
    if (time() < $_SESSION['lockout_time']) {
        $isBlocked = true;
        $timeRemaining = $_SESSION['lockout_time'] - time();
    } else {
        // Masa pemblokiran selesai, reset status
        unset($_SESSION['lockout_time']);
        unset($_SESSION['login_attempts']);
    }
}

$page_title = "Login";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Login Petugas</h2>

    <!-- Tampilkan Notifikasi Flash Error (Termasuk Sisa Percobaan Login) -->
    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <!-- Peringatan Jika Akun Sedang Terkunci -->
    <?php if ($isBlocked): ?>
        <p class="flash flash-error">
            <strong>Akses Terkunci!</strong> Terlalu banyak percobaan login yang gagal. Silakan coba lagi dalam <strong><?php echo $timeRemaining; ?></strong> detik.
        </p>
    <?php else: ?>
        <!-- Form Login Utama -->
        <form method="post" action="proses_login.php">
            <p>
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" required>
            </p>
            <p>
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" required>
            </p>
            <!-- Checkbox Ingat Saya (Dirapikan Sejajar) -->
            <p style="display: flex; align-items: center; gap: 0.5rem; margin: 10px 0;">
                <input type="checkbox" id="remember" name="remember" value="1" style="margin: 0;">
                <label for="remember" style="margin: 0; font-weight: normal;">Ingat Saya</label>
            </p>
            <p>
                <button type="submit">Masuk</button>
            </p>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
