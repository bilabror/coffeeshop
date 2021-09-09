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
              <a href="javascript:void(0)" onclick="get_produk()" class="add btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>
              </a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th width="15%">Gambar</th>
                      <th>Nama</th>
                      <th width="10%">stok</th>
                      <th width="10%">Opsi</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
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
<?php $this->load->view('_layouts/js/produk_rekomendasi_js'); ?>




<div class="modal fade" tabindex="-1" role="dialog" id="modal_form">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table" id="table_produk" width="100%">
            <thead>
              <tr>
                <th width="15%">Gambar</th>
                <th>Nama Produk</th>
                <th width="10%">Stok</th>
                <th width="10%">Opsi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>