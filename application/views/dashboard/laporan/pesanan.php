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
              <h4 class="col-md-6">LAPORAN PESANAN</h4>
              <h5 class="col-md-6 text-md-right tanggal"></h5>
            </div>
            <div class="card-body">
              <input type="hidden" name="awal">
              <input type="hidden" name="akhir">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <a href="javascript:;" class="btn btn-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> tanggal
                    </a>
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <select name="opsi_beli" id="opsi_beli" class="form-control">
                      <option value="">Semua</option>
                      <option value="0">Offline</option>
                      <option value="1">Online</option>
                      <option value="2">Booking</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="text-md-right">
                    <button type="button" class="btn btn-primary btn-icon icon-left get-report" data-url="<?= site_url('dashboard/laporan/pesanan/ajax_get_report'); ?>"><i class="fas fa-print"></i> Get Data</button>
                    <button type="button" class="btn btn-danger btn-icon icon-left pdf" data-url="<?= site_url('dashboard/laporan/pesanan/pdf_pesanan'); ?>"><i class="fas fa-print"></i> Print PDF</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="col-md-6">GENERATE LAPORAN PESANAN</h4>
              <h5 class="col-md-6 text-md-right total-penghasilan"></h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th align="center">No</th>
                      <th>Invoice</th>
                      <th>Customer</th>
                      <th>Phone</th>
                      <th>Opsi Beli</th>
                      <th>Total Bayar</th>
                      <th>Tanggal</th>
                    </tr>
                  </thead>
                  <tbody id="table-get-report"></tbody>
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

<script src="<?= base_url('stisla/'); ?>assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url('stisla/'); ?>assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>

<script>
  $(document).ready(function() {

    $('.daterange-cus').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      },
      drops: 'down',
      opens: 'right'
    });
    $('.daterange-btn').daterangepicker({
      ranges: {
        'Hari Ini': [moment(), moment()],
        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
        '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
        'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate: moment()
    }, function (start, end) {
      $('[name="awal"]').val(start.format('YYYY-MM-DD'));
      $('[name="akhir"]').val(end.format('YYYY-MM-DD'));
      $('.tanggal').html(start.format('YYYY-MM-DD') + ' sampai '+end.format('YYYY-MM-DD'));
    });
  });


  $(document).on('click', '.pdf', function() {
    let awal = $('[name="awal"]').val();
    let akhir = $('[name="akhir"]').val();
    let url = $(this).data('url');
    let opsi_beli = $('[name=opsi_beli]').val();
    console.log(opsi_beli);
    if (opsi_beli == '') {
      window.location.href = `${url}?awal=${awal}&akhir=${akhir}`;
    } else {
      window.location.href = `${url}?awal=${awal}&akhir=${akhir}&opsibeli=${opsi_beli}`;
    }

  });

  $(document).on('click', '.excel', function() {
    let awal = $('[name="awal"]').val();
    let akhir = $('[name="akhir"]').val();
    let url = $(this).data('url');
    let urlfull = url + '?awal='+awal+'&akhir='+akhir;

    window.location.href = urlfull;

  });

  $(document).on('click', '.chart-pesanan', function() {
    let awal = $('[name="awal"]').val();
    let akhir = $('[name="akhir"]').val();
    let url = $(this).data('url');
    let urlfull = url + '?awal='+awal+'&akhir='+akhir;

    window.location.href = urlfull;

  });

  $(document).on('click', '.get-report', function() {
    let awal = $('[name="awal"]').val();
    let akhir = $('[name="akhir"]').val();
    let opsi_beli = $('[name=opsi_beli]').val();
    let url = $(this).data('url');
    $.ajax({
      url: url,
      method: 'get',
      cache: false,
      data: {
        awal: awal,
        akhir: akhir,
        opsi_beli: opsi_beli
      },
      dataType: 'JSON',
      success: function(data) {
        $('#table-get-report').html(data.table);
        $('.total-penghasilan').html(data.penghasilan);
      },
      error: function(jqxhr, textStatus, errorThrown) {
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);

        for (key in jqxhr)
          alert(key + ":" + jqxhr[key])
        for (key2 in textStatus)
          alert(key + ":" + textStatus[key])
        for (key3 in errorThrown)
          alert(key + ":" + errorThrown[key])
      }
    });

  });





</script>