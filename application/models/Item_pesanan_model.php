<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_pesanan_model extends CI_Model {

  var $table = 'item_pesanan';

  // GWT ALL DATA
  public function get_all() {
    return $this->db->get($this->table);
  }

  // GET DATA TERTENTU
  public function get_where($where) {
    $this->db->from($this->table);
    $this->db->where($where);
    $query = $this->db->get();
    return $query;
  }

  // INSERT DATA
  public function insert($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

}