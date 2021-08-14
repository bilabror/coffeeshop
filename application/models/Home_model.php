<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Auth Model
* @property Home | Controller
*/

class Home_model extends CI_Model {

  /**
  * Proses Interaksi dengan database
  * Pada Halaman Beranda
  *
  * @return Array
  */
  public function beranda() {
    // Load @properti Produk Model
    $this->load->model('produk_model', 'produk');

    if (isset($_POST['cari'])) {
      // KEtika ada request percarian produk spesifik

      $cari = htmlspecialchars($this->input->post('cari'), TRUE);
      $all_produk = $this->produk->get_like(['nama_produk' => $cari]);
    } else {
      $all_produk = $this->produk->get();
    }

    // Get data Produk Rekomendasi
    $this->db->select('produk.*');
    $this->db->from('produk_rekomendasi');
    $this->db->join('produk', 'produk_rekomendasi.id_produk = produk.id_produk');

    $rekomendasi = $this->db->get()->result_array();
    $slider = $this->db->get('slider')->result_array();

    return $data = [
      'rekomendasi' => $rekomendasi,
      'slider' => $slider,
      'all_produk' => $all_produk
    ];
  }

}