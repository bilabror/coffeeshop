<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb') ?>
    </div>
    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">
          <div class="row">
            <div class="col-lg-12">
              <div class="invoice-title">
                <h2>Invoice</h2>
                <div class="invoice-number">
                  Order <span style="color:blue">#<?=$invoice->id_pesanan ?></span>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <address>
                    <strong>Penerima:</strong><br>
                    customer offline
                  </address>
                </div>
                <div class="col-md-6 text-md-right">
                  <address>
                    <strong>Order Date:</strong><br>
                    <?= date('d/m/Y', strtotime($invoice->tgl_buat_pesanan)) ?><br>
                    <?= date('H:i', strtotime($invoice->tgl_buat_pesanan)) ?><br>
                  </address>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <div class="section-title">
                Pembelian Produk
              </div>
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
              <div class="row mt-4">
                <div class="col-lg-8">
                  <div class="section-title">
                    Pembayaran
                  </div>
                  <address>
                    <strong>Uang Tunai</strong>
                  </address>
                </div>
                <div class="col-lg-4 text-right">
                  <hr class="mt-2 mb-2">
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-name">
                      Total Bayar
                    </div>
                    <div class="invoice-detail-value invoice-detail-value-lg">
                      <?= rupiah($invoice->total_bayar) ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col">
            <div class="text-md-left mb-4">
              <?php if ($invoice->status == 'pending'): ?>
              <span class="btn btn-lg btn-info">BELUM DITINJAU</span>
              <?php elseif ($invoice->status == 'terima'): ?>
              <span class="btn btn-lg btn-warning">BELUM DIKIRIM</span>
              <?php elseif ($invoice->status == 'tolak'): ?>
              <span class="btn btn-lg btn-danger">DITOLAK</span>
              <?php elseif ($invoice->status == 'kirim'): ?>
              <span class="btn btn-lg btn-success">DALAM PENGIRIMAN</span>
              <?php elseif ($invoice->status == 'batal'): ?>
              <span class="btn btn-lg btn-danger">PESANAN DIBATALKAN</span>
              <?php elseif ($invoice->status == 'selesai'): ?>
              <span class="btn btn-lg btn-success">SELESAI</span>
              <?php endif; ?>
              <span></span>
            </div>
          </div>
          <div class="col">
            <div class="text-md-right">
              <a href="" class="btn btn-danger btn-icon icon-left"><i class="fas fa-print"></i></a>
              <?php if ($invoice->status != 'selesai' && $invoice->status != 'kirim' && $invoice->status != 'batal' && $invoice->status != 'tolak'): ?>
              <button class="btn btn-warning btn-icon icon-left" onclick="edit(<?=$invoice->id_pesanan ?>,'<?=$invoice->status ?>')"><i class="fas fa-edit"></i></button>
              <?php endif; ?>
              <?php if ($invoice->status == 'kirim'): ?>
              <button class="btn btn-info btn-icon icon-left"><i class="fas fa-info"></i></button>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<?php $this->load->view('_layouts/js.php'); ?>



<script>

  function edit(id, status) {
    if (status == 'pending') {
      let select = `<option value="">--- UBAH STATUS ---</option>
      <option value="terima">Terima Pesanan</option>
      <option value="tolak">Tolak Pesanan</option>`;
      $('[name="opsi"]').html(select);
    } else if (status == 'terima') {
      let select = `<option value="" disabled selected>--- UBAH STATUS ---</option>
      <option value="selesai">Selesai</option>`;
      $('[name="opsi"]').html(select);
    }

    $('#form')[0].reset();
    $('input.inputan').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('[name="id"]').val(id);

    $('#modal_form').modal('show');
  }

  function save() {
    $.ajax({
      url: "<?= site_url('dashboard/transaksi/offline/ajax_update') ?>",
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      cache: false,
      success: function(data) {
        if (data.status == false) {
          $.each(data.err, function(key, value) {
            if (value == "") {
              $(`[name="${key}"]`).removeClass('is-invalid');
              $(`[in="${key}"]`).html();
            } else {
              $(`[name="${key}"]`).addClass('is-invalid');
              $(`[in="${key}"]`).html(value);
            }
          });
          console.log('ada yang error');
        } else {
          $('#modal_form').modal('hide');
          window.location.href = "<?=site_url('dashboard/transaksi/offline') ?>";
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
    });
  }







</script>


<div class="modal fade" tabindex="-1" role="dialog" id="modal_form">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" action="" method="post" class="form-horizontal">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group group-opsi">
              <label class="control-label">Opsi</label>
              <select name="opsi" id="opsi" class="form-control" required>
              </select>
              <div class="invalid-feedback" in="opsi"></div>
            </div>
            <div class="form-group input-resi"></div>
            <div class="form-group catatan">
              <label class="control-label">Catatan</label>
              <textarea name="catatan_penjual" id="catatan_penjual" cols="30" rows="10" class="form-control"></textarea>
              <div class="invalid-feedback" in="catatan_penjual"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="save()">SIMPAN</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>