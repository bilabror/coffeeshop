<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method;
  var table;
  $(document).ready(function() {


    $('.form-group #opsi').change(function() {
      let resi = `<div class="form-group group-resi">
      <label class="control-label">Nomor Resi</label>
      <input type="text" name="resi" id="resi" class="form-control" required/>
      </div>`;
      if ($(this).val() == 'kirim') {
        $('.group-opsi').after(resi);
      } else {
        $('.group-resi').hide();
      }
    });


    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/transaksi/online/get_datatables') ?>",
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


  function edit(id, status, noresi) {
    $('.group-resi').hide();
    if (status == 'pending') {
      let select = `<option value="" disabled selected>--- UBAH STATUS ---</option>
      <option value="terima">Terima Pesanan</option>
      <option value="tolak">Tolak Pesanan</option>`;
      $('[name="opsi"]').html(select);
    } else if (status == 'terima') {
      let select = `<option value=""  disabled selected>--- UBAH STATUS ---</option>
      <option value="kirim">Kirim pesanan</option>
      <option value="batal">batalkan Pesanan</option>`;
      $('[name="opsi"]').html(select);
    } else if (status == 'kirim') {
      let select = `<option value="kirim">dalam pengiriman</option>
      <option value="selesai">Pesanan Selesai</option>`;
      $('[name="opsi"]').html(select);
      let resi = `<div class="form-group group-resi">
      <label class="control-label">Nomor Resi</label>
      <input type="text" name="resi" id="resi" class="form-control" required value="`+noresi+`"/>
      </div>`;
      $('.group-opsi').after(resi);
    }
    $('#form')[0].reset(); // reset form on modals
    $('input.inputan').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('[name="id"]').val(id);

    $('#modal_form').modal('show');
  };


  function edit_resi(id, resi) {
    $('#form')[0].reset();
    $('[name="id"]').val(id);
    $('[name="resi"]').val(resi);

    $('#form_resi').modal('show');
  };

  function selesai(id, status, noresi) {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Akan menyalesaikan pesanan ${id}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?= site_url('dashboard/transaksi/online/ajax_update') ?>",
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
        });
      }
    })
  }

  function save() {

    $.ajax({
      url: "<?= site_url('dashboard/transaksi/online/ajax_update') ?>",
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      cache: false,
      success: function(data) {
        if (data.status == false) {
          if (!data.err.opsi == "") {
            $('[name="opsi"]').addClass('is-invalid');
            $('[in="opsi"]').html(data.err.opsi);
          } else {
            $('[name="opsi"]').removeClass('is-invalid');
            $('[in="opsi"]').html();
          }
        } else {
          $('#modal_form').modal('hide');
          reload_table();

          Toast.fire({
            icon: 'success',
            title: "Status Pesanan Telah diubah"
          })


        }
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

  function save_resi() {

    $.ajax({
      url: "<?= site_url('dashboard/transaksi/online/resi_update') ?>",
      type: "POST",
      data: $('#form_edit_resi').serialize(),
      dataType: "JSON",
      cache: false,
      success: function(data) {
        $('#form_resi').modal('hide');
        reload_table();

        Toast.fire({
          icon: 'success',
          title: "Nomor resi Telah diubah"
        })

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');
      }
    });
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
          url: "<?= site_url('dashboard/transaksi/online/ajax_trash') ?>",
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