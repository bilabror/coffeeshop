<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Auth Model
* @property Auth | Controller
*/

class Auth_model extends CI_Model {

  public function __construct() {
    parent::__construct();
  }

  /**
  * Proses Interaksi dengan database
  * ketika melakukan pendaftaran user
  *
  * @return Boolean
  */
  public function register() {

    $username = htmlspecialchars($this->input->post('username'), TRUE);
    $email = htmlspecialchars($this->input->post('email'), TRUE);
    $phone = htmlspecialchars($this->input->post('phone'), TRUE);
    $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

    $data_user = [
      'username' => $username,
      'email' => $email,
      'phone' => $phone,
      'password' => $password,
      'avatar' => 'avatar.png',
      'is_active' => 0,
      'role_id' => 2,
      'created_at' => dt()
    ];
    // Insert data ke Table User
    $insert = $this->db->insert('user', $data_user);
    if ($insert) {

      // Insert data ke Table Poin
      $this->db->insert('poin', ['poin' => 0, 'id_user' => $this->db->insert_id()]);

      // Membuat Random byte
      $token = base64_encode(random_bytes(32));

      // Prepare Data Token
      $user_token = [
        'token' => $token,
        'email' => $email,
        'created_at' => dt()
      ];
      // Insert data ke Table user_token
      $insert = $this->db->insert('user_token', $user_token);

      if ($insert) {
        if ($this->_sendmail($token, '_verify_register', $email) == TRUE) {
          echo json_encode(['status' => TRUE, 'url' => 'login']);

        }
      }

    }

  }

  /**
  * Proses Interaksi dengan database
  * ketika melakukan login user
  *
  * @return Boolean
  */
  public function login() {

    $email = $this->input->post('email');
    $password = $this->input->post('password');

    $user = $this->db->get_where('user', ['email' => $email])->row_array();
    if ($user) {
      // Ketika email ada di database

      if ($user['is_active'] == 1) {
        // Ketika status user aktif / sudah melakukan verify

        if (password_verify($password, $user['password'])) {
          // Ketika password pada input form sesuai dengan database

          $data = [
            'id_user' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role_id' => $user['role_id']
          ];
          // Set session userdata
          $this->session->set_userdata($data);

          $redirect = $this->db->get_where('role', ['id' => $user['role_id']])->row_array()['redirect'];
          echo json_encode(['status' => TRUE, 'url' => $redirect]);
        } else {
          // Ketika password pada input form tidak sesuai dengan database
          $err = [
            'email' => '',
            'password' => 'Password salah'
          ];
          echo json_encode(['status' => FALSE, 'err' => $err]);
        }
      } else {
        // Ketika user belum aktif atau terblokir
        $err = [
          'email' => 'Akun Belum Aktif, Silhakan periksa email untuk verifikasi!',
          'password' => ''
        ];
        echo json_encode(['status' => FALSE, 'err' => $err]);
      }
    } else {
      // Ketika Email tidak terdaftar di database
      $err = [
        'email' => 'email tidak terdaftar didatabase',
        'password' => ''
      ];
      echo json_encode(['status' => FALSE, 'err' => $err]);
    }
  }



  /**
  * Proses Interaksi pengiriman email
  * @property Email | email library
  *
  * @return Boolean
  */
  private function _sendmail($token, $type, $to) {
    $set = getset('email_config');

    // Deklarasi Config
    $config = [
      'protocol' => 'smtp',
      'smtp_host' => $set['smtp_host'],
      'smtp_user' => $set['smtp_username'],
      'smtp_pass' => $set['smtp_password'],
      'smtp_port' => $set['smtp_port'],
      'mailtype' => 'html',
      'charset' => 'utf-8',
      'newline' => "\r\n"
    ];

    // LOAD LIBRARY EMAIL
    $this->load->library('email', $config);

    // Set Email dari
    $this->email->from($set['from_address'], $set['from_name']);
    // Set Email Kepada
    $this->email->to($to);

    // Kirim email untuk verifikasi pendaftaran
    if ($type == '_verify_register') {
      $this->email->subject('Verifikasi Akun');
      $this->email->message('Klik Link Dibawah ini untuk verifikasi <br> <a href="'.base_url() .'auth/verify?email='.$to.'&token='.urlencode($token).'">Aktifasi Akun');
    }

    // Ketika Email Berhasil Dikirim
    if ($this->email->send()) {
      return TRUE;
    }
    // Ketika Email Gagal Dikirim
    else
    {
      echo $this->email->print_debugger();
      die;
    }
  }


}