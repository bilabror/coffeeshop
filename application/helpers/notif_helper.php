<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function push_notif_admin() {

  $ci = get_instance();
  $data_notifikasi = [
    'id_user' => 36,
    'judul' => 'Pesanan Baru',
    'subjudul' => $id_pesanan,
    'link' => "transaksi/pesanan/detail/{$id_pesanan}",
    'icon' => 'fas fa-cart-arrow-down',
    'tgl_buat' => dt()
  ];

  $this->db->insert('notifikasi', $data_notifikasi);
}