<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?=$title; ?></h1>
      <?php $this->load->view('_layouts/breadcrumb'); ?>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Menambahkan Produk Penukaran Poin</h4>
            </div>
            <div class="card-body">
              <form id="form" method="post" action="">
                <input type="hidden" name="slug_produk" id="slug_produk">
                <input type="hidden" name="dsc" id="dsc">
                <div class="form-row">
                  <div class="form-group  col-md-6">
                    <label class="col-form-label" for="nama_produk">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                    placeholder="Nama Produk" value="" />
                    <div class="invalid-feedback" in="nama_produk"></div>
                  </div>
                  <div class="form-group col-md-4">
                    <label class="col-form-label" for="harga_produk">Harga Produk (Poin)</label>
                    <input type="number" class="form-control" id="harga_produk" name="harga_produk"
                    placeholder="Harga Produk.." value="<?= set_value('harga_produk') ?>" />
                    <div class="invalid-feedback" in="harga_produk"></div>
                  </div>
                  <div class="form-group col-md-2">
                    <label class="col-form-label" for="berat_produk">Weight</label>
                    <input type="number" class="form-control" id="berat_produk" name="berat_produk"
                    placeholder="gram" value="<?= set_value('berat_produk') ?>" />
                    <div class="invalid-feedback" in="berat_produk"></div>
                  </div>
                </div>
                <div class="form-row">
                </div>
                <div class="form-row">
                  <div class="form-group col">
                    <label class="col-form-label" for="deskripsi_produk">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="10"></textarea>
                    <div class="invalid-feedback" in="deskripsi_produk"></div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Foto Produk</label>
                  <div class="col-sm-10">
                    <label for="gambar_produk" style="position:absolute;">
                      <i class="fa fa-edit btn-sm btn-primary input-hero-image"><input type="file" class="d-none" id="gambar_produk" name="gambar_produk"></i></label>
                    <img width="40%" id="blah" class="img-thumbnail" src="<?=base_url('assets/img/product_default.png'); ?>" alt="hero image">
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label" class="col-form-label text-left"></label>
                  <div class="col-12">
                    <button class="btn btn-primary" type="button"id="btnsave" name="submit" onclick="save()">Tambahkan</button>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
<?php $this->load->view('_layouts/js'); ?>
<?php $this->load->view('_layouts/js/produk_poin_add'); ?>