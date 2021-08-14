<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ulasan_model extends CI_Model {

  var $table = 'ulasan_produk';

  public function index() {
    $this->db->select('username,rating,komentar,ulasan_produk.tgl_buat,nama_produk');
    $this->db->from('ulasan_produk');
    $this->db->join('user', 'ulasan_produk.id_user = user.id');
    $this->db->join('produk', 'ulasan_produk.id_produk = produk.id_produk');
    $this->db->order_by('ulasan_produk.tgl_buat', 'desc');
    return $this->db->get()->result_array();
  }

}