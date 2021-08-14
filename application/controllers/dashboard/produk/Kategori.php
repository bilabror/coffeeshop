<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Kategori_model', 'kategori');
    proteksi();
  }

  //DASHBOARD/MASTERkategori/
  public function index() {
    $data['title'] = 'Kategori Produk';
    pages('dashboard/produk/kategori_produk', $data);
  }



  public function get_datatables() {
    $this->kategori->get_datatables();
  }


  public function ajax_edit($id) {
    $data = $this->kategori->get_by_id($id);
    echo json_encode($data);
  }
  public function ajax_add() {

    $this->form_validation->set_rules('nama_kategori', 'Ini', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('slug_kategori', 'Slug categories', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_kategori' => form_error('nama_kategori')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {

      $data = array(
        'nama_kategori' => htmlspecialchars($this->input->post('nama_kategori'), true),
        'slug_kategori' => htmlspecialchars($this->input->post('slug_kategori'), true)

      );

      $insert = $this->kategori->save($data);
      echo json_encode(array("status" => TRUE));
    }






  }
  public function ajax_update() {

    $this->form_validation->set_rules('nama_kategori', 'Ini', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('slug_kategori', 'Slug kategori', 'trim|required');


    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_kategori' => form_error('nama_kategori')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {

      $data = array(
        'nama_kategori' => htmlspecialchars($this->input->post('nama_kategori'), true),
        'slug_kategori' => htmlspecialchars($this->input->post('slug_kategori'), true)

      );
      $this->kategori->update(array('id_kategori' => $this->input->post('id_kategori')), $data);
      echo json_encode(array("status" => TRUE));
    }


  }
  public function ajax_delete($id) {
    $this->kategori->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }





}