<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;


  $(document).ready(function() {

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/setting/slider/get_datatables') ?>",
        "type": "POST"
      },

      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],
      "ordering": false,

    });

    $("#gambar").change(function() {
      readURL(this);
    });

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





  function add() {
    save_method = 'add';
    $('#form')[0].reset();
    $('#blah').attr('src', "<?=base_url('assets/img/product_default.png'); ?>");
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Menambahkan Slider');
  }


  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();

    $.ajax({
      url: "<?= site_url('dashboard/setting/slider/ajax_edit/') ?>" + id,
      type: "GET",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        src = "<?=base_url('uploads/image/slider/'); ?>"+data.gambar;
        $('[name="id"]').val(data.id);
        $('[name="judul"]').val(data.judul);
        $('#blah').attr('src', src);

        $('#modal_form').modal('show');
        $('.modal-title').text('Edit Menu');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }


  function save() {
    var url;
    if (save_method == 'add') {
      url = "<?= site_url('dashboard/setting/slider/ajax_add') ?>";
    } else
    {
      url = "<?= site_url('dashboard/setting/slider/ajax_update') ?>";
    }

    $("#btnsave").prop("disabled", true);
    $('#btnsave').html('proses...');
    let form = $('#form')[0];
    let dataForm = new FormData(form);

    $.ajax({
      enctype: 'multipart/form-data',
      url: url,
      type: "post",
      data: dataForm,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        $('#btnsave').html('Tambahkan');
        $("#btnsave").prop("disabled", false);
        if (data.status == false) {
          if (!data.err.gambar == "") {
            Toast.fire({
              icon: 'error',
              title: `${data.err.gambar}`
            })
          }

          if (!data.err.judul == "") {
            $('[name="judul"]').addClass('is-invalid');
            $('[in="judul"]').html(data.err.judul);
          } else {
            $('[name="judul"]').removeClass('is-invalid');
            $('[in="judul"]').html();
          }
        } else {
          $('#modal_form').modal('hide');
          reload_table();
          if (save_method == 'add') {
            Toast.fire({
              icon: 'success',
              title: `Slide berhasil ditambahkan!`
            });
          } else
          {
            Toast.fire({
              icon: 'success',
              title: `Slide berhasil diupdate!`
            });
          }
        }

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }

    });

  }

  // MEMUAT ULANG TABEL DATATABLE
  function reload_table() {
    table.ajax.reload(null,
      false);
  }

  // MENGHAPUS DATA PRODUK
  function delete_slider(id) {
    Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Akan menghapus slide ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo site_url('dashboard/setting/slider/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: `Slide berhasil dihapus!`
            });
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });

      }
    })


  }


</script>