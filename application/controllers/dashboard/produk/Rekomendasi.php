<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Rekomendasi
 *
 * Controller ini berperan untuk mengatur bagian Produk Rekomendasi
 * 
 */
class Rekomendasi extends CI_Controller {

  
/**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('Kategori_model', 'kategori');
    $this->load->model('produk_rekomendasi_model', 'produk_rekomendasi');
    proteksi();
  }

  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['title'] = 'Produk Rekomendasi';
    pages('dashboard/produk/rekomendasi_produk', $data);
  }


  /**
	 * aksi tambah data
	 *
	 * @return json
	 */
  public function insert() {
    // DEKLARASI VARIABELS
    $id_produk = $this->input->post('id_produk');
    $this->db->select_max('urutan');
    $urutan = $this->db->get('produk_rekomendasi')->row_array()['urutan'];
    if ($urutan) {
      $data = [
        'id_produk' => $id_produk,
        'urutan' => $urutan
      ];
    } else {
      $data = [
        'id_produk' => $id_produk,
        'urutan' => 1
      ];
    }
    $this->form_validation->set_rules('id_produk', 'Id Produk', 'is_unique[produk_rekomendasi.id_produk]');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode(['status' => FALSE, 'error' => 'Produk Sudah Ada direkomendasi']);
    } else {
      $this->db->insert('produk_rekomendasi', $data);
      echo json_encode(['status' => TRUE]);

    }

  }

/**
	 * aksi hapus data
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_delete($id) {
    $this->produk_rekomendasi->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }

  /**
	 * Get data dengan style datatable
	 *
	 * @return json
	 */
  public function get_datatables() {
    $this->produk_rekomendasi->get_datatables();
  }

  /**
	 * Get data
	 *
	 * @return json
	 */
  public function get_produk() {
    $this->produk_rekomendasi->get_produk();
  }


/**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->produk->get_by_id($id);
    echo json_encode($data);
  }

/**
	 * get data kategori
	 *
	 * @return json
	 */
  public function kategori_ajax() {
    $kategori = $this->kategori->get();
    echo json_encode($kategori);
  }




}