<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Class Auth
* @property auth_model | Model
*/

class Auth extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('auth_model', 'auth');
  }

  /**
  * Redirect user sesuai role
  */
  private function _redirect() {
    return $this->db->get_where('role', ['id' => sud('role_id')])->row()->redirect;
  }

  /**
  * Halaman Pendaftarna user
  */
  public function daftar() {
    sud('email') != '' ? redirect(site_url($this->_redirect())) : $this->load->view('frontend/register');

  }

  /**
  * Halaman Login user
  */
  public function login() {
    sud('email') != '' ? redirect(site_url($this->_redirect())) : $this->load->view('frontend/login');
  }

  /**
  * Action Logout user
  */
  public function logout() {
    $this->session->sess_destroy();
    redirect('login');
  }



  /**
  * Action pendaftaran user
  */
  public function action_register() {

    // Set aturan pada iinput form pendaftaran
    $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[2]|max_length[10]|is_unique[user.username]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
    $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]');
    $this->form_validation->set_rules('password_confirm', 'password_confirm', 'trim|required|matches[password]');

    if ($this->form_validation->run() == FALSE) {
      // Ketika aturan pada input form pendaftaran tidak terpenuhi

      $err = [
        'username' => form_error('username'),
        'email' => form_error('email'),
        'phone' => form_error('phone'),
        'password' => form_error('password'),
        'password_confirm' => form_error('password_confirm')
      ];
      echo json_encode(['status' => FALSE, 'err' => $err]);
    } else {
      // Ketika aturan pada input form pendaftaran terpenuhi
      // melakukan aksi pada model auth_model method register
      $this->auth->register();
    }

  }


  /**
  * Ketika pendaftaran Berhasil
  * Sebelum redirect ke halaman Login
  */
  public function success_register() {
    $this->session->set_flashdata('success', 'Pendaftaran Berhasil \n periksa email untuk verifikasi!');
    redirect(site_url('login'));
  }


  /**
  * Action login user
  */
  public function action_login() {

    // Set aturan pada iinput form login
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() == FALSE) {
      // Ketika aturan pada input form login tidak terpenuhi
      $err = [
        'email' => form_error('email'),
        'password' => form_error('password')
      ];
      echo json_encode(['status' => FALSE, 'err' => $err]);
    } else {
      // Ketika aturan pada input form login terpenuhi
      // melakukan aksi pada model auth_model method login
      $this->auth->login();
    }

  }




  /**
  * Ketika pendaftaran Berhasil
  * Sebelum redirect ke halaman Login
  */
  public function verify() {
    // Deklarasi Variables
    $email = $this->input->get('email');
    $token = $this->input->get('token');

    $user = $this->db->get_where('user', ['email' => $email])->num_rows();
    // Jika email ada didatabase table user
    if ($user > 0) {
      $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

      if ($user_token) {

        /**
        * Masa berlalu token adalah 1 hari
        */
        $expired = 60*60*24;

        // Jika token belum kadaluarsa
        if (time() - strtotime($user_token['created_at']) < $expired) {
          $this->db->update('user', ['is_active' => 1], ['email' => $email]);
          $this->db->delete('user_token', ['email' => $email]);
          $this->session->set_flashdata('success', 'Verifikasi Berhasil \n Silahkan Login!');
          redirect(site_url('login'));
        }
        // Jika token sudah kadaluarsa
        else
        {
          $id = $this->db->get_where('user', ['email' => $email])->row()->id;
          $this->db->delete('user', ['email' => $email]);
          $this->db->delete('poin', ['id_user' => $id]);
          $this->db->delete('user_token', ['email' => $email]);
          echo 'token sudah kadaluarsa';
        }
      }
      // Jika token tidak valid
      else
      {
        echo '<h1>tokennya salah kak</h1>';
      }
    }
    // Jika user tidak ada didatabase table user
    else
    {
      echo '<h1>emailnya salah kak</h1>';
    }
  }




  // LUPA PASSWORD
  public function forgotpw() {

    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|',
      [
        'required' => 'email harus diisi!',
        'valid_email' => 'masukan email yang benar'
      ]);

    if ($this->form_validation->run() == FALSE) {
      $page = 'auth/lupapw';
      $data['title'] = 'Lupa Password';
      $this->load->view('auth/lupapw', $data);
    } else
    {
      echo 'berhasil'; die;
    }

  }

}