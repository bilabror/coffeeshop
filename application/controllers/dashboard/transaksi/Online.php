<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Pesanan
* @property pesanan_model | Model
*/



class Online extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Order_online_model', 'order_online');
    $this->load->model('Pesanan_model', 'pesanan');
    proteksi();
  }


  /**
  * Halaman Riwayat Pesanan
  */
  public function index() {
    delete_pesanan();
    order_expired();
    $data['title'] = 'Pesanan';
    $page = 'dashboard/transaksi/pesanan_online';
    pages($page, $data);
  }

  /**
  * Halaman Detail Pesanan
  */
  public function detail($id) {
    read_notifiksai_backend("transaksi/online/detail/{$id}");
    $model = $this->order_online->detail_pesanan($id);
    $data['invoice'] = $model['invoice'];
    $data['bukti_pembayaran'] = $model['bukti_pembayaran'];
    $data['bank'] = $model['bank'];
    $data['rekening_toko'] = $model['rekening_toko'];
    if (isset($model['address'])) {
      $data['address'] = $model['address'];
    }
    $data['booking'] = $model['booking'];
    $data['produk'] = $model['produk'];

    $data['title'] = 'Detail Transaksi';
    $page = 'dashboard/transaksi/detail_pesanan_online';
    pages($page, $data);
  }



  /**
  * get data dengan datatable
  * @return json_encode
  */
  public function get_datatables() {
    $this->order_online->get_datatables();
  }



  public function tracking($id) {
    $data['pesanan'] = $this->order_online->tracking($id);
    $data['title'] = 'Tracking ';
    pages('dashboard/transaksi/pesanan_tracking', $data);
  }





  public function invoice_pdf($id) {
    
    $mpdf = new \Mpdf\Mpdf();

    $data['invoice'] = $this->order_online->get_where(['id_pesanan' => $id])->row();
    $data['bukti_pembayaran'] = $this->db->get_where('bukti_pembayaran', ['id_pesanan' => $id])->row_array();
    $data['bank'] = $this->db->get_where('bank', ['code' => $data['bukti_pembayaran']['nama_bank']])->row_array();
    $data['rekening_toko'] = json_decode($data['bukti_pembayaran']['rekening_toko'], true);
    $data_address = json_decode($data['invoice']->data_penerima)->address;
    $data['address'] = "{$data_address->detail}, {$data_address->kec}, {$data_address->kab}, {$data_address->prov}, {$data_address->kode_pos}";


    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $query = $this->db->get();
    $data['produk'] = $query->result();
    $data['title'] = 'Detail Transaksi';
    $this->load->view('pdf/invoice-online', $data);
   // $result = $this->load->view('pdf/invoice-online', $data, TRUE);
   // $mpdf->WriteHTML($result);
   // $mpdf->Output();
  }



  /*-----------  CREATE - UPDATE - DELETE  ---------------- */


  /**
  * Action Update Nomor Resi
  *
  * @return JSON
  */
  public function resi_update() {
    $data['resi'] = htmlspecialchars($this->input->post('resi'), true);
    $this->order_online->update_pesanan($data, ['id_pesanan' => $this->input->post('id')]);
    echo json_encode(array("status" => TRUE));

  }

  /**
  * Action Update dengan AJAX
  *
  * @return JSON
  */
  public function ajax_update() {

    // deklarasi valiables
    $status = htmlspecialchars($this->input->post('opsi'), true);
    $note_Seller = htmlspecialchars($this->input->post('catatan_penjual'), true);
    $resi = htmlspecialchars($this->input->post('resi'), true);
    $id_pesanan = $this->input->post('id');
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];
    $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();

    // set validasi input form
    $this->form_validation->set_rules('opsi', 'Opsi', 'required', ['required' => 'opsi belum diubah']);

    // ketika gagal validasi
    if ($this->form_validation->run() == FALSE) {
      $err = ['opsi' => form_error('opsi')];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    }
    // ketika lolos validasi
    else
    {

      $data = [
        'status' => $status,
        'catatan_penjual' => $note_Seller
      ];

      // ketika pesanan akan masuk dalam pengiriman
      if ($status == 'kirim') {
        $data['tgl_kirim_pesanan'] = dt();
        $data['resi'] = $resi;
      }
      // ketika pesanan akan masuk dalam penyelesaian
      else if ($status == 'selesai') {
        $data['tgl_selesai_pesanan'] = dt();

        // add 1 poin
        add_poin($id_user);

      } else if ($status == 'terima') {
        foreach ($items as $item) {
          produksold($item['id_produk'], $item['kuantitas'], date('d'));
        }
      } else if ($status == 'batal' || $status == 'tolak') {

        // melakukan update stok
        $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();
        foreach ($items as $item) {
          $produk = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row_array()['stok_produk'];
          $this->db->update('produk', ['stok_produk' => $produk+$item['kuantitas']], ['id_produk' => $item['id_produk']]);

        }
      }

      $this->order_online->update_pesanan($data, ['id_pesanan' => $id_pesanan]);
      $this->_push_notifikasi($status, $id_pesanan, $id_user);

      echo json_encode(array("status" => TRUE));
    }

  }

  public function ajax_trash() {
    $data = ['dihapus_penjual' => 1];
    $this->order_online->update_pesanan($data, ['id_pesanan' => $this->input->post('id')]);

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
    $opsi_beli = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['opsi_beli'];
    if ($opsi_beli == 1) {
      $data_notifikasi = [
        'id_user' => $id_user,
        'judul' => $judul,
        'subjudul' => $id_pesanan,
        'icon' => $icon,
        'link' => "riwayat/pesanan/detail/{$id_pesanan}",
        'tgl_buat' => dt(),
      ];
    } else {
      $data_notifikasi = [
        'id_user' => $id_user,
        'judul' => $judul,
        'subjudul' => $id_pesanan,
        'icon' => $icon,
        'link' => "riwayat/booking/detail/{$id_pesanan}",
        'tgl_buat' => dt(),
      ];
    }

    $this->db->insert('notifikasi', $data_notifikasi);
  }


}