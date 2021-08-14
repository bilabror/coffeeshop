<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_terjual extends CI_Controller {

  public function __construct() {
    parent::__construct();
    proteksi();
  }

  public function index() {
    $page = 'backend/laporan-produk-terjual';
    $data['title'] = 'Laporan Produk Terjual';
    pages('dashboard/laporan/produk_terjual', $data);
  }


  public function pdf_produk_terjual() {

    $this->db->select('produk_terjual.*,produk.nama_produk');
    $this->db->from('produk_terjual');
    $this->db->join('produk', 'produk_terjual.id_produk = produk.id_produk');
    $tanggalawal = $this->input->get('awal');
    $tanggalakhir = $this->input->get('akhir');
    if ($tanggalawal && $tanggalakhir) {
      $this->db->where('tgl_buat >=', "$tanggalawal 00:00:00");
      $this->db->where('tgl_buat <=', "$tanggalakhir 23:59:59");
    }
    $this->db->order_by('tgl_buat', 'asc');
    $mpdf = new \Mpdf\Mpdf();
    $data['produk_terjual'] = $this->db->get()->result_array();
    $sum = null;
    foreach ($data['produk_terjual'] as $val) {
      $sum += $val['terjual'];
    }
    $data['sum_terjual'] = $sum;
    $data['awal'] = $tanggalawal;
    $data['akhir'] = $tanggalakhir;
    $result = $this->load->view('pdf/laporan-produk-terjual', $data, TRUE);
    $mpdf->WriteHTML($result);
    $mpdf->Output();
  }



  public function excel_produk_terjual() {
    $this->db->select('*');
    $this->db->from('produk');
    $this->db->join('produk_terjual', 'produk_terjual.id_produk = produk.id_produk');
    $tanggalawal = $this->input->get('awal');
    $tanggalakhir = $this->input->get('akhir');
    $tanggalawalbaru = strtotime($tanggalawal);
    $tanggalakhirbaru = strtotime($tanggalakhir);
    if ($tanggalawal && $tanggalakhir) {
      if ($tanggalawal == $tanggalakhir) {
        $tanggalakhir = $tanggalakhir." 23:59:00";
        $tanggalakhirbaru = strtotime($tanggalakhir);
        $this->db->where('tgl_buat >= ', $tanggalawalbaru);
        $this->db->where('tgl_buat <= ', $tanggalakhirbaru);
      } else {
        $this->db->where('tgl_buat >= ', $tanggalawalbaru);
        $this->db->where('tgl_buat <= ', $tanggalakhirbaru);
      }
    }
    $this->db->order_by('tgl_buat', 'asc');
    $mpdf = new \Mpdf\Mpdf();
    $data['pesanan'] = $this->db->get()->result_array();
    $data['awal'] = $tanggalawal;
    $data['akhir'] = $this->input->get('akhir');
    $result = $this->load->view('excel/excel-produk-terjual', $data);


  }


}