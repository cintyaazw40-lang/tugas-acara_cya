'use strict';

const API_BASE_URL = new URL('../', window.location.href).href.replace(/\/$/, '');
const token = localStorage.getItem('token');
const role = localStorage.getItem('role');
const isAdmin = role === 'admin';
const statusElement = document.querySelector('#status');
const listElement = document.querySelector('#mahasiswa-list');
const form = document.querySelector('#mahasiswa-form');
const management = document.querySelector('#management');
let editingId = null;

if (!token || !role) {
  window.location.replace('login.html');
} else {
  document.querySelector('#role-label').textContent = role;
  management.hidden = !isAdmin;
  document.querySelector('#actions-heading').hidden = !isAdmin;
  loadMahasiswa();
}

function setStatus(message, isError = false) {
  statusElement.textContent = message;
  statusElement.classList.toggle('error', isError);
}

function logout() {
  localStorage.removeItem('token');
  localStorage.removeItem('role');
  window.location.replace('login.html');
}

async function apiRequest(path, options = {}) {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers: {
      Accept: 'application/json',
      ...(options.body ? { 'Content-Type': 'application/json' } : {}),
      Authorization: `Bearer ${token}`,
      ...options.headers,
    },
  });
  const result = await response.json();
  if (!response.ok || !result.success) {
    if (response.status === 401) logout();
    throw new Error(result.message || `Request gagal (${response.status}).`);
  }
  return result;
}

function makeCell(value) {
  const cell = document.createElement('td');
  cell.textContent = String(value ?? '');
  return cell;
}

async function loadMahasiswa() {
  setStatus('Memuat data...');
  listElement.replaceChildren();
  try {
    const result = await apiRequest('/mahasiswa');
    if (!Array.isArray(result.data)) throw new Error('Format data dari API tidak sesuai.');
    document.querySelector('#total').textContent = String(result.data.length);
    if (result.data.length === 0) {
      setStatus('Belum ada data mahasiswa.');
      return;
    }

    for (const mahasiswa of result.data) {
      const row = document.createElement('tr');
      row.append(makeCell(mahasiswa.nim), makeCell(mahasiswa.nama), makeCell(mahasiswa.prodi));
      if (isAdmin) {
        const actions = document.createElement('td');
        const editButton = document.createElement('button');
        editButton.type = 'button';
        editButton.textContent = 'Ubah';
        editButton.addEventListener('click', () => beginEdit(mahasiswa));
        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.textContent = 'Hapus';
        deleteButton.addEventListener('click', () => deleteMahasiswa(mahasiswa.id));
        actions.append(editButton, deleteButton);
        row.append(actions);
      }
      listElement.append(row);
    }
    setStatus(`${result.data.length} data mahasiswa.`);
  } catch (error) {
    setStatus(`Gagal memuat data: ${error.message}`, true);
  }
}

function beginEdit(mahasiswa) {
  editingId = mahasiswa.id;
  form.elements.namedItem('nim').value = mahasiswa.nim;
  form.elements.namedItem('nama').value = mahasiswa.nama;
  form.elements.namedItem('prodi').value = mahasiswa.prodi;
  document.querySelector('#save-button').textContent = 'Simpan perubahan';
  document.querySelector('#cancel-edit').hidden = false;
}

function resetForm() {
  editingId = null;
  form.reset();
  document.querySelector('#save-button').textContent = 'Tambah';
  document.querySelector('#cancel-edit').hidden = true;
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  if (!isAdmin) return;
  const formData = new FormData(form);
  const payload = {
    nim: formData.get('nim'),
    nama: formData.get('nama'),
    prodi: formData.get('prodi'),
  };
  try {
    await apiRequest(editingId ? `/mahasiswa?id=${encodeURIComponent(editingId)}` : '/mahasiswa', {
      method: editingId ? 'PUT' : 'POST',
      body: JSON.stringify(payload),
    });
    resetForm();
    await loadMahasiswa();
    setStatus('Data berhasil disimpan.');
  } catch (error) {
    setStatus(error.message, true);
  }
});

async function deleteMahasiswa(id) {
  if (!isAdmin || !window.confirm('Hapus data mahasiswa ini?')) return;
  try {
    await apiRequest(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'DELETE' });
    await loadMahasiswa();
    setStatus('Data berhasil dihapus.');
  } catch (error) {
    setStatus(error.message, true);
  }
}

document.querySelector('#cancel-edit').addEventListener('click', resetForm);
document.querySelector('#logout').addEventListener('click', logout);
