<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Menu
 *
 * Controller ini berperan untuk mengatur bagian Menu
 * 
 */
class Menu extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    proteksi();
    $this->load->model('Menu_model', 'menu');
    $this->load->library('Datatables');
  }

  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['title'] = 'Menu Group';
    $page = 'dashboard/sistem/menu';
    pages($page, $data);
  }


  /**
	 * aksi menaikan urusan menu
	 *
	 * @return json
	 */
  public function naikan() {
    $id = $this->input->post('id', true);
    $urutan = $this->input->post('urutan', true);
    if ($urutan == 1) {
      echo json_encode(['status' => FALSE]);
    } else {
      $this->menu->update(['urutan' => $urutan], ['urutan' => $urutan-1]);
      $this->menu->update(['urutan' => $urutan-1], ['id' => $id]);
      echo json_encode(["status" => TRUE]);
    }
  }


  /**
	 * aksi menurunkan urusan menu
	 *
	 * @return json
	 */
  public function turunkan() {
    $id = $this->input->post('id', true);
    $urutan = $this->input->post('urutan', true);
    $menu = $this->menu->get_where(['urutan' => $urutan+1]);
    if ($menu->num_rows() < 1) {
      echo json_encode(["status" => FALSE]);
    } else {
      $this->menu->update(['urutan' => $urutan], ['urutan' => $urutan+1]);
      $this->menu->update(['urutan' => $urutan+1], ['id' => $id]);
      echo json_encode(["status" => TRUE]);
    }
  }


  /**
	 * aksi mengganti status menu
	 *
	 * @return json
	 */
  public function status() {
    $id = $this->input->post('id', true);
    $status = $this->menu->get_where(['id' => $id])->row_array()['is_active'];
    if ($status == 1) {
      $this->menu->update(['is_active' => 0], ['id' => $id]);
    } else {
      $this->menu->update(['is_active' => 1], ['id' => $id]);
    }
    //echo json_encode(['status' => true]);
  }




  /* ------------------------------------------------------------------------------------------------------------*/




  /**
	 * Get data dengan style datatable
	 *
	 * @return json
	 */
  public function get_datatables() {
    $this->menu->get_datatables();
  }




  /* ------------------------------------------------------------------------------------------------------------*/





  /**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->menu->get_by_id($id);
    echo json_encode($data);
  }


  /**
	 * aksi tambah data
	 *
	 * @return json
	 */
  public function ajax_add() {

    /* --- set validasi pada form tambah data menu --- */
    $this->form_validation->set_rules('menu', 'Menu', 'required|min_length[3]|is_unique[menu.menu]');
    $this->form_validation->set_rules('title', 'Title', 'required|min_length[3]|is_unique[menu.title]');
    $this->form_validation->set_rules('icon', 'Icon', 'required|min_length[3]');
    $this->form_validation->set_rules('tipe', 'Tipe', 'required');


    // jika tidak lolos validasi
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'menu' => form_error('menu'),
        'title' => form_error('title'),
        'icon' => form_error('icon'),
        'tipe' => form_error('tipe')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    }
    // jika lolos validasi
    else
    {
      $i = $this->db->query('SELECT max(urutan) FROM menu')->row_array();
      $urutan = $i['max(urutan)']+1;
      $data = [
        'menu' => htmlspecialchars(strtolower($this->input->post('menu')), true),
        'title' => htmlspecialchars($this->input->post('title'), true),
        'icon' => htmlspecialchars($this->input->post('icon'), true),
        'tipe' => htmlspecialchars($this->input->post('tipe'), true),
        'urutan' => $urutan,
        'is_active' => 1
      ];
      $this->menu->insert($data);
      echo json_encode(["status" => TRUE]);
    }
  }


  /**
	 * aksi edit data
	 *
	 * @return json
	 */
  public function ajax_update() {

    /* --- set validasi pada form tambah data menu --- */
    $this->form_validation->set_rules('menu', 'Menu', 'required|min_length[3]');
    $this->form_validation->set_rules('title', 'Title', 'required|min_length[3]');
    $this->form_validation->set_rules('icon', 'Icon', 'required|min_length[3]');
    $this->form_validation->set_rules('tipe', 'Tipe', 'required');

    // jika tidak lolos validasi
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'menu' => form_error('menu'),
        'title' => form_error('title'),
        'icon' => form_error('icon'),
        'tipe' => form_error('tipe')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    }
    // jika lolos validasi
    else
    {
      $data = [
        'menu' => htmlspecialchars(strtolower($this->input->post('menu')), true),
        'title' => htmlspecialchars($this->input->post('title'), true),
        'icon' => htmlspecialchars($this->input->post('icon'), true),
        'tipe' => htmlspecialchars($this->input->post('tipe'), true)
      ];

      $this->menu->update($data, ['id' => $this->input->post('id', true)]);
      echo json_encode(["status" => TRUE]);
    }

  }



  /**
	 * aksi hapus data
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_delete($id) {
    $this->menu->delete_by_id($id);
    $this->db->delete('sub_menu', ['menu_id' => $id]);
    $this->db->delete('role_menu', ['menu_id' => $id]);
    echo json_encode(array("status" => TRUE));
  }




}