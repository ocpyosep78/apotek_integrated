<?php
$subNav = array(
        "Pemesanan ; pemesanan.php ; #509601;",
	"Defecta ; defecta.php ; #509601;",
        "Rencana Pesan ; rencana-pemesanan.php ; #509601;"
);
set_include_path("../");
include_once("inc/essentials.php");
include_once("inc/functions.php");
include_once("models/masterdata.php");
include_once("pages/message.php");
//$golongan = golongan_load_data();
//$satuan_kekuatan = satuan_load_data('1');
$kemasan  = satuan_load_data('0');
//$sediaan  = sediaan_load_data();
//$admr     = admr_load_data();
//$perundangan = perundangan_load_data();
//$farmakoterapi   = farmakoterapi_load_data();
//$fda      = fda_load_data();
?>
<script type="text/javascript">
    $('#search').focus();
    $.cookie('session', 'false');
    $.cookie('formbayar', 'false');
    $(document).keydown(function(e) {
        if (e.keyCode === 120) {
            if ($.cookie('session') === 'false') {
                $('#button').click();
            }
        }
    });
    $('#search').keyup(function() {
        var value = $(this).val();
        load_data_defecta('',value,'');
    });

load_data_defecta();
function removeMe(el) {
    var parent = el.parentNode.parentNode;
    parent.parentNode.removeChild(parent);
    hitung_estimasi();
}

function hitung_estimasi() {
    var jml_baris = $('.tr_rows').length;
    var estimasi = 0;
    for (i = 1; i <= jml_baris; i++) {
        var subtotal = parseInt(currencyToNumber($('#subtotal'+i).html()));
        estimasi = estimasi + subtotal;
    }
    $('#estimasi').html(numberToCurrency(parseInt(estimasi)));
}
function add_new_rows(id_brg, nama_brg, jumlah, id_kemasan) {
    if (id_kemasan === null) {
        alert('Kemasan tidak boleh kosong !');
        return false;
    }
    var jml     = $('.tr_rows').length+1;
    var kemasan = $('#kemasan option:selected').text();
    var str = '<tr class="tr_rows">'+
                '<td align=center>'+jml+'</td>'+
                '<td>&nbsp;'+nama_brg+' <input type=hidden name=id_barang[] value="'+id_brg+'" class=id_barang id=id_barang'+jml+' /></td>'+
                '<td align=center>'+kemasan+'<input type=hidden name=kemasan[] id=kemasan'+jml+' value="'+id_kemasan+'" /></td>'+
                '<td><input type=text name=jumlah[] id=jumlah'+jml+' value="'+jumlah+'" size=10 style="text-align: center;" /></td>'+
                '<td align=right id=subtotal'+jml+'></td>'+
                '<td align=center><input type=hidden id=perundangan'+jml+' /><img onclick=removeMe(this); title="Klik untuk hapus" src="img/icons/delete.png" class=add_kemasan align=left /></td>'+
              '</tr>';
    $('#pesanan-list tbody').append(str);
    $.ajax({
        url: 'models/autocomplete.php?method=get_detail_harga_barang_defecta&id='+id_brg+'&id_kemasan='+id_kemasan,
        dataType: 'json',
        cache: false,
        success: function(data) {
            var subtotal = data.esti*jumlah;
            //alert(subtotal+' '+data.esti+' '+jumlah);
            $('#subtotal'+jml).html(numberToCurrency(parseInt(subtotal)));
            $('#perundangan'+jml).val(data.perundangan);
            hitung_estimasi();
        }
    });
}

function cetak_sp(id_defecta) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 0.8;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    var perundangan = $('#perundangan1').val();
    window.open('pages/defecta-print.php?id='+id_defecta+'&perundangan='+perundangan, 'defecta Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function form_add() {
    var str = '<div id="form_defecta">'+
            '<form id="save_defecta">'+
            '<table width=100% class=data-input><tr valign=top><td width=50%><table width=100%>'+
                '<tr><td width=15%>No. SP:</td><td width=30%><?= form_input('no_sp', NULL, 'id=no_sp size=10 readonly') ?></td></tr>'+
                '<tr><td>Tanggal Pembuatan SP:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                '<tr><td>Tanggal Diharapkan Datang:</td><td><?= form_input('tanggal_datang', date("d/m/Y"), 'id=tanggal_datang size=10') ?></td></tr>'+
                '<tr><td>Supplier:</td><td width=20%><?= form_input('supplier', NULL, 'id=supplier size=40') ?><?= form_hidden('id_supplier', NULL, 'id=id_supplier') ?></td></tr>'+
            '</table></td><td width=50%>'+
                '<table><tr><td width=20%>Nama Barang:</td><td width=50%><?= form_input('barang', NULL, 'id=barang size=40') ?><?= form_hidden('id_barang', NULL, 'id=id_barang') ?></td></tr>'+
                '<tr><td>Kemasan:</td><td><select name=id_kemasan id=kemasan style="min-width: 86px;"><option value="">Pilih ...</option><?php foreach ($kemasan as $data) { echo '<option value="'.$data->id.'">'.$data->nama.'</option>'; } ?></select></td></tr>'+
                '<tr><td>Jumlah:</td><td><?= form_input('jumlah', NULL, 'id=jumlah size=10') ?></td></tr>'+
                '<tr><td>Estimasi Harga:</td><td style="font-size: 40px;"><span>Rp</span> <span id=estimasi>0</span>, 00</td></tr>'+
            '</table>'+
            '</td></tr></table>'+
            '<table width=100% cellspacing="0" class="list-data-input" id="pesanan-list"><thead>'+
                '<tr><th width=5%>No.</th><th width=54%>Nama Barang</th><th width=20%>Kemasan</th><th width=10%>Jumlah</th><th width=10%>Subtotal</th><th width=1%>#</th></tr></thead>'+
                '<tbody></tbody>'+
            '</table>'+
            '</form></div>';
    $('body').append(str);
    $(document).keydown(function(e) {
        if (e.keyCode === 119) {
            $('#save_defecta').submit();
        }
    });
    var lebar = $('#pabrik').width();
    $('#jumlah').keydown(function(e) {
        if (e.keyCode === 13) {
            add_new_rows($('#id_barang').val(), $('#barang').val(), $('#jumlah').val(), $('#kemasan').val());
            $('#id_barang').val('');
            $('#jumlah').val('');
            $('#kemasan').html('').append('<option value="">Pilih ...</option>');
            $('#barang').val('').focus();
            
        }
    });
    $('#barang').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#kemasan').focus();
            //$('#jumlah').val('1').focus().select();
        }
    });
    $('#kemasan').keydown(function(e) {
        if (e.keyCode === 13) {
            //$('#kemasan').focus();
            $('#jumlah').val('1').focus().select();
        }
    });
    $('#barang').autocomplete("models/autocomplete.php?method=barang",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama_barang+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama_barang);
        $('#id_barang').val(data.id);
        $('#kemasan').html('');
        $.getJSON('models/autocomplete.php?method=get_kemasan_barang&id='+data.id, function(data){
            if (data === null) {
                alert('Kemasan tidak barang tidak tersedia !');
            } else {
                $.each(data, function (index, value) {
                    $('#kemasan').append("<option value='"+value.id_kemasan+"'>"+value.nama+"</option>");
                });
            }
        });
    });
    $('#supplier').autocomplete("models/autocomplete.php?method=supplier",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama+'<br/> '+data.alamat+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_supplier').val(data.id);
        $('#barang').focus();
    });
    $('#tanggal,#tanggal_datang').datepicker({
        changeYear: true,
        changeMonth: true
    });
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    $('#form_defecta').dialog({
        title: 'Tambah defecta Barang',
        autoOpen: true,
        modal: true,
        width: dWidth,
        height: dHeight,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan (F8)": function() {
                $('#save_defecta').submit();
            }, 
            "Cancel": function() {    
                $(this).dialog().remove();
                $.cookie('session', 'false');
            }
        }, close: function() {
            $(this).dialog().remove();
            $.cookie('session', 'false');
        }, open: function() {
            $('#supplier').focus();
            $.cookie('session', 'true');
            $.ajax({
                url: 'models/autocomplete.php?method=generate_new_sp',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $('#no_sp').val(data.sp);
                }
            });
        }
    });
    $('#save_defecta').submit(function() {
        var jumlah = $('.tr_rows').length;
        if ($('#id_supplier').val() === '') {
            alert('Nama supplier tidak boleh kosong !');
            $('#supplier').focus(); return false;
        }
        if (jumlah === 0) {
            alert('Pilih salah satu barang!');
            $('#barang').focus(); return false;
        }
        $.ajax({
            url: 'models/update-transaksi.php?method=save_defecta',
            data: $(this).serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                if (data.status === true) {
                    alert_tambah('#supplier');
                    $('#supplier, #id_supplier').val('');
                    $('#no_sp').val(data.id_defecta);
                    $('#pesanan-list tbody').html('');
                    $('#estimasi').html('0');
                    load_data_defecta();
                    cetak_sp(data.id);
                } else {
                    alert_edit();
                }
            }
            
        });
        return false;
    });
}
$('#button').button({
    icons: {
        primary: 'ui-icon-newwin'
    }
}).click(function() {
    form_add();
});
$('#reset').button({
    icons: {
        primary: 'ui-icon-refresh'
    }
}).click(function() {
    $('#search').val('');
    load_data_defecta();
});

function load_data_defecta(page, search, id, sort) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    if (sort === undefined) { sort = ''; }
    $.ajax({
        url: 'pages/defecta-list.php',
        cache: false,
        data: 'page='+pg+'&search='+src+'&id_defecta='+id_barg+'&sort='+sort,
        success: function(data) {
            $('#result-defecta').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_defecta(page, search);
}

function add_to_planning(id, page, nama) {
$('<div id=alert>Anda yakin akan memasukkan barang '+nama+' ke rencana pemesanan ?</div>').dialog({
    title: 'Konfirmasi',
    autoOpen: true,
    modal: true,
    buttons: {
        "OK": function() {
            $.ajax({
                url: 'models/update-transaksi.php?method=add_rencana_pemesanan&id='+id,
                cache: false,
                success: function() {
                    load_data_defecta(page);
                    $('#alert').dialog().remove();
                }
            });
        },
        "Cancel": function() {
            $(this).dialog().remove();
        }
    }
});
}
</script>
<h1 class="margin-t-0">Buku Defecta</h1>
<hr>
<button id="reset">Reset</button>
<?= form_input('search', NULL, 'id=search placeholder="Search ..." class=search') ?>
<div id="result-defecta">
    
</div>