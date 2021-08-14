<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Checkout Model
* @property Checkout | Controller
*/

class Checkout_model extends CI_Model {

  /**
  * Proses Interaksi dengan database
  * ketika user berada pada halaman checkout
  *
  * @return Array
  */
  public function index() {
    $this->db->select('*');
    $this->db->from('keranjang');
    $this->db->join('produk', 'produk.id_produk = keranjang.id_produk');
    $this->db->where(['keranjang.id_user' => sud('id_user'), 'status' => 0]);
    $query = $this->db->get();
    $data['prov'] = $this->db->get('provinsi')->result_array();
    $data['items_keranjang'] = $query->result();
    $data['count_keranjang'] = $query->num_rows();
    $data['total_harga'] = 0;
    $data['total_berat'] = 0;
    foreach ($query->result_array() as $row) {
      $data['total_harga'] += $row['total_harga_produk']*$row['kuantitas'];
      $data['total_berat'] += $row['berat_produk']*$row['kuantitas'];
    }
    return $data;
  }


  /**
  * Proses Interaksi dengan database
  * ketika user melakukan checkout
  *
  * @return Array
  */
  public function checkout() {
    // DEKLARASI VARIABLES
    $id_produk = $this->input->post('id_produk[]');
    $id_keranjang = $this->input->post('id_keranjang');
    $id_user = sud('id_user');
    $catatan = $this->input->post('catatan');
    $harga_sementara = $this->input->post('harga_sementara');
    $kuantitas = $this->input->post('kuantitas');
    $id_pesanan = $id_user.time();
    $nama_penerima = htmlspecialchars($this->input->post('nama_penerima'), true);
    $phone = htmlspecialchars($this->input->post('no_penerima'), true);
    $prov = htmlspecialchars($this->input->post('prov'), true);
    $kab = htmlspecialchars($this->input->post('kab'), true);
    $kec = htmlspecialchars($this->input->post('kec'), true);
    $kode_pos = htmlspecialchars($this->input->post('kode_pos'), true);
    $detail_alamat = htmlspecialchars($this->input->post('detail_alamat'), true);
    $opsi_beli = htmlspecialchars($this->input->post('opsiPembelian'), true);
    $kurir = htmlspecialchars($this->input->post('kurir'), true);
    $resi = htmlspecialchars($this->input->post('resi'), true);
    $bayar = htmlspecialchars($this->input->post('total_harga'), true);
    $ongkir = htmlspecialchars($this->input->post('ongkir'), true);
    $total_bayar = htmlspecialchars($this->input->post('total_bayar'), true);
    $layanan = htmlspecialchars($this->input->post('layanan', TRUE));
    $prk_datang = $this->input->post('tanggal').' '.$this->input->post('waktu').date(':s');
    $no_meja = htmlspecialchars($this->input->post('no_meja'), true);
    $address_customer = [
      "customer" => [
        "name" => $nama_penerima,
        "phone" => $phone
      ],
      "address" => [
        "prov" => $prov,
        "kab" => $kab,
        "kec" => $kec,
        "kode_pos" => $kode_pos,
        "detail" => $detail_alamat
      ]];
    $data_penerima = json_encode($address_customer);
    $opsi_beli == 1 ? $data['layanan'] = explode(',', $layanan)[1] : $data['layanan'] = '-';
    $data = [
      'id_pesanan' => $id_pesanan,
      'id_user' => $id_user,
      'bayar' => $bayar,
      'ongkir' => $ongkir,
      'total_bayar' => $total_bayar,
      'kurir' => $kurir,
      'layanan' => $layanan,
      'resi' => '',
      'status' => 'new',
      'opsi_beli' => $opsi_beli,
      'data_penerima' => $data_penerima,
      'tgl_buat_pesanan' => dt()
    ];


    // insert data pesanan
    $insert = $this->db->insert('pesanan', $data);

    // ketika insert data pesanan berhail
    if ($insert) {

      // multy insert item pesanan
      // multy delete produk dalam keranjang
      for ($i = 0; $i < count($id_produk); $i++) {
        $data = [
          'id_produk' => $id_produk[$i],
          'id_pesanan' => $id_pesanan,
          'catatan' => $catatan[$i],
          'harga_sementara' => $harga_sementara[$i],
          'kuantitas' => $kuantitas[$i]
        ];
        $this->db->insert('item_pesanan', $data);
        $this->db->delete('keranjang', ['id_keranjang' => $id_keranjang[$i]]);
      }

      // ketika opsiPembelian 2
      // pemesanan user dengan ditempat
      if ($opsi_beli == 2) {
        $this->_booking_tempat($no_meja, $prk_datang, $id_pesanan);
      }

      // mengembalikan data dalam bentuk json
      echo json_encode(['status' => TRUE]);
    }
  }



  /**
  * Proses Interaksi dengan database
  * ketika user melakukan checkout
  * memiliki opsi ditempat
  *
  */
  private function _booking_tempat($no_meja, $prk_datang, $id_pesanan) {
    $data = [
      'no_meja' => $no_meja,
      'prk_datang' => $prk_datang,
      'id_pesanan' => $id_pesanan
    ];
    $this->db->insert('booking_tempat', $data);
  }



}