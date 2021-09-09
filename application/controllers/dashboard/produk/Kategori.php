<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Kategori
 *
 * Controller ini berperan untuk mengatur bagian kategori produk
 * 
 */
class Kategori extends CI_Controller {


  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('Kategori_model', 'kategori');
    proteksi();
  }

  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['title'] = 'Kategori Produk';
    pages('dashboard/produk/kategori_produk', $data);
  }


  /**
	 * Get data dengan style datatable
	 *
	 * @return json
	 */
  public function get_datatables() {
    $this->kategori->get_datatables();
  }


  /**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->kategori->get_by_id($id);
    echo json_encode($data);
  }

  /**
	 * aksi tambah data
	 *
	 * @return json
	 */
  public function ajax_add() {

    $this->form_validation->set_rules('nama_kategori', 'Ini', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('slug_kategori', 'Slug categories', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_kategori' => form_error('nama_kategori')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {
      $data = [
        'nama_kategori' => htmlspecialchars($this->input->post('nama_kategori'), true),
        'slug_kategori' => htmlspecialchars($this->input->post('slug_kategori'), true)
      ];
      $insert = $this->kategori->save($data);
      echo json_encode(["status" => TRUE]);
    }
  }

  /**
	 * aksi edit data
	 *
	 * @return json
	 */
  public function ajax_update() {

    $this->form_validation->set_rules('nama_kategori', 'Ini', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('slug_kategori', 'Slug kategori', 'trim|required');


    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_kategori' => form_error('nama_kategori')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {
      $data = [
        'nama_kategori' => htmlspecialchars($this->input->post('nama_kategori'), true),
        'slug_kategori' => htmlspecialchars($this->input->post('slug_kategori'), true)
      ];
      $this->kategori->update(array('id_kategori' => $this->input->post('id_kategori')), $data);
      echo json_encode(array("status" => TRUE));
    }
  }

  /**
	 * aksi hapus data
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_delete($id) {
    $this->kategori->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }





}