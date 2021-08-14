<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;
  $(document).ready(function() {
    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/sistem/menu/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],
      "ordering": false,
    });
  });


  function add() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Menambahkan Menu');
  }

  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();

    $.ajax({
      url: "<?= site_url('dashboard/sistem/menu/ajax_edit/') ?>" + id,
      type: "GET",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        $('[name="id"]').val(data.id);
        $('[name="menu"]').val(data.menu);
        $('[name="title"]').val(data.title);
        $('[name="icon"]').val(data.icon);
        $('[name="tipe"]').val(data.tipe);

        $('#modal_form').modal('show');
        $('.modal-title').text('Edit Menu');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }

  function reload_table() {
    table.ajax.reload(null, false);
  }

  function save() {
    var url;
    if (save_method == 'add') {
      url = "<?= site_url('dashboard/sistem/menu/ajax_add') ?>";
    } else
    {
      url = "<?= site_url('dashboard/sistem/menu/ajax_update') ?>";
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
              title: "Menu berhasil ditambahkan"
            })
          } else
          {
            Toast.fire({
              icon: 'success',
              title: "Menu berhasil diupdate"
            })
          }
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }
    });
  }


  function delete_menu(id, menu) {

    Swal.fire({
      title: 'Apakah Kamu yakin?',
      text: "Akan menghapus menu ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo site_url('dashboard/sistem/menu/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: "Menu berhasil dihapus"
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });
      }
    })


  }


  function status(id) {
    $.ajax({
      url: "<?= site_url('dashboard/sistem/menu/status'); ?>",
      type: "post",
      cache: false,
      data: {
        id: id
      },
      success: function() {
        Toast.fire({
          icon: 'success',
          title: "Status menu telah diubah"
        })
      },
      error: function (jqXHR, textStatus, errorThrown) {}

    });
  }



  function naikan(id, urutan) {
    $.ajax({
      url: '<?=site_url("dashboard/sistem/menu/naikan") ?>',
      type: 'post',
      data: {
        id: id,
        urutan: urutan
      },
      dataType: 'JSON',
      cache: false,
      success: function(data) {

        if (data.status == true) {
          reload_table();
          setTimeout(function() {
            location.reload()
          }, 100)
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }
    });
  }

  function turunkan(id, urutan) {
    $.ajax({
      url: '<?=site_url("dashboard/sistem/menu/turunkan") ?>',
      type: 'post',
      data: {
        id: id,
        urutan: urutan
      },
      dataType: 'JSON',
      cache: false,
      success: function(data) {
        if (data.status == true) {
          reload_table();
          setTimeout(function() {
            location.reload()
          }, 100)
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }
    });
  }


</script>