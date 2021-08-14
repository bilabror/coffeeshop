<script src="<?=base_url('assets/function.js') ?>"></script>
<script>

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



  function save() {
    $("#btnsave").prop("disabled", true);
    $('#btnsave').html('proses...');
    let form = $('#form')[0];
    let dataForm = new FormData(form);

    let nama_produk = $('[name="nama_produk"]').val();
    let harga_produk = $('[name="harga_produk"]').val();
    let berat_produk = $('[name="berat_produk"]').val();
    let deskripsi_produk = $('[name="deskripsi_produk"]').val();

    $.ajax({
      enctype: 'multipart/form-data',
      url: "<?= site_url('dashboard/produk/poin/ajax_add'); ?>",
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
            Toast.fire({
              icon: 'error',
              title: `${data.err.gambar_produk}`
            })
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
          $('#form')[0].reset();
          $("#gambar_produk").val();
          $('#blah').attr('src', "<?=base_url('assets/img/avatar.png'); ?>");


          window.location.href = "<?=site_url('dashboard/produk/poin') ?>";

        }

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }

    });

  }

</script>