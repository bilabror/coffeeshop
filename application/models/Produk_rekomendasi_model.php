<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_rekomendasi_model extends CI_Model {

  var $table = 'produk_rekomendasi';
  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }


  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [
      'gambar_produk',
      'nama_produk',
      'stok_produk',
      null];
    $this->datatables->column_search = ['nama_produk'];
    $this->datatables->order = ['urutan' => 'asc'];


    $this->datatables->select = 'urutan,nama_produk,gambar_produk,stok_produk,produk.id_produk,slug_produk,id';
    $this->datatables->join = ['produk',
      'produk_rekomendasi.id_produk = produk.id_produk'];

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
      $row[] = $ls->stok_produk;

      $row[] = '<a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_produk('."'".$ls->id."'".','."'".$ls->nama_produk."'".')"><i class="fa fa-trash"></i></a><a class="m-2" href="javascript:void(0)" title="Detail"
             onclick="detail('."'".$ls->slug_produk."'".')"><i class="fa fa-eye"></i></a>';
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


  private function _config_datatables_produk() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = [
      'gambar_produk',
      'nama_produk',
      null];
    $this->datatables->column_search = ['nama_produk'];
    $this->datatables->order = ['nama_produk' => 'asc'];


    $this->datatables->select = 'nama_produk,gambar_produk,produk.id_produk,stok_produk,produk.id_produk';
    $this->datatables->join = ['produk',
      'produk_rekomendasi.id_produk = produk.id_produk',
      'right'];

  }


  public function get_produk() {
    $this->_config_datatables_produk();
    $list = $this->datatables->get_datatables();
    $data = [];
    $no = $_POST['start'];
    foreach ($list as $ls) {
      $no++;
      $row = [];
      $row[] = '<img src="'.base_url('uploads/image/produk/').$ls->gambar_produk.'" alt="gambar '.$ls->nama_produk.'" class="img-thumbnail" width="100" />';
      $row[] = $ls->nama_produk;
      $row[] = $ls->stok_produk;
      $row[] = '<a class="m-2" href="javascript:void(0)" title="tambah"
                  onclick="add_produk('.$ls->id_produk.')"><i class="fa fa-plus"></i></a>';
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

    $this->db->select('produk.*,kategori.nama_kategori');
    $this->db->from($this->table);
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori');
    $query = $this->db->get();


    return $query->result();
  }
  public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('id_produk', $id);
    $query = $this->db->get();
    return $query->row();
  }
  public function get_like($like) {
    $this->db->select('produk.*,kategori.nama_kategori');
    $this->db->from($this->table);
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori');
    $this->db->like($like);
    $query = $this->db->get();
    return $query->result();
  }
  public function get_where($where) {
    $this->db->select('produk.*,kategori.nama_kategori');
    $this->db->from($this->table);
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori');
    $this->db->where($where);
    $query = $this->db->get();
    return $query;
  }

  public function get_all() {
    return $this->db->get($this->table);
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

}