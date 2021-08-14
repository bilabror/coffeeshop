<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pesanan extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Pesanan_model', 'pesanan');
    proteksi();
  }

  // HALAMAN LAPORAN PESANAN
  public function index() {
    $data['title'] = 'Laporan Pesanan';
    pages('dashboard/laporan/pesanan', $data);
  }


  public function pdf_pesanan() {
    $tanggalawal = $this->input->get('awal');
    $tanggalakhir = $this->input->get('akhir');
    if (isset($_GET['opsibeli'])) {
      $opsi_beli = $this->input->get('opsibeli');
    } else {
      $opsi_beli = '';
    }
    $mpdf = new \Mpdf\Mpdf();
    $data['pesanan'] = $this->pesanan->report($tanggalawal, $tanggalakhir, $opsi_beli)->result_array();
    $data['awal'] = $tanggalawal;
    $data['akhir'] = $tanggalakhir;

    $income = 0;
    foreach ($data['pesanan'] as $row) {
      $income += $row['total_bayar'];
    }
    $data['income'] = $income;
    $result = $this->load->view('pdf/laporan-pesanan', $data, TRUE);
    $mpdf->WriteHTML($result);
    $mpdf->Output();
  }



  public function ajax_get_report() {

    $tanggalawal = $this->input->get('awal');
    $tanggalakhir = $this->input->get('akhir');
    $opsi_beli = $this->input->get('opsi_beli');
    $data['pesanan'] = $this->pesanan->report($tanggalawal,
      $tanggalakhir, $opsi_beli)->result_array();
    $data['awal'] = $tanggalawal;
    $data['akhir'] = $tanggalakhir;
    $income = 0;
    foreach ($data['pesanan'] as $row) {
      $income += $row['total_bayar'];
    }
    $data['income'] = rupiah($income);

    $i = 1;
    foreach ($data['pesanan'] as $row) {
      $data_penerima = json_decode($row['data_penerima'],
        true);
      $jumlah_item = $this->db->get_where('item_pesanan',
        ['id_pesanan' => $row['id_pesanan']])->num_rows();
      $td[] = '<tr>';
      $td[] = '<td scope="row" align="center">'.$i++.'</td>';
      $td[] = '<td>'.$row["id_pesanan"].'</td>';
      if ($data_penerima["customer"]["name"] == "kasir") {
        $td[] = "<td> offline</td>";
      } else {
        $td[] = "<td>".$data_penerima["customer"]["name"]."</td>";
      }
      if ($data_penerima["customer"]["phone"] == 0) {
        $td[] = "<td> - </td>";
      } else {
        $td[] = "<td>".$data_penerima["customer"]["phone"]."</td>";
      }
      if ($row['opsi_beli'] == 0) {
        $td[] = '<td>Offline</td>';
      } elseif ($row['opsi_beli'] == 1) {
        $td[] = '<td>Online</td>';
      } elseif ($row['opsi_beli'] == 2) {
        $td[] = '<td>Booking</td>';
      }
      $td[] = '<td>'.rupiah($row["total_bayar"]).'</td>';
      $td[] = '<td>'.date("d-m-Y", strtotime($row["tgl_buat_pesanan"])).'</td>';
      $td[] = '</tr>';
    }

    echo json_encode(['table' => $td, 'penghasilan' => $data['income']]);


  }



  /*
  public function chart_pesanan() {
    $tanggalawal = $this->input->get('awal');
    $tanggalakhir = $this->input->get('akhir');
    $data['pesanan'] = $this->pesanan->report($tanggalawal, $tanggalakhir)->result_array();
    $jumlah_pesanan = [0];
    $i = 1;
    $add = 0;


    $data['awal'] = $tanggalawal;
    $data['akhir'] = $tanggalakhir;
    $awal = strtotime($data['awal']);
    $akhir = strtotime($data['akhir'])+ (60 * 60 * 24);
    $data['label'] = [];
    $data['jumlah_terjual'] = [];
    for ($awal; $awal < $akhir; $awal += (60 * 60 * 24)) {
      $data['label'][] = date('d-m-Y', $awal);
      $jumlah_terjual = $this->db->get_where('pesanan', ['tgl_buat_pesanan >=' => $awal, 'tgl_buat_pesanan <' => $awal + (60 * 60 * 24)])->num_rows();
      $data['jumlah_terjual'][] = $jumlah_terjual;
    }


    $income = 0;
    foreach ($data['pesanan'] as $row) {
      $income += $row['total_bayar'];
    }
    $data['income'] = $income;
    $page = 'backend/pesanan-chart';
    $data['title'] = 'Chart Pesanan';
    pages($page,
      $data);
  }

  public function excel_pesanan() {
    $tanggalawal = $this->input->get('awal');
    $tanggalakhir = $this->input->get('akhir');
    $data['pesanan'] = $this->pesanan->report($tanggalawal, $tanggalakhir)->result_array();
    $data['awal'] = $tanggalawal;
    $data['akhir'] = $tanggalakhir;
    $result = $this->load->view('excel/excel-pesanan', $data);


  }
*/

}