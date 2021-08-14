<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Poin
* @property home_model|Model
*/

class Poin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Produk_poin_model', 'p_poin');
  }



  public function index() {
    $data['produk'] = $this->p_poin->get_where(['status' => 1])->result();
    $data['poin'] = $this->db->get_where('poin', ['id_user' => sud('id_user')])->row_array()['poin'];
    $data['title'] = 'Poinku';
    pages_frontend('frontend/poin', $data);
  }


  public function tukar_poin() {
    $id = $this->input->post('id');
    $produk_poin = $this->db->get_where('produk_poin', ['id' => $id])->row_array();
    $poin = $this->db->get_where('poin', ['id_user' => sud('id_user')])->row_array();
    if ($poin['poin'] >= $produk_poin['harga_produk']) {
      $data = [
        'id_user' => sud('id_user'),
        'id_produk_poin' => $produk_poin['id'],
        'status' => 'pending',
        'tgl_buat' => dt()
      ];
      $this->db->insert('tukar_poin', $data);
      $update_poin = $poin['poin'] - $produk_poin['harga_produk'];
      $this->db->update('poin', ['poin' => $update_poin], ['id_user' => sud('id_user')]);
      echo json_encode(['status' => TRUE]);
    } else {
      echo json_encode(['status' => FALSE]);
    }

  }




}