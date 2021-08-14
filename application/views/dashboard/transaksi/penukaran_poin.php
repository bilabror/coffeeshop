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
              <p class="card-title">
                Request Penukaran Poin
              </p>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" id="table-penukaran-poin">
                  <thead>
                    <tr>
                      <th>ID Penukaran</th>
                      <th>Customer</th>
                      <th>Penukaran</th>
                      <th>Tgl Penukaran</th>
                      <th>Status</th>
                      <th>Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tukar_poin as $row): ?>
                    <tr>
                      <td><?=$row['id'] ?></td>
                      <td><?=$row['username'] ?></td>
                      <td><?=$row['nama_produk'] ?></td>
                      <td><?=time_ago($row['tgl_buat']) ?></td>
                      <td><?=$row['status'] ?></td>
                      <td>
                        <div class="dropdown">
                          <a href="#" data-toggle="dropdown" class="btn btn-sm btn-primary dropdown-toggle">Options</a>
                          <div class="dropdown-menu">
                            <?php if ($row['status'] == 'pending'): ?>
                            <a href="javascript:void(0)" onclick="selesai(<?=$row['id'] ?>)" class="dropdown-item has-icon"><i class="fas fa-check"></i> Selesai</a>
                            <?php else : ?>
                            <a href="javascript:void(0)" onclick="trash(<?=$row['id'] ?>)" class="dropdown-item has-icon text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                            <?php endif; ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
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
<?php $this->load->view('_layouts/js/penukaran_poin_js'); ?>