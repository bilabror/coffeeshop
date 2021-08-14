<!-- ------------------------------------------------------------------------- MAIN -->
<div
  class="container bg-white p-3 border border-success rounded"
  style="margin-top: 150px"
  >
  <div class="row mt-4">
    <div class="col-md-5">
      <div class="bg-light p-3 rounded border text-center">
        <img
        src="https://s-ecom.ottenstatic.com/thumbnail/5eddc89125c24525432961.jpg"
        alt=""
        />
        <h6 class="my-0 mt-3 tgl-terima-pesanan">

        </h6>
        <small class="mt-1 text-muted kurir-pesanan">
        </small>
        <p class="mt-1 text-muted status-pesanan">
          <span class="badge bg-warning">
            <i class="fas fa-box me-1"></i>
            Paket Sedang Dikemas
          </span>
        </p>
      </div>
    </div>
    <div class="col-md-7 mt-4">
      <h4 class="fw-bold mt-3">LACAK PESANAN</h4>
      <div class="list-group histories">

      </div>
    </div>
  </div>
</div>
<!-- end-MAIN -->

<script type="text/javascript" charset="utf-8">

  $(document).ready(function() {
    let resi = "<?=$pesanan['resi'] ?>";
    let kurir = "<?=$pesanan['kurir'] ?>";
    $.ajax({
      url: "<?=site_url('ajax/tracking_resi') ?>",
      method: 'post',
      data: {
        resi: resi,
        kurir: kurir
      },
      dataType: 'json',
      success: function(data) {
        let resi = data.data.summary.awb;
        let kurir = data.data.summary.courier;
        let status = data.data.summary.status;
        let service = data.data.summary.service;
        $('.kurir-resi').html(`${kurir} : ${resi}`);
        let history = data.data.history;
        $('.kurir-pesanan').html(`Dikirim dengan <strong>${service} - ${kurir}</strong>`);

        if (status == 'DELIVERED') {
          $('.status-pesanan').html(`<span class="badge bg-success">
            <i class="fas fa-box-open me-1"></i>
            Paket Telah Diterima
            </span>`);
          $('.tgl-terima-pesanan').html(`Diterima Pada <strong style="color: #00b14f">${history[0].date}</strong>`);
        } else if (status == 'ON PROCESS') {
          $('.status-pesanan').html(`<span class="badge bg-primary">
            <i class="fas fa-truck me-1"></i>
            Paket Dalam Perjalanan
            </span>`);
        }

        let content = '';
        $.each(history, function(key, val) {
          content += `<div
          class="
          list-group-item
          track
          ps-4
          border-success
          text-success
          py-3
          "
          >
          <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">${val.desc}</h5>
          <small>${val.date}</small>
          </div>
          <div
          class="rounded-circle bg-success position-absolute top-50"
          style="
          width: 15px;
          height: 15px;
          transform: translateY(-50%);
          left: -7px;
          "
          ></div>
          </div>`;
        });
        $('.histories').html(content);

      }
    });
  });

</script>