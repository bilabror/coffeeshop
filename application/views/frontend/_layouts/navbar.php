<!-- ------------------------------------------------------------------------- NAVBAR -->
<nav class="navbar navbar-light bg-light fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?=site_url() ?>"><?=getset('nama_toko') ?></a>
    <?php if (!sud('email')): ?>
    <div class="right ms-auto">
      <form class="search-navbar" id="form_search" method="post">
        <input
        type="text"
        name="search-navbar"
        class="form-control"
        placeholder="Type to search"
        autocomplete="on"
        />
      </form>
      <div class="d-flex align-items-center">
        <a href="<?=site_url('daftar') ?>" class="login signin me-3" style="font-size: 18px;">Daftar</a>
        <a href="<?=site_url('login') ?>" class="login" style="font-size: 18px;">Masuk</a>
      </div>
    </div>
    <?php else : ?>
    <?php if (sud('role_id') == 2): ?>
    <i class="fas fa-search navBottomToggle" style="display: none;"></i>
    <div class="right ms-auto">
      <div class="d-flex justify-content-between align-items-center me-3">
        <div class="poin">
          <a href="<?=site_url('poin') ?>" style="color:white">
            <i class="fas fa-coins"></i>
            <b><?=poin() ?></b>
          </a>
        </div>
      </div>

      <li class="nav-item dropdown list-style">
        <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-bell" style="position: relative">
            <span
              class="badge bg-danger"
              style="position: absolute; right: -3px; top: 0px; font-size: 10px;"
              id="count_notif"
              ></span>
          </i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php foreach (notifikasi() as $row): ?>
          <li>
            <a href="<?=site_url($row['link']) ?>" class="dropdown-item d-flex justify-content-between align-items-center">
              <div class="notif d-flex align-items-center rounded-circle bg-success">
                <i class="<?=$row['icon'] ?> text-white text-center"></i>
              </div>
              <div class="ms-4 pe-3">
                <span class="fw-bold"><?=$row['judul'] ?></span><br>
                <span class="fst-italic" style="font-size: 12px;"><?=$row['subjudul'] ?></span><br>
                <span style="font-size: 14px;">&bull; <?=time_ago(date('Y-m-d H:i:s', strtotime($row['tgl_buat']))) ?></span>
              </div>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </li>
      <a href="<?=site_url('keranjang') ?>" class="d-flex align-items-center">
        <i class="fas fa-shopping-cart" style="position: relative">
          <span
            class="badge bg-danger"
            style="position: absolute; right: -3px; top: 0px; font-size: 10px;"
            id="count_cart"
            ></span>
        </i>
      </a>
      <li class="nav-item dropdown list-style">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li>
            <a class="dropdown-item" href="<?=site_url('profile') ?>">Lihat Profil</a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="<?=site_url('riwayat/pesanan') ?>">Riwayat Pesanan</a>
          </li>
          <li>
            <a class="dropdown-item" href="<?=site_url('riwayat/booking') ?>">Riwayat Booking</a>
          </li>
          <li>
            <a class="dropdown-item" href="<?=site_url('riwayat/poin') ?>">Riwayat Tukar Poin</a>
          </li>
          <li>
            <a class="dropdown-item" href="<?=site_url('ulasan') ?>">Beri nilai Produk</a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?=site_url('auth/Logout') ?>">Logout</a></li>
        </ul>
      </li>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</nav>
<?php if (!sud('email')): ?>
<div class="navBottom">
  <form id="form_search" method="post">
    <input
    type="text"
    name="cari"
    class="form-control"
    placeholder="Type to search"
    autocomplete="on"
    />
  </form>
</div>
<?php endif; ?>

<!-- end-NAVBAR -->