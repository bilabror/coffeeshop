<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Home
 *
 * Controller ini berperan untuk mengatur Frontend
 * 
 */
class Home extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Home_model', 'home');
    expired_token();
  }




  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $beranda = $this->home->beranda();
    $data['slider'] = $beranda['slider'];
    $data['rekomendasi'] = $beranda['rekomendasi'];
    $data['produk'] = $beranda['all_produk'];

    $data['title'] = 'Beranda';
    pages_frontend('frontend/beranda', $data);
  }


  
  /**
	 * get data Produk berdasarkan id
	 *
	 * @return json
	 */
  public function produk_by_id() {
    $id = $this->input->post('id_produk');
    $data = $this->produk->get_by_id($id);

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($data));
  }


  
  /**
	 * Halaman Detail Produk
	 *
   * @param string $slug Slug Produk
	 * @return view
	 */
  public function produk($slug) {
    $model = $this->home->detailProduk($slug);
    if($model == FALSE) return redirect(site_url());
    $data['produkrand'] = $model['produkrand'];
    $data['ulasan'] = $model['ulasan'];
    $data['ulasan_info'] = $model['ulasan_info'];
    $data['produk'] = $model['produk'];
    if (count($model) > 0) {
      $data['user'] = $model['user'];
      $data['title'] = 'Detail Produk';
      pages_frontend('frontend/detail_produk', $data);
    } else {
      
    }

  }


  /**
  *
  * Halaman Lacak Pesanan
  *
  * @param Integer $id
  *
  */
  public function tracking($id) {
    harus_login();
    $data['pesanan'] = $this->db->get_where('pesanan', ['id_pesanan' => $id])->row_array();
    $data['title'] = 'Beranda';
    pages_frontend('frontend/tracking_resi', $data);
  }






  /**
  * Halaman Beranda
  * atau
  * Halaman Utama
  */
  public function order_vqrcode($param = null) {

    $cek_qrcode = $this->db->get_where('qrcode', ['param' => $param])->num_rows();
    if ($cek_qrcode > 0) {
      $param = base64_encode(random_bytes(32));
      $this->db->update('qrcode', ['param' => $param]);

      // judul halaman
      $data['title'] = 'Order via qrcode';
      $this->load->view('frontend/order_via_qrcode', $data);


    } else {
      echo "token tidak valid / telah kedaluarsa";
    }



  }


  public function add_order_vqrcode() {
    // deklarasi variables
    $total_bayar = htmlspecialchars($this->input->post('total_harga'), true);
    $bayar = htmlspecialchars($this->input->post('total_harga'), true);
    $nama = htmlspecialchars($this->input->post('nama'), true);
    $id_user = htmlspecialchars($this->input->post('id_user'), true);
    $id_pesanan = $id_user.time();
    $address_customer = [
      "customer" => [
        "name" => $nama,
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
      'id_user' => $id_user,
      'bayar' => $bayar,
      'ongkir' => 0,
      'total_bayar' => $total_bayar,
      'kurir' => '',
      'layanan' => '',
      'resi' => '',
      'status' => 'pending',
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
      $id_produk = $this->input->post('id');
      $kuantitas = $this->input->post('kuantitas');
      $harga = $this->input->post('harga');
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
        // UNTUK DATA TERTENTU
        $where = [
          'id_produk' => $id_produk[$i],
          'tgl_buat >= ' => date('Y-m-d').' 00:00:00',
          'tgl_buat <= ' => date('Y-m-d').' 23:59:59'
        ];
        // GET DATA PRODUK TERJUAL HARI INI BERDASARKAN ID
        $terjual = $this->db->get_where('produk_terjual', $where);
        // KETIKA DATA PRODUK TERJUAL TIDAK ADA
        if ($terjual->num_rows() < 1) {
          // PREPARE DATA PRODUK TERJUAL
          $data = [
            'id_produk' => $id_produk[$i],
            'terjual' => $kuantitas[$i],
            'tgl_buat' => dt()
          ];
          // INSERT DATA PRODUK TERJUAL KE DATABASE
          $this->db->insert('produk_terjual', $data);
        }
        // KETIKA DATA PRODUK TERJUAL ADA
        else {
          // PREPARE DATA PRODUK TERJUAAL
          $data = [
            'terjual' => $terjual->row_array()['terjual']+$kuantitas[$i]
          ];
          // MELAKUKAN PERUBAHAN PADA JUMLAH PRODUK TERJUAL
          $this->db->update('produk_terjual', $data, ['id_produk' => $id_produk[$i]]);
        }
        // GET STOK PRODUK DARI TABLE PRODUK
        $produk = $this->db->get_where('produk', ['id_produk' => $id_produk[$i]])->row_array()['stok_produk'];
        // UPDATE STOK PRODUK
        $this->db->update('produk', ['stok_produk' => $produk-$kuantitas[$i]], ['id_produk' => $id_produk[$i]]);
      }

    }

    redirect(site_url('home/succes_order_vqrcode'));

  }

  public function succes_order_vqrcode() {
    echo '<h1> ORDERAN SUKSES DILAKUKAN <br> SILAHKAN TUNGGU PAGGILAN DARI KASIR </h1>';

  }



  public function scan() {
    $data['title'] = 'pemesanan via barcode';
    $this->load->view('frontend/qrcode-order', $data);
  }




  public function r_base_url() {
    redirect(site_url());
  }


}