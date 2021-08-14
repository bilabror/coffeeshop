<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir_Model extends CI_Model {

  var $table = 'role';
  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }


  private function _config_datatables() {
    $this->datatables->table = 'produk';

    $this->datatables->column_order = [null,
      'gambar_produk',
      'nama_produk',
      'nama_kategori',
      'harga_produk',
      'stok_produk',
      null];

    $this->datatables->column_search = ['nama_produk'];
    $this->datatables->order = ['nama_produk' => 'asc'];
    $this->datatables->select = 'produk.*,kategori.nama_kategori';
    $this->datatables->join = ['kategori',
      'produk.id_kategori = kategori.id_kategori'];
  }

  public function get_datatables() {
    $this->_config_datatables();
    $list = $this->datatables->get_datatables();
    $data = [];
    $no = $_POST['start'];
    foreach ($list as $ls) {
      $no++;
      $row = [];
      $row[] = '<img src="'.base_url('uploads/image/produk/').$ls->gambar_produk.'" alt="gambar '.$ls->nama_produk.'" class="img-thumbnail" width="100" />';
      $row[] = $ls->nama_produk;
      $row[] = $ls->nama_kategori;
      $row[] = $ls->harga_produk;
      $row[] = $ls->stok_produk;

      $row[] = '<button class="btn btn-primary" href="javascript:void(0)" title="Edit" onclick="add_produk('."'".$ls->id_produk."'".','."'".$ls->nama_produk."'".','."'".$ls->total_harga_produk."'".')"><i class="fa fa-plus"></i></button>';
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