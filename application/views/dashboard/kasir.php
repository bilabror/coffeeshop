<?php $this->load->view('_layouts/datatables_js.php'); ?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb') ?>
    </div>
    <div class="section-body">
      <form action="<?=site_url('dashboard/kasir/add_order') ?>" method="post" id="form_kasir">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button type="button" class="add btn btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i></button>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover table-sm" id="table_pembelian">
                    <thead>
                      <tr>
                        <th>Nama Produk</th>
                        <th width="25%">Harga satuan</th>
                        <th width="10%">kuantitas</th>
                        <th width="25%">Sub harga</th>
                        <th>opsi</th>
                      </tr>
                    </thead>
                    <tbody class="list-produk"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <table class="table table-sm">
                  <tr>
                    <th>TOTAL HARGA</th>
                    <td></td>
                    <td><input type="number" name="total_harga" class="form-control" readonly></td>
                  </tr>
                  <tr>
                    <th>MEMBAYAR</th>
                    <td></td>
                    <td><input type="number" name="bayar" id="bayar" class="form-control" required></td>
                  </tr>
                  <tr>
                    <th>KEMBALIAN</th>
                    <td></td>
                    <td><input type="number" name="kembalian" id="kembalian" class="form-control" readonly></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="card">
              <div class="card-body">
                <button class="btn btn-danger" onclick="reset()">RESET</button>
                <button class="btn btn-primary" id="btnsubmit" type="button">SIMPAN</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>


<?php $this->load->view('_layouts/js.php'); ?>
<?php $this->load->view('_layouts/js/kasir_js'); ?>





<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_form">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-hover table-sm" id="table_produk" width="100%">
          <thead>
            <tr align="center">
              <th>Gambar</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th>stok</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>