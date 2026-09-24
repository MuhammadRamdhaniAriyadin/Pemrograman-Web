// ===== Hamburger menu (JS-driven, menggantikan checkbox hack) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

function updateTableCounter() {
    const counterEl = document.getElementById("table-counter");
    const table = document.querySelector(".table-responsive table");
    if (!counterEl || !table) return;

    const totalRows = table.querySelectorAll("tbody tr");
    let visibleRowsCount = 0;

    totalRows.forEach(function (row) {
        if (row.style.display !== "none") {
            visibleRowsCount++;
        }
    });

    counterEl.textContent = "Menampilkan " + visibleRowsCount + " dari " + totalRows.length + " data";
}

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
function initHapusConfirm() {
    document.querySelectorAll(".btn-hapus").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const nama = row ? row.querySelector("td")?.textContent : "data ini";
            const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
            if (yakin && row) {
                row.remove();       
                updateTableCounter(); // Perbarui counter 
            }
        });
    });
}

// ===== Filter/pencarian tabel real-time =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            //row.textContent menjadi row.querySelector("td").//
            const kolomPertama = row.querySelector("td");
            const teks = kolomPertama ? kolomPertama.textContent.toLowerCase() : "";
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
        updateTableCounter(); // Perbarui counter setelah difilter
    });
}

// ===== Validasi form (client-side) =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

// ===== Refactor Validasi Form =====
function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;
    form.addEventListener("submit", function (e) {
        let valid = true;
        // Array konfigurasi aturan validasi
        const rules = [
            {
                selector: "[name='judul'], [name='nama']",
                test: (val) => val.trim() !== "",
                message: "Field ini wajib diisi."
            },
            {
                selector: "[name='pengarang']",
                test: (val) => val.trim() !== "",
                message: "Pengarang wajib diisi."
            },
            {
                selector: "[name='tahun']",
                test: (val) => {
                    const nilai = parseInt(val, 10);
                    return !isNaN(nilai) && nilai >= 1900 && nilai <= 2026;
                },
                message: "Tahun harus di antara 1900-2026."
            },
            {
                selector: "[name='isbn']",
                test: (val) => {
                    if (val.trim() === "") return true; // ISBN opsional jika kosong
                    return /^[0-9-]+$/.test(val.trim());
                },
                message: "ISBN hanya boleh berisi angka dan tanda hubung (-)."
            },
            {
                selector: "[name='stok']",
                test: (val) => {
                    const nilai = parseInt(val, 10);
                    return !isNaN(nilai) && nilai >= 0;
                },
                message: "Stok tidak boleh negatif."
            }
        ];

        // Eksekusi perulangan validasi untuk setiap elemen
        rules.forEach(function (rule) {
            const input = form.querySelector(rule.selector);
            if (input) {
                if (!rule.test(input.value)) {
                    tampilkanError(input, rule.message);
                    valid = false;
                } else {
                    hapusError(input);
                }
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
}
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
    updateTableCounter(); // Jalankan counter saat halaman pertama kali dimuat
});
