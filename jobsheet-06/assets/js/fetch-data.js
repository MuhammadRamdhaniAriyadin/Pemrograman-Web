/**
 * mengambil dan menampilkan data json ke tabel
 * @param {string} urlJson - Path menuju file json
 * @param {Array<string>} keys - array nama kunci objek yang ingin ditampilkan */

async function muatDaftarData(urlJson, keys) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        // Ganti delay
        await new Promise((resolve) => setTimeout(resolve, 3000));

        const res = await fetch(urlJson);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const dataList = await res.json();

        dataList.forEach(function (item) {
            const tr = document.createElement("tr");
            let rowHtml = "";

            // generasikan <td> 
            keys.forEach(function (key) {
                const val = item[key] !== undefined && item[key] !== null ? item[key] : "-";
                rowHtml += "<td>" + val + "</td>";
            });

            // tambahkan kolom edit dan hapus
            rowHtml += 
                "<td>" +
                '<button type="button" class="btn-edit">Edit</button> ' +
                '<button type="button" class="btn-hapus">Hapus</button>' +
                "</td>";

            tr.innerHTML = rowHtml;
            tbody.appendChild(tr);
        });
    } catch (err) {
        tbody.innerHTML =
            '<tr><td colspan="' + (keys.length + 1) + '" style="text-align:center; color:#d9534f;">Gagal memuat data: ' + err.message + "</td></tr>";
    } finally {
        if (loading) loading.style.display = "none";
    }
}