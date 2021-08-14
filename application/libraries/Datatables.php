<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class datatables {

  public $global_data;

  public $table;

  public $column_order;

  public $column_search;

  public $order;

  public $select;

  public $join;

  public $where;

  protected $CI;

  public function __construct() {
    $this->CI = & get_instance();
    $this->CI->load->database();
  }

  private function _query() {
    if ($this->join != '') {
      if (!empty($this->join[2])) {
        $data = [
          $this->select != '' ? $this->CI->db->select($this->select) : '',
          $this->CI->db->from($this->table),
          $this->join != '' ? $this->CI->db->join($this->join[0], $this->join[1], $this->join[2]) : '',
          $this->where != '' ? $this->CI->db->where($this->where) : ''
        ];
      } else {
        $data = [
          $this->select != '' ? $this->CI->db->select($this->select) : '*',
          $this->CI->db->from($this->table),
          $this->join != '' ? $this->CI->db->join($this->join[0], $this->join[1]) : '',
          $this->where != '' ? $this->CI->db->where($this->where) : ''
        ];
      }
    } else {
      $data = [
        $this->select != '' ? $this->CI->db->select($this->select) : '*',
        $this->CI->db->from($this->table),
        $this->join != '' ? $this->CI->db->join($this->join[0], $this->join[1]) : '',
        $this->where != '' ? $this->CI->db->where($this->where) : ''
      ];
    }


  }

  public function _get_datatables_query() {
    $this->_query();
    $i = 0;
    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->CI->db->group_start();
          $this->CI->db->like($item,
            $_POST['search']['value']);
        } else
        {
          $this->CI->db->or_like($item,
            $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->CI->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->CI->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->CI->db->order_by(key($order), $order[key($order)]);
    }
  }

  // GET DATA
  public function get_datatables() {
    $this->_get_datatables_query();
    if ($_POST['length'] != -1)
      $this->CI->db->limit($_POST['length'], $_POST['start']);
    $query = $this->CI->db->get();
    return $query->result();
  }

  public function count_filtered() {
    $this->_get_datatables_query();
    $query = $this->CI->db->get();
    return $query->num_rows();
  }

  public function count_all() {
    $this->CI->db->from($this->table);
    return $this->CI->db->count_all_results();
  }

  // GET BERDASARKAN ID
  public function get_by_id($id) {
    $this->CI->db->from($this->table);
    $this->CI->db->where('id', $id);
    $query = $this->CI->db->get();
    return $query->row();
  }

  // INSERT DATA
  public function save($data) {
    $this->CI->db->insert($this->table, $data);
    return $this->CI->db->insert_id();
  }

  // UPDATE DATA
  public function update($data, $where) {
    $this->CI->db->update($this->table, $data, $where);
    return $this->CI->db->affected_rows();
  }

  // DELETE DATA
  public function delete_by_id($id) {
    $this->CI->db->where('id', $id);
    $this->CI->db->delete($this->table);
  }





}