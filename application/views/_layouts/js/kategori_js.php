<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;


  $(document).ready(function() {

    $("#modal_form").submit(function(e) {
      e.preventDefault();
      save();
    });

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/produk/kategori/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],
      "ordering": false
    });
  });


  function add() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Tambahkan Kategori');
    console.log(save_method);
  }

  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();

    $.ajax({
      url: "<?= site_url('dashboard/produk/kategori/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {

        $('[name="id_kategori"]').val(data.id_kategori);
        $('[name="nama_kategori"]').val(data.nama_kategori);
        $('[name="slug_kategori"]').val(data.slug_kategori);

        $('#modal_form').modal('show');
        $('.modal-title').text('Edit Kategori');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }

  function reload_table() {
    table.ajax.reload(null,false);
  }

  function save() {
    console.log(save_method);
    var url;
    if (save_method == 'add') {
      url = "<?= site_url('dashboard/produk/kategori/ajax_add') ?>";
    } else
    {
      url = "<?= site_url('dashboard/produk/kategori/ajax_update') ?>";
    }

    $.ajax({
      url: url,
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
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
          $('#modal_form').modal('hide');
          reload_table();
          if (save_method == 'add') {
            Toast.fire({
              icon: 'success',
              title: `Kategori produk ditambahkan!`
            })
          } else
          {
            Toast.fire({
              icon: 'success',
              title: `Kategori produk diedit!`
            })
          }

        }

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }
    });
  }

  function delete_kategori(id) {

    Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Akan menghapus kategori ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo site_url('dashboard/produk/kategori/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: `Kategori produk dihapus!`
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });
      }
    })

  }



  function createTextSlug() {
    var nama_kategori = $('[name="nama_kategori"]').val();
    $('[name="slug_kategori"]').val(generateSlug(nama_kategori));
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


</script>