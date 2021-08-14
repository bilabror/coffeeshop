<?php
header("Content-type:application/octet-stream/");

header("Content-Disposition:attachment; filename=transaction.xls");

header("Pragma: no-cache");

header("Expires: 0");
?>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Produk</th>
      <th>Terjual</th>
      <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($pesanan as $row): ?>
    <tr>
      <td scope="row" align="center"><?=$i++ ?></td>
      <td><?=$row['nama_produk'] ?></td>
      <td><?=$row['terjual'] ?></td>
      <td><?=date('d-m-Y', $row['tgl_buat']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>