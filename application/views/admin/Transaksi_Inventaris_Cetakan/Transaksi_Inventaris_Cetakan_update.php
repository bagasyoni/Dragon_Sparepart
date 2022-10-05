<style>
	#myInput {
		background-image: url('<?php echo base_url() ?>assets/img/search-icon-blue.png');
		background-position: 10px 12px;
		background-repeat: no-repeat;
		width: 100%;
		padding: 12px 20px 12px 40px;
		border: 1px solid #ddd;
		margin-bottom: 12px;
	}
	#myTable {
		border-collapse: collapse;
		width: 100%;
		border: 1px solid #ddd;
	}
	#myTable th,
	#myTable td { text-align: left; padding: 5px; }
	#myTable tr { border-bottom: 1px solid #ddd; }
	#myTable tr.header,
	#myTable tr:hover { background-color: #f1f1f1; }
	input[type=text]:focus { width: 100%; }
	table {	table-layout: fixed; }
	table th {color: black; text-align: center;}
	table td { overflow: hidden; }
	.label {color: black; font-weight: bold;}
	.rightJustified { text-align: right; }
	.total { font-weight: bold; color: blue; }
	.form-control {font-size: small;}
	.bodycontainer {
		/* width: 1000px; */
		max-height: 500px;
		margin: 0;
		overflow-y: auto;
	}
	#datatable td {
		padding: 2px !important;
		vertical-align: middle;
	}
	.alert-container { background-color: #00b386; color: black; font-weight: bolder;}
	.per {
		font-weight: bolder;
		color: black;
	}
	.border-left{
		border-left: 1px solid black !important;
		border-top: 1px solid black !important;
		border-bottom: 1px solid black !important;
		/* border-right: 1px solid black !important; */
	}
	.border-middle{
		/* border-left: 1px solid black !important; */
		border-top: 1px solid black !important;
		border-bottom: 1px solid black !important;
		/* border-right: 1px solid black !important; */
	}
	.border-right{
		/* border-left: 1px solid black !important; */
		border-top: 1px solid black !important;
		border-bottom: 1px solid black !important;
		border-right: 1px solid black !important;
	}
	.table-scrollable {	margin: 0; padding: 0; }
	.modal-bodys { max-height: 250px; overflow-y: auto; }
	.select2-dropdown {	width: 500px !important; }
	/* .container { text-align: center; vertical-align: middle;} */
	.checkbox_container {width: 25px; height: 25px;}
	td input[type="checkbox"] {
		float: left;
		margin: 0 auto;
		width: 100%;
	}
	.text_input {font-size: small; color: black;}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Update <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="transaksiinventariscetakan" name="transaksiinventariscetakan" action="<?php echo base_url('admin/Transaksi_Inventaris_Cetakan/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Cetakan </label>
						</div>
						<div class="col-md-2">
							<input type="hidden" id="NO_ID" name="NO_ID" class="form-control" value="<?= $NO_ID ?>">
							<input class="form-control text_input CETAK" id="CETAK" name="CETAK" type="text" value="<?= $CETAK ?>">
						</div>
                        <div class="col-md-1">
							<label class="label">Kode </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input KODE" id="KODE" name="KODE" type="text" value="<?= $KODE ?>">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Nama </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NAMA" id="NAMA" name="NAMA" type="text" value="<?= $NAMA ?>">
						</div>
						<div class="col-md-1">
							<label class="label">Nomor </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_URUT" id="NO_URUT" name="NO_URUT" type="text" value="<?= $NO_URUT ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive scrollable">
					<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
						<thead>
						</thead>
						<tbody>
							<tr>
								<td class="border-left"><input name="N1" id="N1" type="text" class="form-control N1" value="<?= $N1 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J1" id="J1" type="text" onkeyup="hitung()" class="form-control J1 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J1,2,'.',',');?>"></td>
								<td class="border-left"><input name="N2" id="N2" type="text" class="form-control N2" value="<?= $N2 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J2" id="J2" type="text" onkeyup="hitung()" class="form-control J2 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J2,2,'.',',');?>"></td>
								<td class="border-left"><input name="N3" id="N3" type="text" class="form-control N3" value="<?= $N3 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J3" id="J3" type="text" onkeyup="hitung()" class="form-control J3 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J3,2,'.',',');?>"></td>
								<td class="border-left"><input name="N4" id="N4" type="text" class="form-control N4" value="<?= $N4 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J4" id="J4" type="text" onkeyup="hitung()" class="form-control J4 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J4,2,'.',',');?>"></td>
								<td class="border-left"><input name="N5" id="N5" type="text" class="form-control N5" value="<?= $N5 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J5" id="J5" type="text" onkeyup="hitung()" class="form-control J5 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J5,2,'.',',');?>"></td>
							</tr>
							<tr>
								<td class="border-left"><input name="N6" id="N6" type="text" class="form-control N6" value="<?= $N6 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J6" id="J6" type="text" onkeyup="hitung()" class="form-control J6 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J6,2,'.',',');?>"></td>
								<td class="border-left"><input name="N7" id="N7" type="text" class="form-control N7" value="<?= $N7 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J7" id="J7" type="text" onkeyup="hitung()" class="form-control J7 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J7,2,'.',',');?>"></td>
								<td class="border-left"><input name="N8" id="N8" type="text" class="form-control N8" value="<?= $N8 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J8" id="J8" type="text" onkeyup="hitung()" class="form-control J8 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J8,2,'.',',');?>"></td>
								<td class="border-left"><input name="N9" id="N9" type="text" class="form-control N9" value="<?= $N9 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J9" id="J9" type="text" onkeyup="hitung()" class="form-control J9 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J9,2,'.',',');?>"></td>
								<td class="border-left"><input name="N10" id="N1" type="text" class="form-control N10" value="<?= $N10 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J10" id="J10" type="text" onkeyup="hitung()" class="form-control J10 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J10,2,'.',',');?>"></td>
							</tr>
							<tr>
								<td class="border-left"><input name="N11" id="N11" type="text" class="form-control N11" value="<?= $N11 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J11" id="J11" type="text" onkeyup="hitung()" class="form-control J11 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J11,2,'.',',');?>"></td>
                                <td class="border-left"><input name="N12" id="N12" type="text" class="form-control N12" value="<?= $N12 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J12" id="J12" type="text" onkeyup="hitung()" class="form-control J12 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J12,2,'.',',');?>"></td>
								<td class="border-left"><input name="N13" id="N13" type="text" class="form-control N13" value="<?= $N13 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J13" id="J13" type="text" onkeyup="hitung()" class="form-control J13 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J13,2,'.',',');?>"></td>
								<td class="border-left"><input name="N14" id="N14" type="text" class="form-control N14" value="<?= $N14 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J14" id="J14" type="text" onkeyup="hitung()" class="form-control J14 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J14,2,'.',',');?>"></td>
                                <td class="border-left"><input name="N15" id="N15" type="text" class="form-control N15" value="<?= $N15 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J15" id="J15" type="text" onkeyup="hitung()" class="form-control J15 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J15,2,'.',',');?>"></td>
							</tr>
							<tr>
								<td class="border-left"><input name="N16" id="N16" type="text" class="form-control N16" value="<?= $N16 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J16" id="J16" type="text" onkeyup="hitung()" class="form-control J16 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J16,2,'.',',');?>"></td>
                                <td class="border-left"><input name="N17" id="N17" type="text" class="form-control N17" value="<?= $N17 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J17" id="J17" type="text" onkeyup="hitung()" class="form-control J17 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J17,2,'.',',');?>"></td>
								<td class="border-left"><input name="N18" id="N18" type="text" class="form-control N18" value="<?= $N18 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J18" id="J18" type="text" onkeyup="hitung()" class="form-control J18 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J18,2,'.',',');?>"></td>
								<td class="border-left"><input name="N19" id="N19" type="text" class="form-control N19" value="<?= $N19 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J19" id="J19" type="text" onkeyup="hitung()" class="form-control J19 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J19,2,'.',',');?>"></td>
                                <td class="border-left"><input name="N20" id="N20" type="text" class="form-control N20" value="<?= $N20 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J20" id="J20" type="text" onkeyup="hitung()" class="form-control J20 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J20,2,'.',',');?>"></td>
							</tr>
							<tr>
								<td class="border-left"><input name="N21" id="N21" type="text" class="form-control N21" value="<?= $N21 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J21" id="J21" type="text" onkeyup="hitung()" class="form-control J21 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J21,2,'.',',');?>"></td>
                                <td class="border-left"><input name="N22" id="N22" type="text" class="form-control N22" value="<?= $N22 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J22" id="J22" type="text" onkeyup="hitung()" class="form-control J22 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J22,2,'.',',');?>"></td>
								<td class="border-left"><input name="N23" id="N23" type="text" class="form-control N23" value="<?= $N23 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right"><input name="J23" id="J23" type="text" onkeyup="hitung()" class="form-control J23 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J23,2,'.',',');?>"></td>
								<td class="border-left"><input name="N24" id="N24" type="text" class="form-control N24" value="<?= $N24 ?>"></td>
								<td class="border-middle" style="width: 10px;"><label class="per"> / </label></td>
								<td class="border-right">
									<input name="J24" id="J24" type="text" onkeyup="hitung()" class="form-control J24 rightJustified text-primary font-weight-bold" value="<?php echo number_format($J24,2,'.',',');?>">
									<!-- <input onkeyup="hitung()" value="<?php echo number_format($GAJI,0,'.',',');?>" name="SUBJUMLAH" id="SUBJUMLAH0" type="text" class="form-control SUBJUMLAH" > -->
								</td>
                            </tr>
						</tbody>
						<tfoot>
						</tfoot>
					</table>
				</div>
            </div>
		</div>
		<hr>
		<div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<label class="label">Keterangan 1</label>
				</div>
				<div class="col-md-3">
					<input class="form-control KET1 text_input" id="KET1" name="KET1" value="<?= $KET1 ?>">
				</div>
				<div class="col-md-5"></div>
				<div class="col-md-1">
					<label class="label">Jumlah :</label>
				</div>
				<div class="col-md-2 ">
					<input class="form-control JUMLAH rightJustified text-primary font-weight-bold" onkeyup="hitung()" value="<?php echo number_format($JUMLAH,2,'.',',');?>" id="JUMLAH" name="JUMLAH" readonly>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<label class="label">Keterangan 2</label>
				</div>
				<div class="col-md-3">
					<input class="form-control KET2 text_input" id="KET2" name="KET2" value="<?= $KET2 ?>">
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<label class="label">Keterangan 3</label>
				</div>
				<div class="col-md-3">
					<input class="form-control KET3 text_input" id="KET3" name="KET3" value="<?= $KET3 ?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-9">
				<div class="wells">
					<div class="btn-group cxx">
						<button type="submit"  class="btn btn-success"><i class="fa fa-save"></i> Save</button>										
						<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Cancel</a>
					</div>
					<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Savings.done...</span></h4>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#modal_bagian').DataTable({
			dom: 
				"<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ],
			order: true,
		});
		$('.modal-footer').on('click', '#close', function() {			 
			$('input[type=search]').val('').keyup();  // this line and next one clear the search dialog
		});
	});
</script> 

<script>
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					} else {
						$(this).submit(function() {
							return false;
						});
					}
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
	var target;
	var idrow = 1;
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		$("#J1").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J2").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J3").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J4").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J5").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J6").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J7").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J8").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J9").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J10").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J11").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J12").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J13").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J14").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J15").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J16").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J17").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J18").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J19").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J20").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J21").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J22").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J23").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#J24").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#JUMLAH").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {

		}
		$('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			idrow--;
			nomor();
		});
		$('input[type="checkbox"]').on('change', function(){
			this.value ^= 1;
			console.log( this.value )
		});
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
	});

	function nomor() {
		var i = 1;
		$(".REC").each(function() {
			$(this).val(i++);
		});
		hitung();
	}

	function hitung() {
		var J1 = parseFloat($('#J1').val().replace(/,/g, ''));;
		var J2 = parseFloat($('#J2').val().replace(/,/g, ''));;
		var J3 = parseFloat($('#J3').val().replace(/,/g, ''));;
		var J4 = parseFloat($('#J4').val().replace(/,/g, ''));;
		var J5 = parseFloat($('#J5').val().replace(/,/g, ''));;
		var J6 = parseFloat($('#J6').val().replace(/,/g, ''));;
		var J7 = parseFloat($('#J7').val().replace(/,/g, ''));;
		var J8 = parseFloat($('#J8').val().replace(/,/g, ''));;
		var J9 = parseFloat($('#J9').val().replace(/,/g, ''));;
		var J10 = parseFloat($('#J10').val().replace(/,/g, ''));;
		var J11 = parseFloat($('#J11').val().replace(/,/g, ''));;
		var J12 = parseFloat($('#J12').val().replace(/,/g, ''));;
		var J13 = parseFloat($('#J13').val().replace(/,/g, ''));;
		var J14 = parseFloat($('#J14').val().replace(/,/g, ''));;
		var J15 = parseFloat($('#J15').val().replace(/,/g, ''));;
		var J16 = parseFloat($('#J16').val().replace(/,/g, ''));;
		var J17 = parseFloat($('#J17').val().replace(/,/g, ''));;
		var J18 = parseFloat($('#J18').val().replace(/,/g, ''));;
		var J19 = parseFloat($('#J19').val().replace(/,/g, ''));;
		var J20 = parseFloat($('#J20').val().replace(/,/g, ''));;
		var J21 = parseFloat($('#J21').val().replace(/,/g, ''));;
		var J22 = parseFloat($('#J22').val().replace(/,/g, ''));;
		var J23 = parseFloat($('#J23').val().replace(/,/g, ''));;
		var J24 = parseFloat($('#J24').val().replace(/,/g, ''));;
		var JUMLAH = 0;
		var total_row = idrow;
		for (i=0;i<total_row;i++) {
		};
		var JUMLAH = J1+J2+J3+J4+J5+J6+J7+J8+J9+J10+J11+J12+J13+J14+J15+J16+J17+J18+J19+J20+J21+J22+J23+J24;

		if(isNaN(JUMLAH)) JUMLAH = 0;

		$('#JUMLAH').val(numberWithCommas(JUMLAH));

		$('#JUMLAH').autoNumeric('update');
	}

	function tambah() {}

	function hapus() {}

</script>

<script>
	$(document).ready(function() {
		select_kd_bhn();
	});

	function select_kd_bhn() {
		$('.js-example-responsive-kd_bhn').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Pemesanan/getDataAjax_Bahan') ?>",
				dataType: "json",
				type: "post",
				delay: 10,
				data: function(params) {
					return {
						search: params.term,
						page: params.page
					}
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: data.total_count
						}
					};
				},
				cache: true
			},
			placeholder: 'Pilih Barang',
			minimumInputLength: 0,
			templateResult: format_kd_bhn,
			templateSelection: formatSelection_kd_bhn
		});
	}

	function format_kd_bhn(repo_kd_bhn) {
		if (repo_kd_bhn.loading) {
			return repo_kd_bhn.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kd_bhn.kd_bhn);
		return $container;
	}
	var na_bhn = '';
	var satuan = '';

	function formatSelection_kd_bhn(repo_kd_bhn) {
		na_bhn = repo_kd_bhn.na_bhn;
		satuan = repo_kd_bhn.satuan;
		return repo_kd_bhn.text;
	}

	function kd_bhn(x) {
		var q = x.substring(6, 12);
		$('#NA_BHN' + q).val(na_bhn);
		$('#SATUAN' + q).val(satuan);
		console.log(q);
	}

</script>