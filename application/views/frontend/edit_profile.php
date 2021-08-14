<!-- ------------------------------------------------------------------------- PROFILE -->
<div class="profileContainer container">
  <div class="row">
    <div class="col-md-4 d-flex align-items-center">
      <div class="profile img-thumbnail">
        <img src="<?=site_url('assets/img/avatar.png') ?>" alt="" />
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
                <form action="" method="post">
                  <table class="table table-striped w-100">
                    <tr>
                      <th>Username</th>
                      <td>:</td>
                      <td>
                        <input type="text" class="w-75 form-control" placeholder="Masukan Username perubahan..." value="<?=$user['username'] ?>" required="" name="username">
                      </td>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <td>:</td>
                      <td>
                        <input type="text" class="w-75 form-control" placeholder="Masukan Email perubahan..." value="<?=$user['email'] ?>" readonly="" name="email">
                      </td>
                    </tr>
                    <tr>
                      <th>No Telepon</th>
                      <td>:</td>
                      <td>
                        <input type="text" class="w-75 form-control" placeholder="Masukan No.telepon perubahan..." value="<?=$user['phone'] ?>" name="phone">
                      </td>
                    </tr>
                    <tr>
                      <th>Foto Profil</th>
                      <td>:</td>
                      <td>
                        <input class="form-control w-75" type="file" accept="image/*">
                      </td>
                    </tr>
                  </table>
                  <div class="text-center buttonEdit d-flex justify-content-around pt-3">
                    <button class="btn edit" type="submit" name="submit"><i class="fas fa-save me-2"></i> Simpan Profil</button>
                    <a href="profil.html" class="btn edit">
                      <i class="fas fa-times me-1"></i> Batal
                    </a>
                  </div>
                </form>
              </p>
            </div>
          </div>
        </section>
      </div>
      <!-- end-MADING -->
    </div>
  </div>
</div>
<!-- end-PROFILE -->