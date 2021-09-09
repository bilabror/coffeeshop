<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Role
 *
 * Model ini berperan untuk berinteraksi dengan database table Role
 * 
 */
class Role_Model extends CI_Model {

  /**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'role';
  
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
      'role',
      null];
    $this->datatables->column_search = ['role'];
    $this->datatables->order = ['role' => 'asc'];
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
      $row[] = $ls->role;

      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
             onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a> <a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_role('."'".$ls->id."'".','."'".$ls->role."'".')"><i class="fa fa-trash"></i></a> <a class="m-2" href="javascript:void(0)"
                  onclick="menu_access('."'".$ls->id."'".','."'".$ls->role."'".')"><i class="fa fa-folder"></i></a> <a class="m-2" href="javascript:void(0)"
                  onclick="submenu_access('."'".$ls->id."'".','."'".$ls->role."'".')"><i class="fa fa-folder-open"></i></a> ';
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
    $this->db->where('id', $id);
    $query = $this->db->get();
    return $query->row();
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

   /**
	 * Get All
	 *  
	 * @return ArrayObject
	 */
  public function get_all() {
    return $this->db->get($this->table);
  }

}