<?php $this->load->view('_layouts/datatables_js.php'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb') ?>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <button type="button" class="add btn btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i></button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>menu</th>
                      <th>title</th>
                      <th>icon</th>
                      <th>tipe</th>
                      <th>status</th>
                      <th>opsi</th>
                    </tr>
                  </thead>
                  <tbody>

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



<?php $this->load->view('_layouts/js.php'); ?>


<?php $this->load->view('_layouts/js/menu_js.php'); ?>




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
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group">
              <label class="control-label">menu</label>
              <input name="menu" placeholder="menu" class="form-control inputan" type="text">
              <div class="invalid-feedback" in="menu"></div>
            </div>
            <div class="form-group none">
              <label class="control-label">title</label>
              <input name="title" placeholder="title" class="form-control inputan" type="text">
              <div class="invalid-feedback" in="title"></div>
            </div>
            <div class="form-group none">
              <label class="control-label col-md-2">icon</label>
              <input name="icon" placeholder="icon" class="form-control inputan" type="text">
              <div class="invalid-feedback" in="icon"></div>
            </div>
            <div class="form-group">
              <label for="tipemenu" class="control-label col-md-2">Tipe</label>
              <select name="tipe" class="form-control tipemenu" id="tipemenu">
                <option value="">--- PILIH TIPE ---</option>
                <option value="1">Biasa</option>
                <option value="2">Dropdown</option>
              </select>
              <div class="invalid-feedback" in="tipe"></div>
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