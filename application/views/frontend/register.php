<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css"
  rel="stylesheet"
  integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x"
  crossorigin="anonymous"
  />

  <!-- Font Awesome -->
  <link
  rel="stylesheet"
  href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
  integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
  crossorigin="anonymous"
  />

  <!-- myCSS -->
  <link rel="stylesheet" href="<?=base_url('assets/frontend2/') ?>css/style.css" />
  <link rel="stylesheet" href="<?=base_url('assets/frontend2/') ?>css/responsive.css" />

  <title>Daftar</title>
</head>
<body class="bg-light">
  <!-- ------------------------------------------------------------------------- MAIN -->
  <div class="container position-absolute top-50 start-50 my-5 formLR">
    <div class="mb-4 text-center">
      <h2 class="fw-bold" style="color: #00b14f">
        <i class="fas fa-mug-hot logo text-white"></i>
        <?=getset('nama_toko') ?>
      </h2>
      <h6 class="text-muted" style="color: #00b14f">
        Tempat Nongkrong sambil Ngopi
      </h6>
    </div>
    <div class="row border rowForm">
      <div class="bg-white p-4">
        <h4 class="fw-bold mb-4">DAFTAR</h4>
        <form action="" id="form">
          <div class="mb-3">
            <div class="form-floating">
              <input
              type="text"
              class="form-control"
              id="username"
              placeholder="username"
              name="username"
              />
              <div class="invalid-feedback" in="username"></div>
              <label for="username">Username</label>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <div class="form-floating">
                  <input
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="email"
                  name="email"
                  />
                  <div class="invalid-feedback" in="email"></div>
                  <label for="email">Email</label>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <div class="form-floating">
                  <input
                  type="number"
                  class="form-control"
                  id="phone"
                  placeholder="phone"
                  name="phone"
                  />
                  <div class="invalid-feedback" in="phone"></div>
                  <label for="phone">Nomor Telepon</label>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-6">
              <div class="mb-3">
                <div class="form-floating">
                  <input
                  type="password"
                  class="form-control"
                  id="password"
                  placeholder="password"
                  name="password"
                  />
                  <div class="invalid-feedback" in="password"></div>
                  <label for="password">Password</label>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-6">
              <div class="mb-3">
                <div class="form-floating">
                  <input
                  type="password"
                  class="form-control"
                  id="confirmPassword"
                  placeholder="confirmPassword"
                  name="password_confirm"
                  />
                  <div class="invalid-feedback" in="password_confirm"></div>
                  <label for="confirmPassword">Konfirmasi Password</label>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <button
              class="btn text-white w-100 mt-3"
              style="background-color: #00b14f"
              id="btnSubmit"
              type="button"
              >
              Daftar
            </button>
            <div class="form-check mt-3">
              <input
              class="form-check-input"
              type="checkbox"
              id="agree"
              />
              <label
                class="form-check-label text-muted d-flex align-items-center"
                for="agree"
                >
                <small>
                  Dengan anda meregistrasi berarti anda menyetujui dan percaya
                  kepada kebijakan Privasi kami. <br />
                  Silahkan baca
                  <a href="">Kebijakan Privasi</a>
                </small>
              </label>
            </div>
            <p class="mt-3 mb-0 text-end">
              <small class="text-muted">
                Sudah Punya Akun??
                <a href="<?=site_url('login') ?>">Masuk</a>
              </small>
            </p>
          </div>
        </form>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <!-- end-MAIN -->

  <!-- Bootstrap-->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"
    crossorigin="anonymous"
    ></script>
  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

  <!-- MyJS -->
  <script src="<?=base_url('assets/frontend2/') ?>js/script.js"></script>
  <!-- Bootstrap4 files-->
  <script src="<?= base_url('stisla/'); ?>assets/modules/jquery.min.js"></script>
  <script src="<?= base_url(); ?>assets/function.js"></script>
</body>
</html>


<script>

$(document).ready(function() {
$('#btnSubmit').prop('disabled', true);

$('#agree').change(function() {
if (this.checked == false) {
$('#btnSubmit').prop('disabled', true);
} else {
$('#btnSubmit').prop('disabled', false);
}

});

$('#btnSubmit').on('click',
function() {
register();
})

});


function register() {
let form = $('#form').serialize();
let url = "<?=site_url('auth/action_register') ?>";
$('#btnSubmit').prop('disabled', true);
$('#btnSubmit').html('Proses..');

$.ajax({
url: url,
type: "POST",
data: form,
dataType: "JSON",
success: function(data) {
$('#btnSubmit').prop('disabled',
false);
$('#btnSubmit').html('Register');
if (data.status == false) {
$.each(data.err, function(key, value) {
if (value == "") {
$(`[name="${key}"]`).removeClass('is-invalid');
$(`[in="${key}"]`).html();
} else {
$(`[name="${key}"]`).addClass('is-invalid');
$(`[in="${key}"]`).html(value);
}
});

} else {
// MEMBERSIHKAN FORM
$('input').removeClass('is-invalid');
$('input').val('');
$('textarea').val('');
$('.invalid-feedback').html();

window.location.href = "<?=site_url('auth/success_register') ?>";
}
},
error: function(jqxhr, textStatus, errorThrown) {
console.log(jqxhr)
console.log(textStatus)
console.log(errorThrown)
}
});

}

</script>