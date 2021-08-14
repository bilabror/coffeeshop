<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {


  public function __construct() {
    parent::__construct();
    $this->load->model('Produk_model', 'produk');
    $this->key_rajaongkir = '0c017604e721bbe96b59dbe272f58e9c';
    $this->key_binderbyte = 'da8b19505795176e5d97647e831706cdfce73f01126980bf48c3728e71ceec80';
  }

  public function count_cart() {
    echo $this->db->get_where('keranjang', ['id_user' => sud('id_user')])->num_rows();
  }
  public function count_notifikasi() {
    echo $this->db->get_where('notifikasi', ['id_user' => sud('id_user')])->num_rows();
  }

  public function produk() {

    $id_kategori = $this->input->get('id_kategori');
    $search = $this->input->get('search');
    $data['produk'] = $this->produk->get();
    if (!empty($id_kategori)) {
      $data['produk'] = $this->produk->get_where(['produk.id_kategori' => $id_kategori])->result();
    }
    if (!empty($search)) {
      $data['produk'] = $this->produk->get_like(['nama_produk' => $search]);
    }
    return $this->load->view('ajax_produk', $data);
  }



  public function produk_vbarcode() {
    $id_kategori = $this->input->get('id_kategori');
    $search = $this->input->get('search');
    $data['produk'] = $this->produk->get();
    if (!empty($id_kategori)) {
      $data['produk'] = $this->produk->get_where(['produk.id_kategori' => $id_kategori])->result();
    }
    if (!empty($search)) {
      $data['produk'] = $this->produk->get_like(['nama_produk' => $search]);
    }
    return $this->load->view('ajax_produk_vbarcode', $data);
  }

  public function pengaturan() {
    $get = $this->db->get('pengaturan')->result_array();
    $data = [];
    foreach ($get as $val) {
      $val['key'] == 'alamat_toko' || $val['key'] == 'email_config'
      ? $data += [$val['key'] => json_decode($val['konten'], TRUE)]
      : $data += [$val['key'] => $val['konten']];
    }
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($data));
  }

  /**
  * Method search_autocomplete :
  * Fungsi untuk menyesuaikan keberadaan
  * antara produk yang dicari dengan produk yang ada didatabase
  */
  public function search_autocomplete() {
    if (isset($_GET['term'])) {
      $produk = $this->produk->get_like(['nama_produk' => $_GET['term']]);
    } else {
      $produk = $this->produk->get();
    }

    foreach ($produk as $row) {
      $result[] = [
        'label' => $row->nama_produk,
        'slug' => $row->slug_produk,
        'id' => $row->id_produk
      ];
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($result));
    }


  }

  public function provinsi() {
    $this->db->order_by('nama', 'asc');
    $data = $this->db->get('provinsi')->result_array();
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($data));
  }

  public function kabupaten($id) {
    $data = $this->db->get_where('kabupaten', ['provinsi_id' => $id])->result_array();
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($data));
  }

  public function kecamatan($id) {
    $data = $this->db->get_where('kecamatan', ['kabupaten_id' => $id])->result_array();
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($data));
  }

  public function courier() {
    $getdata = json_decode($this->db->get_where('pengaturan', ['key' => 'alamat_toko'])->row_array()['konten'], TRUE);

    $curl = curl_init();

    $origin = $getdata['kab'];
    $dest = $this->input->post('dest');
    $weight = $this->input->post('total_berat');
    $courier = $this->input->post('kurir');

    curl_setopt_array($curl,
      array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=211&destination=$dest&weight=$weight&courier=$courier",
        CURLOPT_HTTPHEADER => array(
          "content-type: application/x-www-form-urlencoded",
          "key: $this->key_rajaongkir"
        ),
      ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;

      $data = json_decode($response, TRUE);
      echo '<option value="" selected disabled>--- PILIH LAYANAN ---</option>';
      for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {

        for ($l = 0; $l < count($data['rajaongkir']['results'][$i]['costs']); $l++) {

          echo '<option value="'.$data['rajaongkir']['results'][$i]['costs'][$l]['cost'][0]['value'].','.$data['rajaongkir']['results'][$i]['costs'][$l]['service'].'('.$data['rajaongkir']['results'][$i]['costs'][$l]['description'].')">';
          echo $data['rajaongkir']['results'][$i]['costs'][$l]['service'].'('.$data['rajaongkir']['results'][$i]['costs'][$l]['description'].')</option>';

        }

      }

    }
  }


  public function ongkir() {
    $biaya = explode(',', $this->input->post('layanan', TRUE));
    $total = $this->input->post('total_bayar') + $biaya[0];
    echo $biaya[0].','.$total;
  }

  public function tracking_resi() {

    $curl = curl_init();

    $resi = $this->input->post('resi');
    $kurir = $this->input->post('kurir');

    curl_setopt_array($curl,
      array(
        CURLOPT_URL => "https://api.binderbyte.com/v1/track?api_key=$this->key_binderbyte&courier=jne&awb=8827722030541463",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
      ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;

    }







  }


}