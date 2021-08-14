<!-- ------------------------------------------------------------------------- PROFILE -->
<div class="profileContainer container">
  <div class="row">
    <div class="col-md-4 d-flex align-items-center">
      <div class="profile img-thumbnail">
        <img src="<?=base_url('assets/img/avatar.png') ?>" alt="" />
      </div>
    </div>
    <div class="col-md-8 mt-5 ps-3 form">
      <h1 class="title">Ilham Hafidz <i class="fas fa-circle text-success"></i></h1>
      <!-- ------------------------------------------------------------------------- MADING -->
      <div class="mading bg-transparent mt-0">
        <section>
          <div class="text w-100 p-0">
            <div class="textBox" data-item="tentang">
              <p>
                <table class="table table-striped w-100">
                  <tr>
                    <th>Username</th>
                    <td>:</td>
                    <td><?=$user['username'] ?></td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td>:</td>
                    <td><?=$user['email'] ?></td>
                  </tr>
                  <tr>
                    <th>No Telephon</th>
                    <td>:</td>
                    <td><?=$user['phone'] ?></td>
                  </tr>
                  <tr>
                    <th>Sebagai</th>
                    <td>:</td>
                    <td>Pengguna    </td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td>:</td>
                    <td>
                      <i class="badge bg-success">aktif</i>
                    </td>
                  </tr>
                </table>
              </p>
            </div>
          </div>
        </section>
      </div>
      <!-- end-MADING -->
      <div class="text-center buttonProfile">
        <a class="btn profile" href="<?=site_url('profile/edit') ?>"><i class="fas fa-pen me-2"></i> Edit Profil</a>
      </div>
    </div>
  </div>
</div>
<!-- end-PROFILE -->