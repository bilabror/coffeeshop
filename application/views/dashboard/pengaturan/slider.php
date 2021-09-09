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
              <button type="button" class="add_payment btn btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i></button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th width="10%">Gambar</th>
                      <th>Judul</th>
                      <th width="15%">opsi</th>
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
<?php $this->load->view('_layouts/js/slider_js'); ?>


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

        <form action="#" id="form" class="form-horizontal ">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group none">
              <label class="control-label">Judul</label>
              <input name="judul" placeholder="judul" class="form-control inputan" type="text" required>
              <div class="invalid-feedback" in="judul"></div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Foto Produk</label>
              <div class="col-sm-10">
                <label for="gambar" style="position:absolute;">
                  <i class="fa fa-edit btn-sm btn-primary input-hero-image"><input type="file" class="d-none" id="gambar" name="gambar"></i></label>
                <img width="40%" id="blah" class="img-thumbnail" src="<?=base_url('assets/img/product_default.png'); ?>" alt="hero image">
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