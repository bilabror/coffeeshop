<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Keranjang
 *
 * Controller ini berperan untuk mengatur keranjang Belanja
 * 
 */
class Keranjang extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    harus_login();
    $this->load->model('Keranjang_model', 'keranjang');
  }

  /**
	 * Index Method
	 *
	 * @return view
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
	 * aksi tambah data
	 *
	 * @return json
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
	 * aksi remove produk dalam keranjang
	 *
	 * @return json
	 */
  public function remove_keranjang() {
    $id = $this->input->post('id');
    $data = $this->keranjang->remove_keranjang($id);
    echo json_encode(['status' => $data]);
  }


  /**
	 * aksi edit data
	 *
	 * @return json
	 */
  public function ajax_update() {
    $data = $this->keranjang->edit_cart();
    echo json_encode($data);
  }


  /**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->keranjang->get_where(['id_keranjang' => $id])->row();
    echo json_encode($data);
  }

}