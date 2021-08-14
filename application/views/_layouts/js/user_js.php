<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method; //for save method string
  var table;

  $(document).ready(function() {
    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/master/user/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],

    });
  });


  function status(id, status) {
    $.ajax({
      url: "<?= site_url('dashboard/master/user/status/'); ?>"+id,
      type: "post",
      data: {
        id: id,
        status: status
      },
      cache: false,
      success: function() {
        reload_table();
        if (status == 0) {
          Toast.fire({
            icon: 'success',
            title: "user telah diaktifkan"
          })
        } else
        {
          Toast.fire({
            icon: 'success',
            title: "user telah diblokir"
          })
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }


  function add() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambahkan User');
    $('.password').html(` <label class="control-label col-md-2">password</label>
      <input name="password" placeholder="password" class="form-control" type="text" value="123">
      <div class="invalid-feedback" in="password"></div>`);
  }

  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('.password').html('<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="reset_password('+id+')">Reset Password ke 12345678</a>');

    $.ajax({
      url: "<?= site_url('dashboard/master/user/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {

        $('[name="id"]').val(data.id);
        $('[name="username"]').val(data.username);
        $('[name="email"]').val(data.email);
        $('[name="avatar"]').val(data.avatar);
        $('[name="role_id"]').val(data.role_id);

        $('#modal_form').modal('show');
        $('.modal-title').text('Edit User');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }

  function reset_password(id) {
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Akan mereset password ke 12345678!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?= site_url('dashboard/master/user/reset_password/') ?>",
          type: "POST",
          data: {
            id: id
          },
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: "Password berhasil Direset"
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });


      }
    })
  }

  function reload_table() {
    table.ajax.reload(null,
      false);
  }

  function save() {
    console.log(save_method);
    var url;
    if (save_method == 'add') {
      url = "<?= site_url('dashboard/master/user/ajax_add') ?>";
    } else
    {
      url = "<?= site_url('dashboard/master/user/ajax_update') ?>";
    }

    // ajax adding data to database
    $.ajax({
      url: url,
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      cache: false,
      success: function(data) {
        //if success close modal and reload ajax table

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
              title: "User berhasil ditambahkan"
            })
          } else
          {
            Toast.fire({
              icon: 'success',
              title: "User berhasil diupdate"
            })
          }

        }




      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');

      }
    });
  }

  function delete_user(id) {

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Akan menghapus akun user ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        // ajax delete data to database
        $.ajax({
          url: "<?php echo site_url('dashboard/master/user/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: "User berhasil dihapus"
            });
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });


      }
    })



  }

  function detail_user(id) {
    window.location.href = "<?=site_url('dashboard/master/user/detail/') ?>"+id;

  }


</script>