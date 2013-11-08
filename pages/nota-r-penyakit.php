<?php
include_once '../models/transaksi.php';
include_once '../models/masterdata.php';
include_once '../inc/functions.php';

$apt = apotek_atribute();
$param = array(
    'id' => $_GET['id_pasien'],
    'print' => TRUE
);
$array = load_data_customer($param);
foreach ($array['data'] as $rows);
?>
<title></title>
<link rel="stylesheet" href="../themes/theme_default/theme-print.css" />
<script type="text/javascript">
//window.onunload = refreshParent;
//function refreshParent() {
//    window.opener.location.reload();
//}
function cetak() {  		
    window.print();
    setTimeout(function(){ window.close();},300);
}
</script>
<body onload="cetak();">
    <div class="layout-print-struk">
        <h1>Pasien Hand Out / Catatan Pasien</h1>
        <table width="100%" style="border-bottom: 1px solid #000;">
            <tr><td width="20%">No. RM:</td><td><?= $_GET['id_pasien'] ?></td></tr>
            <tr><td>Nama Pasien:</td><td><?= $rows->nama ?></td></tr>
            <tr valign="top"><td>Alamat:</td><td><?= $rows->alamat ?></td></tr>
        </table>
        <table width="100%" style="border-bottom: 1px solid #000;">
            <?php if ($_GET['rpd'] !== '') { ?>
            <tr valign="top"><td width="30%">Riwayat Penyakit Dahulu:</td><td><?= $_GET['rpd'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['rpk'] !== '') { ?>
            <tr valign="top"><td>Riwayat Penyakit Keluarga:</td><td><?= $_GET['rpk'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['ps'] !== '') { ?>
            <tr valign="top"><td>Pengobatan Sekarang:</td><td><?= $_GET['ps'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['oh'] !== '') { ?>
            <tr valign="top"><td>Obat Herbal:</td><td><?= $_GET['oh'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['al'] !== '') { ?>
            <tr valign="top"><td>Alergi Lain:</td><td><?= $_GET['al'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['dl'] !== '') { ?>
            <tr valign="top"><td>Dokter Lain:</td><td><?= $_GET['dl'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['merokok'] !== '') { ?>
            <tr valign="top"><td>Merokok:</td><td><?= $_GET['merokok'] ?></td></tr>
            <?php } ?>
            <?php if ($_GET['ko_alk'] !== '') { ?>
            <tr valign="top"><td>Konsumsi Alkohol:</td><td><?= $_GET['ko_alk'] ?></td></tr>
            <?php } ?>
        </table>
    </div>
</body>