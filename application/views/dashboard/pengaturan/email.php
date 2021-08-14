<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb'); ?>
    </div>
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?=$this->session->flashdata('success') ?>
      <?php unset($_SESSION['success']) ?>
    </div>
    <?php endif; ?>

    <div class="tab-pane fade" id="profile4" role="tabpanel" aria-labelledby="profile-tab4">
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="email_config" id="email_config" value="" />
              <div class="card-header">
                <h4><?=$title ?></h4>
              </div>
              <div class="card-body">
                <div class="form-group row">
                  <label for="smtp_host" class="col-sm-3 col-form-label">SMTP Host</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="smtp_host" required>
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="smtp_port" class="form-control-label col-sm-3">SMTP Port</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="smtp_port" required>
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="smtp_username" class="form-control-label col-sm-3">SMTP Username</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="smtp_username" required>
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="smtp_password" class="form-control-label col-sm-3">SMTP Password</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="smtp_password" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="smtp_encryption" class="form-control-label col-sm-3">SMTP Encryption</label>
                  <div class="col-sm-9">
                    <select id="smtp_encryption" class="form-control">
                      <option value="">No Encryption</option>
                      <option value="tls">TLS</option>
                      <option value="ssl">SSL</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="from_address" class="form-control-label col-sm-3">From addrress</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="from_address" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="from_name" class="form-control-label col-sm-3">From Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="from_name" required>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary" name="submit" id="save-btn">Submit</button>
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
  let setProv;
  let setKab;
  let setKec;
  $(document).ready(function() {
    $.get("<?=site_url('ajax/pengaturan') ?>", function(data) {
      $('#smtp_host').val(data.email_config.smtp_host);
      $('#smtp_port').val(data.email_config.smtp_port);
      $('#smtp_username').val(data.email_config.smtp_username);
      $('#smtp_password').val(data.email_config.smtp_password);
      $('#smtp_encryption').val(data.email_config.smtp_encryption);
      $('#from_address').val(data.email_config.from_address);
      $('#from_name').val(data.email_config.from_name);
    });


    $('#save-btn').click(function() {
      let hst = $('#smtp_host').val();
      let prt = $('#smtp_port').val();
      let usr = $('#smtp_username').val();
      let pass = $('#smtp_password').val();
      let enc = $('#smtp_encryption').val();
      let addr = $('#from_address').val();
      let nm = $('#from_name').val();
      const obj_email = {
        smtp_host: `${hst}`, smtp_port: `${prt}`, smtp_username: `${usr}`, smtp_password: `${pass}`, smtp_encryption: `${enc}`, from_address: `${addr}`, from_name: `${nm}`
      };
      const emailConfig = JSON.stringify(obj_email);
      $('#email_config').val(emailConfig);

    })


  });


</script>