<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Kategori
 *
 * Model ini berperan untuk berinteraksi dengan database table keranjang
 * 
 */
class Keranjang_model extends CI_Model {

   /**
	 * Nama tabel
	 *
	 * @var	string
	 */
  private $table = 'keranjang';




  /**
  * Proses Interaksi dengan database
  * Mengambil data produk yang ada dikeranjang
  * berdasarkan session dan berstatus bukan 1
  *
  * @return Array
  */
  public function index() {
    $this->db->select(
      'produk.nama_produk,
      produk.gambar_produk,
      produk.slug_produk,
      total_harga_produk,
      berat_produk,
      keranjang.*'
    );
    $this->db->from($this->table);
    $this->db->join('produk', 'produk.id_produk = keranjang.id_produk');
    $this->db->where(['keranjang.id_user' => sud('id_user')]);
    $query = $this->db->get();

    $data['total_harga'] = null;
    $data['total_berat'] = null;
    foreach ($query->result_array() as $row) {
      $data['total_harga'] += $row['total_harga_produk']*$row['kuantitas'];
      $data['total_berat'] += $row['berat_produk']*$row['kuantitas'];
    }

    $data['items_keranjang'] = $query->result();
    $data['count_keranjang'] = $query->num_rows();

    return $data;

  }


  /**
	 * Get berdasarkan sesuai yang diinginkan
	 *
   * @param array $where ambil data berdasarkan apa?
	 * @return ArrayObject
	 */
  public function get_where($where) {
    return $this->db->get_where($this->table, $where);
  }


  /**
	 * Get produk dalam keranjang
	 *
   * @param array $where ambil data berdasarkan apa?
	 * @return ArrayObject
	 */
  public function produk_in_cart($where) {
    $this->db->select('keranjang.*,berat_produk,total_harga_produk');
    $this->db->from('keranjang');
    $this->db->join('produk', 'keranjang.id_produk = produk.id_produk', 'left');
    $this->db->where($where);
    return $this->db->get()->row_array();
  }




  /**
	 * Aksi tambah data
	 *
   * @param array items Data yang akan ditambahkan
	 * @return int
	 */
  public function add_cart($items) {

    $produk = $this->get_where(
      [
        'keranjang.id_user' => $items['id_user'],
        'keranjang.id_produk' => $items['id_produk']
      ]);

    $save = FALSE;
    if ($produk->num_rows() > 0) {
      $produk = $produk->row_array();
      $items['kuantitas'] = $produk['kuantitas'] + $items['kuantitas'];
      $update = $this->db->update($this->table, $items, ['id_keranjang' => $produk['id_keranjang']]);
      $save = TRUE;
    } else {
      $insert = $this->db->insert($this->table, $items);
      $save = TRUE;
    }

    return $save;

  }


  /**
	 * Aksi ubah data
	 *
	 * @return boolean
	 */
  public function edit_cart() {
    $id_cart = $this->input->post('id');
    $id_produk = $this->input->post('id_produk');
    $stok = getstok($id_produk);
    $kuantitas = $this->input->post('kuantitas');
    $kuantitas_lama = $this->input->post('kuantitas_lama');
    $catatan = htmlspecialchars($this->input->post('catatan'), true);
    $new_kuantitas = $kuantitas_lama - $kuantitas + $stok;

    $this->db->update('produk', ['stok_produk' => $new_kuantitas], ['id_produk' => $id_produk]);
    $data = [
      'kuantitas' => $kuantitas,
      'catatan' => $catatan
    ];

    $update = $this->db->update($this->table, $data, ['id_keranjang' => $id_cart]);
    if ($update) return ["status" => TRUE];
  }



  /**
	 * Aksi ubah data
	 *
   * @param id $id adalah id pesanan
	 * @return int
	 */
  public function remove_keranjang($id) {

    $keranjang = $this->db->get_where('keranjang', ['id_keranjang' => $id])->row_array();
    $produk = $this->db->get_where('produk', ['id_produk' => $keranjang['id_produk']])->row_array();
    $id_produk = $keranjang['id_produk'];
    $stok_produk = getstok($id_produk);
    $qty = $keranjang['kuantitas'];

    //update stok produk
    $this->db->update('produk', ['stok_produk' => $stok_produk + $qty], ['id_produk' => $id_produk]);

    // delete produk dalam keranjang berdasarkan id
    $this->db->delete($this->table, ['id_keranjang' => $id]);

    return TRUE;
  }


}