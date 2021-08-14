<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Home
* @property home_model|Model
*/
class Home extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
    $this->load->model('Home_model', 'home');
    expired_token();
  }




  /**
  * Halaman Beranda
  * atau
  * Halaman Utama
  */
  public function index() {

    // memanggil method beranda dari Home Model
    $beranda = $this->home->beranda();

    // Memasukan data dari model ke dalam variables
    $data['slider'] = $beranda['slider'];
    $data['rekomendasi'] = $beranda['rekomendasi'];
    $data['produk'] = $beranda['all_produk'];

    // judul halaman
    $data['title'] = 'Beranda';
    pages_frontend('frontend/beranda', $data);
  }


  /**
  *
  * Mengambil data produk Berdasarkan id (@post)
  * @return json
  *
  */
  public function produk_by_id() {
    // memasukan id (@post) kedalam variable
    $id = $this->input->post('id_produk');
    // get data produk berdasarkan id
    $data = $this->produk->get_by_id($id);

    // return data dengan format Array
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($data));
  }


  /**
  *
  * Halaman detail produk
  *
  * @param string $slug
  *
  */
  public function produk($slug) {

    // get data produk berdasarkan slug
    $produk = $this->produk->get_where(['slug_produk' => $slug]);

    // get data produk random limit 7
    $this->db->order_by('rand()');
    $this->db->limit(7);
    $produk_random = $this->produk->get();
    $data['produkrand'] = $produk_random;


    $this->db->select('ulasan_produk.*,user.username');
    $this->db->from('ulasan_produk');
    $this->db->join('user', 'ulasan_produk.id_user = user.id');
    $this->db->where('id_produk', $produk->row()->id_produk);
    $this->db->order_by('tgl_buat', 'DESC');
    $data['ulasan'] = $this->db->get()->result_array();
    $stmt = $this->db->query('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_ulasan FROM ulasan_produk WHERE id_produk = '.$produk->row()->id_produk);
    $data['ulasan_info'] = $stmt->row_array();



    if ($produk->num_rows() > 0) {
      $data['produk'] = $produk->row_array();
      $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row();
      $data['title'] = 'Home';
      $data['title'] = 'Detail Produk';
      pages_frontend('frontend/detail_produk', $data);
    } else {
      redirect(site_url());
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