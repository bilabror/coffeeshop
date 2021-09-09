<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= base_url('stisla/'); ?>assets/modules/bootstrap/css/bootstrap.min.css">
  <title>Invoice</title>
</head>
<body>
  <div class="container mt-4">
  <section class="section">
    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">
          <div class="row">
            <div class="col-lg-12">
              <div class="invoice-title">
                <div class="d-flex justify-content-between">
                   <h1>INVOICE</h1>
                <h3><?=strtoupper(getset('nama_toko')) ?></h3>
                </div>
               
                <div class="invoice-number">
                  <h4 style="color:blue">#<?=$invoice->id_pesanan ?></h4>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <address>
                    <strong>Penerima:</strong><br>
                    <?= json_decode($invoice->data_penerima)->customer->name ?><br>
                    <?=json_decode($invoice->data_penerima)->customer->phone ?><br>
                  </address>
                </div>
                <div class="col-md-6 text-md-right">
                  <address>
                     <strong>Order Date:</strong><br>
                    <?= date('d/m/Y', strtotime($invoice->tgl_buat_pesanan)) ?><br>
                   <p>
                    <strong class="text-uppercase">Nomor Meja</strong><br>
                    <?=$booking->no_meja ?>
                  </p>
                  <p>
                    <strong class="text-uppercase">Perkiraan Datang</strong><br>
                    <?=date('Y-m-d H:i', strtotime($booking->prk_datang)) ?>
                  </p>  
                  </address>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-md">

                  <tr>
                    <th data-width="40">#</th>
                    <th>Item</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Discount</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Totals</th>
                  </tr>

                  <?php $i = 1; ?>
                  <?php foreach ($produk as $row) {
                    ?>
                    <tr>
                      <th scope="row"><?=$i; ?></th>
                      <td>
                        <p>
                          <?=$row->nama_produk ?>
                        </p>
                        <p class="text-muted">
                          <?=$row->catatan ?>
                        </p>
                      </td>
                      <td class="text-center"><?= rupiah($row->harga_produk) ?></td>
                      <td class="text-center"><?= $row->diskon_produk == 0 ? '-' : $row->diskon_produk.'%' ?></td>
                      <td class="text-center"><?=$row->kuantitas ?></td>
                      <td class="text-right"><?= rupiah($row->harga_sementara) ?></td>
                    </tr>
                    <?php $i++; ?>
                    <?php
                  } ?>
                </table>
              </div>
              <div class="row mt-4 mb-4">
                <div class="col-lg-8">
                  <div class="section-title">
                    Pembayaran
                  </div>
                  <address>
                    <strong>Daripada :</strong><br>
                    <?= "{$bukti_pembayaran['nama_bank']} - {$bukti_pembayaran['norek']} A/N {$bukti_pembayaran['atas_nama']}" ?><br>
                    <strong>Kepada :</strong><br>
                    <?= "{$rekening_toko['bank']} - {$rekening_toko['norek']} A/N {$rekening_toko['atas_nama']}" ?><br>
                   </address>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-name">
                      Subtotal
                    </div>
                    <div class="invoice-detail-value">
                      <?= rupiah($invoice->bayar) ?>
                    </div>
                  </div>
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-name">
                      Shipping
                    </div>
                    <div class="invoice-detail-value">
                      <?= rupiah($invoice->ongkir) ?>
                    </div>
                  </div>
                  <hr class="mt-2 mb-2">
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-name">
                      Total
                    </div>
                    <div class="invoice-detail-value invoice-detail-value-lg">
                      <?= rupiah($invoice->total_bayar) ?>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <h2 class="text-center">TERIMAKASIH TELAH MENJADI PELANGGAN KAMI</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
      </div>
    </div>
  </section>
</div>



</body>
</html>