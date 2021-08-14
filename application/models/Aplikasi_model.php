<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aplikasi_model extends CI_Model {

  var $table = 'aplikasi';

  public function get_data() {
    return $this->db->get($this->table)->result();
  }

  public function update($data, $where) {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

}