<!-- ------------------------------------------------------------------------- KERANJANG -->
<div class="keranjangContainer container-fluid">
  <h4 class="mb-3 fw-bold mt-3">KERANJANG</h4>
  <div class="row">
    <div class="col-lg-8">
      <table class="table table-striped keranjangTable">
        <tr class="text-light" style="background-color: #00b14f">
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Harga</th>
          <th></th>
        </tr>
        <?php  foreach ($items_keranjang as $item) : ?>
        <tr>
          <td>
            <img
            src="<?=base_url('uploads/image/produk/').$item->gambar_produk; ?>"
            alt="<?="Gambar $item->nama_produk" ?>"
            width="100px"
            height="100px"
            class="img-thumbnail gambarKeranjang"
            />
            <a href="<?=site_url("produk/{$item->slug_produk}") ?>" style="color:black"><?=$item->nama_produk ?></a>
          </td>
          <td style="padding-top: 40px"><?= $item->kuantitas; ?></td>
          <td style="padding-top: 40px">
            <?= rupiah($item->total_harga_produk*$item->kuantitas) ?>
          </td>
          <td style="padding-top: 40px">
            <div class="dropdown mobileOpsi">
              <button
                class="btn dropdown-toggle text-white"
                style="background-color: #00b14f"
                type="button"
                id="dropdownMenuButton1"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                >
                <i class="fas fa-cog"></i>
              </button>
              <ul
                class="dropdown-menu"
                aria-labelledby="dropdownMenuButton1"
                >
                <li><a onclick="get_produk_in_cart(<?=$item->id_keranjang ?>,<?=$item->id_produk ?>,<?=$item->kuantitas ?>,'<?=$item->catatan ?>')" class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                <li>
                  <a onclick="detele_cart(<?=$item->id_keranjang ?>)" class="dropdown-item" href="javasript:void(0)">Hapus</a>
                </li>
              </ul>
            </div>

            <div class="desktopOpsi">
              <a onclick="get_produk_in_cart(<?=$item->id_keranjang ?>,<?=$item->id_produk ?>,<?=$item->kuantitas ?>,'<?=$item->catatan ?>')"
                class="btn text-white"
                style="background-color: #00b14f"
                ><i class="fas fa-pencil-alt"></i
                ></a>
              <a
                onclick="detele_cart(<?=$item->id_keranjang ?>)"
                href="javascript:void(0)"
                class="btn text-white"
                style="background-color: #db3a34"
                ><i class="fas fa-trash-alt"></i
                ></a>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <strong class="text-uppercase">Catatan :</strong> <?=$item->catatan ?>
          </td>
        </tr>
        <?php endforeach; ?>

      </table>
    </div>
    <div class="col-lg-4">
      <div class="totalBayar">
        <h4 class="d-flex justify-content-between mt-4">
          Total <span><?=rupiah($total_harga) ?></span>
        </h4>
        <?= anchor(site_url('checkout'), '<button class="btn w-100 mt-3">Checkout</button>') ?>

      </div>
    </div>
  </div>
</div>
<!-- end-KERANJANG -->




<script type="text/javascript" charset="utf-8">


  function update_cart() {
    let data = $('#form_update_cart').serialize();
    $.ajax({
      url: "<?=site_url('keranjang/ajax_update') ?>",
      type: "POST",
      data: data,
      dataType: "JSON",
      cache: false,
      success: function(data) {
        $('#editkeranjang').offcanvas('hide');
        if (data.status == false) {
          $('[name="kuantitas"]').addClass('is-invalid');
          $('[in="kuantitas"]').html(data.err.kuantitas);
        } else {
          Toast.fire({
            icon: 'success',
            title: "Keranjang berhasil update"
          })
          setTimeout(function() {
            location.reload();
          }, 2000);

        }
      },
      error: function (jqXHR, textStatus, errorThrown) {}
    });
  }


  function get_produk_in_cart(idKeranjang, idProduk, qty, catatan) {
    $('#form_update_cart')[0].reset(); // reset form on modals
    $('#editkeranjang').offcanvas('show');
    let session = "<?=sud('email') ?>";
    if (!session) {
      Toast.fire({
        icon: 'warning',
        title: 'Anda belum login'
      })
    } else {
      $.ajax({
        url: "<?=site_url('home/produk_by_id/') ?>",
        method: 'post',
        data: {
          id_produk: idProduk
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
          let src = "<?=base_url('uploads/image/produk/') ?>"+data.gambar_produk;
          $('.popupcart-img').attr('src', src);
          $('.popupcart-nama-produk').html(data.nama_produk);
          $('.popupcart-diskon-produk').html(`-${data.diskon_produk}%`);
          $('.popupcart-harga-produk').html(rupiah(data.harga_produk));
          $('h5.popupcart-total-harga-produk').html(rupiah(data.total_harga_produk));
          $('.popupcart-stok-produk').html(`Stok Tersedia :  ${data.stok_produk} pcs`);
          $('.popupcart-berat-produk').html(`Berat Produk : ${data.berat_produk} Gram`);
          $('[name=id]').val(idKeranjang);
          $('[name=id_produk]').val(idProduk);
          $('[name=kuantitas_lama]').val(qty);
          $('[name=kuantitas]').val(qty);
          $('[name=catatan]').val(catatan);
        }
      });
    }

  }


  function detele_cart(idKeranjang) {
    Swal.fire({
      title: 'Kamu yakin?',
      text: "Akan menghapus produk dari keranjang!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!'
    }).then((result) => {
      if (result.isConfirmed) {
        let url = "<?=site_url('keranjang/remove_keranjang') ?>";
        $.ajax({
          url: url,
          type: "POST",
          dataType: "JSON",
          data: {
            id: idKeranjang
          },
          cache: false,
          success: function(data) {
            Toast.fire({
              icon: 'success',
              title: "Keranjang berhasil dihapus"
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
        });
      }
    })

  }

</script>



<!-- -------------------------------------------------- POPUPBOX EDIT KERANJANG-->
<div
  class="offcanvas offcanvas-bottom"
  id="editkeranjang"
  aria-labelledby="offcanvasBottomLabel"
  >
  <div class="container">
    <div class="offcanvas-header position-relative mt-3 pb-5">
      <div class="row w-100">
        <div class="col-md-6">
          <div class="d-flex justify-content-between">
            <div style="width: 150px;">
              <img src="https://s-ecom.ottenstatic.com/thumbnail/5eddc89125c24525432961.jpg" alt="" class="img-thumbnail popupcart-img" style="max-width: 100px; max-height: 150px;">
            </div>
            <div class="ms-3 w-100">
              <h5 class="offcanvas-title fw-bold d-inline popupcart-nama-produk" id="offcanvasBottomLabel">
                Nama produk
              </h5>
              <span class="badge bg-danger popupDiscount popupcart-diskon-produk">-12%</span><br>
              <h5 class="d-inline popupcart-total-harga-produk" style="color: #28bda5">
                Harga produk sebelum diskon
              </h5>
              <small class="text-danger text-decoration-line-through ms-1 popupcart-harga-produk">
                Harga produk setelah diskon
              </small>
              <p class="text-muted my-0 mt-2 popupcart-stok-produk">
                stok produk
              </p>
              <p class="text-muted my-0 popupcart-berat-produk">
                berat produk
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 inputMessageCount">
          <form action="" id="form_update_cart">
            <input type="hidden" name="id" id="id" value="" />
            <input type="hidden" name="id_produk" id="id_produk" value="" />
            <input type="hidden" name="kuantitas_lama" id="kuantitas_lama" value="" />
            <div class="form-floating">
              <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="min-height: 100px; max-height: 100px;" name="catatan"></textarea>
              <label for="floatingTextarea">Pesan Pembelian</label>
            </div>
            <div class="row mt-2">
              <div class="col-5">
                <div class="input-group">
                  <div class="input-group-text" style="background-color: #e9ecef;">
                    Pcs
                  </div>
                  <input
                  type="number"
                  id="points"
                  step="1"
                  class="form-control"
                  name="kuantitas"
                  />
                </div>
              </div>
              <div class="col-7 d-flex align-items-center">
                <button class="btn w-100 btn-sm text-white" type="button" style="background-color: #00b14f;" onclick="update_cart()">
                  <i class="fas fa-cart-plus me-1"></i> Simpan Perubahan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <button
        type="button"
        class="btn-close text-reset position-absolute top-0 end-0 mt-3"
        data-bs-dismiss="offcanvas"
        aria-label="Close"
        ></button>
    </div>
  </div>
</div>
<!-- -------------------------------------------------- POPUPBOX -->