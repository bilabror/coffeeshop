<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_Model extends CI_Model {

  var $table = 'role';
  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }


  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [null,
      'role',
      null];
    $this->datatables->column_search = ['role'];
    $this->datatables->order = ['role' => 'asc'];
  }

  public function get_datatables() {
    $this->_config_datatables();
    $list = $this->datatables->get_datatables();
    $data = [];
    $no = $_POST['start'];
    foreach ($list as $ls) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $ls->role;

      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
             onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a> <a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_role('."'".$ls->id."'".','."'".$ls->role."'".')"><i class="fa fa-trash"></i></a> <a class="m-2" href="javascript:void(0)"
                  onclick="menu_access('."'".$ls->id."'".','."'".$ls->role."'".')"><i class="fa fa-folder"></i></a> <a class="m-2" href="javascript:void(0)"
                  onclick="submenu_access('."'".$ls->id."'".','."'".$ls->role."'".')"><i class="fa fa-folder-open"></i></a> ';
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->datatables->count_all(),
      "recordsFiltered" => $this->datatables->count_filtered(),
      "data" => $data,
    ];
    echo json_encode($output);
  }





  public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('id', $id);
    $query = $this->db->get();
    return $query->row();
  }

  public function save($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($data, $where) {
    $this->db
    ->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function delete_by_id($id) {
    $this->db->where('id', $id);
    $this->db->delete($this->table);
  }

  public function get_all() {
    return $this->db->get($this->table);
  }

}