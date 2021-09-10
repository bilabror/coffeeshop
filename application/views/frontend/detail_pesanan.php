<?php if ($invoice->opsi_beli == 1): ?>
<!-- ------------------------------------------------------------------------ MAIN -->
<div class="container" style="margin-top: 100px">

  <div class="bg-light border border-success">
    <div class="row">
      <div class="col-md-3 col-12">
        <div
          style="background-color: #00b14f; width: 100%; min-height: 240px"
          class="p-3 pt-5 text-white position-relative"
          >
          <h2 class="invoiceTitle">INVOICE</h2>
          <h5 class="text-dark fw-bold">#<?=$invoice->id_pesanan ?></h5>
          <small
            class="
            mt-5
            position-absolute
            end-0
            bottom-0
            me-2
            mb-2
            dateInvoice
            "
            >
            <i class="fas fa-calendar-alt me-2"></i>
            <?= date('d/m/Y', strtotime($invoice->tgl_buat_pesanan)) ?>
          </small>
        </div>
      </div>
      <div class="col-md-5 mt-4 rincian col-6">
        <div>
          <i class="fas fa-user me-1"></i>
          <span class="fw-bold"><?= json_decode($invoice->data_penerima)->customer->name ?></span>
        </div>
        <div class="mt-2">
          <i class="fas fa-phone me-1"></i> <?=json_decode($invoice->data_penerima)->customer->phone ?>
        </div>
        <p class="mt-3">
          <strong class="text-uppercase">Alamat:</strong> <?=$address ?>
        </p>
        <div class="d-flex justify-content-between">
          <p>
            <strong class="text-uppercase">Status :</strong>

            <?php if ($invoice->status == 'new'): ?>
            <span class="badge bg-warning">
              <i class="fas fa-check-circle me-1"></i> belum bayar
            </span>
            <?php elseif ($invoice->status == 'pending'): ?>
            <span class="badge bg-warning">
              <i class="fas fa-check-circle me-1"></i> pending
            </span>
            <?php elseif ($invoice->status == 'terima'): ?>
            <span class="badge bg-success">
              <i class="fas fa-check-circle me-1"></i> belum dikirim
            </span>
            <?php elseif ($invoice->status == 'tolak'): ?>
            <span class="badge bg-danger">
              <i class="fas fa-times-circle me-1"></i> ditolak
            </span>
            <span class="badge bg-danger">
              <?=$invoice->catatan_penjual ?>
            </span>
            <?php elseif ($invoice->status == 'kirim'): ?>
            <span class="badge bg-info">
              <i class="fas fa-box me-1"></i> dikirim
            </span>
            <?php elseif ($invoice->status == 'selesai'): ?>
            <span class="badge bg-success">
              <i class="fas fa-check-circle me-1"></i> selesai
            </span>
            <?php endif; ?>
          </p>
        </div>
      </div>
      <div class="col-md-4 mt-5 ps-4 col-6">
        <br />
        <br />
        <p>
          <strong class="text-uppercase">kurir :</strong>
          <?=$invoice->kurir ?>
        </p>
        <p>
          <strong class="text-uppercase">layanan :</strong>
          <?=explode(',', $invoice->layanan)[1] ?>
        </p>
        <p>
          <strong class="text-uppercase">resi :</strong>
          <?=$invoice->resi ?>
        </p>
      </div>
      <div class="col-3"></div>
      <div class="col-lg-8 col-12 p-4">
        <table class="table table-striped mt-3">
          <thead>
            <tr>
              <th>#</th>
              <th>Item</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Jumlah</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($produk as $row): ?>
            <tr>
              <th><?=$i++ ?></th>
              <td><?=$row->nama_produk ?></td>
              <td><?=$row->harga_produk ?></td>
              <td><?=$row->diskon_produk ?>%</td>
              <td><?=$row->kuantitas ?> pcs</td>
              <td><?=$row->harga_sementara ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="alert alert-success mt-5 d-flex justify-content-around">
          <p class="text-dark text-center my-0">
            <span class="text-uppercase">Sub Total :</span><br />
            <strong class="tet-uppercase"><?=rupiah($invoice->bayar) ?></strong>
          </p>
          <p class="text-dark text-center my-0">
            <span class="text-uppercase">Ongkir :</span><br />
            <strong class="tet-uppercase"><?=rupiah($invoice->ongkir) ?></strong>
          </p>
          <p class="text-dark text-center my-0">
            <span class="text-uppercase">Total :</span><br />
            <strong class="tet-uppercase"><?=rupiah($invoice->total_bayar) ?></strong>
          </p>
        </div>

        <div class="mt-5 mb-5">
          <div class="row">
            <div class="col-md-4 mb-2">
              <a 
                href="<?=site_url('riwayat/invoice_online/'.$invoice->id_pesanan)?>"
                class="btn w-100 text-white"
                style="background-color: #db3a34"
                >
                <i class="fas fa-print me-2"></i> Cetak
              </a>
            </div>
            <div class="col-md-4 mb-2">
                <?php if($invoice->status == 'kirim') : ?>
              <button
                class="btn w-100 text-white"
                style="background-color: #00b14f"
                onclick="selesai(<?=$invoice->id_pesanan ?>)"
                >
                <i class="fas fa-check-circle me-2"></i> <?= $invoice->status != 'selesai' ? 'Terima Pesanan' : 'Pesanan Selesai' ?>
              </button>
                <?php endif;?>
            </div>
            <div class="col-md-4">
              <a
                href="riwayat/pesanan"
                class="btn text-white w-100"
                style="background-color: #db3a34"
                >
                Kembali
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end-MAIN -->

<?php elseif ($invoice->opsi_beli == 2) : ?>
  <!-- ------------------------------------------------------------------------ MAIN -->
<div class="container" style="margin-top: 100px">

  <div class="bg-light border border-success">
    <div class="row">
      <div class="col-md-3 col-12">
        <div
          style="background-color: #00b14f; width: 100%; min-height: 240px"
          class="p-3 pt-5 text-white position-relative"
          >
          <h2 class="invoiceTitle">INVOICE</h2>
          <h5 class="text-dark fw-bold">#<?=$invoice->id_pesanan ?></h5>
          <small
            class="
            mt-5
            position-absolute
            end-0
            bottom-0
            me-2
            mb-2
            dateInvoice
            "
            >
            <i class="fas fa-calendar-alt me-2"></i>
            <?= date('d/m/Y', strtotime($invoice->tgl_buat_pesanan)) ?>
          </small>
        </div>
      </div>
      <div class="col-md-5 mt-4 rincian col-6">
        <div>
          <i class="fas fa-user me-1"></i>
          <span class="fw-bold"><?= json_decode($invoice->data_penerima)->customer->name ?></span>
        </div>
        <div class="mt-2">
          <i class="fas fa-phone me-1"></i> <?=json_decode($invoice->data_penerima)->customer->phone ?>
        </div>
        <div class="d-flex justify-content-between mt-2">
          <p>
            <strong class="text-uppercase">Status :</strong>

            <?php if ($invoice->status == 'new'): ?>
            <span class="badge bg-warning">
              <i class="fas fa-check-circle me-1"></i> belum bayar
            </span>
            <?php elseif ($invoice->status == 'pending'): ?>
            <span class="badge bg-success">
              <i class="fas fa-check-circle me-1"></i> pending
            </span>
            <?php elseif ($invoice->status == 'terima'): ?>
            <span class="badge bg-success">
              <i class="fas fa-check-circle me-1"></i> menunggu datang
            </span>
            <?php elseif ($invoice->status == 'tolak'): ?>
            <span class="badge bg-danger">
              <i class="fas fa-times-circle me-1"></i> gagal
            </span>
            <?php elseif ($invoice->status == 'selesai'): ?>
            <span class="badge bg-success">
              <i class="fas fa-check-circle me-1"></i> selesai
            </span>
            <?php endif; ?>
          </p>
        </div>
      </div>
      <div class="col-md-4 mt-5 ps-4 col-6">
        <br />
        <br />
        <p>
          <strong class="text-uppercase">Nomor Meja :</strong>
          <?=$booking->no_meja ?>
        </p>
        <p>
          <strong class="text-uppercase">Perkiraan Datang :</strong> <br>
          <?=date('Y-m-d H:i', strtotime($booking->prk_datang)) ?>
        </p>
      </div>
      <div class="col-3"></div>
      <div class="col-lg-8 col-12 p-4">
        <table class="table table-striped mt-3">
          <thead>
            <tr>
              <th>#</th>
              <th>Item</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Jumlah</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($produk as $row): ?>
            <tr>
              <th><?=$i++ ?></th>
              <td><?=$row->nama_produk ?></td>
              <td><?=$row->harga_produk ?></td>
              <td><?=$row->diskon_produk ?>%</td>
              <td><?=$row->kuantitas ?> pcs</td>
              <td><?=$row->harga_sementara ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="alert alert-success mt-5 d-flex justify-content-around">
          <p class="text-dark text-center my-0">
            <span class="text-uppercase">Sub Total :</span><br />
            <strong class="tet-uppercase"><?=rupiah($invoice->bayar) ?></strong>
          </p>
          <p class="text-dark text-center my-0">
            <span class="text-uppercase">Total :</span><br />
            <strong class="tet-uppercase"><?=rupiah($invoice->total_bayar) ?></strong>
          </p>
        </div>

        <div class="mt-5 mb-5">
          <div class="row">
            <div class="col-8">
              <button
                class="btn w-100 text-white"
                style="background-color: #00b14f"
                >
                <i class="fas fa-print me-2"></i> Cetak
              </button>
            </div>
            <div class="col-4">
              <button
                class="btn text-white w-100"
                style="background-color: #db3a34"
                >
                Kembali
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end-MAIN -->

<?php endif; ?>


<script>
  
  function selesai(idPesanan) {
    let url = "<?=site_url('riwayat/pesanan_selesai') ?>";

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `Telah menerima pesanan ${idPesanan}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: url,
          method: 'post',
          data: {
            id_pesanan: idPesanan
          },
          dataType: 'json',
          cache: false,
          success: function(data) {
            if (data.status == true) {

              Toast.fire({
                icon: 'success',
                title: `Pesanan ${idPesanan} telah selesai`
              })
              setTimeout(function() {
                location.reload();
              }, 2000);
            }
          },
          error: function(jqxhr, textStatus, errorThrown) {
            console.log(jqxhr);
            console.log(textStatus);
            console.log(errorThrown);

            for (key in jqxhr)
              alert(key + ":" + jqxhr[key])
            for (key2 in textStatus)
              alert(key + ":" + textStatus[key])
            for (key3 in errorThrown)
              alert(key + ":" + errorThrown[key])

          }
        })
      }

    })

  }
</script>