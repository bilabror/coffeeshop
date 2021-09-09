<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Home
* @property home_model|Model
*/
class Pay extends CI_Controller {

  public function __construct() {
    parent::__construct();
    harus_login();
    $this->load->model('Pesanan_model', 'pesanan');
  }



  /**
  *
  * Halaman Upload Bukti Pembayaran
  *
  * @param Integer $id
  *
  */
  public function index($id) {
    // get pesanan berdasarkan id
    $pesanan = $this->db->get_where('pesanan', ['id_pesanan' => $id])->row();
    // jika pesanan tidak ada
    if (!$pesanan) redirect(site_url());
    if (sud('role_id') != 2) redirect(site_url());

    $data['bank'] = $this->db->get('bank')->result_array(); // get data bank
    $data['rekening_toko'] = $this->db->get('rekening_toko')->result_array(); // get data rekening toko
    $data['id_pesanan'] = $id; // id pesanan

    $data['title'] = 'Upload Bukti Pembayaran';
    pages_frontend('frontend/upload_pembayaran', $data);

    // jika tombol submit diklik
    if (isset($_POST['submit'])) $this->_send_bukti();

  }


  /**
  *
  * Proses upload bukti pembayaran
  *
  */
  private function _send_bukti() {
    // deklasari variables
    $id_pesanan = $this->input->post('id_pesanan');
    $rekening_toko = explode('&', $this->input->post('rek_toko'));
    $rekening_toko_arr = [
      'atas_nama' => $rekening_toko[0],
      'norek' => $rekening_toko[1],
      'bank' => $rekening_toko[2]
    ];
    $rekening_toko_arr = json_encode($rekening_toko_arr);
    $nama_bank = htmlspecialchars($this->input->post('bank'), true);
    $an = htmlspecialchars($this->input->post('atas_nama'), true);
    $norek = htmlentities($this->input->post('norek'), true);

    $data = [
      "id_user" => sud('id_user'),
      "id_pesanan" => $id_pesanan,
      "nama_bank" => $nama_bank,
      "atas_nama" => $an,
      "rekening_toko" => $rekening_toko_arr,
      "norek" => $norek,
      "tgl_buat_bukti_pembayaran" => dt()
    ];

    $upload_image = $_FILES['gambar']['name'];
    $extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    // jika ada gambar yang diupload
    if (!empty($upload_image)) {

      $config['upload_path'] = './uploads/image/payment';
      $config['allowed_types'] = 'jpeg|jpg|png';
      $config['file_name'] = 'pay_'.time().".{$extension}";
      $config['overwrite'] = true;
      $config['max_size'] = '5048';
      $this->load->library('upload', $config);
      // jika upload gambar gagal
      if (!$this->upload->do_upload('gambar')) {
        $error = ['error' => $this->upload->display_errors()];
      }
      /// jika upload gambar berhasil
      else {
        $image = $this->upload->data('file_name');
        $data['gambar'] = $image;
      }
    }
    // insert bukti pembayaran
    $insert = $this->db->insert('bukti_pembayaran', $data);
    // jika insert bukti pembayaran berhasil
    if ($insert) {
      // update status pesanan
      $this->db->update('pesanan',
        [
          'status' => "pending",
          "tgl_bayar_pesanan" => dt()
        ],
        ['id_pesanan' => $id_pesanan]);

      // get pesanan berdasarkan id
      $pesanan = $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row_array();

      // ketika opsi beli pesanan dikirim / diantar
      if ($pesanan['opsi_beli'] == 1) {

        $this->db->where('role_id',1);
        $this->db->or_where('role_id',58);
        $user_notif = $this->db->get('user')->result();
        
        foreach($user_notif as $val){
          $data_notifikasi = [
          'id_user' => $val->id,
          'judul' => 'Pesanan Baru',
          'subjudul' => $id_pesanan,
          'link' => "transaksi/online/detail/{$id_pesanan}",
          'icon' => 'fas fa-cart-arrow-down',
          'tgl_buat' => dt()
        ];

        $this->db->insert('notifikasi', $data_notifikasi);
        }

        
        redirect(site_url('riwayat/pesanan'));
      }
      // ketika opsi beli pesanan ditempat / booking tempat
      else {

        $this->db->where('role_id',1);
        $this->db->or_where('role_id',58);
        $user_notif = $this->db->get('user')->result();

        foreach($user_notif as $val){
          $data_notifikasi = [
            'id_user' => $val->id,
            'judul' => 'Pesanan Baru',
            'subjudul' => $id_pesanan,
            'link' => "transaksi/booking/detail/{$id_pesanan}",
            'icon' => 'fas fa-cart-arrow-down',
            'tgl_buat' => dt()
          ];

          $this->db->insert('notifikasi', $data_notifikasi);
        }
        
        redirect(site_url('riwayat/booking'));
      }


    }
  }



}