<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Home
* @property home_model|Model
*/


use Mpdf\Mpdf;

class Riwayat extends CI_Controller {

  public function __construct() {
    parent::__construct();
    harus_login();
    harus_customer();
    $this->load->model('Produk_poin_model', 'p_poin');
    $this->load->model('Pesanan_model', 'pesanan');
    $this->load->model('Order_online_model', 'order_online');
  }



  // Riwayat Penukaran Poin
  public function poin() {
    //expired_tukar_poin();
    $this->db->select('gambar_produk,nama_produk,harga_produk,tukar_poin.tgl_buat,tukar_poin.status,tukar_poin.id');
    $this->db->from('tukar_poin');
    $this->db->join('produk_poin', 'tukar_poin.id_produk_poin = produk_poin.id');
    $this->db->where(['tukar_poin.id_user' => sud('id_user'), 'dihapus_customer' => 0]);
    $data['tukar_poin'] = $this->db->get()->result_array();

    $data['title'] = 'Poinku';
    pages_frontend('frontend/history_tukar_poin', $data);
  }

  // Riwayat Pesanan
  public function pesanan() {
    order_expired();
    delete_pesanan();
    $orders = $this->pesanan->get_where(['id_user' => sud('id_user'), 'opsi_beli' => 1, 'dihapus_pembeli' => 0]);
    $i = $orders->result_array();

    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('pesanan', 'item_pesanan.id_pesanan = pesanan.id_pesanan');
    $this->db->join('produk', 'item_pesanan.id_produk = produk.id_produk');
    $this->db->where(['id_user' => sud('id_user'), 'opsi_beli' => 1, 'dihapus_pembeli' => 0]);
    $this->db->group_by('item_pesanan.id_pesanan');
    $this->db->order_by('tgl_buat_pesanan', 'desc');
    $data['orders'] = $this->db->get()->result_array();


    $data['title'] = 'Riwayat Pesanan';
    pages_frontend('frontend/history_order', $data);
  }

  // Riwayat Booking Tempat
  public function booking() {
    $this->db->select('*');
    $this->db->from('booking_tempat');
    $this->db->join('pesanan', 'pesanan.id_pesanan = booking_tempat.id_pesanan');
    $this->db->where(['id_user' => sud('id_user'), 'opsi_beli' => 2, 'dihapus_pembeli' => 0]);
    $this->db->order_by('tgl_buat_pesanan', 'desc');
    $data['booking'] = $this->db->get()->result_array();

    $data['title'] = 'Beranda';
    pages_frontend('frontend/history_booking', $data);

  }

  // halaman invoice
  public function detail_pesanan($id) {
    read_notifiksai_backend("riwayat/pesanan/detail/{$id}");
    $data['invoice'] = $this->pesanan->get_where(['id_user' => sud('id_user'), 'id_pesanan' => $id])->row();
    $data_address = json_decode($data['invoice']->data_penerima)->address;
    $prov = $this->db->get_where('provinsi', ['id' => $data_address->prov])->row_array()['nama'];
    $kab = $this->db->get_where('kabupaten', ['id' => $data_address->kab])->row_array()['nama'];
    $kec = $this->db->get_where('kecamatan', ['id' => $data_address->kec])->row_array()['nama'];
    $typekab = $this->db->get_where('kabupaten', ['id' => $data_address->kab])->row_array()['tipe'];
    if ($typekab == 'Kabupaten') {
      $data['address'] = "{$data_address->detail}, Kec. {$kec}, Kab. {$kab}, {$prov}, {$data_address->kode_pos}";
    } else {
      $data['address'] = "{$data_address->detail}, Kec. {$kec}, Kota. {$kab}, {$prov}, {$data_address->kode_pos}";
    }
    if (!$data['invoice']) {
      redirect($this->myorder());
    }

    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $query = $this->db->get();
    $data['produk'] = $query->result();


    $data['title'] = 'Detail Pesanan';
    pages_frontend('frontend/detail_pesanan', $data);
  }

  // halaman invoice
  public function detail_booking($id) {
    read_notifiksai_backend("riwayat/booking/detail/{$id}");
    $data['invoice'] = $this->pesanan->get_where(['id_user' => sud('id_user'), 'id_pesanan' => $id])->row();
    $data['booking'] = $this->db->get_where('booking_tempat', ['id_pesanan' => $id])->row();
    if (!$data['invoice']) {
      redirect($this->myorder());
    }

    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $query = $this->db->get();
    $data['produk'] = $query->result();


    $data['title'] = 'Detail Booking';
    pages_frontend('frontend/detail_booking', $data);
  }



  public function poinku() {
    if (isset($_POST['cari'])) {
      $data['produk'] = $this->p_poin->get_like(['nama_produk' => htmlspecialchars($this->input->post('cari'))]);
    } else {
      $data['produk'] = $this->p_poin->get_where(['status' => 1])->result();
    }
    $data['poin'] = $this->db->get_where('poin', ['id_user' => sud('id_user')])->row_array()['poin'];
    $data['title'] = 'Poinku';
    pages_frontend('frontend/poin', $data);

  }

  public function tukar_poin() {
    $id = $this->input->post('id');
    $produk_poin = $this->db->get_where('produk_poin', ['id' => $id])->row_array();
    $poin = $this->db->get_where('poin', ['id_user' => sud('id_user')])->row_array();
    if ($poin['poin'] >= $produk_poin['harga_produk']) {
      $data = [
        'id_user' => sud('id_user'),
        'id_produk_poin' => $produk_poin['id'],
        'status' => 'pending',
        'tgl_buat' => dt()
      ];
      $this->db->insert('tukar_poin', $data);
      $update_poin = $poin['poin'] - $produk_poin['harga_produk'];
      $this->db->update('poin', ['poin' => $update_poin], ['id_user' => sud('id_user')]);
      echo json_encode(['status' => TRUE]);
    } else {
      echo json_encode(['status' => FALSE]);
    }

  }





  public function batal_pesanan() {
    $id_pesanan = $this->input->post('id_pesanan');
    $id_booking = $this->input->post('id_booking');
    $order_items = $this->db->get_where('item_pesanan', ['id_pesanan' => $id_pesanan])->result_array();
    foreach ($order_items as $item) {
      $produk = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row_array();
      $this->db->update('produk', ['stok_produk' => $produk['stok_produk']+$item['kuantitas']], ['id_produk' => $item['id_produk']]);
    }
    if ($id_booking) {
      $this->db->delete('booking_tempat', ['id_booking' => $id_booking]);
    }
    $this->db->delete('item_pesanan', ['id_pesanan' => $id_pesanan]);
    $this->db->delete('pesanan', ['id_pesanan' => $id_pesanan]);
    echo json_encode(['status' => TRUE]);
  }


  public function trash_pesanan() {
    $id_pesanan = $this->input->post('id_pesanan');
    $this->db->update('pesanan', ['dihapus_pembeli' => 1], ['id_pesanan' => $id_pesanan]);
    echo json_encode(['status' => TRUE]);
  }

  public function trash_tukar_poin() {
    $id = $this->input->post('id');
    $this->db->update('tukar_poin', ['dihapus_customer' => 1], ['id' => $id]);
    echo json_encode(['status' => TRUE]);
  }

  public function pesanan_selesai(){
   $id_pesanan = $this->input->post('id_pesanan');
   //$id_pesanan = 521627529515; 
   $id_user = sud('id_user');
    
    $data = [
      'status' => 'selesai',
      'tgl_selesai_pesanan' => dt()
    ];
    $this->db->update('pesanan',$data,['id_pesanan' => $id_pesanan]);
    // add 1 poin
    add_poin($id_user);
    echo json_encode(['status' => TRUE]);
  }

  public function invoice_booking($id) {
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

  public function invoice_online($id) {
    
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


}