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
              <button type="button" class="add btn btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i></button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">menu Group</th>
                      <th scope="col">Title</th>
                      <th scope="col">Icon</th>
                      <th scope="col">Url</th>
                      <th scope="col">Status</th>
                      <th scope="col">Opsi</th>
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



<?php $this->load->view('_layouts/js'); ?>
<?php $this->load->view('_layouts/js/submenu_js'); ?>




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
              <label class="control-label col-md-2">menu</label>
              <select class="form-control" name="menu_id" id="datamenu">
                <option value="">--- menu ---</option>
                <?php foreach ($menu as $m) : ?>
                <option value="<?= $m['id']; ?>" datamenu="<?= $m['menu']; ?>" id="menu"><?= $m['menu']; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback" in="menu_id"></div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">title</label>
              <input name="title" placeholder="title" class="form-control" type="text">
              <div class="invalid-feedback" in="title"></div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">icon</label>
              <input name="icon" placeholder="icon" class="form-control" type="text">
              <div class="invalid-feedback" in="icon"></div>
            </div>
            <div class="form-group">
              <label for="basic-url" class="control-label col-md-2">Url</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3"><?= site_url(); ?></span>
                </div>
                <input type="text" name="url" placeholder="url" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                <div class="invalid-feedback" in="url"></div>
              </div>
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