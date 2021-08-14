<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Kasir_model', 'kasir');
    proteksi();
  }



  // KASIR PAGE
  public function index() {
    $data['title'] = 'Kasir';
    pages('dashboard/kasir', $data);
  }


  // MENAMBAHKAN PESANAN
  public function add_order() {
    // DEKLARASI VARIABLES
    $total_bayar = htmlspecialchars($this->input->post('total_harga'), true);
    $bayar = htmlspecialchars($this->input->post('total_harga'), true);
    $id_user = sud('id_user');
    $id_pesanan = $id_user.time();
    $id_produk = $this->input->post('id');
    $kuantitas = $this->input->post('kuantitas');
    $harga = $this->input->post('harga');
    $address_customer = [
      "customer" => [
        "name" => 'offline',
        "phone" => '0'
      ],
      "address" => [
        "prov" => '',
        "kab" => '',
        "kec" => '',
        "kode_pos" => '',
        "detail" => ''
      ]];
    $data_penerima = json_encode($address_customer);
    $data = [
      'id_pesanan' => $id_pesanan,
      'id_user' => sud('id_user'),
      'bayar' => $bayar,
      'ongkir' => 0,
      'total_bayar' => $total_bayar,
      'kurir' => '',
      'layanan' => '',
      'resi' => '',
      'status' => 'selesai',
      'data_penerima' => $data_penerima,
      'tgl_buat_pesanan' => dt(),
      'tgl_bayar_pesanan' => dt(),
      'tgl_kirim_pesanan' => dt(),
      'tgl_selesai_pesanan' => dt(),
    ];
    // INSERT KE DATABASE TABLE PESANAN
    $insert_trans = $this->db->insert('pesanan', $data);

    // KETIKA BERHASIL INSERT PESANAN
    if ($insert_trans) {
      // PREPARE DATA ITEM PESANAN
      for ($i = 0; $i < count($id_produk); $i++) {
        $data = [
          'id_item_pesanan' => '',
          'id_produk' => $id_produk[$i],
          'id_pesanan' => $id_pesanan,
          'harga_sementara' => $harga[$i] * $kuantitas[$i],
          'kuantitas' => $kuantitas[$i],
        ];
        // INSERT DATA ITEM PESANAN KE DATABASE
        $this->db->insert('item_pesanan', $data);
      }

      for ($i = 0; $i < count($id_produk); $i++) {
        produksold($id_produk[$i], $kuantitas[$i], date('d'));

        // GET STOK PRODUK DARI TABLE PRODUK
        $produk = $this->db->get_where('produk', ['id_produk' => $id_produk[$i]])->row_array()['stok_produk'];
        // UPDATE STOK PRODUK
        $this->db->update('produk', ['stok_produk' => $produk-$kuantitas[$i]], ['id_produk' => $id_produk[$i]]);
      }

    }

    redirect(site_url('dashboard/transaksi/offline/detail/'.$id_pesanan));

  }

  // GET DATA PRODUK DENGAN DATATABLES
  public function get_datatables() {
    $this->kasir->get_datatables();
  }

}