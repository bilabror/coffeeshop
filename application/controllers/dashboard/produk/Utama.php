<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Utama
 *
 * Controller ini berperan untuk mengatur bagian Produk Utama
 * 
 */
class Utama extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('Kategori_model', 'kategori');
    $this->load->model('produk_model', 'produk');
    proteksi();
  }


   /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['title'] = 'Data Produk';
    pages('dashboard/produk/produk', $data);
  }

   /**
	 * Detail Method
	 *
	 * @return view
	 */
  public function detail($slug) {
    $model = $this->produk->detailPage($slug);
    $data['produk'] = $model['produk'];
    $data['pesanan'] = $model['pesanan'];
    $data['ulasan'] = $model['ulasan'];
    $data['title'] = 'Detail Produk';
    pages('dashboard/produk/detail_produk', $data);
  }

  /**
	 * Add Method
	 *
	 * @return view
	 */
  public function add() {
    $data['title'] = 'Tambah Produk';
    pages('dashboard/produk/add_produk', $data);
  }

  /**
	 * Edit Method
	 *
	 * @return view
	 */
  public function edit($id) {
    $data['id_produk'] = $id;
    $query = "SELECT id_kategori,gambar_produk FROM produk WHERE id_produk = {$id}";
    $sql = $this->db->query($query)->row_array();
    $data['id_kategori'] = $sql['id_kategori'];
    $data['gambar_produk'] = $sql['gambar_produk'];
    $data['title'] = 'Edit Produk';
    pages('dashboard/produk/edit_produk', $data);
  }

/**
	 * aksi tambah data
	 *
	 * @return json
	 */
  public function insert() {
    // DEKLARASI VARIABELS
    $nama_produk = strip_tags($this->input->post('nama_produk'), ENT_QUOTES);
    $id_kategori = strip_tags($this->input->post('id_kategori'));
    $stok_produk = $this->input->post('stok_produk');
    $berat_produk = strip_tags($this->input->post('berat_produk'));
    $dsc = $this->input->post('dsc');
    $harga_produk = strip_tags($this->input->post('harga_produk'));
    $diskon_produk = strip_tags($this->input->post('diskon_produk'));
    $deskripsi_produk = strip_tags($this->input->post('deskripsi_produk'));
    $total_harga_produk = strip_tags($this->input->post('total_harga_produk'));
    $slug_produk = $this->input->post('slug_produk');

    // SET VALIDASI FORM
    $this->form_validation->set_rules('nama_produk', 'Nama_produk', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('id_kategori', 'id kategori', 'trim|required');
    $this->form_validation->set_rules('deskripsi_produk', 'deskripsi produk', 'trim|required|min_length[10]');
    $this->form_validation->set_rules('harga_produk', 'harga produk', 'trim|required');
    $this->form_validation->set_rules('berat_produk', 'berat produk', 'trim|required');
    $this->form_validation->set_rules('stok_produk', 'stok produk', 'trim|required');

    // KETIKA TIDAK LOLOS VALIDASI
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_produk' => form_error('nama_produk'),
        'id_kategori' => form_error('id_kategori'),
        'deskripsi_produk' => form_error('deskripsi_produk'),
        'harga_produk' => form_error('harga_produk'),
        'berat_produk' => form_error('berat_produk'),
        'stok_produk' => form_error('stok_produk')
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
        $config['upload_path'] = './uploads/image/produk/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif';
        $config['max_size'] = '5048';
        $config['file_name'] = 'produk_'.time().".{$extension}";
        $this->load->library('upload', $config);
        // KETIKA GAMBAR BERHASIL DIUPLOAD
        if ($this->upload->do_upload('gambar_produk')) {
          $data = [
            'nama_produk' => $nama_produk,
            'deskripsi_produk' => $deskripsi_produk,
            'harga_produk' => $harga_produk,
            'diskon_produk' => $diskon_produk,
            'deskripsi_produk' => $deskripsi_produk,
            'total_harga_produk' => $total_harga_produk,
            'berat_produk' => $berat_produk,
            'stok_produk' => $stok_produk,
            'tgl_buat_produk' => dt(),
            'gambar_produk' => $config['file_name'],
            'slug_produk' => $slug_produk,
            'id_kategori' => $id_kategori
          ];
          // INSERT KE DATABASE
          $insert = $this->produk->save($data);
          $this->session->set_flashdata('success','ditambahakan');
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

  /**
	 * aksi edit data
	 *
	 * @return json
	 */
  public function update() {
    // DEKLARASI VARIABLES
    $id_produk = $this->input->post('id_produk');
    $nama_produk = strip_tags($this->input->post('nama_produk'), ENT_QUOTES);
    $id_kategori = htmlspecialchars($this->input->post('id_kategori'), true);
    $stok_produk = htmlspecialchars($this->input->post('stok_produk'), true);
    $berat_produk = htmlspecialchars($this->input->post('berat_produk'), true);
    $deskripsi_produk = htmlspecialchars($this->input->post('deskripsi_produk'), true);
    $harga_produk = htmlspecialchars($this->input->post('harga_produk'), true);
    $diskon_produk = htmlspecialchars($this->input->post('diskon_produk'), true);
    $total_harga_produk = htmlspecialchars($this->input->post('total_harga_produk'), true);
    $slug_produk = htmlspecialchars($this->input->post('slug_produk'), true);

    // SET VALIDASI FORM
    $this->form_validation->set_rules('nama_produk', 'nama produk', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('id_kategori', 'id kategori', 'trim|required');
    $this->form_validation->set_rules('deskripsi_produk', 'deskripsi produk', 'trim|required|min_length[10]');
    $this->form_validation->set_rules('harga_produk', 'harga produk', 'trim|required');
    $this->form_validation->set_rules('berat_produk', 'berat produk', 'trim|required');
    $this->form_validation->set_rules('stok_produk', 'stok produk', 'trim|required');

    // KETIKA TIDAK LOLOS VALIDASI
    if ($this->form_validation->run() == FALSE) {
      $err = [
        'nama_produk' => form_error('nama_produk'),
        'id_kategori' => form_error('id_kategori'),
        'deskripsi_produk' => form_error('deskripsi_produk'),
        'harga_produk' => form_error('harga_produk'),
        'berat_produk' => form_error('berat_produk'),
        'stok_produk' => form_error('stok_produk')
      ];
      echo json_encode(["status" => false, 'err' => $err]);
    }
    // KETIKA LOLOS VALIDASI
    else
    {
      $data = [
        'nama_produk' => $nama_produk,
        'deskripsi_produk' => $deskripsi_produk,
        'diskon_produk' => $diskon_produk,
        'total_harga_produk' => $total_harga_produk,
        'berat_produk' => $berat_produk,
        'stok_produk' => $stok_produk,
        'slug_produk' => $slug_produk,
        'id_kategori' => $id_kategori,
      ];

      $image = $_FILES['gambar_produk']['name'];
      $extension = pathinfo($_FILES['gambar_produk']['name'], PATHINFO_EXTENSION);

      // KETIKA GAMBAR ADA YANG AKAN DIUPLOAD
      if (!empty($image)) {
        $config['upload_path'] = './uploads/image/produk/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif';
        $config['max_size'] = '5048';
        $config['file_name'] = 'produk_'.time().".{$extension}";
        $this->load->library('upload', $config);
        // KETIKA GAMBAR BERHASIL DIUPLOAD
        if ($this->upload->do_upload('gambar_produk')) {
          $data['gambar_produk'] = $config['file_name'];
          $old_image = $this->db->get_where('produk', ['id_produk' => $id_produk])->row_array()['gambar_produk'];
          // KETIKA GAMBAR LAMA BUKAN GAMBAR DEFAULT
          if ($old_image != 'default_produk.jpg') {
            // KETIKA GAMBAR LAMA ADA DIPENYIMPANAN
            $cek = file_exists(FCPATH."uploads/image/produk/{$old_image}");
            if ($cek) {
              // MENGHAPUS GAMBAR LAMA
              unlink(FCPATH."uploads/image/produk/{$old_image}");
            }
          }
        } else {
          //KETIKA GAMBAR TIDAK LOLOS VALIDASI
          $image_error = array('gambar_produk' => $this->upload->display_errors());
          echo json_encode(["status" => false, 'err' => $image_error]);
        }
      } else {}

      // UPDATE DATA PRODUK KE DATABASE
      $this->produk->update($data, ['id_produk' => $id_produk]);
      $this->session->set_flashdata('success','diedit');
      echo json_encode(array("status" => TRUE));

    }

  }

  /**
	 * aksi hapus data
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_delete($id) {
    $image_produk = $this->produk->get_by_id($id);
    unlink(FCPATH.'uploads/image/produk/'.$image_produk->gambar_produk);
    $this->produk->delete_by_id($id);
    echo json_encode(["status" => TRUE]);
  }

 /**
	 * Get data dengan style datatable
	 *
	 * @return json
	 */
  public function get_datatables() {
    $this->produk->get_datatables();
  }

 /**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->produk->get_by_id($id);
    echo json_encode($data);
  }

 /**
	 * get data kategori
	 *
	 * @return json
	 */
  public function kategori_ajax() {
    $kategori = $this->kategori->get();
    echo json_encode($kategori);
  }




}