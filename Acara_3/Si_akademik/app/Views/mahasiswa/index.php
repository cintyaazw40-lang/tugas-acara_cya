<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Mahasiswa</title>
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
      justify-content: space-between;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 16px;
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .header-left img {
      width: 56px;
      height: 56px;
      object-fit: contain;
    }
    .header-left h1 {
      margin: 0;
      font-size: 1.6em;
      color: #175753;
    }
    .header-left p {
      margin: 2px 0 0;
      color: #8a8f98;
      font-size: 0.95em;
    }
    .header-actions a {
      display: inline-block;
      padding: 8px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9em;
      margin-left: 8px;
    }
    .btn-cyan {
      background-color: #22b8cf;
      color: #fff;
      border: 1px solid #22b8cf;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      overflow: hidden;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background-color: #d0f0f7;
    }
    th, td {
      padding: 14px 20px;
      text-align: left;
      font-size: 0.95em;
    }
    tbody tr {
      border-top: 1px solid #eee;
    }
    tbody tr:hover {
      background-color: #f9fbfc;
    }
    .empty-row {
      text-align: center;
      color: #999;
      padding: 20px;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="header-left">
      <img src="logo.png" alt="Logo Politeknik Negeri Jember" onerror="this.style.display='none'">
      <div>
        <h1>Politeknik Negeri Jember</h1>
        <p>Daftar Mahasiswa</p>
      </div>
    </div>
    <div class="header-actions">
      <a href="create.php" class="btn-cyan">+ Tambah Mahasiswa</a>
    </div>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>NIM</th>
          <th>Nama</th>
          <th>Prodi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- Data akan diisi dari Controller pada pertemuan berikutnya -->
        <tr>
          <td colspan="4" class="empty-row">Belum ada data (View statis, belum terhubung Model/Controller)</td>
        </tr>
      </tbody>
    </table>
  </div>

</body>
</html>