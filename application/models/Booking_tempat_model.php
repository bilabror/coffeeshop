<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_tempat_model extends CI_Model {

  var $table = 'booking_tempat';

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->library('Datatables');
  }



  private function _config_datatables() {
    $this->datatables->table = $this->table;
    $this->datatables->column_order = ['id_pesanan',
      null,
      'no_meja',
      'prk_datang',
      'status',
      'tgl_buat',
      null];
    $this->datatables->column_search = ['id_pesanan',
      'status'];
    $this->datatables->order = ['tgl_bayar_pesanan' => 'desc'];

    $this->datatables->seleect = 'booking_tempat.*,pesanan.status,catatan_penjual,data_penerima,tgl_buat_pesanan';
    $this->datatables->join = ['pesanan',
      'pesanan.id_pesanan = booking_tempat.id_pesanan'];
    $this->datatables->where = [
      'status != ' => 'new',
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
      $row[] = '<a href="'.site_url("dashboard/transaksi/booking/detail/").$ls->id_pesanan.'">'.$ls->id_pesanan.'</a>';
      $row[] = $data_penerima->customer->name;
      $row[] = $ls->no_meja;
      $row[] = date('d-m-Y H:i', strtotime($ls->prk_datang));
      if ($ls->status == 'pending') {
        $row[] = 'Request Booking';
      } else if ($ls->status == 'terima') {
        $row[] = 'Menunggu datang';
      } else if ($ls->status == 'tolak') {
        $row[] = 'booking ditolak';
      } else if ($ls->status == 'batal') {
        $row[] = 'dibatalkan';
      } else {
        $row[] = $ls->status;
      }

      if (date("Y-m-d", strtotime($ls->tgl_buat_pesanan)) == date('Y-m-d')) {
        $row[] = time_ago(date("Y-m-d H:i:s", strtotime($ls->tgl_buat_pesanan)));
      } else {
        $row[] = date("d-m-Y", strtotime($ls->tgl_buat_pesanan));
      }


      if ($ls->status == 'tolak' || $ls->status == 'selesai' || $ls->status == 'batal') {
        $row[] = '<div class="btn-group"> <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button> <div class ="dropdown-menu"><a class="dropdown-item" href="'.site_url("dashboard/transaksi/booking/detail/").$ls->id_pesanan.'">Lihat Detail</a><a class="dropdown-item" href="javascript:void(0)" onclick="delete_order('."'".$ls->id_pesanan."'".')">Hapus</a></div> </div>';
      } elseif ($ls->status == 'pending') {
        $row[] = '<div class="btn-group"> <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button> <div class ="dropdown-menu"><a class="dropdown-item" href="'.site_url("dashboard/transaksi/booking/detail/").$ls->id_pesanan.'">Tinjau</a></div> </div>';
      } elseif ($ls->status == 'terima') {
        $row[] = '<div class="btn-group"> <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opsi</button> <div class ="dropdown-menu"><a class="dropdown-item" href="'.site_url("dashboard/transaksi/booking/detail/").$ls->id_pesanan.'">Lihat Detail</a><a class="dropdown-item" href="javascript:void(0)" onclick="selesai('."'".$ls->id_pesanan."'".')">Selesai</a></div> </div>';
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
    $query = $this->db->get();
    return $query;
  }


  public function get_all() {
    return $this->db->get($this->table);
  }


  public function report($tanggalawal = null, $tanggalakhir = null) {
    //$this->db->select('pesanan.*,item_pesanan.id_item_pesanan');
    $tanggalawalbaru = strtotime($tanggalawal);
    $tanggalakhirbaru = strtotime($tanggalakhir);
    if ($tanggalawal && $tanggalakhir) {
      if ($tanggalawal == $tanggalakhir) {
        $tanggalakhir = $tanggalakhir." 23:59:59";
        $tanggalakhirbaru = strtotime($tanggalakhir);
        $this->db->where('tgl_buat_pesanan >=', $tanggalawalbaru);
        $this->db->where('tgl_buat_pesanan <=', $tanggalakhirbaru);
      } else {
        $this->db->where('tgl_buat_pesanan >=', $tanggalawalbaru);
        $this->db->where('tgl_buat_pesanan <=', $tanggalakhirbaru);
      }
    }
    $this->db->order_by('tgl_buat_pesanan', 'asc');
    $this->db->from($this->table);
    //$this->db->join('item_pesanan', 'item_pesanan.id_pesanan = pesanan.id_pesanan', 'right');
    return $this->db->get();
  }

  public function add_pesanan($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update_pesanan($data, $where) {
    $this->db
    ->update('pesanan', $data, $where);
    return $this->db->affected_rows();
  }

  public function delete_pesanan($where) {
    $this->db->delete($this->table, $where);
  }


}