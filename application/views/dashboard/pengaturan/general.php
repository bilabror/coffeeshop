<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb'); ?>
    </div>
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?= $this->session->flashdata('success') ?>
      <?php unset($_SESSION['success']) ?>
    </div>
    <?php endif; ?>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="logo_toko_lama" id="logo_toko_lama" value="" />
              <div class="card-header">
                <h4><?=$title ?></h4>
              </div>
              <div class="card-body">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Nama Toko</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" required name="nama_toko" id="nama_toko">
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Email Toko</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" required name="email_toko" id="email_toko">
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">WhatsApp Toko</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" required name="wa_toko" id="wa_toko">
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Logo Toko</label>
                  <div class="col-sm-6">
                    <input type="file" class="form-control" name="logo_toko" id="logo_toko">
                  </div>
                  <div class="col-sm-3">
                    <a id="view_logo_toko">Lihat Logo</a>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary" name="submit">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('_layouts/js'); ?>

<script>
  $(document).ready(function() {
    $.get("<?=site_url('ajax/pengaturan') ?>", function(data) {
      src = "<?=site_url('uploads/image/') ?>"+data.logo_toko;
      $('#nama_toko').val(data.nama_toko);
      $('#wa_toko').val(data.wa_toko);
      $('#email_toko').val(data.email_toko);
      $('#logo_toko_lama').val(data.logo_toko);
      $('#view_logo_toko').attr('href', src);
    });




  });


</script>