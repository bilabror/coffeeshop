<!-- GliderJS -->
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.css"
/>
<!-- ------------------------------------------------------------------------- MAIN -->
<main class="detail">
  <div class="container">
    <div class="row">
      <div class="col-md-5">
        <img
        src="<?=base_url('uploads/image/produk/'.$produk['gambar_produk']) ?>"
        alt=""
        class="img-thumbnail"
        id="swapImg"
        />
      </div>
      <div class="col-md-7">
        <div class="detailProduk">
          <div class="titleDetail">
            <h1 class="produk title"><?=$produk['nama_produk'] ?></h1>
            <?php if ($produk['diskon_produk'] > 0): ?>
            <h5 class="text-danger text-decoration-line-through">
              <?=rupiah($produk['harga_produk']) ?>
            </h5>
            <?php endif; ?>
            <h3 class="harga"><?=rupiah($produk['total_harga_produk']) ?></h3>
          </div>

          <table class="table table-striped tentangProduk">
            <tr>
              <td>Kategori</td>
              <td>:</td>
              <td><?=$produk['nama_kategori'] ?></td>
            </tr>
            <tr>
              <td>Diskon</td>
              <td>:</td>
              <td><?=$produk['diskon_produk'] ?>%</td>
            </tr>
            <tr>
              <td>Stok Tersisa</td>
              <td>:</td>
              <td><?=$produk['stok_produk'] ?>pcs</td>
            </tr>
            <tr>
              <td>Berat Produk</td>
              <td>:</td>
              <td><?=$produk['berat_produk'] ?></td>
            </tr>
            <tr>
              <td>Rating Produk</td>
              <td>:</td>
              <td class="text-warning fw-bold">
                <i class="fas fa-star"></i> <?=number_format($ulasan_info['overall_rating'], 1) ?>
              </td>
            </tr>
          </table>
          <div class="row mb-3">
            <div class="col-md-7">
              <button
                class="mt-2 btn btn-success w-100 tambahKeranjang border-0"
                type="button"
                onclick="get_produk_id(<?=$produk['id_produk'] ?>)"
                >
                <i class="fas fa-cart-plus me-1"></i> Tambah Ke Keranjang
              </button>
            </div>
            <div class="col-md-5">
              <a href="" class="mt-2 btn btn-danger w-100 tanyaProduk border-0">
                <i class="fas fa-question me-1"></i> Tanya Seputar Produk
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</main>
<!-- end-MAIN -->

<!-- ------------------------------------------------------------------------- MADING -->
<div class="container">
  <div class="mading">
    <section>
      <ul>
        <li class="list active" data-filter="deskripsi">Deskripsi</li>
        <li class="list" data-filter="info">Informasi</li>
        <li class="list" data-filter="caraPesan">Cara Pesan</li>
      </ul>
      <div class="text">
        <div class="textBox" data-item="deskripsi">
          <p>
            <?=$produk['deskripsi_produk']; ?>
          </p>
        </div>
        <div class="textBox hide" data-item="info">
          <p>
            Informasi dolor sit amet consectetur, adipisicing elit.
            Inventore, dolorum tempora ab saepe fugiat reprehenderit nisi
            consequuntur cumque ducimus doloribus ipsa perspiciatis
            laudantium omnis laborum possimus sed facere, nostrum illum!
          </p>
        </div>
        <div class="textBox hide" data-item="caraPesan">
          <p>
            Informasi dolor sit amet consectetur, adipisicing elit.
            Inventore, dolorum tempora ab saepe fugiat reprehenderit nisi
            consequuntur cumque ducimus doloribus ipsa perspiciatis
            laudantium omnis laborum possimus sed facere, nostrum illum!
          </p>
        </div>
      </div>
    </section>
  </div>
</div>
<!-- end-MADING -->

<!-- ------------------------------------------------------------------------- RECOMENDED -->
<div class="container">
  <div class="glider-contain">
    <h5>KOPI REKOMENDASI</h5>
    <div class="glider">
      <?php foreach ($produkrand as $row): ?>
      <div>
        <div class="card">
          <img
          src="<?=base_url("uploads/image/produk/{$row->gambar_produk}") ?>"
          class="card-img-top"
          alt="gambar<?=$row->nama_produk ?>"
          />
          <?php if ($row->diskon_produk > 0): ?>
          <span class="discount"> -<?=$row->diskon_produk ?>% </span>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title text-md mb-2 text-center">
              <a href="<?=site_url("produk/{$row->slug_produk}") ?>" class="card-title"><?=$row->nama_produk ?></a></h5>
            <div class="d-flex justify-content-between">
              <div
                class="text-center"
                style="color: #13b79d; font-weight: bo position-relativeld"
                >

                <?php if ($row->diskon_produk > 0): ?>
                <small class="text-danger text-decoration-line-through">
                  <?=rupiah($row->harga_produk) ?>
                </small> <br />
                <?php endif; ?>
                <?=rupiah($row->total_harga_produk) ?>
              </div>
              <?php
              $rating = $this->db->query('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_ulasan FROM ulasan_produk WHERE id_produk = '.$row->id_produk)->row_array();
              ?>
              <span style="color: #ff8800; font-weight: bold;"><i class="fas fa-star"></i> <?=number_format($rating['overall_rating'], 1) ?></span>
            </div>
            <a href="javascript:void(0)"
              onclick="get_produk_id(<?=$produk['id_produk'] ?>)"
              class="cart btn w-100 text-white mt-3 mb-2">
              <i class="fas fa-plus"></i> KERANJANG
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <button class="glider-prev">&laquo;</button>
    <button class="glider-next">&raquo;</button>
    <div id="dots"></div>
  </div>
</div>
<!-- END-RECOMENDED -->

<!-- GliderJS -->
<script src="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.js"></script>

<script src="<?=base_url('assets/frontend2/') ?>js/glider.js"></script>