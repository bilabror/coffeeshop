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
              <div class="text-right">
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table">
                  <thead>
                    <tr>
                      <th>NO PESANAN</th>
                      <th>COSTUMER</th>
                      <th>JUMLAH HARGA</th>
                      <th>STATUS</th>
                      <th>TGL PESANAN</th>
                      <th>OPSI</th>
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
<?php $this->load->view('_layouts/js/pesanan_offline_js'); ?>




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
        <form id="form" action="#" class="form-horizontal">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group group-opsi">
              <label class="control-label">Opsi</label>
              <select name="opsi" id="opsi" class="form-control" required>
              </select>
              <div class="invalid-feedback" in="opsi"></div>
            </div>
            <div class="form-group">
              <label class="control-label">Catatan</label>
              <textarea name="catatan" id="catatan" cols="30" rows="10" class="form-control"></textarea>
              <div class="invalid-feedback" in="catatan"></div>
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

<div class="modal fade" tabindex="-1" role="dialog" id="form_resi">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_edit_resi" action="#" class="form-horizontal">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group">
              <label class="control-label">Resi</label>
              <input type="text" name="resi" id="resi" value="" class="form-control" required>
              <div class="invalid-feedback" in="resi"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="save_resi()">SIMPAN</button>

      </div>
    </div>
  </div>
</div>