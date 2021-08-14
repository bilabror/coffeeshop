<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mycart {

  protected $table = 'keranjang';
  protected $primary_key = 'id_keranjang';

  protected $CI;


  public function __construct() {
    $this->CI =& get_instance();
  }

  // --------------------------------------------------------------------

  /**
  * Insert items into the cart and save it to the session table
  *
  * @param	array
  * @return	bool
  */
  public function add($data) {

    $this->CI->db->where('id_user', $data['id_user']);
    $this->CI->db->where('id_produk', $data['id_produk']);
    $produk_in_cart = $this->CI->db->get($this->table);

    if ($produk_in_cart->num_rows() < 0) {
      $this->_insert($data);
    } else {
      $this->_update($data, $produk_in_cart->row_array()['id_keranjang']);
    }

  }


  private function _insert($data) {
    $this->CI->db->insert($this->table, $data);
    return TRUE;
  }


  private function _update($data, $id_keranjang) {
    $this->CI->db->set($data);
    $this->CI->db->where($this->primary_key, $id_keranjang);
    $this->CI->db->update($this->table);
    return TRUE;
  }



}