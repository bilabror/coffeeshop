<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {


  public function __construct() {
    parent::__construct();
    $this->load->model('Dashboard_model', 'dashboard');
    $this->load->model('produk_model', 'produk');
    $this->load->model('Pesanan_model', 'pesanan');
    $this->load->model('User_model', 'user');
    order_expired();
    proteksi();
  }

  /**
  *  Method Index pada controller ini adalah :
  *  halaman ini berisi data ringkasan pada aplikasi ini
  */
  public function index() {
    // PESANAN BARU
    $data['order'] = $this->pesanan->get_where(['status' => 'pending'])->result_array();
    // JUMLAH PRODUK TERJUAL HARI INI
    $data['terjual'] = 0 + $this->dashboard->sold_today();
    // JUMLAH PENGHASILAN HARI INI
    $data['income'] = $this->dashboard->income_today();
    // JUMLAH PENGHASILAN DALAM PENDING
    $data['income_pending'] = 0 + $this->dashboard->income_pending();
    // LABEL CHART
    $data['labels'] = $this->_bulan_ini();
    // DATA PRODUK TERJUAL UNTUK CHART
    $data['chart_sold'] = $this->dashboard->chart_sold();
    // JUMLAH PRODUK
    $data['produk'] = $this->produk->get_all()->num_rows();
    // JUMLAH CUSTOMER
    $data['pelanggan'] = $this->user->get_where(['role_id' => 2])->num_rows();
    // JUMLAH ORDERAN BARU
    $data['pesanan'] = $this->pesanan->get_where(['status' => 'pending'])->num_rows();

    $data['title'] = 'Dashboard';
    pages('dashboard/dashboard', $data);
  }

  public function qrcode() {
    $data_qr = $this->db->get('qrcode')->num_rows();

    $str = 'ini-adalah-token-random';
    $param = str_shuffle($str);
    $param = urlencode($param);
    $data['param'] = "{$param}.png";
    $this->load->library('ciqrcode');
    $params['data'] = $param;
    $params['level'] = 'H';
    $params['size'] = 10;
    $params['savename'] = FCPATH."{$param}.png";
    $qrcode = $this->ciqrcode->generate($params);
    if ($qrcode) {
      if ($data_qr < 1) {
        $this->db->insert('qrcode', ['param' => $param]);
      } else {
        $qr_param = $this->db->get('qrcode')->row()->param;
         if(file_exists(FCPATH."{$qr_param}.png")){
    		unlink(FCPATH."{$qr_param}.png");
   	 }
        $this->db->update('qrcode', ['param' => $param]);
      }
      $this->load->view('dashboard/qrcode', $data);
    }

  }





  private function _bulan_ini() {
    $data = [];
    for ($i = 1; $i <= date('t'); $i++) {
      $d = sprintf("%02d", $i);
      $data[] .= strtotime(date('Y-m-'.$d));
    };
    return $data;
  }


}