// Acara 25: dashboard admin (tampil data, ringkasan, CRUD, logout, RBAC di sisi tampilan)
const token = localStorage.getItem('token');
const role = localStorage.getItem('role');
const isAdmin = role === 'admin';

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const pesan = document.querySelector('#pesan');
const form = document.querySelector('#form-mahasiswa');
let idDiedit = null;

document.querySelector('#role-label').textContent = role;

// Tugas 2: sembunyikan kontrol tambah/ubah/hapus bila role = user
// (pelengkap saja, proteksi sebenarnya ada di Auth::requireAdmin() pada backend)
if (!isAdmin) {
    document.querySelector('#panel-form').style.display = 'none';
    document.querySelector('.kolom-aksi').style.display = 'none';
}

// Tugas 1: logout
document.querySelector('#btn-logout').addEventListener('click', () => {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    window.location.href = 'login.html';
});

function tampilPesan(teks, jenis) {
    pesan.textContent = teks;
    pesan.className = 'pesan ' + jenis;
}

// Wrapper fetch: otomatis menambah header Authorization dan menangani 401
async function api(path, options = {}) {
    const res = await fetch(`${BASE_URL}${path}`, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
    });
    const hasil = await res.json();

    if (res.status === 401) {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        alert('Sesi berakhir, silakan login kembali.');
        window.location.href = 'login.html';
    }
    return hasil;
}

function pesanError(hasil) {
    let teks = hasil.message;
    if (hasil.errors) {
        teks += ': ' + Object.values(hasil.errors).join('; ');
    }
    return teks;
}

async function muatData() {
    const kolom = isAdmin ? 4 : 3;
    tbody.innerHTML = `<tr><td colspan="${kolom}" class="info">Memuat data...</td></tr>`;

    try {
        const hasil = await api('/mahasiswa');
        const data = hasil.data || [];

        // Langkah 5: ringkasan jumlah mahasiswa
        document.querySelector('#total-mahasiswa').textContent = data.length;

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="${kolom}" class="info">Belum ada data</td></tr>`;
            return;
        }

        tbody.innerHTML = '';
        data.forEach((m) => {
            const aksi = isAdmin
                ? `<td>
                       <button data-aksi="ubah" data-id="${m.id}">Ubah</button>
                       <button data-aksi="hapus" data-id="${m.id}" class="danger">Hapus</button>
                   </td>`
                : '';
            tbody.innerHTML += `<tr>
                <td>${escapeHtml(m.nim)}</td>
                <td>${escapeHtml(m.nama)}</td>
                <td>${escapeHtml(m.prodi)}</td>${aksi}</tr>`;
        });
        window.dataMahasiswa = data;
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="${kolom}">Gagal memuat data dari server.</td></tr>`;
    }
}

function resetForm() {
    idDiedit = null;
    form.reset();
    document.querySelector('#judul-form').textContent = 'Tambah Mahasiswa';
    document.querySelector('#btn-simpan').textContent = 'Tambah';
    document.querySelector('#btn-batal').style.display = 'none';
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const body = JSON.stringify({
        nim: document.querySelector('#nim').value,
        nama: document.querySelector('#nama').value,
        prodi: document.querySelector('#prodi').value,
    });

    const hasil = idDiedit
        ? await api(`/mahasiswa?id=${idDiedit}`, { method: 'PUT', body })
        : await api('/mahasiswa', { method: 'POST', body });

    if (hasil.success) {
        tampilPesan(hasil.message, 'ok');
        resetForm();
        muatData();
    } else {
        tampilPesan(pesanError(hasil), 'error');
    }
});

document.querySelector('#btn-batal').addEventListener('click', resetForm);

tbody.addEventListener('click', async (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const id = btn.dataset.id;

    if (btn.dataset.aksi === 'ubah') {
        const m = window.dataMahasiswa.find((x) => String(x.id) === id);
        idDiedit = id;
        document.querySelector('#nim').value = m.nim;
        document.querySelector('#nama').value = m.nama;
        document.querySelector('#prodi').value = m.prodi;
        document.querySelector('#judul-form').textContent = 'Ubah Mahasiswa';
        document.querySelector('#btn-simpan').textContent = 'Simpan Perubahan';
        document.querySelector('#btn-batal').style.display = 'inline-block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (btn.dataset.aksi === 'hapus') {
        if (!confirm('Yakin hapus data ini?')) return;
        const hasil = await api(`/mahasiswa?id=${id}`, { method: 'DELETE' });
        tampilPesan(hasil.success ? hasil.message : pesanError(hasil), hasil.success ? 'ok' : 'error');
        muatData();
    }
});

document.addEventListener('DOMContentLoaded', muatData);
