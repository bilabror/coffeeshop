<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

  var $table = 'user';
  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }


  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [null,
      'username',
      'email',
      'avatar',
      'role',
      'created_at',
      'is_active',
      null];
    $this->datatables->column_search = ['username',
      'email',
      'role',
      'created_at'];
    $this->datatables->order = ['username' => 'asc'];


    $this->datatables->select = 'user.*,role.role';
    $this->datatables->join = ['role',
      'user.role_id = role.id'];
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
      $row[] = '<img src="'.base_url("uploads/image/profile/".$ls->avatar).'" width="70px">';
      $row[] = $ls->username;
      $row[] = $ls->email;
      $row[] = $ls->role;
      if ($ls->email != $this->session->userdata('email')) {
        $ls->is_active == 1 ?
        $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="status('."'".$ls->id."'".','."'".$ls->is_active."'".')"> <span class="custom-switch-indicator"></span></label></tr>'
        : $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="status('."'".$ls->id."'".','."'".$ls->is_active."'".')"> <span class="custom-switch-indicator"></span></label></tr>';

        $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
        onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
        onclick="delete_user('."'".$ls->id."'".','."'".$ls->username."'".')"><i class="fa fa-trash"> </i></a><a class="m-2" href="javascript:void(0)" title="detail"
        onclick="detail_user('."'".$ls->id."'".')"><i class="fa fa-eye"></i></a>';
      } else {
        $row[] = '<td><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input"> <span class="custom-switch-indicator"></span></tr>';
        $row[] = '<span class="badge badge-success">SAYA</span>';
      }
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

  public function get_where($where) {
    return $this->db->get_where($this->table, $where);
  }

  public function save($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($where, $data) {
    $this->db
    ->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function delete_by_id($id) {
    $this->db->where('id', $id);
    $this->db->delete($this->table);
  }

}