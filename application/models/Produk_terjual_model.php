<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_terjual_model extends CI_Model {

  var $table = 'produk_terjual';

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

  public function insert($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($data, $where) {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

}