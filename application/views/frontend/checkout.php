<div class="container" style="margin-top: 150px">
  <main>
    <div class="row">
      <!-- ------------------------------------------------------------ INPUTCHECKOUT -->
      <div class="col-md-12 col-lg-8">
        <h4 class="mb-3 fw-bold">CHECKOUT</h4>
        <form action="" class="needs-validation" id="form" method="post">
          <input type="hidden" name="total_bayar" value="<?=$total_harga ?>">
          <?= form_hidden('total_harga', $total_harga); ?>
          <?= form_hidden('total_berat', $total_berat); ?>
          <?php  foreach ($items_keranjang as $item) : ?>
          <?= form_hidden('id_produk[]', $item->id_produk); ?>
          <?= form_hidden('id_keranjang[]', $item->id_keranjang); ?>
          <?= form_hidden('kuantitas[]', $item->kuantitas); ?>
          <?= form_hidden('catatan[]', $item->catatan); ?>
          <?= form_hidden('harga_sementara[]', $item->total_harga_produk*$item->kuantitas); ?>
          <?php endforeach; ?>
          <div class="row g-3">
            <div
              class="col-md-12 bg-light border border-primary rounded p-3"
              >
              <div class="row">
                <div class="col-md-4">
                  <label for="" class="form-label fw-bolder"
                    >Pilih Opsi Pembelian :</label
                  >
                </div>
                <div class="col-md-4">
                  <div class="d-flex justify-content-around">
                    <div class="form-check">
                      <input
                      name="opsiPembelian"
                      type="radio"
                      class="form-check-input"
                      id="dikirim"
                      value="1"
                      required
                      />
                      <label class="form-check-label" for="dikirim"
                        >Dikirim</label
                      >
                    </div>
                    <div class="form-check">
                      <input
                      name="opsiPembelian"
                      type="radio"
                      class="form-check-input"
                      id="ditempat"
                      value="2"
                      required
                      />
                      <label class="form-check-label" for="ditempat"
                        >Ditempat</label
                      >
                    </div>
                  </div>
                </div>
                <p class="text-muted mt-2">
                  <small
                    >Pilih Opsi Pembelian "Dikirim" jika anda mau menikmati
                    Kopi kami dirumah, atau pilih opsi "Ditempat" jika anda
                    mau menikamati kopi di Cafe Kami.</small
                  >
                </p>
              </div>
            </div>

            <div class="col-md-7 col-sm-7">
              <label for="nama_penerima" class="form-label">Nama Penerima</label>
              <input
              type="text"
              class="form-control"
              placeholder="Nama Anda?"
              name="nama_penerima"
              />
              <div class="invalid-feedback" in="nama_penerima"></div>
            </div>

            <div class="col-md-5 col-sm-5">
              <label for="no_penerima" class="form-label">Nomor Telepon</label>
              <input
              type="text"
              class="form-control"
              placeholder="08...."
              required
              name="no_penerima"
              />
              <div class="invalid-feedback" in="no_penerima"></div>
            </div>

            <!-- ------------------------------------------ OPSI-DIKIRIM -->
            <div class="col-md-6 col-sm-6 col-6 opsi-dikirim">
              <label for="provinsi" class="form-label">Provinsi</label>
              <select class="form-select input-opsi-dikirim" required name="prov" id="prov">
                <option value="" hidden>Pilih Provinsi</option>
              </select>
              <div class="invalid-feedback" in="prov">
              </div>
            </div>

            <div class="col-md-6 col-sm-6 col-6 opsi-dikirim">
              <label for="kabupaten" class="form-label"
                >Kota/Kabupaten</label
              >
              <select class="form-select input-opsi-dikirim" required name="kab" id="kab">
                <option value="" hidden>Pilih Kabupaten</option>
              </select>
              <div class="invalid-feedback" in="kab">
                Di Kota mana Anda Tinggal?
              </div>
            </div>

            <div class="col-md-6 col-sm-6 col-6 opsi-dikirim">
              <label for="kecamatan" class="form-label">Kecamatan</label>
              <select class="form-select input-opsi-dikirim" required name="kec" id="kec">
                <option value="" hidden>Pilih Kecamatan</option>
              </select>
              <div class="invalid-feedback" in="kec"></div>
            </div>

            <div class="col-md-6 col-sm-6 col-6 opsi-dikirim">
              <label for="kode-pos" class="form-label">Kode Pos</label>
              <input
              type="number"
              class="form-control input-opsi-dikirim"
              id="zip"
              placeholder="Kode Pos..."
              required
              name="kode_pos"
              />
              <div class="invalid-feedback" in="kode_pos"></div>
            </div>

            <div class="form-floating opsi-dikirim">
              <textarea
                class="form-control input-opsi-dikirim"
                placeholder="Masukan Alamat Lengkap Anda"
                style="max-height: 100px; height: 100px"
                required
                name="detail_alamat"
                ></textarea>
              <label for="floatingTextarea" class="ms-1"
                >Alamat Lengkap</label
              >
              <div class="invalid-feedback" in="detail_alamat">
              </div>
            </div>

            <div class="col-md-6 col-sm-6 opsi-dikirim">
              <label for="kurir" class="form-label">Kurir</label>
              <select class="form-select input-opsi-dikirim" name="kurir" id="kurir" required>
                <option value="" hidden>Pilih Kurir</option>
                <option value="pos">POS</option>
                <option value="jne">JNE</option>
                <option value="tiki">TIKI</option>
              </select>
              <div class="invalid-feedback" in="kurir"></div>
            </div>

            <div class="col-md-6 col-sm-6 opsi-dikirim">
              <label for="layanan" class="form-label">Layanan</label>
              <select class="form-select input-opsi-dikirim" name="layanan" id="layanan" required>
                <option value="" hidden>Pilih Layanan</option>
              </select>
              <div class="invalid-feedback">
                Pilih Layanan Terpercaya Anda
              </div>
            </div>

            <!-- ------------------------------------------ end-OPSI-DIKIRIM -->

            <!-- ------------------------------------------ OPSI-DITEMPAT -->
            <div class="col-md-5 col-sm-5 opsi-ditempat">
              <label for="meja" class="form-label">No.Meja</label>
              <select class="form-select input-opsi-ditempat" required name="no_meja">
                <option value="" hidden>Pilih No.Meja</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
              <div class="invalid-feedback" in="no_meja">
                Pilih Tempat Untuk Anda Duduk
              </div>
            </div>
            <div class="col-md-7 col-sm-7 d-flex align-items-center opsi-ditempat">
              <small class="text-muted  opsi-ditempat">
                Kami Akan Menyediakan Meja yang Anda Pilih Di waktu yang
                Anda tetapkan. Jadi Pastikan Untuk Datang Ya :)
              </small>
            </div>
            <div class="col-md-6 col-sm-6 opsi-ditempat">
              <label for="tanggal" class="form-label"
                >Perkiraan Tanggal Datang</label
              >
              <input
              type="date"
              name="tanggal"
              id="tanggal"
              class="form-control input-opsi-ditempat"
              required
              />
              <div class="invalid-feedback" in="tanggal">
                Tentukan Tanggal Anda Datang
              </div>
            </div>
            <div class="col-md-6 col-sm-6 opsi-ditempat">
              <label for="waktu" class="form-label"
                >Perkiraan Waktu Datang</label
              >
              <input
              type="time"
              name="waktu"
              id="time"
              class="form-control input-opsi-ditempat"
              required
              />
              <div class="invalid-feedback" in="waktu">
                Tentukan Waktu Anda Datang
              </div>
            </div>
            <!-- ------------------------------------------ end-OPSI-DITEMPAT -->

            <div class="col-md-6 form-floating col-sm-5 col-5">
              <input
              type="text"
              class="form-control"
              placeholder="Ongkos Kirim"
              value="<?=$total_berat ?> Gram"
              readonly
              />
              <label for="ongkir" class="ms-1"> Total Berat</label>
            </div>

            <div class="col-md-6 form-floating col-sm-7 col-7">
              <input
              type="text"
              class="form-control"
              placeholder="Ongkos Kirim"
              value="Rp "
              readonly
              name="ongkir" id="ongkir"
              />
              <label for="ongkir" class="ms-1"> Ongkos Kirim </label>
            </div>

            <div class="col-md-8 col-7 d-flex align-items-center">
              <button class="btn btn-primary w-100 mt-4" type="button" id="save-btn" onclick="checkout()">Checkout</button>
            </div>
            <div class="col-md-4 col-5 d-flex align-items-center">
              <button class="btn btn-danger w-100 mt-4" type="reset">
                Reset
              </button>
            </div>
          </div>
        </form>
      </div>
      <!-- ------------------------------------------------------------ end-INPUTCHECKOUT -->
      <!-- ---------------------------------------------------------------- SIDEBOX -->
      <div
        class="
        col-md-12 col-lg-4
        order-md-last
        position-fixed
        end-0
        me-3
        mt-5
        sideBoxCheckout
        bg-light
        p-3
        rounded
        "
        >
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="fw-bold text-uppercase">Produk yang Dibeli</span>
          <span class="badge rounded-pill"><?=count($items_keranjang) ?></span>
        </h4>
        <ul class="list-group mb-3">
          <?php  foreach ($items_keranjang as $item) : ?>

          <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0"><?= $item->nama_produk ?></h6>
              <small>
                <span class="badge bg-danger">-<?= $item->diskon_produk ?> %</span>
              </small>
              <small>
                <span class="badge bg-success"><?= $item->kuantitas ?> x</span>
              </small>
            </div>
            <div>
              <span class="text-danger" style="font-size: 12px">
                <del><?= rupiah($item->harga_produk) ?></del>
              </span>
              <span class="text-success"><?= rupiah($item->total_harga_produk*$item->kuantitas) ?></span>
            </div>
          </li>
          <?php endforeach; ?>
          <li
            class="
            list-group-item
            d-flex
            justify-content-between
            bg-light
            p-3
            "
            >
            <span class="fw-bold text-secondary"
              >Total yang Harus Dibayar</span
            >
            <strong class="text-success" id="total_bayar"><?=$total_harga ?></strong>
          </li>
        </ul>
      </div>
      <!-- ---------------------------------------------------------------- end-SIDEBOX -->
    </div>
  </main>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    $('#total_bayar').val("<?=$total_harga ?>");
    $('.opsi-dikirim').hide();
    $('.opsi-ditempat').hide();

    $.get("<?=site_url('ajax/provinsi') ?>", function(data) {
      let option = `<option value="" selected disabled>--- PILIH PROVINSI ---</option>`;
      $.each(data, function(key, value) {
        option += `<option value="`+value.id+`" data-nama="`+value.nama+`">`+value.nama+`</option>`;
      });
      $('#prov').html(option);
    });

    $('#prov').change(function() {
      let id = $(this).val();
      $('[name="nama_prov"]').val($(this).find(':selected').data('nama'));
      $.ajax({
        url: "<?=site_url('ajax/kabupaten/') ?>"+id,
        type: 'get',
        success: function(data) {
          let option = `<option value="">--- PILIH KABUPATEN ---</option>`;
          $.each(data, function(key, value) {
            option += `<option value="`+value.id+`" data-nama="`+value.nama+`">`+value.tipe+` `+value.nama+`</option>`;
          });
          $('#kab').html(option);
        }
      });
    });

    $('#kab').change(function() {
      let id = $(this).val();
      $('[name="nama_kab"]').val($(this).find(':selected').data('nama'));
      $.ajax({
        url: "<?=site_url('ajax/kecamatan/') ?>"+id,
        type: 'get',
        success: function(data) {
          let option = `<option value="">--- PILIH KECAMATAN ---</option>`;
          $.each(data, function(key, value) {
            option += `<option value="`+value.id+`" data-nama="`+value.nama+`">`+value.nama+`</option>`;
          });
          $('#kec').html(option);
        }
      });
    });

    $('#kec').change(function() {
      let id = $(this).val();
      $('[name="nama_kec"]').val($(this).find(':selected').data('nama'));
    });

    $('#kurir').change(function() {
      let dest = $('#kab').val();
      let kurir = $('#kurir').val();
      let total_berat = $('[name="total_berat"]').val();
      $('#layanan').html(`<option value=""> MENCARI LAYANAN...</option>`);
      $.ajax({
        url: "<?=site_url('ajax/courier') ?>",
        method: 'post',
        data: {
          dest: dest,
          kurir: kurir,
          total_berat: total_berat
        },
        success: function(data) {
          $('#layanan').html(data);
        }
      });

    });

    $('#layanan').change(function() {
      var layanan = $('#layanan').val();
      var total_bayar = $('#total_bayar').val();

      $.ajax({
        url: "<?=site_url('ajax/ongkir'); ?>",
        method: "POST",
        data: {
          layanan: layanan,
          total_bayar: total_bayar
        },
        success: function(data) {
          console.log(data);
          var hasil = data.split(",");
          $('#ongkir').val(hasil[0]);
          $('#total_bayar').html(hasil[1]);
          $('[name=total_bayar]').val(hasil[1]);
        }
      });
    });

    $('[name=opsiPembelian]').change(function() {
      let opsi = $(this).val();
      if (opsi == 1) {
        $('.opsi-dikirim').show();
        $('.opsi-ditempat').hide();

        $('.input-opsi-dikirim').attr('required', 'on');
        $('.input-opsi-ditempat').removeAttr('required');

      } else if (opsi == 2) {
        $('.opsi-ditempat').show();
        $('.opsi-dikirim').hide();

        $('.input-opsi-dikirim').removeAttr('required');
        $('.input-opsi-ditempat').attr('required', 'on');
      }
    });


  });

  function checkout() {
    let opsiPembelian = $("#form input[type='radio']:checked").val();


    Swal.fire({
      title: 'Kamu yakin?',
      text: "Pemesanan akan dicheckout!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "<?=site_url('Checkout/validasi') ?>",
          method: 'POST',
          dataType: 'json',
          data: $('#form').serialize(),
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
            } else {
              window.location.href = "<?=site_url('checkout/success_checkout/') ?>"+opsiPembelian;
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