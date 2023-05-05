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
               History Verifikasi (Bon Belum Validasi)
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
                        <th width="200px">Tujuan</th>
                        <th width="200px">Pemesan</th>
                        <th width="200px">DR</th>
                        <th width="200px">Verifikasi Kepala</th>
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
    <div class="modal-dialog modal-lg" role="document" style="max-width: 550px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="periodeLabel"> <i class="fas fa-cogs"></i> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url('admin/dashboard/ganti_periode') ?>" class="user">
                    <div class="form-group" style="text-align:center">
                        <h3>Apakah anda akan cetak <br/><Br/><span style="font-weight:bold;font-size:18;" id="sp1"></span><br/></h3>
						<br/>
						<div style="text-align:center;color:blue;">
						Disimpan oleh <span id="sp2"></span></div>
						<br/>
						<div style="text-align:center;color:red">
							Yang sudah validasi:<br/>
							<div id="sp3"></div>
						</div>
                    </div>
                    

                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
				 <button id="btnPrint" data-dismiss="modal" class="btn btn-primary ">Cetak</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#melbbaModal').on('show.bs.modal', function (event) {
			var a = event.relatedTarget;
			var no = $(a).data("no");
			$("#sp1").text(no);
			var ttd1 = $(a).data("ttd1");
			var ttd1d = $(a).data("ttd1d");
			$("#sp2").text(ttd1 + " pada tanggal " + ttd1d);
			var lengkap = 1;
			var hist = "";
			var ttd2 = $(a).data("ttd2");
			var ttd2d = $(a).data("ttd2d");
			if(ttd2 != "" && ttd2 != null )
				hist += (ttd2 + " pada tanggal " + ttd2d) + "<Br/>";
			else lengkap = 0;
			
			var ttd2 = $(a).data("ttd2");
			var ttd2d = $(a).data("ttd2d");
			if(ttd2 != "" && ttd2 != null )
				hist += (ttd2 + " pada tanggal " + ttd2d) + "<Br/>";
			else lengkap = 0;
			
			var ttd3 = $(a).data("ttd3");
			var ttd3d = $(a).data("ttd3d");
			if(ttd3 != "" && ttd3 != null )
				hist += (ttd3 + " pada tanggal " + ttd3d) + "<Br/>";
			else lengkap = 0;
			
			var ttd4 = $(a).data("ttd4");
			var ttd4d = $(a).data("ttd4d");
			if(ttd4 != "" && ttd4 != null )
				hist += (ttd4 + " pada tanggal " + ttd4d) + "<Br/>";
			else lengkap = 0;
			
			var ttd5 = $(a).data("ttd5");
			var ttd5d = $(a).data("ttd5d");
			if(ttd5 != "" && ttd5 != null )
				hist += (ttd5 + " pada tanggal " + ttd5d) + "<Br/>";
			else lengkap = 0;
			
			// var ttd6 = $(a).data("ttd6");
			// var ttd6d = $(a).data("ttd6d");
			// if(ttd6 != "" && ttd6 != null )
			// 	hist += (ttd6 + " pada tanggal " + ttd6d) + "<Br/>";
			// else lengkap = 0;
			
			// var ttd7 = $(a).data("ttd7");
			// var ttd7d = $(a).data("ttd7d");
			// if(ttd7 != "" && ttd7 != null )
			// 	hist += (ttd7 + " pada tanggal " + ttd7d) + "<Br/>";
			// else lengkap = 0;
			
			if(lengkap == 0)
			{
				$("#btnPrint").hide();
			}
			else
			{
				var id = $(a).data("id");
				$("#btnPrint").click(function()
				{
					window.open("JASPER/" + id);
				});
			}
			
			$("#sp3").html(hist);
			console.log(event.relatedTarget);
		});
        
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