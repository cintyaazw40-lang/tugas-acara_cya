<?php
/** @var \App\Models\Mahasiswa[] $daftarMahasiswa Daftar object mahasiswa yang dikirim dari index.php */
?>
<h2 class="mb-3">Daftar Mahasiswa (dari Object)</h2>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>NIM</th>
      <th>Nama</th>
      <th>Prodi</th>
      <th>Angkatan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($daftarMahasiswa as $mhs): ?>
    <tr>
      <td><?php echo $mhs->getNim(); ?></td>
      <td><?php echo $mhs->getNama(); ?></td>
      <td><?php echo $mhs->getProdi(); ?></td>
      <td><?php echo $mhs->getAngkatan(); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>