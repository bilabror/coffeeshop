<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;
  $(document).ready(function() {

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/transaksi/offline/get_datatables') ?>",
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

  function reload_table() {
    table.ajax.reload(null, false);
  }


  function edit(id, status) {
    if (status == 'pending') {
      let select = `<option value="" disabled selected>--- UBAH STATUS ---</option>
      <option value="terima">Terima Pesanan</option>
      <option value="tolak">Tolak Pesanan</option>`;
      $('[name="opsi"]').html(select);
    } else if (status == 'terima') {
      let select = `<option value=""  disabled selected>--- UBAH STATUS ---</option>
      <option value="selesai">Selesai</option>`;
      $('[name="opsi"]').html(select);
    }
    $('#form')[0].reset(); // reset form on modals
    $('input.inputan').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('[name="id"]').val(id);

    $('#modal_form').modal('show');
  };

  function selesai(id, status) {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Akan menyalesaikan pesanan ${id}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?= site_url('dashboard/transaksi/offline/ajax_update') ?>",
          type: "POST",
          data: {
            id: id,
            opsi: 'selesai',
            catatan: 'Terimakasih Telah Berbelanja Dikami'
          },
          dataType: "JSON",
          cache: false,
          success: function(data) {
            reload_table();
            Swal.fire(
              'Selesai!',
              `Pesanan ${id} Telah Diselesaikan`,
              'success'
            )
          },
          error: function(jqxhr, textStatus, errorThrown) {
            console.log(jqxhr);
            console.log(textStatus);
            console.log(errorThrown);

            for (key in jqxhr)
              alert(key + ":" + jqxhr[key])
            for (key2 in textStatus)
              alert(key + ":" + textStatus[key])
            for (key3 in errorThrown)
              alert(key + ":" + errorThrown[key])

          }
        });
      }
    })
  }

  function delete_order(id) {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Akan menghapus pesanan ${id}!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "<?= site_url('dashboard/transaksi/offline/ajax_trash') ?>",
          type: "POST",
          data: {
            id: id
          },
          dataType: "JSON",
          cache: false,
          success: function(data) {
            reload_table();
            Swal.fire(
              'Deleted!',
              `Orderan ${id} Telah dihapus`,
              'success'
            )
          },
        });
      }
    })

  }





</script>