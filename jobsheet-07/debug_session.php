<?php
session_start();

$page_title = "Debug Session";
include __DIR__ . '/includes/header.php';
?>

<section>
    <h2>Debug Isi $_SESSION Mentah</h2>
    <p>Halaman ini menampilkan seluruh data yang sedang tersimpan dalam session di server saat ini.</p>
    
    <div style="margin-top: 1rem; margin-bottom: 1rem;">
        <!-- Tombol untuk mereset/membersihkan session jika ingin tes dari awal -->
        <form method="post" style="display: inline;">
            <button type="submit" name="reset_session" value="1" style="background-color: #d9534f; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">
                Reset Session (Hapus Semua Data)
            </button>
        </form>
    </div>

    <?php
    // Tangani aksi reset session jika tombol diklik
    if (isset($_POST['reset_session'])) {
        session_unset();
        session_destroy();
        session_start();
        echo '<p class="flash flash-success">Session berhasil dibersihkan!</p>';
    }
    ?>

    <h3>Struktur Data:</h3>
    <pre style="background-color: #2b2b2b; color: #f8f8f2; padding: 1rem; border-radius: 6px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;"><?php print_r($_SESSION); ?></pre>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>