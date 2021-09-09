<!-- -------------------------------------------------- POPUPBOX -->
<div
  class="offcanvas offcanvas-bottom"
  id="popupboxcart"
  aria-labelledby="offcanvasBottomLabel"
  >
  <div class="container">
    <div class="offcanvas-header position-relative mt-3 pb-5">
      <div class="row w-100">
        <div class="col-md-6">
          <div class="d-flex justify-content-between">
            <div style="width: 150px;">
              <img src="" alt="gambar produk" class="img-thumbnail popupcart-img" style="max-width: 100px; max-height: 150px;">
            </div>
            <div class="ms-3 w-100">
              <h5 class="offcanvas-title fw-bold d-inline popupcart-nama-produk" id="offcanvasBottomLabel">
                nama produk
              </h5>
              <span class="badge bg-danger popupDiscount popupcart-diskon-produk">diskon produk (%)</span><br>
              <h5 class="d-inline popupcart-total-harga-produk" style="color: #28bda5">
                harga produk sebelum diskon
              </h5>
              <small class="text-danger text-decoration-line-through ms-1 popupcart-harga-produk">
                harga produk setelah diskon
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
          <form action="" id="formpopupboxcart">
            <input type="hidden" name="id_produk_cart" id="id_produk_cart" value="" />
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
                  name="qty"
                  />
                </div>
              </div>
              <div class="col-7 d-flex align-items-center">
                <button class="btn w-100 btn-sm text-white" type="button" style="background-color: #00b14f;" onclick="add_cart()">
                  <i class="fas fa-cart-plus me-1"></i> Tambah Keranjang
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



<!-- ------------------------------------------------------------------------- FOOTER -->
<footer>
  &copy; <?=date('Y') ?> Hak Cipta Terpelihara Oleh <?=getset('nama_toko') ?>
</footer>
<!-- end-FOOTER -->


<!-- Bootstrap-->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"
  crossorigin="anonymous"
  ></script>

  
<!-- MyJS -->
<script src="<?=base_url('assets/frontend2/') ?>js/script.js"></script>
<script src="<?=base_url('assets/function.js') ?>"></script>

<script>


  $(document).ready(function() {
    count_cart()
    count_notif()
    produk()



    $('#form_search').submit(false);

    /*------ WHEN CHOOSE CATEGORY PRODUCT (MOBILE) --------*/
    $('#inputGroupSelect01').change(function() {
      let id_kategori = $('#inputGroupSelect01').val();
      produk(id_kategori);
    });

    /*------ WHEN INPUT SEARCH SUBMIT --------*/
    // $('#form_search').submit(function() {
    //   search_produk()
    //});

    $('.search-desktop').submit(function() {
      let search = $('[name=search-desktop]').val();
      search_produk(search);
    });
    $('.button-search-desktop').on('click', function() {
      let search = $('[name=search-desktop]').val();
      search_produk(search);
    });
    $('.search-mobile').submit(function() {
      let search = $('[name=search-mobile]').val();
      search_produk(search);
    });
    $('.button-search-mobile').on('click', function() {
      let search = $('[name=search-mobile]').val();
      search_produk(search);
    });
    $('.search-navbar').submit(function() {
      let search = $('[name=search-navbar]').val();
      search_produk(search);
    });


    $('.kategori-dekstop').on('click', function() {
      let idProduk = $(this).data('idproduk');
      produk(idProduk);
      $('.kategori-dekstop').removeClass('active');
      $(this).addClass('active');
    })

    $('#listkopi > .item-listkopi').hide();

  });


  /*------ LIVE CHAT BY TAWK --------*/
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/613a260bd326717cb6809be4/1ff5i9aht';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();



  /*------ aksi scroll pada bagian tertentu disuatu halaman --------*/
  function scrollToAnchor(name) {
    var aTag = $("a[name='"+ name +"']");
    $('html,body').animate({
      scrollTop: aTag.offset().top
    },
      'slow');
  }



  /*------ MENAMPILKAN ANGKA DALAM FORMAT RUPIAH --------*/
  function rupiah(angka) {
    let
    number_string = angka.toString(),
    sisa = number_string.length % 3,
    rupiah = number_string.substr(0,
      sisa),
    ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
      separator = sisa ? '.': '';
      rupiah += separator + ribuan.join('.');
    }

    // Cetak hasil
    return `Rp. ${rupiah}`;
  }


  /*------ ACTION ADD PRODUCT TO CART --------*/
  function add_cart() {
    let idProduk = $('[name=id_produk_cart]').val();
    let url = "<?=site_url('keranjang/add_cart') ?>";
    let qty = $("[name=qty]").val();
    let catatan = $("[name=catatan]").val();
    if (qty == '') {
      Toast.fire({
        icon: 'error',
        title: 'GAGAL: kuantitas harus diisi!'
      })
    } else {

      $.ajax({
        url: url,
        method: 'post',
        data: {
          qty: qty,
          catatan: catatan,
          id_produk: idProduk
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
          $('#popupboxcart').offcanvas('hide');
          count_cart();
          Toast.fire({
            icon: 'success',
            title: data.message
          })
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
  }



  function get_produk_id(idProduk) {

    let session = "<?=sud('email') ?>";
    if (!session) {
      Toast.fire({
        icon: 'warning',
        title: 'Anda belum login'
      })
    } else {

      let url = "<?=site_url('keranjang/add_cart') ?>";
      $.ajax({
        url: "<?=site_url('home/produk_by_id/') ?>",
        method: 'post',
        data: {
          id_produk: idProduk
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
          $('#formpopupboxcart')[0].reset(); // reset form on modals
          $('#popupboxcart').offcanvas('show');
          let src = "<?=base_url('uploads/image/produk/') ?>"+data.gambar_produk;
          let idProduk = $('[name=id_produk_cart]').val(data.id_produk);
          $('.popupcart-img').attr('src', src);
          $('.popupcart-nama-produk').html(data.nama_produk);
          $('.popupcart-diskon-produk').html(`-${data.diskon_produk}%`);
          $('.popupcart-harga-produk').html(rupiah(data.harga_produk));
          $('h5.popupcart-total-harga-produk').html(rupiah(data.total_harga_produk));
          $('.popupcart-stok-produk').html(`Stok Tersedia :  ${data.stok_produk} pcs`);
          $('.popupcart-berat-produk').html(`Berat Produk : ${data.berat_produk} Gram`);
        }
      });
    }

  }



  /*------ COUNT CART --------*/
  function count_cart() {
    $.get("<?=site_url('ajax/count_cart') ?>",
      function(data) {
        if (data > 0) {
          $("#count_cart").show();
          $("#count_cart").html(data);
        } else {
          $("#count_cart").hide();
        }
      });
  }

  /*------ COUNT NOTIFICATION --------*/
  function count_notif() {
    $.get("<?=site_url('ajax/count_notifikasi') ?>",
      function(data) {
        if (data != 0) {
          $("#count_notif").html(data);
        } else {
          $("#count_notif").hide();
        }
      });
  }

  /*------ LIST PRODUCT --------*/
  function produk(id_kategori = null) {

    $.ajax({
      url: "<?=site_url('ajax/produk') ?>",
      data: {
        id_kategori: id_kategori
      },
      cache: false,
      success: function(data) {
        $("#listkopi").html(data);
      }
    })


  }

  /*------ ACTION SEARCH PRODUCT --------*/
  function search_produk(search) {
    // let search = $('[name="cari"]').val();
    console.log(search);
    $.get("<?=site_url('ajax/produk') ?>",
      {
        search: search
      })
    .done(function(data) {
      scrollToAnchor('anchor-listkopi');
      $("#listkopi").html(data);
    });
  }


  /*
  function autocomplete() {
    $.ajax({
      url: "<?=site_url('ajax/search_autocomplete') ?>",
      type: 'get',
      success: function(result) {
        $('[name="cari"]').autocomplete({
          source: result,
          select: function(event, data) {}
        });
      }
    });
  }
*/

</script>

</body>
</html>