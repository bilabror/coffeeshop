<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Pesanan
* @property pesanan_model | Model
*/

class Barqode extends CI_Controller {

  public function __construct() {
    parent::__construct();
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
    $page = 'dashboard/transaksi/pesanan';
    pages($page, $data);
  }

  /**
  * Halaman Detail Pesanan
  */
  public function detail($id) {
    read_notifiksai_backend("transaksi/pesanan/detail/{$id}");
    $model = $this->pesanan->detail_pesanan($id);
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
    $page = 'backend/pesanan_detail';
    pages($page, $data);
  }



  /**
  * get data dengan datatable
  * @return json_encode
  */
  public function get_datatables() {
    $this->pesanan->get_datatables();
  }



  public function tracking($id) {
    $data['pesanan'] = $this->pesanan->tracking($id);
    $data['title'] = 'Tracking ';
    $page = 'backend/pesanan_tracking';
    pages($page, $data);
  }





  public function invoice_pdf($id) {
    $data['invoice'] = $this->pesanan->get_where(['id_pesanan' => $id])->row();
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

    $this->pesanan->update_pesanan($data, ['id_pesanan' => $id_pesanan]);
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];
    $this->_push_notifikasi($status, $id_pesanan, $id_user);
    $opsi_beli = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['opsi_beli'];
    if ($opsi_beli == 1) {
      redirect(site_url('dashboard/transaksi/pesanan'));
    } else {
      redirect(site_url('dashboard/transaksi/booking'));
    }

  }

  /**
  * Action menghapus pesanan
  */
  public function delete($id) {
    $this->db->delete('pesanans', ['id_pesanan' => $id]);
    $this->db->delete('item_pesanan', ['id_pesanan' => $id]);
    redirect(site_url('dashboard/pesanan'));
  }


  /**
  * Action Update Nomor Resi
  *
  * @return JSON
  */
  public function resi_update() {
    $data['resi'] = htmlspecialchars($this->input->post('resi'), true);
    $this->pesanan->update_pesanan($data, ['id_pesanan' => $this->input->post('id')]);
    echo json_encode(array("status" => TRUE));

  }

  /**
  * Action Update dengan AJAX
  *
  * @return JSON
  */
  public function ajax_update() {
    $status = htmlspecialchars($this->input->post('opsi'), true);
    $note_Seller = htmlspecialchars($this->input->post('catatan_penjual'), true);
    $resi = htmlspecialchars($this->input->post('resi'), true);
    $id_pesanan = $this->input->post('id');
    $id_user = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array()['id_user'];

    $this->form_validation->set_rules('opsi', 'Opsi', 'required', ['required' => 'opsi belum diubah']);

    if ($this->form_validation->run() == FALSE) {
      $err = ['opsi' => form_error('opsi')];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {
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

          // UNTUK DATA TERTENTU
          $where = [
            'id_produk' => $item['id_produk'],
            'tgl_buat >=' => date('Y-m-d'),
            'tgl_buat <=' => date('Y-m-d').' 23:59:59'
          ];
          // GET DATA PRODUK TERJUAL HARI INI BERDASARKAN ID
          $terjual = $this->db->get_where('produk_terjual', $where);
          // KETIKA DATA PRODUK TERJUAL TIDAK ADA
          if ($terjual->num_rows() < 1) {
            // PREPARE DATA PRODUK TERJUAL
            $data_sold = [
              'id_produk' => $item['id_produk'],
              'terjual' => $item['kuantitas'],
              'tgl_buat' => dt()
            ];
            // INSERT DATA PRODUK TERJUAL KE DATABASE
            $this->db->insert('produk_terjual', $data_sold);
          }
          // KETIKA DATA PRODUK TERJUAL ADA
          else {
            // PREPARE DATA PRODUK TERJUAAL
            $data_sold = [
              'terjual' => $terjual->row_array()['terjual']+$item['kuantitas']
            ];
            // MELAKUKAN PERUBAHAN PADA JUMLAH PRODUK TERJUAL
            $this->db->update('produk_terjual', $data_sold, ['id_produk' => $item['id_produk']]);
          }
        }


      } else if ($status == 'batal' || $status == 'tolak') {

        $items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();
        foreach ($items as $item) {
          $produk = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row_array()['stok_produk'];
          $this->db->update('produk', ['stok_produk' => $produk+$item['kuantitas']], ['id_produk' => $item['id_produk']]);

        }
      }

      $this->pesanan->update_pesanan($data, ['id_pesanan' => $id_pesanan]);
      $this->_push_notifikasi($status, $id_pesanan, $id_user);

      echo json_encode(array("status" => TRUE));
    }

  }

  public function ajax_trash() {
    $data = ['dihapus_penjual' => 1];
    $this->pesanan->update_pesanan($data, ['id_pesanan' => $this->input->post('id')]);

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