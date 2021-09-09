<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller General
 *
 * Controller ini berperan untuk mengatur bagian Setting Umum
 * 
 */
class General extends CI_Controller {

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
    $data['title'] = 'General Setting';
    pages('dashboard/pengaturan/general', $data);
    if (isset($_POST['submit'])) {
      $this->_update();

    }

  }

  /**
	 * aksi edit data
	 *
	 * @return json
	 */
  private function _update() {
    foreach ($_POST as $key => $val) {
      if ($key != 'logo_toko') $this->db->update('pengaturan', ['konten' => $val], ['key' => $key]);
    }

    $image = $_FILES['logo_toko']['name'];
    $old_image = $this->input->post('logo_toko_lama');
    $extension = pathinfo($_FILES['logo_toko']['name'], PATHINFO_EXTENSION);

    // KETIKA GAMBAR ADA YANG AKAN DIUPLOAD
    if (!empty($image)) {
      $config['upload_path'] = './uploads/image/';
      $config['allowed_types'] = 'jpeg|jpg|png';
      $config['max_size'] = '5048';
      $config['file_name'] = "logo_toko.{$extension}";
      $this->load->library('upload', $config);
      // KETIKA GAMBAR BERHASIL DIUPLOAD
      if ($this->upload->do_upload('logo_toko')) {
        $logo_toko = $config['file_name'];
        $this->db->update('pengaturan', ['konten' => $logo_toko], ['key' => 'logo_toko']);
        unlink(FCPATH."uploads/image/{$old_image}");
      } else {
        echo 'error';
      }
    } else {}
    $this->session->set_flashdata('success', 'Data Berhasil di update');
    redirect(site_url('dashboard/setting/general'));
  }



}