<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Produk Rekomendasi
 *
 * Model ini berperan untuk berinteraksi dengan database table Produk Rekomendasi
 * 
 */
class Produk_rekomendasi_model extends CI_Model {

  /**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'produk_rekomendasi';

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }

  /**
	 * Configurasi databale
	 *
	 * @return	void
	 */
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

  /**
	 * Get Datatable
	 *
	 * @return json
	 */
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

/**
	 * Configurasi databale Data Produk
	 *
	 * @return	void
	 */
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

/**
	 * Get data Produk
	 *
	 * @return	json
	 */
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

 /**
	 * Get All
	 *
	 * @return ArrayObject
	 */
  public function get() {
    $this->db->select('produk.*,kategori.nama_kategori');
    $this->db->from($this->table);
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori');
    $query = $this->db->get();
    return $query->result();
  }

  /**
	 * Get berdasarkan id
	 *
   * @param int $id kunci table
	 * @return ArrayObject
	 */
  public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('id_produk', $id);
    $query = $this->db->get();
    return $query->row();
  }

  /**
	 * Get berdasarkan sesuai karakter yang diinginkan
	 *
   * @param string $like karakter yang dicari
	 * @return ArrayObject
	 */
  public function get_like($like) {
    $this->db->select('produk.*,kategori.nama_kategori');
    $this->db->from($this->table);
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori');
    $this->db->like($like);
    $query = $this->db->get();
    return $query->result();
  }

  /**
	 * Get berdasarkan sesuai yang diinginkan
	 *
   * @param array $where ambil data berdasarkan apa?
	 * @return ArrayObject
	 */
  public function get_where($where) {
    $this->db->select('produk.*,kategori.nama_kategori');
    $this->db->from($this->table);
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori');
    $this->db->where($where);
    $query = $this->db->get();
    return $query;
  }

  /**
	 * Get All
	 *
	 * @return ArrayObject
	 */
  public function get_all() {
    return $this->db->get($this->table);
  }

  /**
	 * Aksi tambah data
	 *
   * @param array $data Data yang akan ditambahkan
	 * @return int
	 */
  public function save($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  /**
	 * Aksi ubah data
	 *
   * @param array $data Data yang akan diedit
   * @param array $where ubah data berdasarkan apa?
	 * @return int
	 */
  public function update($data, $where) {
    $this->db
    ->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }
  
/**
	 * Aksi hapus data
	 *
   * @param int $id kunci table
	 * @return boolean
	 */
  public function delete_by_id($id) {
    $this->db->where('id', $id);
    $this->db->delete($this->table);
  }

}