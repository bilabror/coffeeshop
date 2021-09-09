<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;

  $(document).ready(function() {

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/produk/poin/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],

    });
  });

  function reload_table() {
    table.ajax.reload(null,
      false);
  }

  function edit(id) {
    window.location.href = "<?=site_url('dashboard/produk/poin/edit/') ?>"+id;
  }



  function status(id, status) {
    $.ajax({
      url: "<?= site_url('dashboard/produk/poin/status/'); ?>",
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
            title: "produk poin diaktifkan"
          })
        } else
        {
          Toast.fire({
            icon: 'success',
            title: "produk poin dinonatifkan"
          })
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }




  function delete_produk(id) {

    Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Akan menghapus produk ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo site_url('dashboard/produk/poin/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close(); Toast.fire({
              icon: 'success',
              title: `produk berhasil dihapus`
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });

      }
    })


  }


  function detail_produk(id) {
    window.location.href = "<?=site_url('dashboard/produk/poin/detail/') ?>"+id;
  }

</script>


<?php if($this->session->flashdata('success')): ?>
  <script>
    Toast.fire({
      icon: 'success',
      title: "produk poin berhasil <?=$this->session->flashdata('success')?>"
    });
  </script>
<?php unset($_SESSION['success']); endif;?>
