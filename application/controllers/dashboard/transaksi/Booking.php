<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Pesanan_model', 'pesanan');
    $this->load->model('Booking_tempat_model', 'booking');
    proteksi();
  }

  // HALAMAN PESANAN
  public function index() {
    $this->db->delete('booking_tempat', ['prk_datang' => '0000-00-00 00:00:00']);
    delete_pesanan();
    order_expired();
    $data['title'] = 'Pesanan';
    $page = 'dashboard/transaksi/booking_tempat';
    pages($page, $data);
  }


  // HALAMAN DETAIL PESANAN
  public function detail($id) {
    read_notifiksai_backend("transaksi/booking/detail/{$id}");
    $data['invoice'] = $this->pesanan->get_where(['id_pesanan' => $id])->row();
    $data['booking'] = $this->db->get_where('booking_tempat', ['id_pesanan' => $id])->row();
    $data['bukti_pembayaran'] = $this->db->get_where('bukti_pembayaran', ['id_pesanan' => $id])->row_array();
    $data['bank'] = $this->db->get_where('bank', ['code' => $data['bukti_pembayaran']['nama_bank']])->row_array();
    $data['rekening_toko'] = json_decode($data['bukti_pembayaran']['rekening_toko'], true);
    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $query = $this->db->get();
    $data['produk'] = $query->result();
    $data['title'] = 'Detail Transaksi';
    $page = 'dashboard/transaksi/detail_booking';
    pages($page, $data);
  }




  /* ------------  GET DATA JSON   -----------------*/


  public function get_datatables() {
    $this->booking->get_datatables();
  }


  public function invoice_pdf($id) {
    $data['invoice'] = $this->pesanan->get_where(['id_pesanan' => $id])->row();
    $data['booking'] = $this->db->get_where('booking_tempat', ['id_pesanan' => $id])->row();
    $data['bukti_pembayaran'] = $this->db->get_where('bukti_pembayaran', ['id_pesanan' => $id])->row_array();
    $data['bank'] = $this->db->get_where('bank', ['code' => $data['bukti_pembayaran']['nama_bank']])->row_array();
    $data['rekening_toko'] = json_decode($data['bukti_pembayaran']['rekening_toko'], true);
    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $query = $this->db->get();
    $data['produk'] = $query->result();
    $data['title'] = 'Detail Transaksi';
    $this->load->view('pdf/invoice-booking', $data);
  }



  /*-----------  CREATE - UPDATE - DELETE  ---------------- */

  // UPDATE DATA
  public function ajax_update() {
    // Dekslarasu variables
    $id_pesanan = $this->input->post('id');
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];
    $opsi = htmlspecialchars($this->input->post('opsi'), true);
    $catatan = htmlspecialchars($this->input->post('catatan'), true);
    $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();


    // set validasi form
    $this->form_validation->set_rules('opsi', 'Opsi', 'required');

    // jika tidak lolos validasi
    if ($this->form_validation->run() == FALSE) {
      $err['opsi'] = form_error('opsi');
      echo json_encode(["status" => FALSE, 'err' => $err]);
    }
    // jika lolos validasi
    else
    {
      $data = [
        'status' => $opsi,
        'catatan_penjual' => $catatan
      ];
      // ketika pesanan masuk pada tahan status peyelesaian
      if ($opsi == 'selesai') {
        $data['tgl_selesai_pesanan'] = dt();

        // add 1 poin untuk customer
        add_poin($id_user);

      } else if ($opsi == 'terima') {
        foreach ($items as $item) {
          produksold($item['id_produk'], $item['kuantitas'], date('d'));
        }
      }

      // jika pemesanan dibatalkan atau ditolak
      else if ($opsi == 'batal' || $opsi == 'tolak') {

        // melakukan update stok produk
        foreach ($items as $item) {
          $stok_old = getstok($item['id_produk']);
          $stok_new = $stok_old + $item['kuantitas'];
          upstok($item['id_produk'], $stok_new);
        }

      }

      // update status pemesanan
      $this->pesanan->update_pesanan($data, ['id_pesanan' => $id_pesanan]);
      $this->_push_notifikasi($opsi, $id_pesanan, $id_user);

      echo json_encode(array("status" => TRUE));
    }

  }


  public function ajax_trash() {
    $data = ['dihapus_penjual' => 1];
    $this->booking->update_pesanan($data, ['id_pesanan' => $this->input->post('id')]);

    echo json_encode(array("status" => TRUE));
  }

  // DELETE DATA
  public function ajax_delete() {
    $this->db->delete('pesanan', ['id_pesanan' => $this->input->post('id')]);
    $this->db->delete('item_pesanan', ['id_pesanan' => $this->input->post('id')]);
    echo json_encode(array("status" => TRUE));
  }



  private function _push_notifikasi($status, $id_pesanan, $id_user) {

    if ($status == 'terima') {
      $judul = 'Pesanan Diterima';
      $icon = 'fas fa-check';
    } elseif ($status == 'tolak') {
      $judul = 'Pesanan Ditolak';
      $icon = 'fas fa-ban';
    } elseif ($status == 'batal') {
      $judul = 'Pesanan Dibatalkan';
      $icon = 'fas fa-ban';
    } elseif ($status == 'selesai') {
      $judul = 'Pesanan Selesai';
      $icon = 'fas fa-box-open';
    } elseif ($status == 'kirim') {
      $judul = 'Pesanan Dikirim';
      $icon = 'fas fa-truck-loading';
    } else {}
    $data_notifikasi = [
      'id_user' => $id_user,
      'judul' => $judul,
      'subjudul' => $id_pesanan,
      'icon' => $icon,
      'link' => "riwayat/booking/detail/{$id_pesanan}",
      'tgl_buat' => dt(),
    ];

    $this->db->insert('notifikasi', $data_notifikasi);
  }




}