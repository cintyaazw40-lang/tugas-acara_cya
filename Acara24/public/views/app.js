// Sesuaikan dengan alamat project-mu di localhost
const BASE_URL = 'http://localhost/tugas%20acara_cya/Acara24/public';

function tampilkanPesan(tbody, teks) {
    tbody.innerHTML = `<tr><td colspan="3" class="pesan">${teks}</td></tr>`;
}

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Tugas 1: indikator loading
    tampilkanPesan(tbody, 'Memuat data...');

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        // Tugas 2: data kosong
        if (!hasil.data || hasil.data.length === 0) {
            tampilkanPesan(tbody, 'Belum ada data');
            return;
        }

        tbody.innerHTML = '';
        hasil.data.forEach((mhs) => {
            tbody.innerHTML +=
                `<tr><td>${mhs.nim}</td><td>${mhs.nama}</td><td>${mhs.prodi}</td></tr>`;
        });
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan(tbody, 'Gagal memuat data');
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);