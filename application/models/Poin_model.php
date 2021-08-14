<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poin_model extends CI_Model {

  var $table = 'poin';
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


    $this->datatables->select = 'user.*,poin.*';
    $this->datatables->join = ['user',
      'poin.id_user = user.id'];
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
      $row[] = $ls->username;
      $row[] = $ls->email;
      $row[] = $ls->poin;
      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
             onclick="edit('."'".$ls->id_poin."'".')"><i class="fa fa-edit"></i></a>';
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
    $this->db->where('id_poin', $id);
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


}