<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  let save_method;
  let table;
  $(document).ready(function() {
    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/sistem/role/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],
      "ordering": false,
    });

    $("#form").submit(function(e) {
      e.preventDefault();
      save();
    });

  });



  function add() {
    save_method = 'add';
    $('#form')[0].reset();
    $('input.inputan').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Tambahkan Role');
    console.log(save_method);
  }


  function menu_access(id, role) {
    $.ajax({
      url: "<?= site_url('dashboard/sistem/role/menu_access/') ?>"+id,
      method: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        console.log(data);
        $('#modal_menu').modal('show');
        $('#table-access-menu').html(data);
        $('.modal-title').text('Hak Akses '+role);
      }
    });
  }


  function submenu_access(id, role) {
    $.ajax({
      url: "<?= site_url('dashboard/sistem/role/submenu_access/') ?>"+id,
      method: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        $('#modal_submenu').modal('show');
        $('#table-access-submenu').html(data);
        $('.modal-title').text('Hak Akses '+role);
      }
    });
  }




  function changeAccessMenu(menuId, roleId) {

    $.ajax({
      url: "<?= site_url('dashboard/sistem/role/change_menu_access'); ?>",
      type: "post",
      data: {
        menuId: menuId,
        roleId: roleId
      },
      cache: false,
      success: function() {
        Toast.fire({
          icon: 'success',
          title: "Akses menu telah diubah"
        })
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }

    });
  }

  function changeAccessSubmenu(submenuId, roleId) {

    $.ajax({
      url: "<?= site_url('dashboard/sistem/role/change_submenu_access'); ?>",
      type: "post",
      data: {
        submenuId: submenuId,
        roleId: roleId
      },
      cache: false,
      success: function() {
        Toast.fire({
          icon: 'success',
          title: "Akses submenu telah diubah"
        })
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }

    });
  }


  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('input.inputan').removeClass('is-invalid');
    $('.invalid-feedback').empty();

    $.ajax({
      url: "<?= site_url('dashboard/sistem/role/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {
        console.log(data);

        $('[name="id"]').val(data.id);
        $('[name="role"]').val(data.role);

        $('#modal_form').modal('show');
        $('.modal-title').text('Edit Role');

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
    let url;
    if (save_method == 'add') {
      url = "<?= site_url('dashboard/sistem/role/ajax_add') ?>";
    } else
    {
      url = "<?= site_url('dashboard/sistem/role/ajax_update') ?>";
    }

    $.ajax({
      url: url,
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      cache: false,
      success: function(data) {
        console.log(data.status);
        if (data.status == false) {
          $('input.inputan').addClass('is-invalid');
          $('.invalid-feedback').html(data.errors);
          console.log(data.errors);
        } else {
          let namaRole = $('[name="role"]').val();
          $('#modal_form').modal('hide');
          reload_table();
          if (save_method == 'add') {
            Toast.fire({
              icon: 'success',
              title: "Role berhasil ditambahkan"
            })
          } else
          {
            Toast.fire({
              icon: 'success',
              title: "Role berhasil diupdate"
            })
          }

        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');

      }
    });
  }

  function delete_role(id) {

    Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Akan menghapus role ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        // ajax delete data to database
        $.ajax({
          url: "<?php echo site_url('dashboard/sistem/role/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: "Role berhasil dihapus"
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });
      }
    })


  }

  function akses(id) {
    document.location.href = "<?=site_url('dashboard/sistem/role/akses/') ?>"+id;
  }

</script>