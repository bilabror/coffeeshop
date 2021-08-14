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
              <h4 class="col-md-6">LAPORAN PRODUK TERJUAL</h4>
              <h5 class="col-md-6 text-md-right tanggal"></h5>
            </div>
            <div class="card-body">
              <input type="hidden" name="awal">
              <input type="hidden" name="akhir">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label class="d-block">Atur Tanggal</label>
                    <a href="javascript:;" class="btn btn-primary daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> Fiter Data
                    </a>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="text-md-right">
                    <button type="button" class="btn btn-danger btn-icon icon-left pdf" data-url="<?= site_url('dashboard/laporan/produk_terjual/pdf_produk_terjual'); ?>"><i class="fas fa-print"></i> Print PDF</button>
                    <!--
                                                            <button type="button" class="btn btn-success btn-icon icon-left excel" data-url="<?= site_url('dashboard/laporan/produk_terjual/excel_produk_terjual'); ?>"><i class="fas fa-print"></i> Export Excel</button>
                                                            <button type="button" class="btn btn-info btn-icon icon-left"><i class="fas fa-print"></i> View Grafik</button>
                                                            -->
                  </div>
                </div>
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
        '7 Hari yang lalu': [moment().subtract(6, 'days'), moment()],
        '30 Hari yang lalu': [moment().subtract(29, 'days'), moment()],
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
    let urlfull = url + '?awal='+awal+'&akhir='+akhir;

    window.location.href = urlfull;

  });

  $(document).on('click', '.excel', function() {
    let awal = $('[name="awal"]').val();
    let akhir = $('[name="akhir"]').val();
    let url = $(this).data('url');
    let urlfull = url + '?awal='+awal+'&akhir='+akhir;

    window.location.href = urlfull;

  });


</script>