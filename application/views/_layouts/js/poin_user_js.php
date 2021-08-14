<script src="<?=base_url('assets/function.js') ?>"></script>
<script>
  var save_method; //for save method string
  var table;

  $(document).ready(function() {
    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= site_url('dashboard/master/poin/get_datatables') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [-1],
        "orderable": false,
      },
      ],

    });
  });


  function edit(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').empty();

    //Ajax Load data from ajax
    $.ajax({
      url: "<?= site_url('dashboard/master/poin/ajax_edit/') ?>" + id,
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function(data) {

        $('[name="id_poin"]').val(data.id_poin);
        $('[name="poin"]').val(data.poin);

        $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
        $('.modal-title').text('Edit Poin User'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
      }
    });
  }

  function reload_table() {
    table.ajax.reload(null,
      false); //reload datatable ajax
  }

  function save() {
    let  url = "<?= site_url('dashboard/master/poin/ajax_update') ?>";

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

          Toast.fire({
            icon: 'success',
            title: "Poin User telah diupdate"
          })

        }


      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error adding / update data');

      }
    });
  }



</script>