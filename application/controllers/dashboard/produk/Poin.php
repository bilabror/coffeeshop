<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('User_model', 'user');
    $this->load->model('Produk_poin_model', 'p_poin');
    proteksi();
  }




  public function index() {
    $data['title'] = 'Produk Poin';
    pages('dashboard/produk/produk_poin', $data);
  }



  public function status() {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    if ($status == 1) {
      $status = 0;
      $this->p_poin->update(['id' => $id], ['status' => $status]);
      echo json_encode(array("status" => TRUE));
    } else
    {
      $status = 1;
      $this->p_poin->update(['id' => $id], ['status' => $status]);
      echo json_encode(array("status" => TRUE));
    }

  }


  public function get_datatables() {
    $this->p_poin->get_datatables();
  }

  public function add() {
    $data['title'] = 'Menambahkan Produk Penukaran Poin';
    pages('dashboard/produk/add_produk_poin', $data);
  }


  // HALAMAN EDIT PRODUK
  public function edit($id) {
    $data['id'] = $id;
    $data['gambar_produk'] = $this->db->query("SELECT gambar_produk FROM produk_poin WHERE id = {$id}")->row_array()['gambar_produk'];
    $data['title'] = 'Edit Data Produk Penukaran Poin';
    pages('dashboard/produk/edit_produk_poin', $data);
  }


  // GET DATA PRODUK UNTUK HALAMAN EDIT
  public function ajax_edit($id) {
    $data = $this->p_poin->get_by_id($id);
    echo json_encode($data);
  }

  // INSERT PRODUK
  public function ajax_add() {
    // DEKLARASI VARIABELS
    $nama_produk = htmlspecialchars($this->input->post('nama_produk'), true);
    $berat_produk = htmlspecialchars($this->input->post('berat_produk'), true);
    $harga_produk = htmlspecialchars($this->input->post('harga_produk'), true);
    $deskripsi_produk = htmlspecialchars($this->input->post('deskripsi_produk'), true);

    // SET VALIDASI FORM
    $this->form_validation->set_rules('nama_produk', 'Nama_produk', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('harga_produk', 'harga produk', 'trim|required');
    $this->form_validation->set_rules('berat_produk', 'berat produk', 'trim|required');
    $this->form_validation->set_rules('deskripsi_produk', 'deskripsi produk', 'trim|required|min_length[10]');

    // KETIKA TIDAK LOLOS VALIDASI
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_produk' => form_error('nama_produk'),
        'harga_produk' => form_error('harga_produk'),
        'berat_produk' => form_error('berat_produk'),
        'deskripsi_produk' => form_error('deskripsi_produk')
      ];
      echo json_encode(["status" => false, 'err' => $err]);
    }
    // KETIKA LOLOS VALIDASI
    else
    {
      //DEKLARASI VARIABLES
      $image = $_FILES['gambar_produk']['name'];
      $extension = pathinfo($_FILES['gambar_produk']['name'], PATHINFO_EXTENSION);

      // KETIKA GAMBR ADA YANG AKAN DIUPLOAD
      if (!empty($image)) {
        $config['upload_path'] = './uploads/image/produk_poin/';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['max_size'] = '5048';
        $config['file_name'] = 'produk_'.time().".{$extension}";
        $this->load->library('upload', $config);
        // KETIKA GAMBAR BERHASIL DIUPLOAD
        if ($this->upload->do_upload('gambar_produk')) {
          $data = [
            'nama_produk' => $nama_produk,
            'deskripsi_produk' => $deskripsi_produk,
            'harga_produk' => $harga_produk,
            'berat_produk' => $berat_produk,
            'gambar_produk' => $config['file_name'],
            'status' => 1,
            'tgl_buat' => dt()
          ];
          // INSERT KE DATABASE
          $this->p_poin->save($data);
          echo json_encode(array("status" => TRUE));
        } else {
          // KETIKA GAMBAR TIDAK LOLOS VALIDASI
          $image_error = array('gambar_produk' => $this->upload->display_errors());
          echo json_encode(["status" => false, 'err' => $image_error]);
        }
      } else {
        //KETIKA GAMBAR TIDAK ADA YANG DIUPLOAD
        $image_error = array('gambar_produk' => 'Gambar Produk Masih Kosong');
        echo json_encode(["status" => false, 'err' => $image_error]);
      }

    }

  }


  public function ajax_update() {
    $id = $this->input->post('id');
    $nama_produk = htmlspecialchars($this->input->post('nama_produk'), true);
    $berat_produk = htmlspecialchars($this->input->post('berat_produk'), true);
    $harga_produk = htmlspecialchars($this->input->post('harga_produk'), true);
    $deskripsi_produk = htmlspecialchars($this->input->post('deskripsi_produk'), true);


    // SET VALIDASI FORM
    $this->form_validation->set_rules('nama_produk', 'Nama_produk', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('harga_produk', 'harga produk', 'trim|required');
    $this->form_validation->set_rules('berat_produk', 'berat produk', 'trim|required');
    $this->form_validation->set_rules('deskripsi_produk', 'deskripsi produk', 'trim|required|min_length[10]');

    // KETIKA TIDAK LOLOS VALIDASI
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_produk' => form_error('nama_produk'),
        'harga_produk' => form_error('harga_produk'),
        'berat_produk' => form_error('berat_produk'),
        'deskripsi_produk' => form_error('deskripsi_produk')
      ];
      echo json_encode(["status" => false, 'err' => $err]);
    }
    // KETIKA LOLOS VALIDASI
    else
    {

      $data = [
        'nama_produk' => $nama_produk,
        'deskripsi_produk' => $deskripsi_produk,
        'harga_produk' => $harga_produk,
        'berat_produk' => $berat_produk
      ];

      $image = $_FILES['gambar_produk']['name'];
      $extension = pathinfo($_FILES['gambar_produk']['name'], PATHINFO_EXTENSION);

      // KETIKA GAMBAR ADA YANG AKAN DIUPLOAD
      if (!empty($image)) {
        $config['upload_path'] = './uploads/image/produk_poin/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif';
        $config['max_size'] = '5048';
        $config['file_name'] = 'produk_'.time().".{$extension}";
        $this->load->library('upload', $config);
        // KETIKA GAMBAR BERHASIL DIUPLOAD
        if ($this->upload->do_upload('gambar_produk')) {
          $data['gambar_produk'] = $config['file_name'];
          $old_image = $this->db->get_where('produk_poin', ['id' => $id])->row_array()['gambar_produk'];
          // KETIKA GAMBAR LAMA BUKAN GAMBAR DEFAULT
          if ($old_image != 'default_produk.jpg') {
            // KETIKA GAMBAR LAMA ADA DIPENYIMPANAN
            $cek = file_exists(FCPATH."uploads/image/penukaran-poin/{$old_image}");
            if ($cek) {
              // MENGHAPUS GAMBAR LAMA
              unlink(FCPATH."uploads/image/penukaran-poin/{$old_image}");
            }
          }
        } else {
          //KETIKA GAMBAR TIDAK LOLOS VALIDASI
          $image_error = array('gambar_produk' => $this->upload->display_errors());
          echo json_encode(["status" => false, 'err' => $image_error]);
        }
      } else {}

      // UPDATE DATA PRODUK KE DATABASE
      $this->p_poin->update(['id' => $id], $data);
      echo json_encode(array("status" => TRUE));

    }
  }

  public function ajax_delete($id) {
    $gambar_produk = $this->p_poin->get_by_id($id)->gambar_produk;
    if ($gambar_produk != 'default_produk.png') {
      unlink(FCPATH . 'uploads/image/produk_poinuk/' . $gambar_produk);
    } else {}
    $this->p_poin->delete_by_id($id);

    echo json_encode(["status" => TRUE]);
  }





}