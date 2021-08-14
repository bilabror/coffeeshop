<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
* kombilasi proteksi
* - harus login
* - akses menu
* - akses submenu
*/
function proteksi() {
  harus_login();
  akses_menu();
  akses_submenu();
}


/**
* akses halaman dengan syarat sudah login
*/
function harus_login() {
  $ci = get_instance();
  if (!sud('email')) {
    redirect(site_url('login'));
  }
}


function harus_customer() {
  $ci = get_instance();
  if (sud('role_id') != 2) {
    redirect(site_url());
  }
}


/**
* akses menu dengan syarat akses diizinkan
*/
function akses_menu() {
  $ci = get_instance();
  $role_id = sud('role_id');
  if (empty($ci->uri->segment(2))) {
    $menu = $ci->uri->segment(1);
  } else {
    $menu = $ci->uri->segment(2);
  }
  $status = $ci->db->get_where('menu', ['menu' => $menu, 'is_active' => 1]);
  if ($status->num_rows() < 1 && $menu != 'qrcode') {
    echo '<center style="margin-top:15%;"><h1>HALAMAN DINONAKTIFKAN</h1><h2>404</h2><a href="'.site_url("dashboard").'">kembali ke home</a></center>';
    die;
  } else {
    if (!empty($status->row_array())) {
      $menu_id = $status->row_array()['id'];
      $access = $ci->db->get_where('role_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);
      if ($access->num_rows() < 1) {
        echo '<center style="margin-top:15%;"><h1>AKSES ANDA DITOLAK</h1><h2>403</h2><a href="'.site_url("dashboard").'">kembali ke home</a></center>';
        die;
      }
    }

  }

}


/**
* akses submenu dengan syarat akses diizinkan
*/
function akses_submenu() {
  $ci = get_instance();
  $role_id = sud('role_id');
  if (!empty($ci->uri->segment(3)) && $ci->uri->segment(1) != 'ajax') {
    $uri1 = $ci->uri->segment(2);
    $uri2 = $ci->uri->segment(3);
    $url = "{$uri1}/{$uri2}";
    $status = $ci->db->get_where('sub_menu', ['url' => $url]);
    if ($status->num_rows() < 1) {} else {
      if ($status->row_array()['is_active'] < 1) {
        echo '<center style="margin-top:15%;"><h1>HALAMAN DINONAKTIFKAN</h1><h2>404</h2><a href="'.site_url("dashboard").'">kembali ke home</a></center>';
        die;
      } else {
        $submenu = $status->row_array();
        $access = $ci->db->get_where('role_submenu', ['role_id' => $role_id, 'submenu_id' => $submenu['id']]);
        if ($access->num_rows() < 1) {
          echo '<center style="margin-top:15%;"><h1>AKSES ANDA DITOLAK</h1><h2>403</h2><a href="'.site_url("dashboard").'">kembali ke home</a></center>';
          die;
        }
      }
    }
  }

}



function access($role_id, $menuId) {
  $ci = get_instance();

  $ci->db->where('role_id', $role_id);
  $ci->db->where('menu_id', $menuId);
  $return = $ci->db->get('role_menu');

  if ($return->num_rows() > 0) {
    return 'checked="checked"';
  }

}