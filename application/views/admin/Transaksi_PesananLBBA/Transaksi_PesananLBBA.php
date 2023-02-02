<?php $kode_menu = $this->session->userdata['kode_menu']; ?>
<?php $level = $this->session->userdata['level']; ?>
<?php $super_admin = $this->session->userdata['super_admin']; ?>
<?php $dr = $this->session->userdata['dr']; ?>

<style>
    .alert-container {
        background-color: #00b386;
        color: black;
        font-weight: bolder;
    }

    .table {
        height: 350px;
        overflow: scroll;
    }

    .table>thead>tr>th {
        background-color: #00b386;
        top: 0;
        position: sticky !important;
        z-index: 999;
        text-align: center;
        color: black;
        font-weight: bold;
    }

    .table>tbody>tr>td {
        color: black;
        text-align: center;
    }

    .table-striped>tbody>tr:nth-child(odd)>td,
    .table-striped>tbody>tr:nth-child(odd)>th {
        background-color: #f2f2f2;
    }

    .table-striped>tbody>tr:nth-child(even)>td,
    .table-striped>tbody>tr:nth-child(even)>th {
        background-color: #d9d9d9;
    }

    .table>tbody>tr>td>div {
        text-align: center;
    }

    .table>tbody>tr>td>div>a {
        font-size: 13px;
        color: black;
        background-color: #00b386;
    }

    .table>tbody>tr>td>div>a:hover {
        transition: 0.4s;
        color: #b3b3b3;
        background-color: #00b386;
    }

    .table>tbody>tr>td>div>a::selection {
        color: white;
    }

    .table>tbody>tr>td>div>div>a:hover {
        transition: 0.4s;
        color: white;
        background-color: #00b386;
    }

    .table>tbody>tr>td>div>div>a>i {
        color: black;
        background-color: transparent;
    }
</style>


<section>
    <br>
    <div class="container-fliud mx-4">
        <div class="alert alert-success alert-container" role="alert">
            <i class="fas fa-university"></i>
            <label>
                Transaksi Pesanan LBBA
            </label>
        </div>
        <?php echo $this->session->flashdata('pesan') ?>
        <form method="post" action="<?php echo base_url('admin/Transaksi_PesananLBBA/delete_multiple') ?>">
            <div class="btn-group" role="group" aria-label="Basic example">
            </div>
            <table id="example" class="table table-bordered table-striped table-hover table-responsive bodycontainer scrollable" style="width:100%;">
                <thead>
                    <tr>
                        <th width="75px"><input type="checkbox" id="selectall" /></th>
                        <th width="75px">Menu</th>
                        <th width="75px">No</th>
                        <th width="325px">No Bukti</th>
                        <th width="275px">Tanggal</th>
                        <th width="275px">Tanggal Diminta</th>
                        <th width="100px">Devisi</th>
                        <th width="225px">Ket</th>
                        <th width="200px">Pesan</th>
                        <th width="200px">Tujuan</th>
                        <th width="200px">Flag</th>
                        <th width="200px">Gambar</th>
                        <th width="200px">Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </div>
    <br>
</section>

<!-- Modal -->
<div class="modal fade" id="melbbaModal" tabindex="-1" role="dialog" aria-labelledby="periodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 250px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="periodeLabel"> <i class="fas fa-cogs"></i> Ganti Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url('admin/dashboard/ganti_periode') ?>" class="user">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" list="month" id="bulanPeriode" placeholder="Pilih Bulan..." name="bulan">
                        <datalist id="month">
                            <option value='01'>01</option>
                            <option value='02'>02</option>
                            <option value='03'>03</option>
                            <option value='04'>04</option>
                            <option value='05'>05</option>
                            <option value='06'>06</option>
                            <option value='07'>07</option>
                            <option value='08'>08</option>
                            <option value='09'>09</option>
                            <option value='10'>10</option>
                            <option value='11'>11</option>
                            <option value='12'>12</option>
                        </datalist>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" id="tahunPeriode" placeholder="Tahun..." name="tahun">
                    </div>

                    <button class="btn btn-primary btn-user btn-block">Ubah Periode</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
                "<'row'<'col-md-2'l><'col-md-6 test_btn'><'col-md-4'f>>" + // peletakan entries, search, dan test_btn
                "<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
            "order": [
                [3, 'asc']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('admin/Transaksi_PesananLBBA/get_ajax_pp') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1, 2],
                "orderable": false
            }]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mb-3');
        // menambahkan button  di test_btn
        $("div.test_btn").html('  <a class="btn  btn-md btn-success" href="input"> <i class="fas fa-plus fa-sm md-3" ></i></a> ' +
            ' <button  type="submit" class="btn btn-md btn-danger" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"> <i class="fas fa-trash fa-sm md-3"></i></button> ' +
            '  <a class="btn type="hidden" btn-md btn-primary"  href="<?php echo site_url('admin/account/print') ?>"></a> '
        );
        $('#example').show();

    });
</script>