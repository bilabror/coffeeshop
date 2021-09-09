<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Poin
 *
 * Model ini berperan untuk berinteraksi dengan database table Poin User
 * 
 */
class Poin_model extends CI_Model {

  /**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'poin';

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
	 * Configurasi datatable
	 *
	 * @return	void
	 */
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
      'created_at'];
    $this->datatables->order = ['username' => 'asc'];
    $this->datatables->select = 'user.*,poin.*';
    $this->datatables->join = ['user',
      'poin.id_user = user.id'];
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

/**
	 * Get berdasarkan id
	 *
   * @param int $id kunci table
	 * @return ArrayObject
	 */
  public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('id_poin', $id);
    $query = $this->db->get();
    return $query->row();
  }

  /**
	 * Get berdasarkan sesuai yang diinginkan
	 *
   * @param array $where ambil data berdasarkan apa?
	 * @return ArrayObject
	 */
  public function get_where($where) {
    return $this->db->get_where($this->table, $where);
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
  public function update($where, $data) {
    $this->db
    ->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }


}