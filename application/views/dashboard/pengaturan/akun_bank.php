<?php $this->load->view('_layouts/datatables_js'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb'); ?>
    </div>

    <div class="section-body">

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <button type="button" class="add_payment btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Rekening</th>
                      <th>Nomor Rekening</th>
                      <th>Atas Nama</th>
                      <th>opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; foreach ($rekening_toko as $row): ?>
                    <tr>
                      <td><?=$i++ ?></td>
                      <td><?=$row['bank'] ?></td>
                      <td><?=$row['norek'] ?></td>
                      <td><?=$row['atas_nama'] ?></td>
                      <td>
                        <a href="javascript:void(0)" class="edit_payment" data-bank="<?=$row['bank'] ?>" data-id="<?=$row['id'] ?>" data-norek="<?=$row['norek'] ?>" data-atasnama="<?=$row['atas_nama'] ?>"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="delete_rekening(<?=$row['id'] ?>)"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>



<?php $this->load->view('_layouts/js'); ?>
<script src="<?=base_url('assets/function.js') ?>"></script>


<script>

  $(document).ready(function() {
    $('#table').DataTable({});
  });


  $(document).on('click', '.add_payment', function() {
    $('#form')[0].reset();
    $('input').val('');
    $('#modal_payment').modal('show');
    $('.modal-title').text('Tambah Nomor Rekening Toko');
    $('#form').attr('action', '<?=site_url("dashboard/setting/bank_account/add_payment") ?>');
  });

  $(document).on('click', '.edit_payment', function() {
    $('#form')[0].reset();
    $('input').val('');
    $('#modal_payment').modal('show');
    $('.modal-title').text('Edit Nomor Rekening Toko');
    let id = $(this).data('id');
    let bank = $(this).data('bank');
    let norek = $(this).data('norek');
    let atasnama = $(this).data('atasnama');
    $('[name="id"]').val(id);
    $('[name="bank"]').val(bank);
    $('[name="norek"]').val(norek);
    $('[name="atas_nama"]').val(atasnama);
    $('#form').attr('action', '<?=site_url("dashboard/setting/bank_account/edit_payment") ?>');
  });


  function delete_rekening(id) {

    Swal.fire({
      title: 'Apakah Kamu yakin?',
      text: "Akan menghapus rekening ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: "<?=site_url('dashboard/setting/bank_account/delete_payment/') ?>"+id,
          type: "POST",
          dataType: "JSON",
          cache: false,
          success: function(data) {
            $('#modal_form').modal('hide');
            swal.close();
            Toast.fire({
              icon: 'success',
              title: "Rekening toko berhasil dihapus"
            });
            setTimeout(function() {
              location.reload()
            }, 2000)
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });
      }
    })


  }



</script>


<div class="modal fade" tabindex="-1" role="dialog" id="modal_payment">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" method="post" class="form-horizontal needs-validation" novalidate="" action="<?=site_url('dashboard/setting/bank_account/add_payment'); ?>">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group">
              <label class="control-label">Nama Rekening</label>
              <input name="bank" id="bank" class="form-control" type="text" required>
              <div class="invalid-feedback">
                Tidak boleh kosong
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Nomor Rekening</label>
              <input name="norek" id="norek" class="form-control" type="text" required>
              <div class="invalid-feedback">
                Tidak boleh kosong
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Atas Nama</label>
              <input name="atas_nama" id="atas_nama" class="form-control" type="text" required>
              <div class="invalid-feedback">
                Tidak boleh kosong
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btnSave">SIMPAN</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>