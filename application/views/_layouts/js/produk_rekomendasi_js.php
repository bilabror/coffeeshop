<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  let table;
  let table_produk;


  $(document).ready(function() {

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/produk/rekomendasi/get_datatables') ?>",
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



  function get_produk() {
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambahkan Produk Rekomendasi'); // Set Title to Bootstrap modal title
    table_produk = $('#table_produk').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/produk/rekomendasi/get_produk') ?>",
        "type": "POST"
      },

      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],
      "ordering": false,

    });
  }

  function add_produk(id_produk) {
    $.ajax({
      url: "<?= site_url('dashboard/produk/rekomendasi/insert'); ?>",
      type: "post",
      data: {
        id_produk: id_produk
      },
      dataType: "json",
      cache: false,
      success: function(data) {
        $('#modal_form').modal('hide');
        reload_table();
        if (data.status == false) {

          Toast.fire({
            icon: 'error',
            title: `${data.error}`
          });
        } else {
          Toast.fire({
            icon: 'success',
            title: `menambahkan produk rekomendasi`
          });
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }

    });
  }

  // MENUJU HALAMAN DETAIL PRODUK
  function detail(slug) {
    window.location.href = "<?=site_url('dashboard/produk/utama/detail/') ?>"+slug;

  }

  // MEMUAT ULANG TABEL DATATABLE
  function reload_table() {
    table.ajax.reload(null,
      false);
  }

  // MENGHAPUS DATA PRODUK
  function delete_produk(id, namaProduk) {

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `Akan menhapus produk ${namaProduk} dari rekomendasi!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo site_url('dashboard/produk/rekomendasi/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            reload_table();
            Toast.fire({
              icon: 'success',
              title: "Produk Dihapus dari rekomendasi"
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