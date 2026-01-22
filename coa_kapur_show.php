<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Sistem Informasi Laboratorium</title>
<link rel="icon" type="image/png" href="/Silab-v3/public/admin_template/img/QC.png"/>
<?php
    function formatTanggalIndonesia($tanggal)
    {
        $bulanIndo = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $parts = explode('-', $tanggal); // [2025, 05, 17]
        $tahun = $parts[0];
        $bulan = $bulanIndo[$parts[1]];
        $hari  = $parts[2];

        return ltrim($hari, '0') . ' ' . $bulan . ' ' . $tahun;
    }
?>
<style>
  body, html {
    margin: 0; padding: 0; height: 100%;
    font-family: "Times New Roman", Times, serif;
  }
  .a4-container {
    /* width: 794px;    */
    width: 900px;    /* Lebar A4 px */
    height: 1123px;  /* Tinggi A4 px */
    margin: 20px auto;
    background-image: url('/Silab-v3/public/admin_template/img/template_kop.jpg');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: top center;
    position: relative;
  }
  .content {
    position: absolute;
    top: 150px;  /* posisi naik dari 220px */
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 700px;
    color: black;
    font-size: 12px; /* font kecil */
  }

  /* Judul section */
  .title-section h2, .title-section h3 {
    text-align: center;
    color: blue;
    margin: 0;
  }
  .title-section h2 {
    font-size: 26px;
    font-weight: normal;
  }
  .title-section h3 {
    font-style: italic;
    font-size: 20px;
    margin-top: 5px;
    margin-bottom: 30px;
  }

  .info-row {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 4px;
  font-size: 16px;
  /* HAPUS margin-left */
}

.info-subnote {
  margin: 0 0 10px 0; /* HAPUS margin-left 20px */
  font-size: 14px;
  font-style: italic;
  color: #333;
}
  .info-label {
    width: 150px;
    font-weight: bold;
  }
  .info-value {
    flex-grow: 1;
  }

  /* Section HASIL, pos absolute supaya menimpa background juga */
  .hasil-section {
    position: absolute;
    top: 400px; /* sesuaikan posisi vertikal agar tidak bertumpuk dengan content sebelumnya */
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 700px;
    color: black;
    font-family: "Times New Roman", Times, serif;
    font-size: 16px;
  }
  .hasil-section h2 {
    text-align: center;
    letter-spacing: 10px;
    margin-bottom: 8px;
    font-size: 26px;
  }

  .hasil-section table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
    text-align: center;
  }
  .hasil-section table th,
  .hasil-section table td {
    border: 1px solid black;
    padding: 6px;
  }
  .hasil-section table td:first-child,
  .hasil-section table th:first-child {
    width: 40px;
  }
  .hasil-section table td:nth-child(2),
  .hasil-section table th:nth-child(2) {
    text-align: left;
  }
  .hasil-section p {
    margin: 10px 0 0 0;
  }
  .hasil-section p.result-text {
    font-style: italic;
    text-align: center;
    margin-top: 8px;
    font-weight: normal;
  }
  .hasil-section p.note {
    margin-top: 30px;
    font-style: italic;
  }
</style>
</head>
<body>

  <div class="a4-container">
    <div class="content">
      <div class="title-section">
        <br>
        <h2 style="margin-bottom: 0px;">
            <!-- <u>LAPORAN HASIL ANALISA</u> -->
        </h2>
        <h3 style="margin-top: 0px; font-style: italic;">
            <!-- CERTIFICATE OF ANALYSIS -->
        </h3>

      </div>

      <br><br><br><br>

      <div class="info-row">
        <div class="info-label">No.</div>
        <div class="info-value">:
            <!-- No. KBA/FRM/QCT/030-2025/005 -->
            <?= $nomor_dokumen ?>
        </div>
      </div>
      <p class="info-subnote">No</p>

      <div class="info-row">
        <div class="info-label">Contoh</div>
        <div class="info-value">: Kapur Gamping</div>
      </div>
      <p class="info-subnote">Sample</p>

      <div class="info-row">
        <div class="info-label">Tanggal Terima</div>
        <div class="info-value">: <?php echo formatTanggalIndonesia($tanggal_terima); ?></div>
      </div>
      <p class="info-subnote">Date of received</p>

      <div class="info-row">
        <div class="info-label">Tanggal Pengujian</div>
        <div class="info-value">: <?php echo formatTanggalIndonesia($tanggal_pengujian); ?></div>
      </div>
      <p class="info-subnote">Date of analysis</p>
    </div>

    <!-- Section HASIL -->
    <section class="hasil-section">
        <br><br>
        <h5 style="font-size: 12px; font-weight: bold; text-decoration: underline; margin: 0; text-align:center;">HASIL</h5>
        <h5 style="font-size: 12px; font-weight: bold; font-style: italic; margin: 0; text-align:center;">RESULT</h5>
        <br>

      <table>
        <thead>
          <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2" style="text-align: center;">Pemasok<br>Supplier</th>
            <th colspan="4">Hasil Pengujian<br>Test Result</th>
          </tr>
          <tr>
            <!-- <th style="text-align: center;">Nopol</th> -->
            <th style="text-align: center; width: 120px;">Nopol</th>
            <th style="text-align: center;">CaO (%) <br><span style="font-size: 10px; font-style: italic;">Min : 90%</span></th>
            <th style="text-align: center;">Nilai Dispersitas <br><span style="font-size: 10px; font-style: italic;">Min : 90%</span></th>
            <th style="text-align: center;">Keterangan</th>
          </tr>
        </thead>
        <tbody>
            <?php $loop = 1; ?>
            <?php foreach($data as $dataX): ?>
            <tr>
                <td><?= $loop ?></td>
                <td><?= $dataX->material_name ?></td>
                <td><?= $dataX->Nopol ?></td>
                <td>
                    <?= is_numeric($dataX->{'%Kapur'}) ? number_format($dataX->{'%Kapur'}, 2) : '-' ?>
                  </td>
                  <td>
                    <?= is_numeric($dataX->Dispersitas) ? number_format($dataX->Dispersitas, 2) : '-' ?>
                </td>
                <td>
                    <?php if($dataX->{'%Kapur'} < 90): ?>
                        <?= "Tidak Baik" ?>
                    <?php else : ?>
                        <?= "Baik" ?>
                    <?php endif ?>
                </td>
            </tr>
            <?php $loop++; endforeach; ?>
        </tbody>
      </table>

      <p><strong>Method of Test :</strong> Complexometric</p>

      <p class="note">
        <strong>Catatan :</strong> Hasil analisa hanya berdasarkan sampel yang diterima<br>
        <strong>Notice :</strong> This report is based on testing sample only
      </p>



    <div style="width: 100%; text-align: right; margin-top: 60px; font-size: 16px; font-family: 'Times New Roman', Times, serif;">
        <p style="margin: 0;">Malang, <?php echo formatTanggalIndonesia($tanggal_cetak); ?></p>
        <br><br><br><br><br><br>
        <!-- <p style="margin: 0; font-weight: bold;"><u>Tri Sunu Hardi</u></p> -->
        <p style="margin: 0;">Kasubsie / Kasie Pabrikasi</p>
      </div>
    </section>


  </div>

</body>
</html>
