// BASE_URL, escapeHtml(), dan fetchWithRetry() ada di config.js

function tampilkanPesan(tbody, teks) {
    tbody.innerHTML = `<tr><td colspan="3" class="pesan">${escapeHtml(teks)}</td></tr>`;
}

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Langkah 1: indikator loading
    tampilkanPesan(tbody, 'Memuat data...');

    try {
        // Tugas 2: otomatis diulang satu kali bila jaringan gagal
        const response = await fetchWithRetry(`${BASE_URL}/mahasiswa`);
        if (!response.ok) throw new Error('Server merespons dengan status ' + response.status);
        const hasil = await response.json();

        if (!hasil.data || hasil.data.length === 0) {
            tampilkanPesan(tbody, 'Belum ada data');
            return;
        }

        // Langkah 2: semua data dinamis di-escape sebelum masuk innerHTML
        tbody.innerHTML = hasil.data.map((mhs) =>
            `<tr><td>${escapeHtml(mhs.nim)}</td><td>${escapeHtml(mhs.nama)}</td><td>${escapeHtml(mhs.prodi)}</td></tr>`
        ).join('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan(tbody, `Gagal memuat data: ${error.message}`);
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);