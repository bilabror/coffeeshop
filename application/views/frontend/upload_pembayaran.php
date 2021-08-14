<!-- ------------------------------------------------------------------------- MAIN -->
<div
  class="container bg-white border border-success p-3"
  style="margin-top: 150px"
  >
  <h4 class="fw-bold text-uppercase mt-3">Pembayaran</h4>
  <div class="row">
    <div class="col-md-7">
      <form action="" class="needs-validation" novalidate="" enctype="multipart/form-data" method="post">
        <?= form_hidden('id_pesanan', $id_pesanan); ?>
        <div class="mt-4">
          <div class="input-group">
            <div class="input-group-text bg-light">
              A/N
            </div>
            <input type="text" class="form-control" placeholder="Nama" name="atas_nama" required="" />
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-6 col-sm-12">
            <div class="mt-3">
              <label for="noRek" class="form-label"
                >No Rekening Anda :</label
              >
              <div class="input-group">
                <input
                type="text"
                class="form-control"
                id="noRek"
                placeholder="Masukan no Rekening"
                name="norek"
                required=""
                />
              </div>
            </div>
          </div>
          <div class="col-md-6 col-6 mt-4">
            <div>
              <label for="fromBank" class="form-label">Bank Anda :</label>
              <select id="fromBank" class="form-select" name="bank" required>
                <option selected hidden value="">-- PILIH BANK --</option>
                <?php foreach ($bank as $val): ?>
                <option value="<?=$val['name'] ?>"><?=$val['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6 mt-4 col-6">
            <label for="fromBank" class="form-label">Bank Tujuan :</label>
            <select id="fromBank" class="form-select" name="rek_toko" required>
              <option selected hidden value="">-- PILIH BANK --</option>
              <?php foreach ($rekening_toko as $row) : ?>
              <option value="<?= "{$row['atas_nama']}&{$row['norek']}&{$row['bank']}" ?>"><?="{$row['bank']} - {$row['norek']} a/n {$row['atas_nama']}" ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 mt-4">
            <label for="formFile" class="form-label">
              Foto Bukti Pengiriman
            </label>
            <input
            class="form-control"
            type="file"
            id="formFile"
            accept="image/*"
            name="gambar"
            required=""
            />
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6">
            <button
              class="btn text-white w-100"
              style="background-color: #00b14f"
              type="submit"
              name="submit"
              >
              Kirim Bukti Pembayaran
            </button>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-flex align-items-center mt-2"
              >Pastikan Form terisi dengan benar dan dengan Bukti Pembayaran
              yang jelas</small
            >
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-5">
      <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">PERINGATAN <strong>!</strong></h4>
        <small>
          Dimohon untuk foto bukti pembayaran dikirm dengan foto yang jelas
          dan tidak gelap. Untuk mempermudah kami dalam proses pengiriman.
          <br />
          Dan dimohon untuk tidak "Mengakali" bukti dengan mengirimkan foto
          transfer editan. Pengguna akan kami Langsung Ban jika ketahuan
          melakukan hal tersebut. <br />
          <br />
          <div class="row">
            <div class="col-6">
              <strong>TERIMAKASIH</strong>
            </div>
            <div class="col-6 text-end">
              <strong>-- ADMIN <?=getset('nama_toko') ?> --</strong>
            </div>
          </div>
        </small>
      </div>
    </div>
  </div>
</div>
<!-- end-MAIN -->