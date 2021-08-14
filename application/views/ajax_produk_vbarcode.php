<div class="row">
  <?php foreach ($produk as $produk): ?>

  <div class="col-md-3 col-sm-6 item-listkopi" id="item-listkopi">
    <div class="card">
      <img
      src="<?=base_url('uploads/image/produk/').$produk->gambar_produk; ?>"
      class="card-img-top"
      alt="<?="Gambar {$produk->nama_produk}" ?>"
      />
      <?php if ($produk->diskon_produk > 0): ?>
      <span class="discount"> -<?=$produk->diskon_produk ?>% </span>
      <?php endif; ?>
      <div class="card-body">
        <h5 class="card-title">
          <a href="<?=site_url("produk/{$produk->slug_produk}") ?>" class="card-title"><?=$produk->nama_produk ?></a>
        </h5>
        <p class="card-text mt-2">
          <div class="d-flex justify-content-between">
            <span style="color: #13b79d;" class="fw-bold position-relative">
              <?php if ($produk->diskon_produk > 0): ?>
              <small class="text-danger text-decoration-line-through">
                <?=rupiah($produk->harga_produk) ?>
              </small> <br />
              <?php endif; ?>
              <?=rupiah($produk->total_harga_produk) ?></span>
            <?php
            $rating = $this->db->query('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_ulasan FROM ulasan_produk WHERE id_produk = '.$produk->id_produk)->row_array();
            ?>
            <span style="color: #ff7b00;" class="fw-bold"><i class="fas fa-star"></i> <?=number_format($rating['overall_rating'], 1) ?></span>
          </div>

          <?php if ($produk->stok_produk == 0): ?>
          <a href="" class="cart btn w-100 text-white mt-3 position-relative">
            <i class="fas fa-ban"></i> <del>KERANJANG</del>
            <span class="badge bg-danger position-absolute stok">stok habis</span>
          </a>
          <?php else : ?>
          <a class="cart btn w-100 text-white mt-3" href="javascript:void(0)" onclick="add_to_cart(<?=$produk->id_produk ?>,'<?=$produk->nama_produk ?>',<?=$produk->total_harga_produk ?>)">
            <i class="fas fa-plus"></i> KERANJANG
          </a>
          <?php endif; ?>

        </p>
      </div>
    </div>
  </div>

  <?php endforeach; ?>
</div>