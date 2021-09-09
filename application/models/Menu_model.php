<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Menu
 *
 * Model ini berperan untuk berinteraksi dengan database table Menu
 * 
 */
class Menu_Model extends CI_Model {

  /**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'menu';

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
    $this->datatables->column_order = ['urutan',
      'menu',
      'title',
      'icon',
      'tipe',
      'status',
      null];
    $this->datatables->column_search = ['menu'];
    $this->datatables->order = ['urutan' => 'asc'];
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
      $row[] = $ls->urutan;
      $row[] = $ls->menu;
      $row[] = $ls->title;
      $row[] = '<i class="'.$ls->icon.'"></i>';
      if ($ls->tipe == 1) {
        $row[] = 'biasa';
      } else {
        $row[] = 'dropdown';
      }
      $ls->is_active > 0 ?
      $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="status('."'".$ls->id."'".')"> <span class="custom-switch-indicator"></span></label></tr>'
      : $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="status('."'".$ls->id."'".')"> <span class="custom-switch-indicator"></span></label></tr>';
      $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit" onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
                  onclick="delete_menu('."'".$ls->id."'".','."'".$ls->menu."'".')"><i class="fa fa-trash"></i></a><a href="javascript:void(0)" class="m-2" onclick="naikan('."'".$ls->id."'".','."'".$ls->urutan."'".')"><i class="fa fa-sort-up"></i></a> <a href="javascript:void(0)" class="m-2" onclick="turunkan('."'".$ls->id."'".','."'".$ls->urutan."'".')"><i class="fa fa-sort-down"></i></a>';
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
  public function insert($data) {
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
    $this->db->update($this->table, $data, $where);
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
    return $this->db->get($this->table)->result_array();
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

}