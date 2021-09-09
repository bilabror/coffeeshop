<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Model Home
 * 
 */
class Home_model extends CI_Model {

/**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Slider_model', 'slider');
  }

  /**
	 * Keperluan Beranda / Halaman Utama
	 *
	 * @return array
	 */
  public function beranda() {

    if (isset($_POST['cari'])) {
      $cari = htmlspecialchars($this->input->post('cari'), TRUE);
      $all_produk = $this->produk->get_like(['nama_produk' => $cari]);
    } else {
      $all_produk = $this->produk->get();
    }

    return $data = [
      'rekomendasi' => $this->_produk_rekomendasi(),
      'slider' => $this->slider->get_all(),
      'all_produk' => $all_produk
    ];
  }

  /**
	 * Get Data Produk Rekomendasi
	 *
	 * @return array
	 */
  private function _produk_rekomendasi(){
    $this->db->select('produk.*');
    $this->db->from('produk_rekomendasi');
    $this->db->join('produk', 'produk_rekomendasi.id_produk = produk.id_produk');
    return $this->db->get()->result_array();
  }

  /**
	 * Keperluan Halaman Detail Produk
	 *
	 * @return array
	 */
  public function detailProduk($slug){
    $data['produk'] = $this->produk->get_where(['slug_produk' => $slug])->row_array();
    if(!$data['produk']) return FALSE;
    $data['produkrand'] = $this->_random_produk(7);
    $data['ulasan'] = $this->_ulasan($data['produk']['id_produk']);
    $data['ulasan_info'] = $this->_ulasan_info($data['produk']['id_produk']);
    $data['user'] = $this->db->get_where('user', ['email' => sud('email')])->row();
    return $data;
  }

  /**
	 * Get Data Produk Random
	 *
	 * @return array
	 */
  private function _random_produk($jml){
    $this->db->order_by('rand()');
    $this->db->limit($jml);
    return $this->produk->get();
  }

  /**
	 * Get Data Ulasan
	 *
	 * @return array
	 */
  private function _ulasan($id_produk){
    $this->db->select('ulasan_produk.*,user.username');
    $this->db->from('ulasan_produk');
    $this->db->join('user', 'ulasan_produk.id_user = user.id');
    $this->db->where('id_produk', $id_produk);
    $this->db->order_by('tgl_buat', 'DESC');
    $data['ulasan'] = $this->db->get()->result_array();
  }

  /**
	 * Get Data Ulasan Info
	 *
	 * @return array
	 */
  private function _ulasan_info($id_produk){
    $query = "SELECT AVG(rating)
                  AS overall_rating, COUNT(*) 
                  AS total_ulasan 
                FROM ulasan_produk
                WHERE id_produk = {$id_produk}";
    return $this->db->query($query)->row_array();
  }



}