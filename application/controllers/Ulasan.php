<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Home
* @property home_model|Model
*/
class Ulasan extends CI_Controller {

  public function __construct() {
    parent::__construct();
    harus_login();
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Kategori_model', 'kategori');
    $this->load->model('Keranjang_model', 'keranjang');
    $this->load->model('Pesanan_model', 'pesanan');
    $this->load->model('Home_model', 'home');
  }




  public function index() {
    $this->db->select('id_user,
    produk.nama_produk,
    produk.gambar_produk,
    total_harga_produk,
    produk.id_produk,
    id_item_pesanan,
    item_pesanan.id_pesanan',);
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->join('pesanan', 'item_pesanan.id_pesanan = pesanan.id_pesanan', 'left');
    $this->db->where(['pesanan.id_user' => sud('id_user'), 'item_pesanan.ulasan' => 0]);
    $query = $this->db->get();
    $data['produk'] = $query->result_array();

    $data['title'] = 'beri-ulasan';

    pages_frontend('frontend/review-produk', $data);
  }


  public function nilai_produk() {
    $id_produk = htmlspecialchars($this->input->post('id_produk'), true);
    $id_item_pesanan = htmlspecialchars($this->input->post('id_item_pesanan'), true);
    $id_pesanan = htmlspecialchars($this->input->post('id_pesanan'), true);
    $ratting = htmlspecialchars($this->input->post('ratting'), true);
    $komentar = htmlspecialchars($this->input->post('komentar'), true);

    $this->form_validation->set_rules('ratting', 'Ratting', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $err = [
        'ratting' => form_error('ratting')
      ];
      echo json_encode(['status' => false, 'err' => $err]);
    } else {
      if ($ratting > 5) {
        $err = [
          'ratting' => 'maksimal penilaian 5'
        ];
        echo json_encode(['status' => false, 'err' => $err]);
      } else {
        $data = [
          'id_produk' => $id_produk,
          'id_user' => sud('id_user'),
          'id_item_pesanan' => $id_item_pesanan,
          'komentar' => $komentar,
          'rating' => $ratting,
          'tgl_buat' => dt()
        ];
        $this->db->insert('ulasan_produk', $data);
        $this->db->update('item_pesanan', ['ulasan' => 1], ['id_item_pesanan' => $id_item_pesanan]);

        echo json_encode(['status' => true]);
      }

    }



  }

  public function tidak_nilai_produk() {
    $id_item_pesanan = $this->input->post('id_item_pesanan');
    $this->db->update('item_pesanan', ['ulasan' => -1], ['id_item_pesanan' => $id_item_pesanan]);
    echo json_encode(['status' => true]);
  }


}