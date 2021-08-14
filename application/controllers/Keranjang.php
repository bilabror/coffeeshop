<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Keranjang
* @property Keranjang_model|Model
*/

class Keranjang extends CI_Controller {

  public function __construct() {
    parent::__construct();
    harus_login();
    $this->load->model('Keranjang_model', 'keranjang');
  }



  /**
  * Halaman Keranjang
  */
  public function index() {

    $query = $this->keranjang->index();

    $data['total_harga'] = $query['total_harga'];
    $data['total_berat'] = $query['total_berat'];
    $data['items_keranjang'] = $query['items_keranjang'];
    $data['count_keranjang'] = $query['count_keranjang'];


    $data['title'] = 'Keranjang belanja';
    pages_frontend('frontend/keranjang', $data);
  }


  /**
  * Action Penambahan Produk Ke keranjang
  *
  * @return JSON
  */
  public function add_cart() {

    $id_user = sud('id_user');
    $qty = htmlspecialchars($this->input->post('qty'));
    $catatan = htmlspecialchars($this->input->post('catatan'));
    $id_produk = htmlspecialchars($this->input->post('id_produk'));

    $data = [
      'id_user' => $id_user,
      'id_produk' => $id_produk,
      'catatan' => $catatan,
      'kuantitas' => $qty
    ];

    if ($this->keranjang->add_cart($data) == TRUE) {
      echo json_encode(
        [
          'status' => TRUE,
          'message' => 'Masuk dalam keranjang'
        ]
      );
    }


  }


  /**
  * Action Remove / menghapus produk dalam keranjang
  *
  * @RETURN JSON
  */
  public function remove_keranjang() {
    $id = $this->input->post('id');
    $data = $this->keranjang->remove_keranjang($id);
    echo json_encode(['status' => $data]);
  }


  /**
  * Action Update / Edit produk dalam keranjang
  * EDIT : kuantitas dan catatan customer
  *
  * @RETURN JSON
  */
  public function ajax_update() {
    $data = $this->keranjang->edit_cart();
    echo json_encode($data);
  }


  /**
  * Menambilkan modal Edit produk dalam keranjang
  */
  public function ajax_edit($id) {
    $data = $this->keranjang->get_where(['id_keranjang' => $id])->row();
    echo json_encode($data);
  }

}