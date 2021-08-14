<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {



  public function __construct() {
    parent::__construct();
    proteksi();
  }

  public function index() {
    $email = $this->session->userdata('email');
    $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
    $page = 'admin/dashboard';
    $data['title'] = 'Backup database';
    //pages($page, $data);
    $this->load->helper('file');

    $namaC = 'NewController';
    $data = "
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {$namaC} extends CI_Controller {


}";



    if (! write_file("./application/controllers/{$namaC}.php", $data)) {
      echo 'Unable to write the file';
    } else
    {
      echo 'File written!';
    }

    //$this->load->view('sistem/backup', $data);
  }

  public function backup() {
    $this->load->dbutil();

    $aturan = array (
      'format' => 'zip',
      'filename' => 'my_db_backup.sql'
    );
    $backup =& $this->dbutil->backup($aturan);
    // Nama database sudah ada tanggal downloadnya
    $nama_database = 'backup-on-'. date("Y-m-d-H-i-5") .'.zip';
    $simpan = './backup/database/'.$nama_database;
    $this->load->helper('file');
    write_file($simpan, $backup);
    $this->load->helper('download');
    force_download($nama_database, $backup);
  }

  function restoredb() {
    $isi_file = file_get_contents('./backup/database/mybackup.sql');
    $string_query = rtrim($isi_file, "\n; ");
    $array_query = explode("; ", $query);
    foreach ($array_query as $query) {
      $this->db->query($query);
    }
  }


}