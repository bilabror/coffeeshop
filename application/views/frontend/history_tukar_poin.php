<!-- ------------------------------------------------------------------------- MAIN -->
<div class="container" style="margin-top: 150px">
  <h4 class="fw-bold">HISTORY</h4>
  <div class="row">
    <?php foreach ($tukar_poin as $row): ?>
    <div class="col-lg-6 mt-3">
      <div class="card border">
        <div class="row">
          <div class="col-sm-3 align-items-center d-flex">
            <img
            src="<?=base_url('uploads/image/produk_poin/'.$row['gambar_produk']) ?>"
            alt=""
            class="ms-2 img-thumbnail"
            style="width: 100%"
            />
          </div>
          <div class="col-sm-9">
            <div class="card-body">
              <h5 class="card-title fw-bold mb-0"><a href="<?=site_url('pesanan-saya/'.$row["id"]) ?>">#<?=$row['id'] ?></a></h5>
              <div
                class="d-flex justify-content-between align-items-center"
                >
                <span class="card-subtitle text-muted d-inline">
                  <?=$row['nama_produk'] ?>
                </span>
                <span class="card-subtitle">
                  <p>
                    <?php if ($row['status'] == 'pending'): ?>
                    <span class="badge bg-warning">
                      <i class="fas fa-info-circle"></i> PENDING
                    </span>
                    <?php elseif ($row['status'] == 'tolak'): ?>
                    <span class="badge bg-danger">
                      <i class="fas fa-times-circle"></i> DITOLAK
                    </span>
                    <?php elseif ($row['status'] == 'selesai'): ?>
                    <span class="badge bg-success">
                      <i class="fas fa-check-circle"></i> SELESAI
                    </span>
                    <?php endif; ?>
                  </p>
                </span>
              </div>
              <div class="mt-2 card-text pb-3">
                <div>
                  <h6 class="fw-bold d-inline" style="color: #13b79d">
                    POIN  -<?=$row['harga_produk'] ?>
                  </h6>
                  <br />
                </div>
              </div>
              <div
                class="
                d-flex
                justify-content-between
                align-items-center
                mt-3
                "
                >
                <div class="w-100">

                  <span class="card-subtitle text-muted d-inline">
                    <?=$row['tgl_buat'] ?>
                  </span>
                </div>
                <div class="w-100 text-end">

                  <?php if ($row['status'] == 'new'): ?>
                  <a
                    class="btn btn-sm text-white mb-3 text-uppercase batal-pesanan"
                    style="background-color: #00b14f"
                    >
                    <i class="fas fa-times"></i>
                  </a>
                  <a
                    href=""
                    class="btn btn-sm text-white mb-3 text-uppercase"
                    style="background-color: #00b14f"
                    >
                    <i class="fas fa-credit-card"></i>
                  </a>
                  <?php elseif ($row['status'] == 'selesai' || $row['status'] == 'tolak'): ?>
                  <a
                    href="javascript:void(0)"
                    class="btn btn-sm text-white mb-3 text-uppercase"
                    style="background-color: #00b14f"
                    onclick="trashTukarPoin(<?=$row['id'] ?>)"
                    >
                    <i class="fas fa-trash"></i>
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<!-- end-MAIN -->

<script>

  function trashTukarPoin(id) {
    let url = "<?=site_url('riwayat/trash_tukar_poin') ?>";

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `Akan menghapus penukaran poin dengan id : ${id}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tentu saja!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          url: url,
          method: 'post',
          data: {
            id: id
          },
          dataType: 'json',
          cache: false,
          success: function(data) {
            if (data.status == true) {

              Toast.fire({
                icon: 'success',
                title: "Riwayat penukaran poin telah dihapus"
              })
              setTimeout(function() {
                location.reload();
              }, 2000);
            }
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
        })
      }

    })

  }

</script>