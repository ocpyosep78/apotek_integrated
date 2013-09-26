<?php
include_once '../models/masterdata.php';
include_once '../inc/functions.php';
?>
<table cellspacing="0" width="50%" class="list-data">
<thead>
<tr class="italic">
    <th width="5%">No.</th>
    <th width="35%">Nama layanan</th>
    <th width="20%">Nominal (Rp.)</th>
    <th width="10%">Kode Akun</th>
    <th width="4%">#</th>
</tr>
</thead>
<tbody>
    <?php 
    $limit = 10;
    $page  = $_GET['page'];
    if ($_GET['page'] === '') {
        $page = 1;
        $offset = 0;
    } else {
        $offset = ($page-1)*$limit;
    }
    
    $param = array(
        'id' => $_GET['id_layanan'],
        'limit' => $limit,
        'start' => $offset,
        'search' => $_GET['search']
    );
    $list_data = load_data_layanan($param);
    $master_barang = $list_data['data'];
    $total_data = $list_data['total'];
    foreach ($master_barang as $key => $data) { 
        $str = $data->id.'#'.$data->nama.'#'.$data->nominal.'#'.$data->kode_akun;
        ?>
    <tr class="<?= ($key%2==0)?'even':'odd' ?>">
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->nama ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td><?= $data->kode_akun ?></td>
        <td class='aksi' align='center'>
            <a class='edition' onclick="edit_layanan('<?= $str ?>');" title="Klik untuk edit layanan">&nbsp;</a>
            <a class='deletion' onclick="delete_layanan('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus layanan">&nbsp;</a>
        </td>
    </tr>
    <?php } ?>
</tbody>
</table>
<?= paging_ajax($total_data, $limit, $page, '1', $_GET['search']) ?>