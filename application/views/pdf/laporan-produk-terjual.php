<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    table {
      border: 0.1mm solid #000000;
    }
    td {
      vertical-align: top;
    }
    table td {
      border-left: 0.1mm solid #000000;
      border-right: 0.1mm solid #000000;
    }
    table thead th {
      background-color: #EEEEEE;
      text-align: center;
      border: 0.1mm solid #000000;
      font-variant: small-caps;
    }
  </style>
</head>
<body>
  <h2 align="center" class="">Laporan Produk Terjual</h2>
  <?php if (!empty($awal)&&!empty($akhir)): ?><?php if ($awal == $akhir): ?>
  <h4 align="center">Tanggal <?=$awal ?></h4>
  <?php else : ?>
  <h4 align="center">Tanggal <?=$awal ?> - Tanggal <?=$akhir ?></h4>
  <?php endif; ?>
  <?php else : ?>
  <h4 align="center">semua data yang ada</h4>
  <?php endif; ?>


  <hr>
  <table cellspacing="0" cellpadding="10" width="100%">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Terjual</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($produk_terjual as $row): ?>
      <tr>
        <td scope="row" align="center"><?=$i++ ?></td>
        <td><?=$row['nama_produk'] ?></td>
        <td><?=$row['terjual'] ?></td>
        <td><?=date('d-m-Y', strtotime($row['tgl_buat'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
  <p>
    TOTAL PRODUK TERJUAL : <?= $sum_terjual ?>
  </p>

</body>
</html>