<?php $kode_menu = $this->session->userdata['kode_menu']; ?>
<?php $level = $this->session->userdata['level']; ?>
<?php $super_admin = $this->session->userdata['super_admin']; ?>

<style>
    .table {
        height: 420px;
        overflow: scroll;
    }

    .table>thead>tr>th {
        background-color: #01BAEF;
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
        background-color: #ebedeb;
    }

    .table-striped>tbody>tr:nth-child(even)>td,
    .table-striped>tbody>tr:nth-child(even)>th {
        background-color: white;
    }

    .table>tbody>tr>td>div {
        text-align: center;
    }

    .table>tbody>tr>td>div>a {
        font-size: 13px;
        color: black;
        background-color: #9c774c;
    }

    .table>tbody>tr>td>div>a:hover {
        transition: 0.4s;
        color: #b3b3b3;
        background-color: #9c774c;
    }

    .table>tbody>tr>td>div>a::selection {
        color: white;
    }

    .table>tbody>tr>td>div>div>a:hover {
        transition: 0.4s;
        color: white;
        background-color: #01BAEF;
    }

    .table>tbody>tr>td>div>div>a>i {
        color: black;
        background-color: transparent;
    }
</style>

<section>
    <br>
    <div class="container-fliud mx-4">
        <div class="alert alert-container" role="alert">
            <i class="fas fa-university"></i>
            <label>
                Transaksi PO Non Bahan
            </label>
        </div>

        <div class="form-body">
			<div class="row">
				<div class="col-md-1">
					<label class="label">CARI PO</label>
				</div>
				<div class="input-group col-md-2">
					<input class="form-control text_input BUKTI" placeholder="NO BUKTI" id="BUKTI" name="BUKTI" type="text" value=''>
				</div>
                <div class="input-group col-md-2">
					<input class="form-control text_input PP" placeholder="NO PP" id="PP" name="PP" type="text" value=''>
				</div>
                <div class="input-group col-md-2">
					<input class="form-control text_input SUPPLIER" placeholder="SUPPLIER" id="SUPPLIER" name="SUPPLIER" type="text" value=''>
				</div>
                <div class="input-group col-md-2">
					<input class="form-control text_input NA_BHN" placeholder="NA_BHN" id="NA_BHN" name="NA_BHN" type="text" value=''>
				</div>
                 <div class="input-group col-md-2">
					<input class="form-control text_input KD_BHN" placeholder="KD_BHN" id="KD_BHN" name="KD_BHN" type="text" value=''>
				</div>
				<div class="col-md-2">
					<span class="input-group-btn">
						<a class="btn default" id="0" onclick='data_po()' data-target="#mymodal_pononbahan" data-toggle="modal" href=""><i class="fa fa-search"></i></a>
					</span>
				</div>
			</div>
		</div>

        <?php echo $this->session->flashdata('pesan') ?>
        <form method="post" action="<?php echo base_url('admin/Transaksi_PO_NonBahan/delete_multiple') ?>">
            <div class="btn-group" role="group" aria-label="Basic example">
            </div>
            <table id="example" class="table table-bordered table-striped table-hover table-responsive bodycontainer scrollable" style="width:100%;">
                <thead>
                    <tr>
                        <!-- 1200px -->
                        <th width="75px"><input type="checkbox" id="selectall" /></th>
                        <th width="75px">Menu</th>
                        <th width="75px">No</th>
                        <th width="250px">No Bukti</th>
                        <th width="250px">Tanggal</th>
                        <th width="200px">Notes</th>
                        <th width="220px">Total Qty</th>
                        <th width="50px">Nett</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </div>
    <br>
</section>

<!-- myModal History PO-->
<div id="mymodal_pononbahan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">History PO Nonbahan</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_pononbahan'>
					<thead>
						<th>NO PO</th>
						<th>PO PPIC</th>
						<th>TGL</th>
						<th>KODE</th>
						<th>ORDER</th>
						<th>NAMA SUP</th>
						<th>QTY</th>
						<th>HARGA</th>
						<th>TOTAL</th>
					</thead>
					<tbody id="show-no_pp">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#example').DataTable({
            dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
                "<'row'<'col-md-2'l><'col-md-6 test_btn'><'col-md-4'f>>" + // peletakan entries, search, dan test_btn
                // "<'row'<'col-md-12'f>>" + // peletakan search2
                "<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
            "order": [
                [3, 'asc']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('admin/Transaksi_PO_NonBahan/get_ajax_po') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1, 2],
                "orderable": false
            }]
        });


        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mb-3');
        $("div.test_btn").html('  <a class="btn  btn-md btn-success" href="input"> <i class="fas fa-plus fa-sm md-3" ></i></a> ' +
            ' <button  type="submit" class="btn btn-md btn-danger" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"> <i class="fas fa-trash fa-sm md-3"></i></button> ' +
            '  <a class="btn type="hidden" btn-md btn-primary"  href="<?php echo site_url('admin/Transaski_PO_Bahan/print') ?>"></a> ' 
            // ' <a class="btn default" data-target="#mymodal_pononbahan" data-toggle="modal" href=""><i class="fa fa-search"></i></a> '
        );
        $('#example').show();
    });

    function data_po(){
        console.log($('#BUKTI').val());
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('index.php/admin/Transaksi_Po_NonBahan/historypo'); ?>',
			contentType: 'application/json; charset=utf-8',
			data: {
				// "KODES": kodes
				bukti : $('#BUKTI').val(),
				pp : $('#PP').val(),
				supplier : $('#SUPPLIER').val(),
				na_bhn : $('#NA_BHN').val(),
				kd_bhn : $('#KD_BHN').val(),
			},
			dataType: 'text json',
			cache: false,
			success: function(response) {

                $('#modal_pononbahan').DataTable({
                        "bDestroy": true,
                        data: response,
                        columns: [
							{data: "NO_BUKTI"},
							{data: "NO_PP"},
							{data: "TGL"},
							{data: "KD_BHN"},
							{data: "NA_BHN"},
							{data: "NAMAS"},
							{data: "QTY"},
							{data: "HARGA"},
							{data: "TOTAL"},
                        ],
                        dom: "<'row'<'col-md-6'B><'col-md-6'>>" +
							"<'row'<'col-md-2'l><'col-md-6'><'col-md-4'f>>" + // peletakan entries, search, dan test_btn
							"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
						buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                });
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				
				console.log('error bro');
				console.log(textStatus);
				console.log(errorThrown);
				$('#show-no_pp').html(``);
			}
		});
	}

</script>