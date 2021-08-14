<!-- ------------------------------------------------------------------------- MAIN -->
<div
  class="container bg-white p-4 border border-success rounded"
  style="margin-top: 100px"
  >
  <h4 class="fw-bold mt-3">TUKAR POINT</h4>
  <div class="row">
    <div class="col-md-4">
      <table class="table table-striped">
        <tr>
          <th>ID User</th>
          <td><i class="fas fa-id-card me-2"></i> <?= sud('id_user') ?></td>
        </tr>
        <tr>
          <th>Username</th>
          <td><i class="fas fa-id-card me-2"></i> <?= sud('username') ?></td>
        </tr>
        <tr>
          <th>Point Saya</th>
          <td><i class="fas fa-coins me-2"></i> <?=$poin ?></td>
        </tr>
      </table>
    </div>
    <div class="col-md-7">
      <small>
        <strong>BAGAIMANA CARA DAPAT POINT?</strong>
        <span class="text-muted">
          Membeli produk kami akan memberikan anda point minimal 100 Point.
          Point bisa ditukarkan dengan produk pilihan kami, Jika pointnya
          sudah mencukupi anda bisa beli secara GRATIS.
        </span>
      </small>
    </div>
    <!-- -------------------------------------------------------------- LIST-TRADE -->
    <div class="col-md-12 mt-5 pb-3">
      <span>
        <h5 class="d-inline me-2 fw-bold">PILIH PRODUK</h5>
        <smal class="text-muted">
          Tukarkan point anda dengan hadiah menarik
        </smal>
      </span>
      <div class="row mt-3">
        <?php foreach ($produk as $row): ?>
        <div class="col-lg-6">
          <div class="card mb-3" style="max-width: 540px">
            <div class="row">
              <div class="col-4">
                <img
                src="<?=base_url('uploads/image/produk_poin/'.$row->gambar_produk) ?>"
                class="img-fluid rounded-start"
                style="min-height: 100%; min-width: 100%"
                alt="..."
                />
              </div>
              <div class="col-8 d-flex align-items-center">
                <div class="card-body">
                  <h5 class="card-title fw-bold mt-2"><?=$row->nama_produk ?></h5>
                  <p class="my-0">
                    <span class="fw-bold text-muted">Tukar Dengan </span>
                    <span class="badge" style="background-color: #00b14f">
                      <i class="fas fa-coins"></i>
                      <span><?=$row->harga_produk ?></span>
                    </span>
                  </p>

                  <?php if ($row->harga_produk > $poin): ?>
                  <a
                    href="javascript:void(0)"
                    class="
                    btn btn-sm
                    mt-4
                    text-white
                    btn-secondary
                    w-50
                    mb-3
                    "
                    >
                    Tukarkan
                  </a>
                  <?php else : ?>
                  <a
                    href="javascript:void(0)"
                    class="
                    btn btn-sm
                    mt-4
                    text-white
                    btn-primary
                    w-50
                    mb-3
                    "
                    onclick="tukar(<?=$row->id ?>)"
                    >
                    Tukarkan
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- end-LIST-TRADE -->
  </div>
</div>
<!-- end-MAIN -->

<script type="text/javascript" charset="utf-8">
  function tukar(id) {

    Swal.fire({
      title: 'Kamu yakin?',
      text: "Poin akan dikurangi saat penukaran poin",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!'
    }).then((result) => {
      if (result.isConfirmed) {
        let url = "<?=site_url('poin/tukar_poin') ?>";
        $.ajax({
          url: url,
          type: "POST",
          dataType: "JSON",
          data: {
            id: id
          },
          cache: false,
          success: function(data) {
            Toast.fire({
              icon: 'success',
              title: "Poin berhasil ditukarkan"
            })
            setTimeout(function() {
              window.location.href = '<?=site_url('riwayat/poin') ?>'
            }, 2000);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
          }
        });
      }
    })



  }
</script>