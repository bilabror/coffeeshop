<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submenu_model extends CI_Model {

  var $table = 'sub_menu';

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }



  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [null,
      'menu',
      'title',
      'icon',
      'url',
      'is_active',
      null
    ];
    $this->datatables->column_search = ['title'];
    $this->datatables->order = ['menu' => 'asc'];



    $this->datatables->select = 'sub_menu.*,menu.menu';
    $this->datatables->join = ['menu',
      'sub_menu.menu_id = menu.id'];

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
      $row[] = $ls->menu;
      $row[] = $ls->title;
      $row[] = '<i class="'.$ls->icon.'"></i>';
      $row[] = $ls->url;
      $ls->is_active > 0 ?
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="status('."'".$ls->id."'".')"> <span class="custom-switch-indicator"></span></label></tr>'
      : $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="status('."'".$ls->id."'".')"> <span class="custom-switch-indicator"></span></label></tr>';
      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
             onclick="edit('."'".$ls->id."'".')"><i class="fas fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_submenu('."'".$ls->id."'".','."'".$ls->title."'".')"><i class="fas fa-trash"></i></a>';
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


  // GET BERDASARKAN ID
  public function get_by_id($id) {
    return $this->db->get_where($this->table, ['id' => $id])->row();
  }


  public function save($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }



  public function update($data, $where) {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }


  public function delete_by_id($id) {
    $this->db->where('id', $id);
    $this->db->delete($this->table);
  }

  public function get_where($where) {
    return $this->db->get_where($this->table, $where);
  }


}