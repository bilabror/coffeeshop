<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb') ?>
    </div>
    <div class="section-body">

      <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
                Detail Produk
              </h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <img src="<?=base_url('uploads/image/produk/'.$produk->gambar_produk); ?>" alt="gambar<?=$produk->nama_produk ?>" class="img-thumbnail" />
                </div>
              </div>
              <div class="row mt-3">
                <table border="0" class="table">
                  <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <th><?=$produk->nama_produk ?></th>
                  </tr>
                  <tr>
                    <td>Harga Awal</td>
                    <td>:</td>
                    <th><?=rupiah($produk->harga_produk) ?></th>
                  </tr>
                  <tr>
                    <td>Harga Diskon</td>
                    <td>:</td>
                    <th><?=rupiah($produk->total_harga_produk) ?></th>
                  </tr>
                  <tr>
                    <td>Diskon</td>
                    <td>:</td>
                    <th><?= $produk->diskon_produk != 0 ? "{$produk->diskon_produk}%" : '-' ?></th>
                  </tr>
                  <tr>
                    <td>stok</td>
                    <td>:</td>
                    <th><?=$produk->stok_produk ?></th>
                  </tr>
                  <tr>
                    <td>Berat</td>
                    <td>:</td>
                    <th><?=$produk->berat_produk ?> gram</th>
                  </tr>
                </table>

              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-7 col-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="card-header">
                    <h4 class="card-title">
                      Penjualan Terbaru
                    </h4>
                  </div>
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Qty</th>
                        <th>Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($pesanan as $val): ?>
                      <tr>
                        <?php if ($val->opsi_beli == 0): ?>
                        <td><a href="<?=site_url("dashboard/transaksi/offline/detail/{$val->id_pesanan}") ?>"><?=$val->id_pesanan ?></a></td>
                        <?php elseif ($val->opsi_beli == 1): ?>
                        <td><a href="<?=site_url("dashboard/transaksi/online/detail/{$val->id_pesanan}") ?>"><?=$val->id_pesanan ?></a></td>
                        <?php elseif ($val->opsi_beli == 2): ?>
                        <td><a href="<?=site_url("dashboard/transaksi/booking/detail/{$val->id_pesanan}") ?>"><?=$val->id_pesanan ?></a></td>
                        <?php endif; ?>

                        <td><?=json_decode($val->data_penerima)->customer->name ?></td>
                        <td><?=$val->kuantitas ?></td>
                        <td><?=time_ago($val->tgl_bayar_pesanan) ?></td>
                        <td><?=$val->status ?></td>
                      </tr>
                      <?php endforeach; ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="card-header">
                    <h4 class="card-title">
                      Ulasan Pembeli
                    </h4>
                  </div>
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Customer</th>
                        <th>Ratting</th>
                        <th>Komentar</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($ulasan as $val): ?>
                      <tr>
                        <td><?=$val->username ?></td>
                        <td><?=str_repeat('&#9733;', $val->rating) ?></td>
                        <td><?=$val->komentar != '' ? $val->komentar : '-' ?></td>
                        <td><?=time_ago($val->tgl_buat) ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>


<?php $this->load->view('_layouts/js.php'); ?>