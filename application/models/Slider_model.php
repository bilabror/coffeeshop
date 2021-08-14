<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_Model extends CI_Model {

  var $table = 'slider';

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }

  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [null,
      'gambar',
      'judul',
      null];
    $this->datatables->column_search = ['judul'];
    $this->datatables->order = ['id' => 'asc'];
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
      $row[] = '<img src="'.base_url('uploads/image/slider/').$ls->gambar.'" alt="gambar '.$ls->judul.'" class="img-thumbnail" width="100" />';
      $row[] = $ls->judul;
      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit" onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_slider('."'".$ls->id."'".')"><i class="fa fa-trash"></i></a>';
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
    $this->db->from($this->table);
    $this->db->where('id', $id);
    $query = $this->db->get();
    return $query->row();
  }

  // INSERT DATA
  public function insert($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  // UPDATE DATA
  public function update($data, $where) {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  // DELETE DATA
  public function delete_by_id($id) {
    $this->db->where('id', $id);
    $this->db->delete($this->table);
  }

  public function get_all() {
    return $this->db->get($this->table)->result_array();
  }

  public function get_where($where) {
    return $this->db->get_where($this->table, $where);
  }

}