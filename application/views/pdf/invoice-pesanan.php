<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="<?= base_url('stisla/'); ?>assets/modules/bootstrap/css/bootstrap.min.css">
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

  <div class="container">
    <section class="section">
      <div class="section-body">
        <div class="invoice">
          <div class="invoice-print">
            <div class="row">
              <div class="col-lg-12">
                <div class="invoice-title">
                  <h2>Invoice</h2>
                  <div class="invoice-number">
                    Order #<?=$invoice->id_pesanan ?>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <address>
                      <strong>Penerima:</strong><br>
                      <?php if (json_decode($invoice->data_penerima)->customer->name == 'offline'): ?>
                      customer offline
                      <?php else : ?>
                      <?= json_decode($invoice->data_penerima)->customer->name ?><br>
                      <?=json_decode($invoice->data_penerima)->customer->phone ?><br>
                      <?=$address ?>
                      <?php endif; ?>

                    </address>
                  </div>
                  <div class="col-md-6 text-md-right">
                    <address>
                      <strong>Order Date:</strong><br>
                      <?= date('d/m/Y', $invoice->tgl_buat_pesanan) ?><br>
                      <strong>Pemgiriman:</strong><br>
                      <?= $invoice->kurir ?> - <?= $invoice->layanan ?><br>
                    </address>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <table cellspacing="0" cellpadding="10" width="100%">
                  <thead>
                    <tr>
                      <th data-width="40">#</th>
                      <th>Item</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Discount</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-right">Totals</th>
                    </tr>
                  </thead>
                  <tbody>
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
                  </tbody>
                </table>
                <div class="row mt-4">
                  <div class="col-lg-8">
                    <div class="section-title">
                      Pembayaran
                    </div>
                    <?php if (json_decode($invoice->data_penerima)->customer->name == 'offline'): ?>
                    <address>
                      <strong>Pembayaran Tunai</strong>
                    </address>
                    <?php else : ?>
                    <address>
                      <strong>Daripada :</strong><br>
                      <?= "{$bukti_pembayaran['nama_bank']} - {$bukti_pembayaran['norek']} A/N {$bukti_pembayaran['atas_nama']}" ?><br>
                      <strong>Kepada :</strong><br>
                      <?= "{$rekening_toko['bank']} - {$rekening_toko['norek']} A/N {$rekening_toko['atas_nama']}" ?><br>
                      <a href="<?=base_url('uploads/image/payment/'.$bukti_pembayaran['gambar']) ?>">Lihat bukti Pembayaran</a>
                    </address>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-4 text-right">
                    <?php if (json_decode($invoice->data_penerima)->customer->name != 'offline'): ?>

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
                    <?php endif; ?>
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
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col">
            </div>
            <div class="col">
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>


  <?php $this->load->view('_layouts/js.php'); ?>



  <script>

    $(document).ready(function() {
      $('.form-group #opsi').change(function() {
        let resi = `<div class="form-group group-resi">
        <label class="control-label">Nomor Resi</label>
        <input type="text" name="resi" id="resi" class="form-control" required/>
        </div>`;
        if ($(this).val() == 'kirim') {
          $('.group-opsi').after(resi);
          $('[name="resi"]').val();
        } else {
          $('.group-resi').hide();
          $('[name="resi"]').val('-');
        }
      });
    });


    function edit(id, status) {
      if (status == 'pending') {
        let select = `<option value="">--- UBAH STATUS ---</option>
        <option value="terima">Terima Pesanan</option>
        <option value="tolak">Tolak Pesanan</option>`;
        $('[name="opsi"]').html(select);
      } else if (status == 'terima') {
        let select = `<option value="" disabled selected>--- UBAH STATUS ---</option>
        <option value="kirim">Kirim pesanan</option>
        <option value="batal">batalkan Pesanan</option>`;
        $('[name="opsi"]').html(select);
      } else if (status == 'kirim') {
        let select = `<option value="kirim">dalam pengiriman</option>
        <option value="selesai">Pesanan Selesai</option>`;
        $('[name="opsi"]').html(select);
        let resi = `<div class="form-group group-resi">
        <label class="control-label">Nomor Resi</label>
        <input type="text" name="resi" id="resi" class="form-control" required value="`+noresi+`"/>
        </div>`;
        $('.group-opsi').after(resi);
      }


      if (status == 'pending') {} else if (status == 'terima') {}

      $('#form')[0].reset();
      $('input.inputan').removeClass('is-invalid');
      $('.invalid-feedback').empty();
      $('[name="id"]').val(id);

      $('#modal_form').modal('show');
    }

    function save() {
      $.ajax({
        url: "<?= site_url('dashboard/pesanan/ajax_update') ?>",
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        cache: false,
        success: function(data) {
          if (data.status == false) {
            if (!data.err.opsi == "") {
              $('[name="opsi"]').addClass('is-invalid');
              $('[in="opsi"]').html(data.err.opsi);
            } else {
              $('[name="opsi"]').removeClass('is-invalid');
              $('[in="opsi"]').html();
            }
          } else {
            $('#modal_form').modal('hide');
            window.location.href = "<?=site_url('dashboard/pesanan') ?>";
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert('Error adding / update data');
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
          <form id="form" action="<?= site_url('dashboard/pesanan/update') ?>" method="post" class="form-horizontal">
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
            <button type="submit" class="btn btn-primary">SIMPAN</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>