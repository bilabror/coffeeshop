<?php $this->load->view('_layouts/datatables_js.php'); ?>
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
              <button type="button" class="add btn btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i></button>
            </div>
            <div class="card-body">
              <d9v class="table-responsive">

                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Kategori</th>
                      <th>Opsi</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </d9v>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>



<?php $this->load->view('_layouts/js'); ?>
<?php $this->load->view('_layouts/js/kategori_js'); ?>



<div class="modal fade" tabindex="-1" role="dialog" id="modal_form">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="id_kategori" />
          <input name="slug_kategori" placeholder="" class="form-control" type="hidden">
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-2">Nama</label>
              <input name="nama_kategori" placeholder="" class="form-control" type="text" onkeyup="createTextSlug()">
              <div class="invalid-feedback" in="nama_kategori"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">SIMPAN</button>
      </div>
    </div>
  </div>
</div>