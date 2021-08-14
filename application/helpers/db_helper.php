<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Get data pengaturan
*/
function getset($key = '') {
  $ci = get_instance();
  if ($key == '') {
    return $ci->db->get('pengaturan')->result_array();
  } else {
    if ($key == 'email_config' || $key == 'alamat_toko') {
      $get = $ci->db->get_where('pengaturan', ['key' => $key])->row_array()['konten'];
      return $set = json_decode($get, TRUE);
    } else {
      return $ci->db->get_where('pengaturan', ['key' => $key])->row_array()['konten'];
    }
  }
}


/**
* Get data kategori produk
*/
function kategori() {
  $ci = get_instance();
  return $ci->db->get('kategori')->result();
}



/**
* Get data notifikasi
*/
function notifikasi() {
  $ci = get_instance();
  return $ci->db->get_where('notifikasi', ['id_user' => sud('id_user')])->result_array();
}


/**
* Get data user
*/
function datauser($email) {

  $ci = get_instance();
  $query = "
  SELECT `avatar`,`username` FROM `user` WHERE `email` = '$email'
  ";
  $user = $ci->db->query($query)->row_array();
  return $datauser = [
    'avatar' => $user['avatar'],
    'username' => $user['username']
  ];
}


/**
* Get data poin
*/
function poin() {
  $ci = get_instance();
  return $ci->db->get_where('poin', ['id_user' => sud('id_user')])->row()->poin;
}


/**
* Get data menu
*/
function menu() {
  $ci = get_instance();
  $role_id = $ci->session->userdata('role_id');
  $ci->db->Select('menu.*');
  $ci->db->from('menu');
  $ci->db->join('role_menu', 'role_menu.menu_id = menu.id');
  $ci->db->where(['is_active' => 1, 'role_menu.role_id' => $role_id]);
  $ci->db->order_by('urutan', 'asc');
  return $menu = $ci->db->get()->result_array();

}


/**
* Get data submenu
*/
function submenu($id) {
  $ci = get_instance();
  $menu_id = $id;
  $role_id = $ci->session->userdata('role_id');
  $ci->db->Select('sub_menu.*');
  $ci->db->from('sub_menu');
  $ci->db->join('role_submenu', 'role_submenu.submenu_id = sub_menu.id');
  $ci->db->where(['menu_id' => $menu_id, 'is_active' => 1, 'role_submenu.role_id' => $role_id]);
  $ci->db->order_by('title', 'asc');
  return $subMenu = $ci->db->get()->result_array();
}







/**
* Tambah poin user
*/
function add_poin($id_user) {
  $ci = get_instance();
  $poin = $ci->db->get_where('poin', ['id_user' => $id_user]);
  if ($poin->num_rows() > 0) {
    $p = $poin->row_array();
    $ci->db->update('poin', ['poin' => $p['poin']+1], ['id_user' => $id_user]);
  }
}



/**
* Menghitung penilaian produk
*/
function count_rating() {
  $ci = get_instance();
  $stmt = $ci->db->query('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_ulasan FROM ulasan_produk WHERE id_produk = '.$produk->row()->id_produk);
}



/**
* Menghitung jumlah produk dalam keranjang
*/
function count_keranjang() {
  $ci = get_instance();
  $cart = $ci->load->model('Keranjang_model', 'keranjang');
  return $ci->keranjang->get_where(['id_user' => sud('id_user'), 'status' => 0])->num_rows();

}


/**
* Menghitung jumlah notifikasi
*/
function count_notifikasi() {
  $ci = get_instance();
  $result = $ci->db->get_where('notifikasi', ['id_user' => sud('id_user')]);
  return $result->num_rows();
}


function getstok($id_produk) {
  $ci = get_instance();
  return $ci->db->get_where('produk', ['id_produk' => $id_produk])->row()->stok_produk;

}

function upstok($id_produk, $stok) {
  $ci = get_instance();
  $ci->db->update('produk', ['stok_produk' => $stok], ['id_produk' => $id_produk]);
}


function produksold($id_produk, $qty, $tgl) {
  $ci = get_instance();
  $where = [
    'id_produk' => $id_produk,
    'tgl_buat >=' => date("Y-m-{$tgl}").' 00:00:00',
    'tgl_buat <=' => date("Y-m-{$tgl}").' 23:59:59'
  ];
  $terjual = $ci->db->get_where('produk_terjual', $where);

  // Ketika belum ada produk terjual (hari ini)
  if ($terjual->num_rows() < 1) {

    // insert produk terjual
    $data_sold = [
      'id_produk' => $id_produk,
      'terjual' => $qty,
      'tgl_buat' => dt()
    ];
    $ci->db->insert('produk_terjual', $data_sold);
  }

  // ketika produk telah terjual sebelumnya (hari ini)
  else {

    // update data produk terjual hari ini
    $data_sold = [
      'terjual' => $terjual->row_array()['terjual']+$qty
    ];
    $ci->db->update('produk_terjual', $data_sold, ['id_produk' => $id_produk]);
  }
}


/**
* Menghapus pesanan yang expired
*/
function order_expired() {
  $ci = get_instance();
  $a = $ci->db->get_where('pesanan', ['status' => 'new'])->result_array();
  foreach ($a as $row) {
    if (time()-strtotime($row['tgl_buat_pesanan']) >= 86400) {
      $ci->db->delete('pesanan', ['id_pesanan' => $row['id_pesanan']]);
    }
  }
}


/**
* Menghapus token yang expired
*/
function expired_token() {
  $ci = get_instance();
  $token = $ci->db->get('user_token')->result_array();
  foreach ($token as $val) {
    if (time() - strtotime($val['created_at']) >= 86400) {
      $ci->db->delete('user_token', ['id' => $val['id']]);
    }
  }
}



/**
* Menghapus notifikasi yang sudah dilihat
*/
function read_notifiksai_backend($link) {
  $ci = get_instance();
  $ci->db->delete('notifikasi', ['id_user' => sud('id_user'), 'link' => $link]);
}


/**
* Menghapus pesanan dengan syarat
* - customer telah memasukan ke dalam trash
* - admin telah memasukan ke dalam trash
*/
function delete_pesanan() {
  $ci = get_instance();
  $ci->db->delete('pesanan', ['dihapus_pembeli' => 1, 'dihapus_penjual' => 1]);
}