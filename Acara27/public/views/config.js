// BASE_URL dihitung otomatis dari alamat halaman, sehingga tetap benar
// walaupun nama folder proyek berbeda (contoh: /tugas%20acara_cya/Acara25/public)
const BASE_URL = window.location.origin +
    window.location.pathname.replace(/\/views\/[^/]*$/, '');

function escapeHtml(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}
