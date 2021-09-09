<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg<?=count_notifikasi() > 0 ? '  beep' : '' ?>"><i class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">
                Notifikasi
              </div>
              <div class="dropdown-list-content dropdown-list-icons">
                <?php foreach (notifikasi() as $row): ?>
                <a href="<?=site_url('dashboard/'.$row['link']) ?>" class="dropdown-item">
                  <div class="dropdown-item-icon bg-primary text-white">
                    <i class="<?=$row['icon'] ?>"></i>
                  </div>
                  <div class="dropdown-item-desc">
                    <?=$row['judul'] ?> - <strong><?=$row['subjudul'] ?></strong>
                    <div class="time text-primary">
                      <?=time_ago(date('Y-m-d H:i:s', strtotime($row['tgl_buat']))) ?>
                    </div>
                  </div>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
          </li>
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= base_url('uploads/image/profile/').datauser($this->session->userdata('email'))['avatar']; ?>" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">
              Hi, <?=datauser($this->session->userdata('email'))['username']; ?>
            </div>
          </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="<?=site_url('auth/logout'); ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>