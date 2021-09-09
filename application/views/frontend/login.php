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

  <!-- Sweetalert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
  <title>Masuk</title>
</head>
<body class="bg-light">
  <!-- ------------------------------------------------------------------------- MAIN -->
  <div class="container position-absolute top-50 start-50 formLR">
    <div class="mb-4 text-center">
      <h2 class="fw-bold" style="color: #00b14f">
        <i class="fas fa-mug-hot logo text-white"></i>
        <?=getset('nama_toko') ?>
      </h2>
      <h6 class="text-muted">Tempat Nongkrong sambil Ngopi</h6>
    </div>
    <div class="row border rowForm">
      <div class="col-md-5 p-0 formLeft"></div>
      <div class="col-md-7 p-0">
        <div class="bg-white p-4">
          <h4 class="fw-bold mb-4">LOGIN</h4>
          <form action="" id="form">
            <div class="mb-3">
              <div class="form-floating">
                <input
                type="email"
                class="form-control"
                id="floatingInputGrid"
                placeholder="name@example.com"
                name="email"
                />
                <div class="invalid-feedback" in="email"></div>
                <label for="floatingInputGrid">Masukan Email</label>
              </div>
            </div>
            <div class="mb-3">
              <div class="form-floating">
                <input
                type="password"
                class="form-control"
                id="floatingInputGrid"
                placeholder="name@example.com"
                name="password"
                />
                <div class="invalid-feedback" in="password"></div>
                <label for="floatingInputGrid">Masukan Password</label>
              </div>
            </div>
            <div class="form-check">
              <input
              class="form-check-input"
              type="checkbox"
              id="rememberMe"
              />
              <label class="form-check-label" for="rememberMe">
                ingat saya
              </label>
            </div>
            <div class="mb-3">
              <button
                class="btn text-white w-100 mt-3"
                style="background-color: #00b14f"
                id="btnSubmit"
                type="button"
                >
                Masuk
              </button>
              <p class="mt-3">
                <small class="text-muted">
                  Belum Punya Akun??
                  <a href="<?=site_url('daftar') ?>">Daftar</a>
                </small>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end-MAIN -->


  <!-- Bootstrap-->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"
    crossorigin="anonymous"
    ></script>

  <!-- MyJS -->
  <script src="<?=base_url('assets/frontend2/') ?>js/script.js"></script>
  <!-- Bootstrap4 files-->
  <script src="<?= base_url('stisla/'); ?>assets/modules/jquery.min.js"></script>


<script>

/*------ MENDEFINISIKAN TOAST --------*/
const Toast = Swal.mixin({
toast: true,
position: 'top-end',
showConfirmButton: false,
timer: 5000,
timerProgressBar: true,
didOpen: (toast) => {
toast.addEventListener('mouseenter', Swal.stopTimer)
toast.addEventListener('mouseleave', Swal.resumeTimer)
}
});

$(document).ready(function() {
if ("<?=$this->session->flashdata('success') ?>") {
Toast.fire({
icon: 'success',
title: "<?=$this->session->flashdata('success');unset($_SESSION['success']) ?>"
})
}

$('#btnSubmit').click(function() {
login();
})

})

function login() {
$('#btnSubmit').html('proses');
$.ajax({
url: "<?= site_url('auth/action_login') ?>",
method: "POST",
data: $('#form').serialize(),
dataType: "JSON",
success: function(data) {
$('#btnSubmit').html('Login');
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
} else
{
window.location.href = data.url;
}
},
error: function (jqXHR, textStatus, errorThrown) {
console.log(jqXHR);
console.log(textStatus);
console.log(errorThrown);

}
});
}
</script>

</body>
</html>
