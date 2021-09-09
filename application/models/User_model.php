<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model User
 *
 * Model ini berperan untuk berinteraksi dengan database table User
 * 
 */
class User_model extends CI_Model {
/**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'user';

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
      'role',
      'created_at'];
    $this->datatables->order = ['username' => 'asc'];


    $this->datatables->select = 'user.*,role.role';
    $this->datatables->join = ['role',
      'user.role_id = role.id'];
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
      $row[] = '<img src="'.base_url("uploads/image/profile/".$ls->avatar).'" width="70px">';
      $row[] = $ls->username;
      $row[] = $ls->email;
      $row[] = $ls->role;
      if ($ls->email != $this->session->userdata('email')) {
        $ls->is_active == 1 ?
        $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input" onclick="status('."'".$ls->id."'".','."'".$ls->is_active."'".')"> <span class="custom-switch-indicator"></span></label></tr>'
        : $row[] = '<td><label><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" onclick="status('."'".$ls->id."'".','."'".$ls->is_active."'".')"> <span class="custom-switch-indicator"></span></label></tr>';

        $row[] = '<a class="m-2" href="javascript:void(0)" title="Edit"
        onclick="edit('."'".$ls->id."'".')"><i class="fa fa-edit"></i></a><a class="m-2" href="javascript:void(0)" title="Hapus"
        onclick="delete_user('."'".$ls->id."'".','."'".$ls->username."'".')"><i class="fa fa-trash"> </i></a><a class="m-2" href="javascript:void(0)" title="detail"
        onclick="detail_user('."'".$ls->id."'".')"><i class="fa fa-eye"></i></a>';
      } else {
        $row[] = '<td><input type="checkbox" name="custom-switch-checkbox" checked="checked" class="custom-switch-input"> <span class="custom-switch-indicator"></span></tr>';
        $row[] = '<span class="badge badge-success">SAYA</span>';
      }
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