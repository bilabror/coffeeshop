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
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="alamat_toko" id="alamat_toko" value="" />
              <div class="card-header">
                <h4><?=$title ?></h4>
              </div>
              <div class="card-body">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Provinsi</label>
                  <div class="col-sm-9">
                    <select name="prov" id="prov" class="form-control"></select>
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Kabupaten</label>
                  <div class="col-sm-9">
                    <select name="kab" id="kab" class="form-control"></select>
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Kecamatan</label>
                  <div class="col-sm-9">
                    <select name="kec" id="kec" class="form-control"></select>
                    <div class="invalid-feedback">
                      tidak boleh kosong
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Detail Alamat</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" required="" name="detail_alamat" id="detail_alamat"></textarea>
                    <div class="invalid-feedback">
                      tidak boleh Kosong
                    </div>
                  </div>
                </div>
                <div class="form-group mb-0 row">
                  <label class="col-sm-3 col-form-label">Embed Map</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" required="" name="embed_map_toko" id="embed_map_toko"></textarea>
                    <div class="invalid-feedback">
                      Tidak Boleh Kosong
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary" id="save-btn" name="submit">Submit</button>
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
      src = "<?=site_url('uploads/image/') ?>"+data.logo_toko;
      $('#detail_alamat').val(data.alamat_toko.detail_alamat);
      $('#embed_map_toko').val(data.embed_map_toko);
      getKab(data.alamat_toko.prov);
      getKec(data.alamat_toko.kab);
      setProv = data.alamat_toko.prov;
      setKab = data.alamat_toko.kab;
      setKec = data.alamat_toko.kec;
    });

    $.get("<?=site_url('ajax/provinsi') ?>", function(data) {
      let option = `<option value="">--- PROVINSI ---</option>`;
      $.each(data, function(key, value) {
        if (value.id == setProv) {
          option += `<option value="${value.id}" data-nama="${value.nama}" selected>${value.nama}</option>`;
        } else {
          option += `<option value="${value.id}" data-nama="${value.nama}">${value.nama}</option>`;
        }
      });
      $('#prov').html(option);
    });



    $('#prov').change(function() {
      let id = $(this).val();
      getKab(id);
    });

    $('#kab').change(function() {
      let id = $(this).val();
      getKec(id);
    });

    $('#save-btn').click(function() {
      let prov = $('#prov').val();
      let kab = $('#kab').val();
      let kec = $('#kec').val();
      let detail_alamat = $('#detail_alamat').val();
      const obj_alamat = {
        prov: `${prov}`, kab: `${kab}`, kec: `${kec}`, detail_alamat: `${detail_alamat}`
      };
      const alamat = JSON.stringify(obj_alamat);
      $('#alamat_toko').val(alamat);

    })


  });


  function getKab(id) {
    $.ajax({
      url: "<?=site_url('ajax/kabupaten/') ?>"+id,
      type: 'get',
      success: function(data) {
        let option = `<option value="">--- KABUPATEN ---</option>`;
        $.each(data, function(key,
          value) {
          if (value.id == setKab) {
            option += `<option value="${value.id}" data-nama="${value.nama}" selected>${value.tipe} ${value.nama}</option>`;
          } else {
            option += `<option value="${value.id}" data-nama="${value.nama}">${value.tipe} ${value.nama}</option>`;
          }
        });
        $('#kab').html(option);
      }
    });
  }

  function getKec(id) {
    $.ajax({
      url: "<?=site_url('ajax/kecamatan/') ?>"+id,
      type: 'get',
      success: function(data) {
        console.log(1);
        let option = `<option value="">--- KECAMATAN ---</option>`;
        $.each(data,
          function(key,
            value) {
            if (value.id == setKec) {
              option += `<option value="${value.id}" data-nama="${value.nama}" selected>${value.nama}</option>`;
            } else {
              option += `<option value="${value.id}" data-nama="${value.nama}">${value.nama}</option>`;
            }
          });
        $('#kec').html(option);
      }
    });
  }

</script>