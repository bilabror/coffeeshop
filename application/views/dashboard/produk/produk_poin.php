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
              <a href="<?=site_url('dashboard/produk/poin/add') ?>" class="add btn btn-primary btn-sm"></button><i class="fa fa-plus"></i></a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-sm" id="table">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="10%">gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>tgl dibuat</th>
                    <th width="10%">Status</th>
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
<?php $this->load->view('_layouts/js/produk_poin_js'); ?>