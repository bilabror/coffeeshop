<script src="<?=base_url('assets/function.js') ?>"></script>
<script>

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
        console.log(data);
        let resi = data.data.summary.awb;
        let kurir = data.data.summary.courier;
        $('.kurir-resi').html(`${kurir} : ${resi}`);
        let history = data.data.history;

        let content = '';
        $.each(history, function(key, val) {
          content += `<div class="activity">
          <div class="activity-icon bg-primary text-white shadow-primary">
          <i class="fas fa-truck"></i>
          </div>
          <div class="activity-detail">
          <div class="mb-2">
          <span class="text-job text-primary">${val.date}</span>
          <span class="bullet"></span>
          </div>
          <p>
          ${val.desc}
          </p>
          </div>
          </div>`;
        });
        $('.activities').html(content);

      }
    });
  });


</script>