<?php
$uris = explode('/', $this->uri->uri_string);
$role_id = $this->session->userdata('role_id');

?>
<div class="section-header-breadcrumb">
  <?php if (count($uris) == 1): ?>
  <div class="breadcrumb-item active">
    <?= $uris[0] != 'dashboard' ?  '<a href="'.site_url('dashboard').'">Dashboard</a>' : 'Dashboard'; ?>
  </div>
  <?php endif; ?>
  <?php
  /*------------- KONDISI SELAIN DASHBOARD ------------------*/
  if (count($uris) > 1) {
    echo '<div class="breadcrumb-item"><a href="'.site_url('dashboard').'">Dashboard</a></div>';
    if (count($uris) == 3) {
      $i = 1;
      foreach (array_slice($uris, 1, 1) as $uri) {
        $link = implode('/', array_slice($uris, 0, $i++));
        echo '<div class="breadcrumb-item"><a href="'.site_url($link).'">'.$uri.'</a></div>';
      };
    };
    if (count($uris) == 4) {
      $i = 1;
      foreach (array_slice($uris, 1, 2) as $uri) {
        $link = implode('/', array_slice($uris, 0, $i++));
        echo '<div class="breadcrumb-item"><a href="'.site_url($link).'">'.$uri.'</a></div>';
      };
    };
    echo $uris[1] != 'dashboard' ?  '<div class="breadcrumb-item">'.end($uris).'</div>' : '';
  };
  /*------------- END KONDISI SELAIN DASHBOARD ------------------*/
  ?>
</div>