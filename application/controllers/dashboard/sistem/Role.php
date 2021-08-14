<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {



  public function __construct() {

    parent::__construct();
    proteksi();
    $this->load->model('Submenu_model', 'submenu');
    $this->load->model('Role_Model', 'role');
    $this->load->model('Akses_menu_Model', 'access');
  }


  /**
  *  Method Index pada controller ini adalah :
  *   halaman berisi data role yang bisa kkita atur
  *   mencakup melihat,menambah,mengedit, dan menghapus
  *
  * Url :
  * 		http://localhost/nama-projek/dashboard/sistem/role
  */
  public function index() {
    $data['role'] = $this->role->get_all()->result_array();
    $data['title'] = 'Role';
    pages('dashboard/sistem/role', $data);
  }


  public function menu_access($id) {
    $menu = $this->db->get('menu')->result();
    $i = 1;
    foreach ($menu as $m) {
      $row[] = "<tr>";
      $row[] = "<td>".$i++."</td><td>{$m->menu}</td><label class=\"custom-switch mt-2\"></td>";
      $this->db->where('role_id', $id);
      $this->db->where('menu_id', $m->id);
      $return = $this->db->get('role_menu');
      $return->num_rows() > 0 ?
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="changeAccessMenu('."'".$m->id."'".','."'".$id."'".')"> <span class="custom-switch-indicator"></span></label></td>':
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="changeAccessMenu('."'".$m->id."'".','."'".$id."'".')"> <span class="custom-switch-indicator"></span></label></td>';
      $row[] = "</tr>";
    }
    echo json_encode($row);
  }



  public function submenu_access($id) {
    $this->db->select('sub_menu.*,menu.menu');
    $this->db->from('sub_menu');
    $this->db->join('menu', 'sub_menu.menu_id = menu.id');
    $this->db->order_by('menu.menu', 'asc');
    $submenu = $this->db->get()->result();
    $i = 1;
    foreach ($submenu as $sm) {
      $row[] = "<tr>";
      $row[] = "<td>".$i++."</td>";
      $row[] = "<td>{$sm->menu}</td>";
      $row[] = "<td>{$sm->title}</td><label class=\"custom-switch mt-2\"></td>";
      $this->db->where('role_id', $id);
      $this->db->where('submenu_id', $sm->id);
      $return = $this->db->get('role_submenu');
      $return->num_rows() > 0 ?
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="changeAccessSubmenu('."'".$sm->id."'".','."'".$id."'".')"> <span class="custom-switch-indicator"></span></label></td':
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="changeAccessSubmenu('."'".$sm->id."'".','."'".$id."'".')"> <span class="custom-switch-indicator"></span></label></td>';
      $row[] = "</tr>";
    }

    echo json_encode($row);
  }


  // proses ubah akses
  public function change_menu_access() {

    $menuId = $this->input->post('menuId');
    $roleId = $this->input->post('roleId');
    $data = [
      'role_id' => $roleId,
      'menu_id' => $menuId
    ];
    $result = $this->db->get_where('role_menu', $data);

    if ($result->num_rows() < 1) {
      $this->db->insert('role_menu', $data);
    } else {
      $this->db->delete('role_menu', $data);
    }

  }


  // proses ubah akses
  public function change_submenu_access() {

    $submenuId = $this->input->post('submenuId');
    $roleId = $this->input->post('roleId');
    $data = [
      'role_id' => $roleId,
      'submenu_id' => $submenuId
    ];
    $result = $this->db->get_where('role_submenu', $data);

    if ($result->num_rows() < 1) {
      $this->db->insert('role_submenu', $data);
    } else {
      $this->db->delete('role_submenu', $data);
    }

  }


  // data ajax untuk halaman utama
  public function get_datatables() {
    $this->role->get_datatables();
  }
  public function ajax_edit($id) {
    $data = $this->role->get_by_id($id);
    echo json_encode($data);
  }


  private function _set_validate() {
    $this->form_validation->set_rules('role',
      'Role',
      'required|min_length[3]',
      [
        'required' => 'role tidak boleh kosong',
        'min_length' => 'nama role terlalu pendek'
      ]
    );
  }


  // insert data
  public function ajax_add() {

    $this->_set_validate();

    if ($this->form_validation->run() == FALSE) {
      $errors = validation_errors();
      echo json_encode(["status" => FALSE, 'errors' => $errors]);
    } else
    {
      $data = ['role' => htmlspecialchars($this->input->post('role'), true)];
      $insert = $this->role->save($data);
      echo json_encode(["status" => TRUE]);
    }
  }

  // update data
  public function ajax_update() {
    $this->_set_validate();

    if ($this->form_validation->run() == FALSE) {
      $errors = validation_errors();
      echo json_encode(["status" => FALSE, 'errors' => $errors]);
    } else
    {
      $data = ['role' => $this->input->post('role')];
      $this->role->update($data, ['id' => $this->input->post('id')]);
      echo json_encode(["status" => TRUE]);
    }

  }

  // delete data
  public function ajax_delete($id) {
    $this->db->delete('user', ['role_id' => $id]);
    $this->role->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }



}