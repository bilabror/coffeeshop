<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Slider
 *
 * Controller ini berperan untuk mengatur bagian Setting slider
 * 
 */
class Slider extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('Slider_model', 'slider');
    proteksi();
  }

  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['title'] = 'Setting Slider';
    pages('dashboard/pengaturan/slider', $data);
  }

  /**
	 * Get data dengan style datatable
	 *
	 * @return json
	 */
  public function get_datatables() {
    $this->slider->get_datatables();
  }


  /**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->slider->get_by_id($id);
    echo json_encode($data);
  }

  /**
	 * aksi tambah data
	 *
	 * @return json
	 */
  public function ajax_add() {
    // DEKLARASI VARIABELS
    $judul = htmlspecialchars($this->input->post('judul'), TRUE);

    // SET VALIDASI FORM
    $this->form_validation->set_rules('judul', 'Judul', 'trim|required|min_length[2]',
      [
        'required' => 'Judul slider harus diisi!',
        'min_length' => 'Judul slider terlalu pendek!'
      ]);

    // KETIKA TIDAK LOLOS VALIDASI
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'judul' => form_error('judul')
      ];
      echo json_encode(["status" => false, 'err' => $err]);
    }
    // KETIKA LOLOS VALIDASI
    else
    {
      //DEKLARASI VARIABLES
      $image = $_FILES['gambar']['name'];
      $extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);

      // KETIKA GAMBR ADA YANG AKAN DIUPLOAD
      if (!empty($image)) {
        $config['upload_path'] = './uploads/image/slider/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif';
        $config['max_size'] = '5048';
        $config['file_name'] = 'slider_'.time().".{$extension}";
        $this->load->library('upload', $config);
        // KETIKA GAMBAR BERHASIL DIUPLOAD
        if ($this->upload->do_upload('gambar')) {
          $data = [
            'judul' => $judul,
            'gambar' => $config['file_name']
          ];
          // INSERT KE DATABASE
          $insert = $this->slider->insert($data);
          echo json_encode(array("status" => TRUE));
        } else {
          // KETIKA GAMBAR TIDAK LOLOS VALIDASI
          $image_error = array('gambar' => $this->upload->display_errors());
          echo json_encode(["status" => false, 'err' => $image_error]);
        }
      } else {
        //KETIKA GAMBAR TIDAK ADA YANG DIUPLOAD
        $image_error = array('gambar' => 'Gambar Belum diatur');
        echo json_encode(["status" => false, 'err' => $image_error]);
      }

    }

  }

/**
	 * aksi edit data
	 *
	 * @return json
	 */
  public function ajax_update() {
    // DEKLARASI VARIABELS
    $judul = htmlspecialchars($this->input->post('judul'), TRUE);
    $id = $this->input->post('id');
    // SET VALIDASI FORM
    $this->form_validation->set_rules('judul', 'Judul', 'trim|required|min_length[2]',
      [
        'required' => 'Judul slider harus diisi!',
        'min_length' => 'Judul slider terlalu pendek!'
      ]);

    // KETIKA TIDAK LOLOS VALIDASI
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'judul' => form_error('judul')
      ];
      echo json_encode(["status" => false, 'err' => $err]);
    }
    // KETIKA LOLOS VALIDASI
    else
    {

      $data = [
        'judul' => $judul
      ];

      $image = $_FILES['gambar']['name'];
      $extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);

      // KETIKA GAMBAR ADA YANG AKAN DIUPLOAD
      if (!empty($image)) {
        $config['upload_path'] = './uploads/image/slider/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif';
        $config['max_size'] = '5048';
        $config['file_name'] = 'slider_'.time().".{$extension}";
        $this->load->library('upload', $config);
        // KETIKA GAMBAR BERHASIL DIUPLOAD
        if ($this->upload->do_upload('gambar')) {
          $data['gambar'] = $config['file_name'];
          $old_image = $this->db->get_where('slider', ['id' => $id])->row_array()['gambar'];
          // KETIKA GAMBAR LAMA BUKAN GAMBAR DEFAULT
          if ($old_image != 'default_produk.jpg') {
            // KETIKA GAMBAR LAMA ADA DIPENYIMPANAN
            $cek = file_exists(FCPATH."uploads/image/slider/{$old_image}");
            if ($cek) {
              // MENGHAPUS GAMBAR LAMA
              unlink(FCPATH."uploads/image/slider/{$old_image}");
            }
          }
        } else {
          //KETIKA GAMBAR TIDAK LOLOS VALIDASI
          $image_error = array('gambar' => $this->upload->display_errors());
          echo json_encode(["status" => false, 'err' => $image_error]);
        }
      } else {}

      // UPDATE DATA PRODUK KE DATABASE
      $this->slider->update($data, ['id' => $id]);
      echo json_encode(array("status" => TRUE));

    }

  }

/**
	 * Aksi hapus data
	 *
   * @param int $id kunci table
	 * @return boolean
	 */ 
  public function ajax_delete($id) {
    $gambar = $this->slider->get_by_id($id)->gambar;
    unlink(FCPATH."uploads/image/slider/{$gambar}");
    $this->slider->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }

}