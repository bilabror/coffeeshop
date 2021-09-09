<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Bank Acccount
 *
 * Controller ini berperan untuk mengatur bagian Setting Rekening Bank
 * 
 */
class Bank_account extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    proteksi();
  }

/**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['rekening_toko'] = $this->db->get('rekening_toko')->result_array();
    $data['title'] = 'Setting Bank Account';
    pages('dashboard/pengaturan/akun_bank', $data);
  }

  /**
	 * aksi tambah data
	 *
	 * @return json
	 */
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

/**
	 * aksi edit data
	 *
	 * @return json
	 */
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

/**
	 * Aksi hapus data
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function delete_payment($id) {
    $this->db->delete('rekening_toko', ['id' => $id]);
    echo json_encode(['status' => 'success']);
  }

}