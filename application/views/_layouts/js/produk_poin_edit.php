<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  $(document).ready(function() {
    edit("<?=$id ?>");
  });


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
      url: "<?= site_url('dashboard/produk/poin/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        console.log(data);
        $('[name="id"]').val(data.id);
        $('[name="nama_produk"]').val(data.nama_produk);
        $('[name="berat_produk"]').val(data.berat_produk);
        $('[name="harga_produk"]').val(data.harga_produk);
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


    $.ajax({
      enctype: 'multipart/form-data',
      url: "<?= site_url('dashboard/produk/poin/ajax_update'); ?>",
      type: "post",
      data: dataForm,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        console.log(data);
        $('#btnsave').html('Simpan Perubahan');
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

          window.location.href = "<?=site_url('dashboard/produk/poin') ?>";
        }

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }

    });

  }



</script>