<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_Model extends CI_Model {

  // DEKLARASI VARIABLES
  var $table = 'menu';
  var $column_order = ['urutan',
    'menu',
    'title',
    'icon',
    'tipe',
    null];
  var $column_search = ['menu'];
  var $order = ['urutan' => 'asc'];

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }


  private function _get_datatables_query() {
    $this->db->from($this->table);
    $i = 0;

    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item,
            $_POST['search']['value']);
        } else
        {
          $this->db->or_like($item,
            $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  // GET DATA
  function get_datatables() {
    $this->_get_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered() {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all() {
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  // GET BERDASARKAN ID
  public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('id', $id);
    $query = $this->db->get();
    return $query->row();
  }

  // INSERT DATA
  public function save($data) {
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
    return $this->db->get($this->table);
  }
  public function get_where($where) {
    return $this->db->get_where($this->table, $where);
  }

}