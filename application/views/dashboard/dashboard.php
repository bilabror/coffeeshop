<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb') ?>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-box"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>produk</h4>
              </div>
              <div class="card-body">
                <?=$produk ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="far fa-user"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Pelanggan</h4>
              </div>
              <div class="card-body">
                <?=$pelanggan ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="far fa-user"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Sold Today</h4>
              </div>
              <div class="card-body">
                <?=$terjual ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-cart-arrow-down"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Pesanan Baru</h4>
              </div>
              <div class="card-body">
                <?=$pesanan ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Income pending</h4>
              </div>
              <div class="card-body">
                <?=rupiah($income_pending) ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Income Today</h4>
              </div>
              <div class="card-body">
                <?=rupiah($income) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-6">
          <div class="card">
            <div class="card-header">
              <h4>Pesanan Baru</h4>
            </div>
            <div class="card-body">
              <table class="table table-hover table-sm">
                <thead>
                  <tr>
                    <th>Customer</th>
                    <th>Pembelian</th>
                    <th>bayar</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($order as $row): ?>
                  <?php
                  $item = $this->db->get_where('item_pesanan', ['id_pesanan' => $row['id_pesanan']])->num_rows();
                  ?>
                  <tr>
                    <?php if ($row['opsi_beli'] == 0): ?>
                    <td><a href="<?=site_url("dashboard/transaksi/offline/detail/{$row['id_pesanan']}") ?>"><?="#{$row['id_pesanan']}"; ?></a></td>
                    <?php elseif ($row['opsi_beli'] == 1): ?>
                    <td><a href="<?=site_url("dashboard/transaksi/online/detail/{$row['id_pesanan']}") ?>"><?="#{$row['id_pesanan']}"; ?></a></td>
                    <?php elseif ($row['opsi_beli'] == 2): ?>
                    <td><a href="<?=site_url("dashboard/transaksi/booking/detail/{$row['id_pesanan']}") ?>"><?="#{$row['id_pesanan']}"; ?></a></td>
                    <?php else : ?>
                    <?php endif; ?>

                    <td><?="{$item} Produk" ?></td>
                    <td><?=rupiah($row['total_bayar']) ?></td>
                    <td><?=time_ago(date("Y-m-d H:i:s", strtotime($row['tgl_bayar_pesanan']))) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
          <div class="card">
            <div class="card-header">
              <h4>Produk Terjual</h4>
            </div>
            <div class="card-body">
              <canvas id="myChart"></canvas>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>


<?php $this->load->view('_layouts/js.php'); ?>
<script src="<?= base_url('stisla/'); ?>assets/modules/chart.min.js"></script>
<script src="<?= base_url('stisla/'); ?>assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
  $(document).ready(function() {
    /*
    $('.daterange-cus').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      },
      drops: 'down',
      opens: 'right'
    });
    $('.daterange-btn').daterangepicker({
      ranges: {
        '7 Hari yang lalu': [moment().subtract(6, 'days'), moment()],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
        'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
      },
      startDate: moment().startOf('month'),
      endDate: moment().endOf('month')
    }, function (start, end) {
      $('[name="awal"]').val(start.format('YYYY-MM-DD'));
      $('[name="akhir"]').val(end.format('YYYY-MM-DD'));
      $('.tanggal').html(start.format('YYYY-MM-DD') + ' sampai '+end.format('YYYY-MM-DD'));
    });
    */

  });



  let ctx = $("#myChart").html('2d');
  let myChart = new Chart(ctx, {
    type: 'line',
    data: {
      //labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
      labels: [
        <?php
        foreach ($labels as $row) {
          echo "'".date('d', $row)."',";
        }
        ?>
      ],
      datasets: [{
        label: 'Statistics',
        data: [<?php foreach ($chart_sold as $row) {
          echo $row.', ';
        } ?>],
        borderWidth: 2,
        backgroundColor: '#6777ef',
        borderColor: '#6777ef',
        borderWidth: 2.5,
        pointBackgroundColor: '#ffffff',
        pointRadius: 2
      }]
    },
    options: {
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            drawBorder: false,
            color: '#f2f2f2',
          },
        }],
        xAxes: [{
          ticks: {
            display: false
          },
          gridLines: {
            display: false
          }
        }]
      },
    }
  });


</script>