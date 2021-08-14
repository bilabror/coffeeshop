<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;


  $(document).ready(function() {

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/produk/utama/get_datatables') ?>",
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

  // MENUJU HALAMAN DETAIL PRODUK
  function detail(slug) {
    window.location.href = "<?=site_url('dashboard/produk/utama/detail/') ?>"+slug;

  }

  // MENUJU HALAMAN EDIT PRODUK
  function edit(id) {
    window.location.href = "<?=site_url('dashboard/produk/utama/edit/') ?>"+id;
  }

  // MEMUAT ULANG TABEL DATATABLE
  function reload_table() {
    table.ajax.reload(null,
      false);
  }

  // MENGHAPUS DATA PRODUK
  function delete_produk(id) {

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Akan menghapus produk ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?php echo site_url('dashboard/produk/utama/ajax_delete/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            reload_table();
            swal.close();
            Toast.fire({
              icon: 'success',
              title: `Produk berhasil dihapus!`
            })
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });


      }
    })


  }


  // MEMBUAT SLUG PRODUK
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