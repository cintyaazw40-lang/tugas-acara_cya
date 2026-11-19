'use strict';

const API_BASE_URL = new URL('../', window.location.href).href.replace(/\/$/, '');
const statusElement = document.querySelector('#status');
const listElement = document.querySelector('#mahasiswa-list');

function setStatus(message, isError = false) {
  statusElement.textContent = message;
  statusElement.classList.toggle('error', isError);
}

async function loadMahasiswa() {
  listElement.replaceChildren();
  setStatus('Memuat data...');

  try {
    const response = await fetch(`${API_BASE_URL}/mahasiswa`, {
      headers: { Accept: 'application/json' },
    });
    const result = await response.json();
    if (!response.ok || !result.success) {
      throw new Error(result.message || `Request gagal (${response.status}).`);
    }
    if (!Array.isArray(result.data)) {
      throw new Error('Format data dari API tidak sesuai.');
    }
    if (result.data.length === 0) {
      setStatus('Belum ada data');
      return;
    }

    for (const mahasiswa of result.data) {
      const row = document.createElement('tr');
      for (const value of [mahasiswa.nim, mahasiswa.nama, mahasiswa.prodi]) {
        const cell = document.createElement('td');
        cell.textContent = String(value ?? '');
        row.append(cell);
      }
      listElement.append(row);
    }
    setStatus(`${result.data.length} data mahasiswa berhasil dimuat.`);
  } catch (error) {
    setStatus(`Gagal memuat data: ${error.message}`, true);
  }
}

document.querySelector('#refresh').addEventListener('click', loadMahasiswa);
document.addEventListener('DOMContentLoaded', loadMahasiswa, { once: true });
