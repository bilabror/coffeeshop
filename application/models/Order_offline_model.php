<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_offline_model extends CI_Model {

  var $table = 'pesanan';

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }


  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = ['id_pesanan',
      'total_bayar',
      'status',
      'tgl_buat_pesanan',
      null];
    $this->datatables->column_search = ['id_pesanan',
      'status',
      'data_penerima'];
    $this->datatables->order = ['tgl_bayar_pesanan' => 'desc'];

    $this->datatables->where = [
      'status !=' => 'new',
      'opsi_beli' => 0,
      'dihapus_penjual' => 0
    ];

  }

  public function get_datatables() {
    $this->_config_datatables();
    $list = $this->datatables->get_datatables();
    $data = [];
    $no = $_POST['start'];



    foreach ($list as $ls) {
      $data_penerima = json_decode($ls->data_penerima);
      $no++;
      $row = [];

      $row[] = '<a href="'.site_url("dashboard/transaksi/offline/detail/").$ls->id_pesanan.'">'.$ls->id_pesanan.'</a>';

      $row[] = $data_penerima->customer->name;
      $row[] = rupiah($ls->total_bayar);
      if ($ls->status == 'pending') {
        $row[] = 'Pesanan Baru';
      } else if ($ls->status == 'terima') {
        $row[] = 'dikemas';
      } else if ($ls->status == 'tolak') {
        $row[] = 'pesanan ditolak';
      } else if ($ls->status == 'kirim') {
        $row[] = 'Dalam pengiriman';
      } else if ($ls->status == 'batal') {
        $row[] = 'dibatalkan';
      } else {
        $row[] = $ls->status;
      }

      if (date("Y-m-d", strtotime($ls->tgl_buat_pesanan)) == date('Y-m-d')) {
        $row[] = time_ago(date("Y-m-d H:i:s", strtotime($ls->tgl_bayar_pesanan)));
      } else {
        $row[] = date("d-m-Y", strtotime($ls->tgl_bayar_pesanan));
      }


      if ($ls->status == 'tolak' || $ls->status == 'selesai' || $ls->status == 'batal') {
        $row[] = '<div class="btn-group"> <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button> <div class ="dropdown-menu"><a class="dropdown-item" href="javascript:void(0)" onclick="delete_order('."'".$ls->id_pesanan."'".')">Hapus</a></div> </div>';
      } elseif ($ls->status == 'pending') {
        $row[] = '<div class="btn-group"> <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button> <div class ="dropdown-menu"><a class="dropdown-item" href="'.site_url("dashboard/transaksi/offline/detail/").$ls->id_pesanan.'">Tinjau</a></div> </div>';
      } elseif ($ls->status == 'terima') {
        $row[] = '<div class="btn-group"> <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button> <div class ="dropdown-menu"><a class="dropdown-item" href="javascript:void(0)" onclick="selesai('."'".$ls->id_pesanan."'".','."'".$ls->status."'".','."'".$ls->resi."'".')">Selesai</a></div> </div>';
      }
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->datatables->count_all(),
      "recordsFiltered" => $this->datatables->count_filtered(),
      "data" => $data,
    ];
    echo json_encode($output);
  }



  /**
  * Proses Interaksi dengan database
  * ketika berada pada halaman detail pesanan
  *
  * @param string $id
  * @param string merupakan id pesanan / nomor invoice
  *
  * @return Array
  */
  public function detail_pesanan($id) {
    $data['invoice'] = $this->pesanan->get_where(['id_pesanan' => $id])->row();
    $data['bukti_pembayaran'] = $this->db->get_where('bukti_pembayaran', ['id_pesanan' => $id])->row_array();

    if ($data['bukti_pembayaran'] != null) {
      $data['bank'] = $this->db->get_where('bank',
        ['code' => $data['bukti_pembayaran']['nama_bank']])->row_array();
      $data['rekening_toko'] = json_decode($data['bukti_pembayaran']['rekening_toko'], true);
    }
    $data['booking'] = $this->db->get_where('booking_tempat', ['id_pesanan' => $id])->row();
    $data_address = json_decode($data['invoice']->data_penerima)->address;
    if (!empty($data_address->prov)) {
      $prov = $this->db->get_where('provinsi', ['id' => $data_address->prov])->row_array()['nama'];
      $kab = $this->db->get_where('kabupaten', ['id' => $data_address->kab])->row_array()['nama'];
      $kec = $this->db->get_where('kecamatan', ['id' => $data_address->kec])->row_array()['nama'];
      $data['address'] = "{$data_address->detail}, {$kec}, {$kab}, {$prov}, {$data_address->kode_pos}";
    }

    $this->db->select('*');
    $this->db->from('item_pesanan');
    $this->db->join('produk', 'produk.id_produk = item_pesanan.id_produk', 'left');
    $this->db->where('item_pesanan.id_pesanan', $data['invoice']->id_pesanan);
    $data['produk'] = $this->db->get()->result();

    return $data;

  }

  public function tracking($id) {
    return $this->pesanan->get_where(['id_pesanan' => $id])->row_array();
  }


  // GET BERDASARKAN ID
  public function get_by_id($id) {
    $this->db->from($this->table);
    $this->db->where('id_pesanan', $id);
    $query = $this->db->get();
    return $query->row();
  }

  // get data tertentu
  public function get_where($where) {
    $this->db->from($this->table);
    $this->db->where($where);
    $this->db->order_by('tgl_buat_pesanan', 'desc');
    $query = $this->db->get();
    return $query;
  }


  public function get_all() {
    return $this->db->get($this->table);
  }



  public function add_pesanan($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update_pesanan($data, $where) {
    $this->db
    ->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function delete_pesanan($where) {
    $this->db->delete($this->table, $where);
  }

  public function report($tanggalawal = null, $tanggalakhir = null) {
    if ($tanggalawal && $tanggalakhir) {
      $this->db->where('tgl_buat_pesanan >=', "$tanggalawal 00:00:00");
      $this->db->where('tgl_buat_pesanan <=', "$tanggalakhir 23:59:59");
    }
    $this->db->order_by('tgl_buat_pesanan', 'asc');
    $this->db->from($this->table);
    //$this->db->join('item_pesanan', 'item_pesanan.id_pesanan = pesanan.id_pesanan', 'right');
    return $this->db->get();
  }







}