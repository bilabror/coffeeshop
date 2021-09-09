<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller User
 *
 * Controller ini berperan untuk mengatur bagian User
 * 
 */
class User extends CI_Controller {

  /**
	 * Class constructor
	 *
	 * @return	void
	 */
  public function __construct() {
    parent::__construct();
    $this->load->model('User_model', 'user');
    proteksi();
  }

  /**
	 * Index Method
	 *
	 * @return view
	 */
  public function index() {
    $data['role'] = $this->db->get('role')->result_array();
    $data['title'] = 'Data User';
    pages('dashboard/master/user', $data);
  }

/**
	 * Detail Method
   * @param int $id kunci table
	 *
	 * @return view
	 */
  public function detail($id) {
    $data['user'] = $this->db->get_where('user', ['id' => $id])->row();
    $data['poin'] = $this->db->get_where('poin', ['id_user' => $id])->row()->poin;
    $data['transaksi'] = $this->db->get_where('pesanan', ['id_user' => $id])->num_rows();
    $data['review'] = $this->db->get_where('ulasan_produk', ['id_user' => $id])->num_rows();
    $data['title'] = 'Detail User';
    pages('dashboard/master/user_detail', $data);
  }

  /**
	 * Status Method
	 *
	 * @return json
	 */
  public function status() {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    if ($status == 1) {
      $status = 0;
      $this->user->update(['id' => $id], ['is_active' => $status]);
      echo json_encode(array("status" => TRUE));
    } else
    {
      $status = 1;
      $this->user->update(['id' => $id], ['is_active' => $status]);
      echo json_encode(array("status" => TRUE));
    }

  }

  /**
	 * Get data dengan style datatable
	 *
	 * @return json
	 */
  public function get_datatables() {
    $this->user->get_datatables();
  }

/**
	 * aksi Reset/Restart Password
	 *
	 * @return json
	 */
  public function reset_password() {
    $id = htmlspecialchars($this->input->post('id'));
    $data = ['password' => password_hash('12345678', PASSWORD_DEFAULT)];
    $this->db->update('user', $data, ['id' => $id]);
    echo json_encode(['status' => true]);
  }

   /**
	 * get data berdasarkan id untuk diedit
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_edit($id) {
    $data = $this->user->get_by_id($id);
    echo json_encode($data);
  }

  /**
	 * aksi tambah data
	 *
	 * @return json
	 */
  public function ajax_add() {
    $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[2]');
    $this->form_validation->set_rules('role_id', 'Role_id', 'trim|required');



    if ($this->form_validation->run() == FALSE) {
      $err = [
        'username' => form_error('username'),
        'email' => form_error('email'),
        'password' => form_error('password'),
        'role_id' => form_error('role_id')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {

      $data = array(
        'username' => htmlspecialchars($this->input->post('username'), true),
        'email' => htmlspecialchars($this->input->post('email'), true),
        'avatar' => 'avatar.png',
        'password' => htmlspecialchars(password_hash($this->input->post('password'), PASSWORD_DEFAULT)),
        'is_active' => 1,
        'role_id' => htmlspecialchars($this->input->post('role_id'), true),
        'created_at' => dt()
      );
      $insert_user = $this->user->save($data);

      // Insert data ke Table Poin
      $this->db->insert('poin', ['poin' => 0, 'id_user' => $insert_user]);

      echo json_encode(array("status" => TRUE));
    }
  }

  /**
	 * aksi edit data
	 *
	 * @return json
	 */
  public function ajax_update() {

    $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[2]',
      [
        'required' => 'username harus diisi!',
        'min_length' => 'usernamen terlalu pendek!'
      ]);
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email',
      [
        'required' => 'email harus diisi!',
        'valid_email' => 'masukan email yang benar',
        'is_unique' => 'email ini telah digunakan'

      ]);
    $this->form_validation->set_rules('role_id', 'Role_id', 'trim|required',
      [
        'required' => 'role harus dipilih!'
      ]);



    if ($this->form_validation->run() == FALSE) {
      $err = [
        'username' => form_error('username'),
        'email' => form_error('email'),
        'role_id' => form_error('role_id')
      ];
      echo json_encode(["status" => FALSE, 'err' => $err]);
    } else
    {

      $data = array(
        'username' => htmlspecialchars($this->input->post('username'), true),
        'email' => htmlspecialchars($this->input->post('email'), true),
        'role_id' => htmlspecialchars($this->input->post('role_id'), true)
      );
      $this->user->update(array('id' => $this->input->post('id')), $data);
      echo json_encode(array("status" => TRUE));
    }
  }

  /**
	 * Aksi hapus data
	 *
   * @param int $id kunci table
	 * @return json
	 */
  public function ajax_delete($id) {
    $this->db->delete('notifikasi', ['id_user' => $id]);
    $this->db->delete('keranjang', ['id_user' => $id]);
    $this->db->delete('poin', ['id_user' => $id]);
    $this->db->update('pesanan', ['dihapus_pembeli' => 1], ['id_user' => $id]);
    $avatar = $this->user->get_by_id($id)->avatar;
    if ($avatar != 'avatar.png') {
      unlink(FCPATH . 'uploads/image/profile/' . $avatar);
    } else {}
    $this->user->delete_by_id($id);

    echo json_encode(["status" => TRUE]);
  }





}