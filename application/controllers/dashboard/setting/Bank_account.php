<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account extends CI_Controller {

  public function __construct() {
    parent::__construct();
    proteksi();
  }

  // HALAMAN PENGATURAN AKUN BANK TOKO
  public function index() {
    $data['rekening_toko'] = $this->db->get('rekening_toko')->result_array();
    $data['title'] = 'Setting Bank Account';
    pages('dashboard/pengaturan/akun_bank', $data);
  }

  // MENAMBAHKAN AKUN PEMBAYARAN TOKO
  public function add_payment() {
    $bank = htmlspecialchars($this->input->post('bank'), true);
    $norek = htmlspecialchars($this->input->post('norek'), true);
    $atas_nama = htmlspecialchars($this->input->post('atas_nama'), true);
    $data = [
      'bank' => $bank,
      'norek' => $norek,
      'atas_nama' => $atas_nama
    ];
    $this->db->insert('rekening_toko', $data);
    redirect(site_url('dashboard/setting/bank-account'));
    $this->session->set_flashdata('success', 'Rekening berhasil ditambahkan');

  }

  // EDIT PEMBAYARAN TOKO
  public function edit_payment() {
    $id = $this->input->post('id');
    $bank = htmlspecialchars($this->input->post('bank'), true);
    $norek = htmlspecialchars($this->input->post('norek'), true);
    $atas_nama = htmlspecialchars($this->input->post('atas_nama'), true);
    $data = [
      'bank' => $bank,
      'norek' => $norek,
      'atas_nama' => $atas_nama
    ];
    $this->db->update('rekening_toko', $data, ['id' => $id]);
    redirect(site_url('dashboard/setting/bank-account'));
    $this->session->set_flashdata('success', 'Rekening berhasil diubah');

  }

  //MENGHAPUS PEMBAYARAN TOKO
  public function delete_payment($id) {
    $this->db->delete('rekening_toko', ['id' => $id]);
    echo json_encode(['status' => 'success']);
  }

}