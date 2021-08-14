<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb') ?>
    </div>
    <div class="section-body">

      <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
                Detail User
              </h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <img src="<?=base_url('uploads/image/profile/'.$user->avatar); ?>" alt="gambar<?=$user->username ?>" class="img-thumbnail" />
                  <table border="0" class="table table-sm mt-2">
                    <tr>
                      <td>ID User</td>
                      <td>:</td>
                      <th><?=$user->id ?></th>
                    </tr>
                    <tr>
                      <td>Username</td>
                      <td>:</td>
                      <th><?=$user->username ?></th>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td>:</td>
                      <th><?=$user->email ?></th>
                    </tr>
                    <tr>
                      <td>Phone</td>
                      <td>:</td>
                      <th><?=$user->phone ?></th>
                    </tr>
                    <tr>
                      <td>Poin</td>
                      <td>:</td>
                      <th><?=$poin ?></th>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-cart-arrow-down"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Total transaksi</h4>
              </div>
              <div class="card-body">
                <?=$transaksi ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="far fa-star"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Total Riview</h4>
              </div>
              <div class="card-body">
                <?=$review ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<?php $this->load->view('_layouts/js.php'); ?>