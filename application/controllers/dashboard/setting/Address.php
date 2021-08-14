<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('aplikasi_model', 'aplikasi');
    proteksi();
  }

  // HALAMAN PENGATURAN BASIC
  public function index() {
    $data['title'] = 'Pengaturan alamat toko';
    pages('dashboard/pengaturan/alamat_toko', $data);

    if (isset($_POST['submit'])) {
      foreach ($_POST as $key => $val) {
        $this->db->update('pengaturan', ['konten' => $val], ['key' => $key]);
      }
      $this->session->set_flashdata('success', 'Data Berhasil di update');
      redirect(site_url('dashboard/setting/address'));
    }

  }


}