// BASE_URL dihitung otomatis dari alamat halaman, sehingga tetap benar
// walaupun nama folder proyek berbeda (contoh: /tugas%20acara_cya/Acara25/public)
const BASE_URL = window.location.origin +
    window.location.pathname.replace(/\/views\/[^/]*$/, '');

function escapeHtml(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

// ===== Acara 29 =====

// Tugas Mandiri 2: percobaan ulang otomatis satu kali bila fetch gagal karena
// masalah jaringan sementara. fetch() hanya melempar error pada kegagalan
// jaringan; respons HTTP 4xx/5xx tidak diulang.
async function fetchWithRetry(url, options = {}, retries = 1) {
    try {
        return await fetch(url, options);
    } catch (error) {
        if (retries > 0) {
            await new Promise((resolve) => setTimeout(resolve, 800));
            return fetchWithRetry(url, options, retries - 1);
        }
        throw error;
    }
}

// Langkah 3: nonaktifkan tombol selama permintaan berlangsung (dipanggil di try
// dan dikembalikan di finally agar tombol tidak pernah tersangkut nonaktif).
function setBusy(tombol, sibuk, teksSibuk = 'Memproses...') {
    if (!tombol) return;
    if (sibuk) {
        tombol.dataset.teksAsli = tombol.textContent;
        tombol.disabled = true;
        tombol.textContent = teksSibuk;
    } else {
        tombol.disabled = false;
        if (tombol.dataset.teksAsli) tombol.textContent = tombol.dataset.teksAsli;
    }
}