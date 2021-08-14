<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekomendasi extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('Kategori_model', 'kategori');
    $this->load->model('produk_rekomendasi_model', 'produk_rekomendasi');
    proteksi();
  }


  // HALAMAN DATA PRODUK
  public function index() {
    $data['title'] = 'Produk Rekomendasi';
    pages('dashboard/produk/rekomendasi_produk', $data);
  }


  // INSERT PRODUK
  public function insert() {
    // DEKLARASI VARIABELS
    $id_produk = $this->input->post('id_produk');
    $this->db->select_max('urutan');
    $urutan = $this->db->get('produk_rekomendasi')->row_array()['urutan'];
    if ($urutan) {
      $data = [
        'id_produk' => $id_produk,
        'urutan' => $urutan
      ];
    } else {
      $data = [
        'id_produk' => $id_produk,
        'urutan' => 1
      ];
    }
    $this->form_validation->set_rules('id_produk', 'Id Produk', 'is_unique[produk_rekomendasi.id_produk]');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode(['status' => FALSE, 'error' => 'Produk Sudah Ada direkomendasi']);
    } else {
      $this->db->insert('produk_rekomendasi', $data);
      echo json_encode(['status' => TRUE]);

    }


  }


  // HAPUS DATA PRODUK
  public function ajax_delete($id) {
    $this->produk_rekomendasi->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }

  // GET DATA PRODUK DENGAN DATATABLES
  public function get_datatables() {
    $this->produk_rekomendasi->get_datatables();
  }

  public function get_produk() {
    $this->produk_rekomendasi->get_produk();
  }



  // GET DATA PRODUK UNTUK HALAMAN EDIT
  public function ajax_edit($id) {
    $data = $this->produk->get_by_id($id);
    echo json_encode($data);
  }


  // GET DATA KATEGORI
  public function kategori_ajax() {
    $kategori = $this->kategori->get();
    echo json_encode($kategori);
  }




}