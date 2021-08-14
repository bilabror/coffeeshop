<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  $(document).ready(function () {
    $('#table-penukaran-poin').DataTable();
  });

  function selesai(id, status, noresi) {

    Swal.fire({
      title: 'Apakah Anda Yakin?',
      text: "Akan menyelesaikan penukaran poin ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?= site_url('dashboard/transaksi/poin/selesai') ?>",
          type: "POST",
          data: {
            id: id,
            status: 'selesai'
          },
          dataType: "JSON",
          cache: false,
          success: function(data) {
            Toast.fire({
              icon: 'success',
              title: `Penukaran poin ${id} telah diselesaikan`
            })
            setTimeout(function() {
              location.reload();
            }, 2000);
          },
        });

      }
    })


  }

  function trash(id) {

    Swal.fire({
      title: 'Apakah Anda Yakin?',
      text: "Akan menghapus riwayat penukaran poin ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?= site_url('dashboard/transaksi/poin/hapus_tukar_poin') ?>",
          type: "POST",
          data: {
            id: id
          },
          dataType: "JSON",
          cache: false,
          success: function(data) {
            Toast.fire({
              icon: 'success',
              title: `Penukaran poin ${id} telah dihapus`
            })
            setTimeout(function() {
              location.reload();
            }, 2000);
          },
          error: function() {
            console.log('error');
          }
        });


      }
    })


  }

</script>