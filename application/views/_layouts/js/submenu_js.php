<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method; //for save method string
  var table;
  $(document).ready(function() {

    $('#datamenu').on('change', function() {
      var menu = $("#datamenu option:selected").attr('datamenu').toLowerCase();
      var urlval = $('[name="url"]').val();
      if (save_method == 'add') {
        $('[name="url"]').val(menu+'/');
      } else {
        var url = urlval.split('/');
        url[0] = menu;
        $('[name="url"]').val(url.join('/'));

      }
    });

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo site_url('dashboard/sistem/submenu/get_datatables') ?>",
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


  function status(id) {
    $.ajax({
      url: "<?= site_url('dashboard/sistem/submenu/status'); ?>",
      type: "post",
      cache: false,
      data: {
        id: id
      },
      success: function() {
        Toast.fire({
          icon: 'success',
          title: "Status submenu berhasil diupdate"
        })
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }

    });
  }


  function add() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Tambahkan submenu');
    console.log(save_method);
  }

  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    console.log(id);
    //Ajax Load data from ajax
    $.ajax({
      url: "<?= site_url('dashboard/sistem/submenu/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {

        $('[name="id"]').val(data.id);
        $('[name="menu_id"]').val(data.menu_id);
        $('[name="title"]').val(data.title);
        $('[name="icon"]').val(data.icon);
        $('[name="url"]').val(data.url);

        $('#modal_form').modal('show');
        $('.modal-title').text('Edit submenu');

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
      url = "<?= site_url('dashboard/sistem/submenu/ajax_add') ?>";
    } else
    {
      url = "<?= site_url('dashboard/sistem/submenu/ajax_update') ?>";
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
              title: "Submenu berhasil ditambahkan"
            })
          } else
          {
            Toast.fire({
              icon: 'success',
              title: "Submenu berhasil diupdate"
            })
          }

        }

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');

      }
    });
  }

  function delete_submenu(id) {

    Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Akan menghapus submenu ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {
        // ajax delete data to database
        $.ajax({
          url: "<?php echo site_url('dashboard/sistem/submenu/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            //if success reload ajax table
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: "Submenu berhasil dihapus"
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });

      }
    })


  }



</script>