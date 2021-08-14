<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Poin_model', 'poin');
    proteksi();
  }




  public function index() {
    $data['role'] = $this->db->get('role')->result_array();
    $data['title'] = 'Data Poin User';
    pages('dashboard/master/poin', $data);
  }


  public function get_datatables() {
    $this->poin->get_datatables();
  }



  public function ajax_edit($id) {
    $data = $this->poin->get_by_id($id);
    echo json_encode($data);
  }
  public function ajax_update() {

    $this->form_validation->set_rules('poin', 'poin', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $err = [
        'poin' => form_error('poin')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {

      $data = array(
        'poin' => htmlspecialchars($this->input->post('poin'), true)
      );
      $this->poin->update(array('id_poin' => $this->input->post('id_poin')), $data);
      echo json_encode(array("status" => TRUE));
    }


  }



}