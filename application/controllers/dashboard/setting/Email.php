<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Email
 *
 * Controller ini berperan untuk mengatur bagian Setting Email Verifikasi
 * 
 */
class Email extends CI_Controller {

   /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    proteksi();
  }

  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['title'] = 'Pengaturan Email Config';
    pages('dashboard/pengaturan/email', $data);
    if (isset($_POST['submit'])) {
      foreach ($_POST as $key => $val) {
        $this->db->update('pengaturan', ['konten' => $val], ['key' => $key]);
      }
      $this->session->set_flashdata('success', 'Data Berhasil di update');
      redirect(site_url('dashboard/setting/email'));
    }

  }



}