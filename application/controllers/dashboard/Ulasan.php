<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ulasan extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Ulasan_model', 'ulasan');
    proteksi();
  }

  public function index() {
    $data['ulasan'] = $this->ulasan->index();
    $data['title'] = 'Ulasan Pembeli';
    pages('dashboard/ulasan', $data);
  }


}