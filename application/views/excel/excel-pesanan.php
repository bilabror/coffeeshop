<?php
header("Content-type:application/octet-stream/");

header("Content-Disposition:attachment; filename=transaction.xls");

header("Pragma: no-cache");

header("Expires: 0");
?>
<table class="table table-striped">
  <thead>
    <tr>
      <td align="center"><b>No</b></td>
      <th>Invoice</th>
      <th>Customer</th>
      <th>Total Harga</th>
      <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($transaction as $row): ?>
    <?php
    $data_penerima = json_decode($row['data_penerima'], true);
    ?>
    <tr>
      <td scope="row" align="center"><?=$i++ ?></td>
      <td><?=$row['id_pesanan'] ?></td>
      <td><?=$data_penerima['customer']['name'] == 'kasir' ? 'offline' : $data_penerima['customer']['name'] ?></td>
      <td><?=$row['total_bayar'] ?></td>
      <td><?=date('d-m-Y', $row['tgl_buat_pesanan']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>