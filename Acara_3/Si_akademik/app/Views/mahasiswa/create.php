<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Mahasiswa</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
      color: #2b2f33;
    }
    .header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 24px;
    }
    .header img {
      width: 56px;
      height: 56px;
      object-fit: contain;
    }
    .header h1 {
      margin: 0;
      font-size: 1.6em;
      color: #175753;
    }
    .header p {
      margin: 2px 0 0;
      color: #8a8f98;
      font-size: 0.95em;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      padding: 30px;
      max-width: 500px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-size: 0.95em;
      color: #333;
    }
    input, select {
      width: 100%;
      padding: 10px 14px;
      margin-bottom: 20px;
      border: 1px solid #dcdfe3;
      border-radius: 6px;
      font-size: 0.95em;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #4dabf7;
      box-shadow: 0 0 0 3px rgba(77,171,247,0.2);
    }
    .btn-row {
      display: flex;
      gap: 10px;
    }
    button, .btn-cancel {
      padding: 10px 22px;
      border-radius: 6px;
      font-size: 0.95em;
      cursor: pointer;
      text-decoration: none;
      border: none;
    }
    .btn-save {
      background-color: #22b8cf;
      color: #fff;
    }
    .btn-cancel {
      background-color: #fff;
      color: #495057;
      border: 1px solid #ced4da;
    }
  </style>
</head>
<body>

  <div class="header">
    <img src="logo.png" alt="Logo Politeknik Negeri Jember" onerror="this.style.display='none'">
    <div>
      <h1>Politeknik Negeri Jember</h1>
      <p>Tambah Mahasiswa</p>
    </div>
  </div>

  <div class="card">
    <!-- action masih kosong. Nanti diarahkan ke Controller untuk simpan ke database. -->
    <form action="#" method="POST">
      <label for="nim">NIM</label>
      <input type="text" id="nim" name="nim" placeholder="Masukkan NIM" required>

      <label for="nama">Nama</label>
      <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>

      <label for="prodi">Program Studi</label>
      <select id="prodi" name="prodi" required>
        <option value="" selected disabled>Pilih program studi</option>
        <option value="Teknik Informatika">Teknik Informatika</option>
        <option value="Sistem Informasi">Sistem Informasi</option>
        <option value="Teknik Komputer">Teknik Komputer</option>
      </select>

      <div class="btn-row">
        <button type="submit" class="btn-save">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>

</body>
</html>