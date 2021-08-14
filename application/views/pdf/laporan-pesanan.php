<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    * {
      font-family: arial,Sans-Serif;
    }
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
  <h2 align="center" class="">Laporan Pesanan</h2>
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
        <th align="center">No</th>
        <th>Invoice</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Opsi Beli</th>
        <th>Total Bayar</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($pesanan as $row): ?>
      <?php
      $data_penerima = json_decode($row['data_penerima'], true);
      $jumlah_item = $this->db->get_where('item_pesanan', ['id_pesanan' => $row['id_pesanan']])->num_rows();
      ?>
      <tr>
        <td scope="row" align="center"><?=$i++ ?></td>
        <td><?=$row['id_pesanan'] ?></td>
        <td><?=$data_penerima['customer']['name'] == 'kasir' ? 'offline' : $data_penerima['customer']['name'] ?></td>
        <td><?=$data_penerima['customer']['phone'] == '0' ? '-' : $data_penerima['customer']['phone'] ?></td>
        <?php if ($row['opsi_beli'] == 0): ?>
        <td>Offline</td>
        <?php elseif ($row['opsi_beli'] == 1): ?>
        <td>Online</td>
        <?php elseif ($row['opsi_beli'] == 2): ?>
        <td>Booking</td>
        <?php endif; ?>
        <td><?=rupiah($row['total_bayar']) ?></td>
        <td><?=date('d-m-Y', strtotime($row['tgl_buat_pesanan'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
  <p>
    TOTAL PENDAPATAN : <?=rupiah($income) ?>
  </p>

</body>
</html>