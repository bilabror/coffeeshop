<script src="<?=base_url('assets/function.js') ?>"></script>
<script>

  $(document).ready(function() {

    edit("<?=$id_produk ?>");

    $.ajax({
      url: "<?=site_url('dashboard/produk/utama/kategori_ajax') ?>",
      type: 'post',
      dataType: 'json',
      cache: false,
      success: function(data) {
        let kategori;
        let id_kategori = "<?=$id_kategori ?>";
        console.log(id_kategori);
        $.each(data, function(k, v) {
          kategori += '<option value="'+v.id_kategori+'">'+v.nama_kategori+'</option>';
        });
        $('.pilih-kategori').after(kategori);
        $('[name="id_kategori"]').val(id_kategori);
      }
    });
  });


  function createTextSlug() {
    var nama_produk = $('[name="nama_produk"]').val();
    $('[name="slug_produk"]').val(generateSlug(nama_produk));
  }
  function generateSlug(text) {
    return text.toString().toLowerCase()
    .replace(/^-+/,
      '')
    .replace(/-+$/,
      '')
    .replace(/\s+/g,
      '-')
    .replace(/\-\-+/g,
      '-')
    .replace(/[^\w\-]+/g,
      '');
  }


  function discount() {
    let price = $('[name="harga_produk"]').val();
    let discount = $('[name="diskon_produk"]').val();
    let process = price / 100 * discount;
    let total = price - process;
    $('[name="total_harga_produk"]').val(total);
  }


  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#blah').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#gambar_produk").change(function() {
    readURL(this);
  });


  function edit(id) {
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();

    $.ajax({
      url: "<?= site_url('dashboard/produk/utama/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        console.log(data);
        $('[name="slug_produk"]').val(data.slug_produk);
        $('[name="id_produk"]').val(data.id_produk);
        $('[name="nama_produk"]').val(data.nama_produk);
        $('[name="id_kategori"]').val(data.id_kategori);
        $('[name="stok_produk"]').val(data.stok_produk);
        $('[name="berat_produk"]').val(data.berat_produk);
        $('[name="harga_produk"]').val(data.harga_produk);
        $('[name="diskon_produk"]').val(data.diskon_produk);
        $('[name="total_harga_produk"]').val(data.total_harga_produk);
        $('[name="deskripsi_produk"]').val(data.deskripsi_produk);

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }


  function update() {
    $("#btnsave").prop("disabled", true);
    $('#btnsave').html('proses...');
    let form = $('#form')[0];
    let dataForm = new FormData(form);

    let nama_produk = $('[name="nama_produk"]').val();
    let id_kategori = $('[name="id_kategori"]').val();
    let stok_produk = $('[name="stok_produk"]').val();
    let berat_produk = $('[name="berat_produk"]').val();
    let harga_produk = $('[name="harga_produk"]').val();
    let diskon_produk = $('[name="diskon_produk"]').val();
    let total_harga_produk = $('[name="total_harga_produk"]').val();
    let deskripsi_produk = $('[name="deskripsi_produk"]').val();

    $.ajax({
      enctype: 'multipart/form-data',
      url: "<?= site_url('dashboard/produk/utama/update'); ?>",
      type: "post",
      data: dataForm,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        console.log(data);
        $('#btnsave').html('Tambahkan');
        $("#btnsave").prop("disabled", false);
        if (data.status == false) {
          if (!data.err.gambar_produk == "") {
            iziToast.error({
              title: 'Upload belum Selesai!!',
              message: data.err.gambar_produk,
              position: 'topRight'
            });
          }

          $.each(data.err, function(key, value) {
            if (value == "") {
              $(`[name="${key}"]`).removeClass('is-invalid');
              $(`[in="${key}"]`).html();
            } else {
              $(`[name="${key}"]`).addClass('is-invalid');
              $(`[in="${key}"]`).html(value);
            }
          });
        } else
        {
          $('.form-control').removeClass('is-invalid');
          $('.invalid-feedback').empty();

          window.location.href = "<?=site_url('dashboard/produk/utama') ?>";
        }

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }

    });

  }

</script>