<?php  defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Checkout
* @property checkout_model | Model
*/

class Checkout extends CI_Controller {


  public function __construct() {
    parent::__construct();
    $this->load->model('checkout_model', 'checkout');
    $this->load->model('keranjang_model', 'keranjang');
    if (!$this->session->userdata('email')) {
      redirect(site_url());
    }
  }


  /**
  * Halaman checkout produk
  */
  public function index() {
    $data['title'] = 'checkout';
    $query = $this->checkout->index();
    $data['prov'] = $query['prov'];
    $data['items_keranjang'] = $query['items_keranjang'];
    $data['count_keranjang'] = $query['count_keranjang'];
    $data['total_harga'] = $query['total_harga'];
    $data['total_berat'] = $query['total_berat'];
    pages_frontend('frontend/checkout', $data);
  }


  /**
  * Validasi dan aksi Checkout
  * ketika button checkout diklik
  */
  public function validasi() {
    // set validations

    // ketika opsiPembelian 1
    // pemesanan user dengan dikirim
    if ($this->input->post('opsiPembelian') == 1) {
      $this->form_validation->set_rules('prov', 'Provinsi', 'trim|required',
        ['required' => ' Di Provinsi mana Anda Tinggal?']);
      $this->form_validation->set_rules('kab', 'Kabupaten', 'trim|required',
        ['required' => ' Di Kota mana Anda Tinggal?']);
      $this->form_validation->set_rules('kec', 'Kecamatan', 'trim|required',
        ['required' => 'Di Kecamatan mana Anda Tinggal?']);
      $this->form_validation->set_rules('kode_pos', 'Kode Pos', 'trim|required',
        ['required' => 'Mohon Untuk Isi Kodepos']);
      $this->form_validation->set_rules('detail_alamat', 'detail_alamat', 'trim|required',
        ['required' => 'Isi Alamat Anda Supaya Pengiriman Lebih Cepat']);
      $this->form_validation->set_rules('kurir', 'kurir', 'trim|required',
        ['required' => 'Pilih Kurir Terpercaya Anda']);
      $this->form_validation->set_rules('layanan', 'Layanan', 'trim|required',
        ['required' => ' Pilih Layanan Terpercaya Anda']);
    }
    // ketika opsiPembelian 2
    // pemesanan user dengan ditempat
    else {
      $this->form_validation->set_rules('no_meja', 'nomor meja', 'trim|required',
        ['required' => ' Pilih Tempat Untuk Anda Duduk']);
      $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required',
        ['required' => ' Tentukan Tanggal Anda Datang']);
      $this->form_validation->set_rules('waktu', 'Waktu', 'trim|required',
        ['required' => ' Tentukan Waktu Anda Datang']);
    }
    // untuk opsiPembelian 1 dan 2 (semua)
    $this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'trim|required',
      ['required' => 'Tolong Beritahukan Nama Anda']);
    $this->form_validation->set_rules('no_penerima', 'Nomor Penerima', 'trim|required',
      ['required' => 'Masukan No.tlp Anda untuk Pengiriman Lebih Mudah']);

    // ketika tidak lolos validasi
    if ($this->form_validation->run() == FALSE) {
      // set message error
      $err = [
        'nama_penerima' => form_error('nama_penerima'),
        'no_penerima' => form_error('no_penerima'),
        'prov' => form_error('prov'),
        'kab' => form_error('kab'),
        'kec' => form_error('kec'),
        'detail_alamat' => form_error('detail_alamat'),
        'kode_pos' => form_error('kode_pos'),
        'kurir' => form_error('kurir'),
        'layanan' => form_error('layanan'),
        'no_meja' => form_error('no_meja'),
        'tanggal' => form_error('tanggal'),
        'waktu' => form_error('waktu')
      ];
      // mengembalikan data dalam bentuk json
      echo json_encode(['status' => FALSE, 'err' => $err]);
    }
    // ketika lolos validasi
    // memanggil method checkout pada checkout model
    else {
      $action = $this->checkout->checkout();
    }
  }


  public function success_checkout($opsi_beli) {
    if ($opsi_beli == 1) {
      $this->session->set_flashdata('success',
        'segara lakukan pembayaran \n agar pesanan segera diproses ');
      redirect(site_url('riwayat/pesanan'));
    } else {
      $this->session->set_flashdata('success',
        'segara lakukan pembayaran \n agar pesanan segera diproses ');
      redirect(site_url('riwayat/booking'));
    }
  }



}