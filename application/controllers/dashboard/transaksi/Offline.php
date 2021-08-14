<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Pesanan
* @property pesanan_model | Model
*/

class Offline extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Order_offline_model', 'order_offline');
    proteksi();
  }


  /**
  * Halaman Riwayat Pesanan
  */
  public function index() {
    delete_pesanan();
    $data['title'] = 'Pesanan';
    $page = 'dashboard/transaksi/pesanan_offline';
    pages($page, $data);
  }

  /**
  * Halaman Detail Pesanan
  */

  // HALAMAN DETAIL PESANAN
  public function detail($id) {
    $data['invoice'] = $this->db->get_where('pesanan', ['id_pesanan' => $id])->row();

    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $query = $this->db->get();
    $data['produk'] = $query->result();
    $data['title'] = 'Detail Transaksi';
    $page = 'dashboard/transaksi/detail_pesanan_offline';
    pages($page, $data);
  }




  /**
  * get data dengan datatable
  * @return json_encode
  */
  public function get_datatables() {
    $this->order_offline->get_datatables();
  }



  public function invoice_pdf($id) {
    $data['invoice'] = $this->order_offline->get_where(['id_pesanan' => $id])->row();
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
    $this->load->view('pdf/invoice-pesanan', $data);
  }



  /*-----------  CREATE - UPDATE - DELETE  ---------------- */

  /**
  * Action Update Pesanan tanpa ajax
  * ini dilakukan pada halaman detail pesanan
  */
  public function update() {
    $status = htmlspecialchars($this->input->post('opsi'), true);
    $note_Seller = htmlspecialchars($this->input->post('catatan_penjual'), true);
    $resi = htmlspecialchars($this->input->post('resi'), true);
    $id_pesanan = $this->input->post('id');
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];

    $data = [
      'status' => $status,
      'catatan_penjual' => $note_Seller
    ];
    if ($status == 'kirim') {
      $data['tgl_kirim_pesanan'] = dt();
      $data['resi'] = $resi;
    } else if ($status == 'selesai') {
      $data['tgl_selesai_pesanan'] = dt();

      $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();
      $poin = $this->db->get_where('poin', ['id_user' => $id_user]);
      if ($poin->num_rows() > 0) {
        $p = $poin->row_array();
        $this->db->update('poin', ['poin' => $p['poin']+1], ['id_user' => $id_user]);
      }
      foreach ($items as $item) {
        $data_sold = [
          'id_produk' => $item['id_produk'],
          'terjual' => $item['kuantitas'],
          'tgl_buat_pesanan' => dt()
        ];
        $this->db->insert('produk_terjual', $data_sold);

        $produk = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row_array()['stok_produk'];
        $this->db->update('produk', ['stok_produk' => $produk-$item['kuantitas']], ['id_produk' => $item['id_produk']]);
      }
    } else if ($status == 'batal' || $status == 'tolak') {
      $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();
      foreach ($items as $item) {
        $produk = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row_array()['stok_produk'];
        $this->db->update('produk', ['stok_produk' => $produk+$item['kuantitas']], ['id_produk' => $item['id_produk']]);

      }
    }

    $this->order_offline->update_pesanan($data, ['id_pesanan' => $id_pesanan]);
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];
    redirect(site_url('dashboard/transaksi/offline'));

  }




  /**
  * Action Update dengan AJAX
  *
  * @return JSON
  */
  public function ajax_update() {
    // Deklarasi variables
    $status = htmlspecialchars($this->input->post('opsi'), true);
    $note_Seller = htmlspecialchars($this->input->post('catatan_penjual'), true);
    $resi = htmlspecialchars($this->input->post('resi'), true);
    $id_pesanan = $this->input->post('id');
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];
    $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();

    // set validasi input form
    $this->form_validation->set_rules('opsi', 'Opsi', 'required', ['required' => 'opsi belum diubah']);

    // ketika tidak lolos validasi
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
      // jika pesanan masuk dalam tahap status penyelesaian
      if ($status == 'selesai') {
        $data['tgl_selesai_pesanan'] = dt();

        // add 1 poin
        add_poin($id_user);

        foreach ($items as $item) {
          produksold($item['id_produk'], $item['kuantitas'], date('d'));
        }


      }
      // jika pesanan dibatalkan atau ditolak
      else if ($status == 'batal' || $status == 'tolak') {

        // update stok
        foreach ($items as $item) {
          $produk = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row_array()['stok_produk'];
          $this->db->update('produk', ['stok_produk' => $produk+$item['kuantitas']], ['id_produk' => $item['id_produk']]);

        }
      }

      $this->order_offline->update_pesanan($data, ['id_pesanan' => $id_pesanan]);

      echo json_encode(array("status" => TRUE));
    }

  }

  public function ajax_trash() {
    $data = ['dihapus_penjual' => 1];
    $this->order_offline->update_pesanan($data, ['id_pesanan' => $this->input->post('id')]);

    echo json_encode(array("status" => TRUE));
  }

  // DELETE DATA
  public function ajax_delete() {
    $this->db->delete('pesanan', ['id_pesanan' => $this->input->post('id')]);
    $this->db->delete('item_pesanan', ['id_pesanan' => $this->input->post('id')]);
    echo json_encode(array("status" => TRUE));
  }


}