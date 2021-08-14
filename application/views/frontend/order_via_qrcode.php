<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
  rel="stylesheet"
  integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
  crossorigin="anonymous"
  />

  <!-- Font Awesome -->
  <link
  rel="stylesheet"
  href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
  integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
  crossorigin="anonymous"
  />

  <!-- myCSS -->
  <link rel="stylesheet" href="<?=base_url('assets/frontend2/') ?>css/style.css" />
  <link rel="stylesheet" href="<?=base_url('assets/frontend2/') ?>css/responsive.css" />

  <!-- Jquery UI -->
  <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

                                                                                                                                                <!-- Sweetalert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>


  <!-- Jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.0-rc.1/jquery-ui.min.js" integrity="sha256-mFypf4R+nyQVTrc8dBd0DKddGB5AedThU73sLmLWdc0=" crossorigin="anonymous"></script>



  <title><?=$title ?></title>
</head>
<body>
  <!-- ------------------------------------------------------------------------- NAVBAR -->
  <nav class="navbar navbar-light bg-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="<?=site_url() ?>"><?=getset('nama_toko') ?></a>
    </div>
  </nav>
  <div class="navBottom">
    <form id="form_search" method="post">
      <input
      type="text"
      name="cari"
      class="form-control"
      placeholder="Type to search"
      autocomplete="on"
      />
    </form>
  </div>
  <!-- end-NAVBAR -->

  <div class="container">
    <br><br><br>
    <form action="<?=site_url('home/add_order_vqrcode') ?>" method="post">
      <div class="row mb-4">
        <div class="col-12">
          <table class="table table-striped keranjangTable" id="table_order">
            <thead>
              <tr class="text-light" style="background-color: #00b14f">
                <th>Produk</th>
                <th>Harga/1pc</th>
                <th>Jumlah Beli</th>
                <th>Sub Harga</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="list-produk-cart">
            </tbody>
          </table>
        </div>
        <div class="col-6">
          <table class="table table-sm">
            <tr>
              <th>ID USER</th>
              <td><input type="number" name="id_user" class="form-control"></td>
            </tr>
            <tr>
              <th>NAMA</th>
              <td><input type="text" name="nama" class="form-control" required></td>
            </tr>
            <tr>
              <th>TOTAL HARGA</th>
              <td><input type="number" name="total_harga" class="form-control" readonly></td>
            </tr>
            <tr>
              <th>MEMBAYAR</th>
              <td><input type="number" name="bayar" id="bayar" class="form-control" required></td>
            </tr>
            <tr>
              <th>KEMBALIAN</th>
              <td><input type="number" name="kembalian" id="kembalian" class="form-control" readonly></td>
            </tr>
          </table>
        </div>
        <div class="col-6">
          <button class="btn btn-success" id="btnsubmit" type="submit" name="submit">PESAN SEKARANG</button>
        </div>
      </div>
    </form>
    <a name="anchor-listkopi" />
    <!-- ------------------------------------------------------------------------- LIST KATEGORI -->
    <section class="d-flex justify-content-around">
      <ul class="kategori dekstop">
        <li class="active kategori-dekstop" data-idproduk="">
          <a href="javascript:void(0)">Semua</a>
        </li>
        <?php foreach (kategori() as $kategori): ?>
        <li class="kategori-dekstop" data-idproduk="<?=$kategori->id_kategori ?>">
          <a href="javascript:void(0)" class=""><?=$kategori->nama_kategori; ?></a>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <div class="kategori mobile d-flex justify-content-between">
      <section style="width: 44%;">
        <select class="form-select" id="inputGroupSelect01">
          <option selected hidden>Pilih Kategori...</option>
          <option value="" <?=$this->uri->segment(2) == null ? 'selected' : '' ?>>Semua</option>
          <?php foreach (kategori() as $kategori): ?>
          <option value="<?=$kategori->id_kategori; ?>" <?=$kategori->slug_kategori == $this->uri->segment(2) ? 'selected' : '' ?>><?=$kategori->nama_kategori; ?></option>
          <?php endforeach; ?>
        </select>
      </section>
      <section style="width: 45%;">
        <form id="form_search" method="post">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari Produk..." name="cari" id="cari" style="border-color: #00b14f;">
            <div class="input-group-text" id="search_produk" onclick="search_produk()">
              <i class="fas fa-search text-white"></i>
            </div>
          </div>
        </form>
      </section>
    </div>
    <!-- end-LIST KATEGORI -->

    <!-- ------------------------------------------------------------------------- LIST KOPI -->
    <div class="listKopi" id="listkopi"></div>
    <!-- end-LIST KOPI -->
  </div>

  <!-- ------------------------------------------------------------------------- FOOTER -->
  <footer>
    &copy; <?=date('Y') ?> Hak Cipta Terpelihara Oleh Anu Store
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
      produk()
      total()

      $('#form_search').submit(false);

      /*------ WHEN CHOOSE CATEGORY PRODUCT (MOBILE) --------*/
      $('#inputGroupSelect01').change(function() {
        let id_kategori = $('#inputGroupSelect01').val();
        produk(id_kategori);
      });

      /*------ WHEN INPUT SEARCH SUBMIT --------*/
      $('#form_search').submit(function() {
        search_produk()
      });



      $(document).on('keyup', '#kuantitas', function() {
        let idproduk = $(this).attr('id-produk');
        let kuantitas = $(this).val();
        let harga = $(this).attr('harga');
        let subharga = harga * kuantitas;
        $("#harga_sementara"+idproduk).val(subharga);

        total();

      });

      $(document).on('keyup', '#bayar', function() {
        let bayar = $(this).val();
        let total_harga = parseInt($('[name="total_harga"]').val());
        $('#kembalian').val(bayar - total_harga);

      });

      $(document).on('click', '.btn_remove', function() {
        let button_id = $(this).attr("id");
        $('#row'+button_id+'').remove();

        total();

      });


      $('.kategori-dekstop').on('click', function() {
        let idProduk = $(this).data('idproduk');
        produk(idProduk);
        $('.kategori-dekstop').removeClass('active');
        $(this).addClass('active');
      })

      $('#listkopi > .item-listkopi').hide();


    });






    function add_to_cart(id, name, harga) {
      $('.list-produk-cart').append(`
        <tr id="row`+id+`"><input type="hidden" name="id[]" value="`+id+`">
        <td><input type="text" name="name[]" class="form-control" value="`+name+`" readonly></td>
        <td><input type="number" name="harga[]" id="harga" class="form-control" readonly value="`+harga+`"></td>
        <td><input type="number" harga="`+harga+`" id-produk="`+id+`" name="kuantitas[]" min="1" id="kuantitas" class="form-control" required></td>
        <td><input type="number" name="harga_sementara[]" id="harga_sementara`+id+`" class="form-control harga_sementara" value="0" readonly></td>
        <td>
        <button id="`+id+`" harga="`+harga+`"  class="btn btn-sm btn-danger btn_remove" name="remove"><i class="fa fa-trash"></i></button>
        </td>
        </tr>`);

      Toast.fire({
        icon: 'success',
        title: 'berhasil masuk ke keranjang'
      })

    }



    function total() {
      let sum = 0;
      let harga_total = parseInt($('[name="total_harga"]').val());
      $('#table_order > tbody  > tr').each(function() {
        let kuantitas = $(this).find('#kuantitas').val();
        let harga = $(this).find('.harga').val();
        let amount = parseInt($(this).find('.harga_sementara').val());
        sum += amount;
      });
      $('[name="total_harga"]').val(sum);
    }








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



    /*------ LIST PRODUCT --------*/
    function produk(id_kategori = null) {

      $.ajax({
        url: "<?=site_url('ajax/produk_vbarcode') ?>",
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
    function search_produk() {
      let search = $('[name="cari"]').val();
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



  </script>

</body>
</html>