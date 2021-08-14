<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Home
* @property home_model|Model
*/

class Profile extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Produk_poin_model', 'p_poin');
    $this->load->model('Kategori_model', 'kategori');
    $this->load->model('Keranjang_model', 'keranjang');
    $this->load->model('Pesanan_model', 'pesanan');
    $this->load->model('Home_model', 'home');
  }


  public function index() {
    if (!$this->session->userdata('email')) {
      redirect(site_url());
    }
    if ($this->session->userdata('role_id') != 2) {
      redirect(site_url());
    }
    $data['orders'] = $this->pesanan->get_where(['id_user' => sud('id_user')])->result_array();
    $data['user'] = $this->db->get_where('user', ['email' =>
      $this->session->userdata('email')])->row_array();
    $email_tmp = $this->session->userdata('email');

    $data['title'] = 'Profile saya';
    pages_frontend('frontend/profile', $data);
  }

  public function edit() {
    if (!$this->session->userdata('email')) {
      redirect(site_url());
    }
    if ($this->session->userdata('role_id') != 2) {
      redirect(site_url());
    }
    $data['orders'] = $this->pesanan->get_where(['id_user' => sud('id_user')])->result_array();
    $data['user'] = $this->db->get_where('user', ['email' =>
      $this->session->userdata('email')])->row_array();
    $data['title'] = 'Edit Profile';
    pages_frontend('frontend/edit_profile', $data);

    if (isset($_POST['submit'])) {
      $username = htmlspecialchars($this->input->post('username'), true);
      $phone = htmlspecialchars($this->input->post('phone'), true);
      $this->db->update('user', ['username' => $username, 'phone' => $phone], ['email' => $this->session->userdata('email')]);

      redirect(site_url('profile'));
    }

  }





}