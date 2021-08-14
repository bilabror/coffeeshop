<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_poin_model extends CI_Model {

  var $table = 'produk_poin';
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }


  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [null,
      'nama_produk',
      'harga_produk',
      'status',
      null];;
    $this->datatables->column_search = ['nama_produk'];
    $this->datatables->order = ['nama_produk' => 'asc'];


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
      $row[] = '<img src="'.base_url("uploads/image/produk_poin/".$ls->gambar_produk).'" width="70px">';
      $row[] = $ls->nama_produk;
      $row[] = "{$ls->harga_produk} poin";
      $row[] = date('d-m-Y', strtotime($ls->tgl_buat));
      $ls->status == 1 ?
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="status('."'".$ls->id."'".','."'".$ls->status."'".')"> <span class="custom-switch-indicator"></span></label></tr>'
      : $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="status('."'".$ls->id."'".','."'".$ls->status."'".')"> <span class="custom-switch-indicator"></span></label></tr>';

      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
        onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
        onclick="delete_produk('."'".$ls->id."'".','."'".$ls->nama_produk."'".')"><i class="fa fa-trash"> </i></a>';

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



  public function get() {
    $query = $this->db->get($this->table);
    return $query->result();
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

  public function get_like($like) {
    $this->db->like($like);
    $query = $this->db->get($this->table);
    return $query->result();
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