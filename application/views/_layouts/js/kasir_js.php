<script src="<?=base_url('assets/function.js') ?>"></script>
<script>

  var save_method;
  var table;


  $(document).ready(function() {
    let i = 0;
    total();

    table = $('#table_produk').DataTable({

      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/kasir/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],
      "ordering": false

    });


    $(document).on('keyup', '#kuantitas', function() {
      let idproduk = $(this).attr('id-produk');
      let kuantitas = $(this).val();
      let harga = $(this).attr('harga');
      let subharga = harga * kuantitas;
      $("#harga_sementara"+idproduk).val(harga * kuantitas);

      total();

    });

    $(document).on('keyup', '#bayar', function() {
      let bayar = $(this).val();
      let total_harga = parseInt($('[name="total_harga"]').val());
      $('#kembalian').val(bayar - total_harga);

    });

    $(document).on('click', '.btn_remove', function() {
      let button_id = $(this).attr("id");
      $('#row'+button_id+'').remove();

      total();

    });

    $('#btnsubmit').on('click', function() {

      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Pesanan ini akan disubmit`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, tentu saja!'
      }).then((result) => {
        if (result.isConfirmed) {

          $('#form_kasir').submit();

        }
      })

    })

  });


  function reset() {
    $('#form_kasir')[0].reset();
    $('.list-produk').html('');
  }

  function total() {
    let sum = 0;
    let harga_total = parseInt($('[name="total_harga"]').val());
    $('#table_pembelian > tbody  > tr').each(function() {
      let kuantitas = $(this).find('#kuantitas').val();
      let harga = $(this).find('.harga').val();
      let amount = parseInt($(this).find('.harga_sementara').val());
      sum += amount;
    });
    $('[name="total_harga"]').val(sum);
  }


  function add() {
    $('#modal_form').modal('show');
  }

  function add_produk(id, name, harga) {
    $('.list-produk').append(`
      <tr  id="row`+id+`"><input type="hidden" name="id[]" value="`+id+`">
      <td><input type="text" name="name[]" class="form-control" value="`+name+`" readonly></td>
      <td><input type="number" name="harga[]" id="harga" class="form-control" readonly value="`+harga+`"></td>
      <td><input type="number" harga="`+harga+`" id-produk="`+id+`" name="kuantitas[]" min="1" id="kuantitas" class="form-control" required></td>
      <td><input type="number" name="harga_sementara[]" id="harga_sementara`+id+`" class="form-control harga_sementara" value="0" readonly></td>
      <td>
      <button id="`+id+`" harga="`+harga+`"  class="btn btn-sm btn-danger btn_remove" name="remove"><i class="fa fa-trash"></i></button>
      </td>
      </tr>`);

    $('#modal_form').modal('hide');
  }
</script>