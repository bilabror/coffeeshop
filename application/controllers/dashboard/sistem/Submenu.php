<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submenu extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Submenu_model', 'submenu');
    $this->load->model('Menu_model', 'menu');
    proteksi();
  }

  public function index() {
    $menu = $this->menu->get_where(['tipe' => 2]);
    $data['menu'] = $menu->result_array();
    $data['title'] = 'Submenu';
    pages('dashboard/sistem/submenu', $data);
  }


  public function status() {
    $id = $this->input->post('id');
    $status = $this->db->get_where('sub_menu', ['id' => $id])->row_array()['is_active'];
    if ($status == 1) {
      $this->db->update('sub_menu', ['is_active' => 0], ['id' => $id]);
    } else {
      $this->db->update('sub_menu', ['is_active' => 1], ['id' => $id]);
    }
    echo json_encode(['status' => true]);
  }


  public function get_datatables() {
    $this->submenu->get_datatables();
  }

  public function ajax_edit($id) {
    $data = $this->submenu->get_by_id($id);
    echo json_encode($data);
  }


  private function _set_validate() {

    $this->form_validation->set_rules('menu_id', 'Menu_id', 'required',
      [
        'required' => 'menu tidak boleh kosong',
        'min_length' => 'nama menu terlalu pendek'
      ]
    );
    $this->form_validation->set_rules('title', 'Title', 'required|min_length[3]',
      [
        'required' => 'title tidak boleh kosong',
        'min_length' => 'nama title terlalu pendek'
      ]
    );
    $this->form_validation->set_rules('icon', 'Icon', 'required|min_length[3]',
      [
        'required' => 'icon tidak boleh kosong',
        'min_length' => 'nama icon terlalu pendek'
      ]
    );
    $this->form_validation->set_rules('url', 'Url', 'required',
      [
        'required' => 'tipe tidak boleh kosong'
      ]
    );
  }


  public function ajax_add() {

    $this->_set_validate();
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'menu_id' => form_error('menu_id'),
        'title' => form_error('title'),
        'icon' => form_error('icon'),
        'url' => form_error('url')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {
      $data = [
        'menu_id' => htmlspecialchars($this->input->post('menu_id'), true),
        'title' => htmlspecialchars($this->input->post('title'), true),
        'icon' => htmlspecialchars($this->input->post('icon'), true),
        'url' => htmlspecialchars($this->input->post('url'), true),
        'is_active' => 1
      ];
      $insert = $this->submenu->save($data);

      echo json_encode(array("status" => TRUE));
    }




  }

  public function ajax_update() {

    $this->_set_validate();

    if ($this->form_validation->run() == FALSE) {
      $err = [
        'menu_id' => form_error('menu_id'),
        'title' => form_error('title'),
        'icon' => form_error('icon'),
        'url' => form_error('url')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {
      $data = [
        'menu_id' => htmlspecialchars($this->input->post('menu_id'), true),
        'title' => htmlspecialchars($this->input->post('title'), true),
        'icon' => htmlspecialchars($this->input->post('icon'), true),
        'url' => htmlspecialchars($this->input->post('url'), true),
      ];
      $this->submenu->update($data, ['id' => $this->input->post('id')]);
      echo json_encode(array("status" => TRUE));
    }

  }

  public function ajax_delete($id) {
    $this->submenu->delete_by_id($id);
    $this->db->delete('role_submenu', ['submenu_id' => $id]);
    echo json_encode(array("status" => TRUE));
  }







}