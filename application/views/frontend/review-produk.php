<!-- ------------------------------------------------------------------------- MAIN -->
<div class="mb-4 text-center banner">
  <h2 class="fw-bold">
    <i class="fas fa-mug-hot logo text-white"></i>
    <?=getset('nama_toko') ?>
  </h2>
  <h6 class="text-muted">Tempat Nongkrong sambil Ngopi</h6>
</div>
<div class="container bg-white p-3 rounded border border-success">
  <div class="my-4 mb-5">
    <h4 class="fw-bold d-inline">REVIEW</h4>
    <small class="text-muted ms-3">
      Berikan ulasan dan rating pada produk kami.
    </small>
  </div>
  <!-- end-BANNER -->

  <div class="row">
    <?php foreach ($produk as $value): ?>
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="row">
          <div class="col-sm-6 col-md-12 col-6">
            <img
            src="<?=base_url('uploads/image/produk/'.$value['gambar_produk']) ?>"
            class="img-fluid rounded-start"
            alt="foto-produk"
            style="min-width: 100%; min-height: 100%"
            />
          </div>
          <div class="col-sm-6 col-md-12 col-6">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <small class="card-title">#<?=$value['id_pesanan'] ?></small>
                  <h5 class="card-title reviewProduct fw-bold text-nowrap">
                    <?=$value['nama_produk'] ?>
                  </h5>
                </div>
                <div class="col-lg-6 text-end reviewProduct text-nowrap">
                  <h5 style="color: #28bda5"><?=rupiah($value['total_harga_produk']) ?></h5>
                </div>
              </div>
              <?php
              $rating = $this->db->query('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_ulasan FROM ulasan_produk WHERE id_produk = '.$value['id_produk'])->row_array();
              ?>
              <p class="card-text text-muted mt-3 reviewProduct">
                Rating Saat Ini :
                <span class="text-warning fw-bold">
                  <i class="fas fa-star"></i><?=number_format($rating['overall_rating'], 1) ?>
                </span>
              </p>
              <button
                type="button"
                class="btn btn-success w-100 mb-3 text-white"
                onclick="produk_id(<?=$value['id_produk'] ?>,'<?=number_format($rating['overall_rating'], 1) ?>',<?=$value['id_item_pesanan'] ?>,<?=$value['id_pesanan'] ?>)"
                >
                Berikan Review
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<!-- end-MAIN -->
<!-- ---------------------------------------------------------------- MODAL -->
<div
  class="modal fade"
  id="modalUlasan"
  tabindex="-1"
  >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUlasanLabel">Review</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          ></button>
      </div>
      <div class="modal-body">
        <form action="<?=site_url('ulasan/ulasan_produk') ?>" id="form_ulasan" method="post">
          <input type="hidden" name="id_produk" id="id_produk-modal" value="" />
          <input type="hidden" name="id_item_pesanan" value="" />
          <input type="hidden" name="id_pesanan" value="" />
          <div class="row">
            <div class="col-md-5 text-center">
              <img
              alt="gambar-produk"
              style="max-width: 100%; max-height: 160px"
              class="img-thumbnail"
              id="gambar-produk-modal"
              />
            </div>
            <div class="col-md-7">
              <label for="rating" class="form-label"
                >Rating dari Anda :</label
              >
              <div class="input-group w-100">
                <div class="input-group-text bg-light">
                  <i class="fas fa-star text-warning"></i>
                </div>
                <input
                type="number"
                class="form-control"
                id="rating"
                placeholder="Beri Rating"
                name="ratting"
                />
                <div in="ratting" class="invalid-feedback"></div>
              </div>
              <div id="emailHelp" class="form-text">
                Rating saat ini :
                <span class="text-warning fw-bolder" id="ratting-modal">

                </span>
              </div>
            </div>
          </div>
          <div class="form-floating mt-3">
            <textarea
              class="form-control"
              placeholder="Leave a comment here"
              id="comment"
              style="min-height: 200px; max-height: 200px"
              name="komentar"
              ></textarea>
            <label for="comment">Komentarmu</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="tidak_nilai_produk()" name="tolak" class="btn btn-secondary">
            Tidak Mau Review
          </button>
          <button type="button" onclick="nilai_produk()" class="btn btn-success text-white">
            Kirim Review
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript" charset="utf-8">

  function produk_id(idProduk, ratting, idItemPesanan, idPesanan) {
    $.ajax({
      url: "<?=site_url('home/produk_by_id/') ?>",
      method: 'post',
      data: {
        id_produk: idProduk
      },
      dataType: 'json',
      cache: false,
      success: function(data) {
        $('#form_ulasan')[0].reset(); // reset form on modals
        $('#modalUlasan').modal('show');
        let src = "<?=base_url('uploads/image/produk/') ?>"+data.gambar_produk;
        $('[name=id_produk]').val(data.id_produk);
        $('[name=id_item_pesanan]').val(idItemPesanan);
        $('[name=id_pesanan]').val(idPesanan);
        $('#gambar-produk-modal').attr('src', src);
        $('#ratting-modal').html(`<i class="fas fa-star"></i> ${ratting}`);
      }
    });

  }


  function nilai_produk() {
    let url = "<?=site_url('ulasan/nilai_produk') ?>";
    let formData = $('#form_ulasan').serialize();

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `penilaian produk ini akan disubmit?`,
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
          data: formData,
          dataType: 'json',
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
              $('#modalUlasan').modal('hide');

              Toast.fire({
                icon: 'success',
                title: "Penilaian anda telah dikirim"
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




  function tidak_nilai_produk() {
    let url = "<?=site_url('ulasan/tidak_nilai_produk') ?>";
    let formData = $('#form_ulasan').serialize();

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `tidak akan menilai produk ini?`,
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
          data: formData,
          dataType: 'json',
          cache: false,
          success: function(data) {
            $('#modalUlasan').modal('hide');

            Toast.fire({
              icon: 'success',
              title: "Produk tidak dinilai"
            })
            setTimeout(function() {
              location.reload();
            }, 2000);
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