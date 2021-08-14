<!-- ------------------------------------------------------------------------- MAIN -->
<div class="container" style="margin-top: 150px">
  <h4 class="fw-bold">HISTORY</h4>
  <div class="row">
    <?php foreach ($booking as $row): ?>
    <?php
    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'item_pesanan.id_produk = produk.id_produk');
    $this->db->where(['id_pesanan' => $row['id_pesanan']]);
    $this->db->group_by('item_pesanan.id_pesanan');
    $produk = $this->db->get()->row_array();
    ?>
    <div class="col-lg-6 mt-3">
      <div class="card border">
        <div class="row">
          <div class="col-sm-3 align-items-center d-flex">
            <img
            src="<?=base_url('uploads/image/produk/'.$produk['gambar_produk']) ?>"
            alt=""
            class="ms-2 img-thumbnail"
            style="width: 100%"
            />
          </div>
          <div class="col-sm-9">
            <div class="card-body">
              <h5 class="card-title fw-bold mb-0"><a href="<?=site_url('riwayat/booking/detail/'.$row["id_pesanan"]) ?>">#<?=$row['id_pesanan'] ?></a></h5>
              <div
                class="d-flex justify-content-between align-items-center"
                >
                <span class="card-subtitle text-muted d-inline mt-2">
                  <span class="text-muted text-uppercase">Perkiraan datang</span>
                </span>
                <span class="card-subtitle mt-2">
                  <?=date('Y-m-d H:i', strtotime($row['prk_datang'])) ?>
                </span>
              </div>
              <div class="mt-2 card-text pb-3">
                <div>
                  <br />
                  <small class="text-muted fw-bold">
                    Meja :
                    <span class="fw-bold badge bg-success"><?= $row['no_meja'] ?></span>
                  </small>
                </div>
                <div class="me-2">
                  <span class="text-muted text-uppercase">TOTAL :</span>
                  <span class="fw-bold"><?=rupiah($row['total_bayar']) ?></span>
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
                  <p>
                    <strong>STATUS :</strong>
                    <?php if ($row['status'] == 'new'): ?>
                    <span class="badge bg-primary">
                      <i class="fas fa-info-circle"></i> BELUM BAYAR
                    </span>
                    <?php elseif ($row['status'] == 'pending'): ?>
                    <span class="badge bg-warning">
                      <i class="fas fa-info-circle"></i> PENDING
                    </span>
                    <?php elseif ($row['status'] == 'terima'): ?>
                    <span class="badge bg-success">
                      <i class="fas fa-check-circle"></i> MENUNGGU DATANG
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
                </div>
                <div class="w-100 text-end">

                  <?php if ($row['status'] == 'new'): ?>
                  <a
                    class="btn btn-sm text-white mb-3 text-uppercase batal-pesanan"
                    style="background-color: #00b14f"
                    onclick="batalPesanan(<?=$row['id_pesanan'] ?>,<?=$row['id_booking'] ?>)"
                    >
                    <i class="fas fa-times"></i>
                  </a>
                  <a
                    href="<?=site_url("pay/{$row['id_pesanan']}") ?>"
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
                    onclick="trashPesanan(<?=$row['id_pesanan'] ?>)"
                    >
                    <i class="fas fa-trash"></i>
                  </a>
                  <?php endif; ?>
                  <a
                    href="<?=site_url('riwayat/booking/detail/'.$row['id_pesanan']) ?>"
                    class="btn btn-sm text-white mb-3 text-uppercase"
                    style="background-color: #00b14f"
                    >
                    <i class="fas fa-eye"></i>
                  </a>
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

  function trashPesanan(idPesanan) {
    let url = "<?=site_url('riwayat/trash_pesanan') ?>";

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `Akan menghapus pesanan ${idPesanan}`,
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
            id_pesanan: idPesanan
          },
          dataType: 'json',
          cache: false,
          success: function(data) {
            if (data.status == true) {

              Toast.fire({
                icon: 'success',
                title: "Riwayat Pesanan telah dihapus"
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


  function batalPesanan(idPesanan, idBooking) {
    let url = "<?=site_url('riwayat/batal_pesanan') ?>";

    Swal.fire({
      title: 'Apakah anda yakin?',
      text: `Akan membatalkan pesanan ${idPesanan}`,
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
            id_pesanan: idPesanan,
            id_booking: idBooking
          },
          dataType: 'json',
          cache: false,
          success: function(data) {
            if (data.status == true) {

              Toast.fire({
                icon: 'success',
                title: "Pesanan telah dibatalkan"
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