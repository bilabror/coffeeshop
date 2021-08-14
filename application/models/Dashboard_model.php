<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

  public function sold_today() {
    $this->db->select_sum('terjual');
    $this->db->where(['tgl_buat >=' => date('Y-m-d').' 00:00:00']);
    $result = $this->db->get('produk_terjual')->row_array()['terjual'];
    return $result;
  }

  public function income_today() {
    $this->db->select_sum('total_bayar');
    $this->db->where(['tgl_selesai_pesanan >=' => date('Y-m-d').' 00:00:00']);
    $result = $this->db->get('pesanan')->row_array()['total_bayar'];
    return $result;
  }

  public function income_pending() {
    $where = [
      'tgl_selesai_pesanan' => '',
      'status' => 'pending'
    ];
    $this->db->select_sum('total_bayar');
    $this->db->where($where);
    $result = $this->db->get('pesanan')->row_array()['total_bayar'];
    return $result;
  }


  public function chart_sold() {
    $result = [];
    for ($i = 1; $i <= date('t'); $i++) {
      $date = date("Y-m-d", strtotime(date("Y-m-$i")));
      $this->db->select_sum('terjual');
      $terjual = $this->db->get_where('produk_terjual',
        [
          'tgl_buat >=' => "$date 00:00:00",
          'tgl_buat <' => "$date 23:59:59"
        ])->row_array()['terjual'];
      if ($terjual > 0) {
        $result[] .= $terjual;
      } else {
        $result[] .= 0;
      }
    }

    return $result;
  }


}