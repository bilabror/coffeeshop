<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Produk
 *
 * Model ini berperan untuk berinteraksi dengan database table Produk Utama
 * 
 */
class Produk_model extends CI_Model {

/**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'produk';

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
      $row[] = $ls->nama_kategori;
      $row[] = rupiah($ls->harga_produk);
      $row[] = $ls->stok_produk;

      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
             onclick="edit('."'".$ls->id_produk."'".')"><i class="fa fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_produk('."'".$ls->id_produk."'".','."'".$ls->nama_produk."'".')"><i class="fa fa-trash"></i></a><a class="m-2" href="javascript:void(0)" title="Detail"
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
	 * Get berdasarkan slug
	 *
   * @param string $slug slug produk
	 * @return ArrayObject
	 */
  public function getBySlug($slug){
    $this->db->from($this->table);
    $this->db->where('slug_produk', $slug);
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
    $this->db->where('id_produk', $id);
    $this->db->delete($this->table);
  }

  /**
	 * Get kebutuhan di halaman detail produk
	 *
   * @param string $slug slug produk
	 * @return array
	 */
  public function detailPage($slug){
    $produk = $this->getBySlug($slug);
    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('pesanan', 'item_pesanan.id_pesanan = pesanan.id_pesanan');
    $this->db->limit(5);
    $this->db->order_by('tgl_bayar_pesanan', 'desc');
    $this->db->where('id_produk', $produk->id_produk);
    $pesanan = $this->db->get()->result();

    $this->db->select('*');
    $this->db->from('ulasan_produk');
    $this->db->join('user', 'ulasan_produk.id_user = user.id');
    $this->db->where('id_produk', $produk->id_produk);
    $ulasan = $this->db->get()->result();

    return [
      'produk' => $produk,
      'pesanan' => $pesanan,
      'ulasan' => $ulasan
    ];
  }

}