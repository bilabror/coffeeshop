<!-- SPLIDE -->
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css"
/>
<link rel="stylesheet" href="<?=base_url('assets/frontend2/') ?>css/splide.css" />
<!-- GliderJS -->
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.css"
/>

<!-- LightBox -->
<link
rel="stylesheet"
href="<?=base_url('assets/frontend2/') ?>node_modules/lightbox2/dist/css/lightbox.css"
/>

<!-- ------------------------------------------------------------------------- SLIDER -->
<div class="container">
  <div class="splide">
    <div class="splide__track">
      <ul class="splide__list">
        <?php foreach ($slider as $row): ?>
        <li class="splide__slide"><img src="<?=base_url('uploads/image/slider/'.$row['gambar']) ?>" alt="" /></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <!-- end-SLIDER -->

  <!-- ------------------------------------------------------------------------- RECOMENDED -->
  <div class="glider-contain mt-3">
    <h5>KOPI REKOMENDASI</h5>
    <div class="glider">
      <?php foreach ($rekomendasi as $row): ?>
      <div>
        <div class="card">
          <img
          src="<?=base_url('uploads/image/produk/'.$row['gambar_produk']) ?>"
          class="card-img-top"
          alt="<?= 'Gambar'. $row['gambar_produk'] ?>"
          />
          <?php if ($row['diskon_produk'] > 0): ?>
          <span class="discount"> -<?=$row['diskon_produk'] ?>% </span>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title">
              <a href="<?=site_url("produk/{$row['slug_produk']}") ?>" class="card-title"><?=$row['nama_produk'] ?></a>
            </h5>
            <p class="card-text mt-2">
              <div class="d-flex justify-content-between">
                <span style="color: #13b79d;" class="fw-bold position-relative">
                  <?php if ($row['diskon_produk'] > 0): ?>
                  <small class="text-danger text-decoration-line-through">
                    <?=rupiah($row['harga_produk']) ?>
                  </small> <br />
                  <?php endif; ?>
                  <?=rupiah($row['total_harga_produk']) ?>
                </span>
                <?php
                $rating = $this->db->query('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_ulasan FROM ulasan_produk WHERE id_produk = '.$row['id_produk'])->row_array();
                ?>
                <span style="color: #ff7b00;" class="fw-bold"><i class="fas fa-star"></i> <?=number_format($rating['overall_rating'], 1) ?></span>
              </div>

              <?php if ($row['stok_produk'] == 0): ?>
              <a href="" class="cart btn w-100 text-white mt-3 position-relative">
                <i class="fas fa-ban"></i> <del>KERANJANG</del>
                <span class="badge bg-danger position-absolute stok">stok habis</span>
              </a>
              <?php else : ?>
              <a class="cart btn w-100 text-white mt-3" onclick="get_produk_id(<?=$row['id_produk'] ?>)">
                <i class="fas fa-plus"></i> KERANJANG
              </a>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <button class="glider-prev">&laquo;</button>
    <button class="glider-next">&raquo;</button>
    <div id="dots"></div>
  </div>
  <!-- END-RECOMENDED -->


  <a name="anchor-listkopi" />

  <!-- ------------------------------------------------------------------------- LIST KATEGORI -->
  <section class="d-flex justify-content-around">
    <ul class="kategori dekstop">
      <li class="active kategori-dekstop" data-idproduk="">
        <a href="javascript:void(0)">Semua</a>
      </li>
      <?php foreach (kategori() as $kategori): ?>
      <li class="kategori-dekstop" data-idproduk="<?=$kategori->id_kategori ?>">
        <a href="javascript:void(0)" class=""><?=$kategori->nama_kategori; ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php if (sud('email')): ?>
    <form action="" class="search-desktop" id="form_search" method="post">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Cari Produk..." style="border-color: #00b14f;" name="search-desktop">
        <div class="input-group-text button-search-desktop">
          <i class="fas fa-search text-white"></i>
        </div>
      </div>
    </form>
    <?php endif; ?>
  </section>

  <div class="kategori mobile d-flex justify-content-between">
    <section style="width: 44%;">
      <select class="form-select" id="inputGroupSelect01">
        <option selected hidden>Pilih Kategori...</option>
        <option value="" <?=$this->uri->segment(2) == null ? 'selected' : '' ?>>Semua</option>
        <?php foreach (kategori() as $kategori): ?>
        <option value="<?=$kategori->id_kategori; ?>" <?=$kategori->slug_kategori == $this->uri->segment(2) ? 'selected' : '' ?>><?=$kategori->nama_kategori; ?></option>
        <?php endforeach; ?>
      </select>
    </section>
    <section style="width: 45%;">
      <form action="" id="form_search" class="search-mobile" method="post">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Cari Produk..." name="search-mobile" style="border-color: #00b14f;">
          <div class="input-group-text button-search-mobile">
            <i class="fas fa-search text-white"></i>
          </div>
        </div>
      </form>
    </section>
  </div>
  <!-- end-LIST KATEGORI -->

  <!-- ------------------------------------------------------------------------- LIST KOPI -->
  <div class="listKopi" id="listkopi"></div>
  <!-- end-LIST KOPI -->
</div>


<?php if (!sud('email')): ?>
<!-- ------------------------------------------------------------------------- TENTANG -->
<div class="container-tentang">
  <div class="container">
    <div class="tentang">
      <div class="lr">
        <div class="left">
          <div class="container-slider">
            <ul class="slider">
              <?php foreach ($slider as $row): ?>
              <li class="splide__slide"><img src="<?=base_url('uploads/image/slider/'.$row['gambar']) ?>" alt="" /></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="right">
          <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            Suscipit et, exercitationem saepe soluta reprehenderit mollitia
            enim delectus dolorum nemo est non voluptatum optio ipsum quam
            omnis ut similique tenetur? Quas.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end-TENTANG -->


<!-- ------------------------------------------------------------------------- BANTUAN -->
<div class="container bantuan">
  <h3>BANTUAN</h3>
  <div class="row row-bantuan">
    <div class="col-lg-5 col-md-6">
      <div class="card">
        <iframe
          src="<?=getset('embed_map_toko') ?>"
          width="100%"
          height="300"
          style="border: 0"
          allowfullscreen=""
          loading="lazy"
          ></iframe>
        <div class="card-body">
          <h5 class="card-title">LOKASI <?=getset('nama_toko') ?> DI GOGGLE MAP</h5>
          <p class="card-text text-muted">
            Klik "Lihat peta lebih besar" untuk melihat rute menuju <?=getset('nama_toko') ?>*
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="card">
        <img
        src="https://s-ecom.ottenstatic.com/original/5f2d2eeb741cb549464431.jpg"
        class="card-img-top"
        alt="..."
        />
        <div class="card-body">
          <h5 class="card-title">CARA ORDER KOPI</h5>
          <p class="card-text text-muted">
            Some quick example text to build on the card title and make up
            the bulk of the card's content.
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">BUTUH BANTUAN LAIN?</h5>
          <p class="card-text text-muted">
            Silahkan tanyakan langsung masalah anda kepada kami lewat pesan
            whatsApp yang tertera dibawah sini
          </p>
          <div class="contact">
            <a href="https://api.whatsapp.com/send?phone=<?=getset('wa_toko') ?>"><i class="fab fa-whatsapp"></i> whatsApp</a>
            <a href="mailto:<?=getset('email_toko') ?>"><i class="far fa-envelope"></i> G mail</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end-BANTUAN -->

<?php endif; ?>


<!-- LightBoxJS -->
<script src="<?=base_url('assets/frontend2/') ?>node_modules/lightbox2/dist/js/lightbox-plus-jquery.js"></script>
<!-- SPLIDE -->
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>

<!-- GliderJS -->
<script src="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.js"></script>
<script src="<?=base_url('assets/frontend2/') ?>js/glider.js"></script>