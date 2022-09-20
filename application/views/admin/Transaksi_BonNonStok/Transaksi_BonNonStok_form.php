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
		<i class="fas fa-university"></i> Input <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="barangmasuk" name="barangmasuk" action="<?php echo base_url('admin/Transaksi_Barang_Masuk/input_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value='' readonly>
						</div>
						<div class="col-md-1">
							<label class="label">No Bukti Beli </label>
						</div>
						<div class="col-md-2 input-group">
							<input name="NO_BUKTI_BL" id="NO_BUKTI_BL" type="text" class="form-control NO_BUKTI_BL text_input" onkeypress="return tabE(this,event)" required readonly>
							<span class="input-group-btn">
								<a class="btn default" onfocusout="hitung()" id="0" data-target="#mymodal_nobukti_bl" data-toggle="modal" href="#lupnobukti_bl" ><i class="fa fa-search"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tgl </label>
						</div>
						<div class="col-md-2">
							<input 
								type="text" 
								class="date form-control TGL text_input" 
								id="TGL" 
								name="TGL" 
								data-date-format="dd-mm-yyyy" 
								value="<?php if (isset($_POST["tampilkan"])) { echo $_POST["TGL"]; } else echo date('d-m-Y'); ?>" 
								onclick="select()" 
							>
						</div>
						<div class="col-md-1">
							<label class="label">Ket </label>
						</div>
						<div class="col-md-3">
							<input class="form-control text_input KET" id="KET" name="KET" type="text" value=''>
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
							<tr>
								<th width="50px">No</th>
								<th width="250px">Kode</th>
								<th width="250px">Nama</th>
								<th width="150px">Rak</th>
								<th width="125px">Qty Beli</th>
								<th width="150px">Satuan Beli</th>
								<th width="125px">Qty</th>
								<th width="150px">Satuan</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody id="show-data">
							<tr>
								<td><input name="REC[]" id="REC0" type="text" value="1" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
								<td><input name="KD_BHN[]" id="KD_BHN0" type="text" class="form-control KD_BHN text_input" readonly></td>
								<td><input name="NA_BHN[]" id="NA_BHN0" type="text" class="form-control NA_BHN text_input" readonly></td>
								<td><input name="RAK[]" id="RAK0" type="text" class="form-control RAK text_input"></td>
								<td><input name="QTY_BL[]" onkeyup="hitung()" value="0" id="QTY_BL0" type="text" class="form-control QTY_BL rightJustified text-primary" readonly></td>
								<td><input name="SATUAN_BL[]" id="SATUAN_BL0" type="text" class="form-control SATUAN_BL text_input" readonly></td>
								<td><input name="QTY[]" onkeyup="hitung()" value="0" id="QTY0" type="text" class="form-control QTY rightJustified text-primary"></td>
								<td><input name="SATUAN[]" id="SATUAN0" type="text" class="form-control SATUAN text_input"></td>
								<td>
									<!-- <button style="visibility: hidden;" type="hidden" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
										<i class="fa fa-fw fa-trash-alt"></i>
									</button> -->
								</td>
							</tr>
						</tbody>
						<tfoot>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY_BL rightJustified text-primary font-weight-bold" id="TOTAL_QTY_BL" name="TOTAL_QTY_BL" value="0" readonly></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="0" readonly></td>
							<td></td>
							<td></td>
						</tfoot>
					</table>
				</div>
            </div>
		</div>
		<br><br>
		<!--tab-->
		<!-- <div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div> -->
		<br>
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
		$('#modal_bl').DataTable({
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

<!-- myModal No Bukti BL Beli-->
<div id="mymodal_nobukti_bl" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Data Beli</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_bl'>
					<thead>	
						<th>No Bukti Beli Bon</th>
						<th>Periode</th>
						<th>Val</th>
						<th>DR</th>
					</thead>
					<tbody>
					<?php
						$dr= $this->session->userdata['dr'];
						$sql = "SELECT NO_BUKTI AS NO_BUKTI_BL, 
								PER AS PER, 
								TTD1 AS TTD1, 
								DR AS DR
							FROM beli 
							WHERE FLAG = 'BL' 
							AND FLAG2='SP' 
							AND DR='$dr'
							AND TTD1=1
							ORDER BY PER, NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach($a as $b ) { 
					?>
						<tr>
							<td class='NBBVAL'><a href="#" class="select_nobukti_bl"><?php echo $b->NO_BUKTI_BL;?></a></td>
							<td class='PEBVAL text_input'><?php echo $b->PER;?></td>
							<td class='VABVAL text_input'><?php echo $b->TTD1;?></td>
							<td class='DRBVAL text_input'><?php echo $b->DR;?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						alert("Data Belum Lengkap");
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
		$("#TOTAL_QTY_BL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#TOTAL_QTY").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY_BL" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#QTY" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}
		//mymoodal no bukti beli
			$('#mymodal_nobukti_bl').on('show.bs.modal', function (e) {
			target = $(e.relatedTarget);
		});
		$('body').on('click', '.select_nobukti_bl', function() {
			var val = $(this).parents("tr").find(".NBBVAL").text();
			target.parents("div").find(".NO_BUKTI_BL").val(val);
			$('#mymodal_nobukti_bl').modal('toggle');
			var no_bukti_bl = $(this).parents("tr").find(".NBBVAL").text();
			$.ajax({
				type:'get',
				url : '<?php echo base_url('index.php/admin/Transaksi_Barang_Masuk/filter_bl'); ?>',
				data:{ no_bukti_bl : no_bukti_bl},
				dataType: 'json',
				success:function(response) {
				// alert(response);
					var html = '';
                    var i;
                    for(i=0; i<response.length; i++){
                        html += '<tr>'+
									'<td><input name="REC[]" id=REC'+i+' type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly value='+(i+1)+' ></td>'+
									'<td><input name="KD_BHN[]" value="'+response[i].KD_BHN+'" id=KD_BHN'+i+' type="text" class="form-control KD_BHN text_input" readonly></td>'+
									'<td><input name="NA_BHN[]" value="'+response[i].NA_BHN+'" id=NA_BHN'+i+' type="text" class="form-control NA_BHN text_input" readonly></td>'+
									'<td><input name="RAK[]" id=RAK'+i+' type="text" class="form-control RAK text_input"></td>'+
									'<td><input name="QTY_BL[]" onclick="select()" value="'+numberWithCommas(response[i].QTY_BL)+'" onkeyup="hitung()" id=QTY_BL'+i+' type="text" class="form-control QTY_BL rightJustified text-primary" readonly></td>'+
									'<td><input name="SATUAN_BL[]" id=SATUAN_BL'+i+' type="text" class="form-control SATUAN_BL text_input" readonly></td>'+
									'<td><input name="QTY[]" onclick="select()" value="0" onkeyup="hitung()" id=QTY'+i+' type="text" class="form-control QTY rightJustified text-primary"></td>'+
									'<td><input name="SATUAN[]" id=SATUAN'+i+' type="text" class="form-control SATUAN text_input"></td>'+
									'<td><input type="hidden" name="NO_ID[]" id=NO_ID'+i+'  class="form-control"  value="'+response[i].NO_ID+'"  >'+
									'<button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" style="visibility: hidden;" onclick=""> <i class="fa fa-fw fa-trash-alt"></i> </button></td>'+
								'</tr>';
                    }
					idrow=i;
					$('#show-data').html(html);
					jumlahdata = 100 ;
					for(i=0; i<=jumlahdata; i++){
						$("#QTY_BL" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
						$("#QTY" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					}
					$('input[type="checkbox"]').on('change', function(){
						this.value ^= 1;
						console.log( this.value )
					});
					select_kd_bhn();
					$(".date").datepicker({
						'dateFormat': 'dd-mm-yy',
					})
				}
			});
		});
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
		var TOTAL_QTY_BL = 0;
		var TOTAL_QTY = 0;
		var total_row = idrow;
		for (i=0;i<total_row;i++) {

		};
		$(".QTY_BL").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if(isNaN(val)) val = 0;
			TOTAL_QTY_BL+=val;
		});
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if(isNaN(val)) val = 0;
			TOTAL_QTY+=val;
		});

		if(isNaN(TOTAL_QTY_BL)) TOTAL_QTY_BL = 0;
		if(isNaN(TOTAL_QTY)) TOTAL_QTY = 0;

		$('#TOTAL_QTY_BL').val(numberWithCommas(TOTAL_QTY_BL));
		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));

		$('#TOTAL_QTY_BL').autoNumeric('update');
		$('#TOTAL_QTY').autoNumeric('update');
	}

	function tambah() {

		var x = document.getElementById('datatable').insertRow(idrow + 1);
		var td1 = x.insertCell(0);
		var td2 = x.insertCell(1);
		var td3 = x.insertCell(2);
		var td4 = x.insertCell(3);
		var td5 = x.insertCell(4);
		var td6 = x.insertCell(5);
		var td7 = x.insertCell(6);
		var td8 = x.insertCell(7);
		var td9 = x.insertCell(8);
		
		var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN0' name='KD_BHN[]' id=KD_BHN0" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()' required></select></div>";

		var kd_bhn = kd_bhn0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = kd_bhn;
		td3.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN' readonly>";
		td4.innerHTML = "<input name='RAK[]' id=RAK" + idrow + " type='text' class='form-control RAK' required>";
		td5.innerHTML = "<input name='QTY_BL[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY_BL" + idrow + " type='text' class='form-control QTY_BL rightJustified text-primary' required>";
		td6.innerHTML = "<input name='SATUAN_BL[]' id=SATUAN_BL" + idrow + " type='text' class='form-control SATUAN_BL'>";
		td7.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
		td8.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN'>";
		td9.innerHTML = "<input type='hidden' name='NO_ID[]' id=NO_ID" + idrow + " class='form-control'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY_BL" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#QTY" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}
		idrow++;
		nomor();
		$(".ronly").on('keydown paste', function(e) {
			e.preventDefault();
			e.currentTarget.blur();
		});
		$('input[type="checkbox"]').on('change', function(){
			this.value ^= 1;
			console.log( this.value )
		});
		select_kd_bhn();
	}

	function hapus() {
		if (idrow > 1) {
			var x = document.getElementById('datatable').deleteRow(idrow);
			idrow--;
			nomor();
		}
	}

</script>

<script>
	$(document).ready(function() {
		select_kd_bhn();
	});

	function select_kd_bhn() {
		$('.js-example-responsive-kd_bhn').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Barang_Masuk/getDataAjax_bhn') ?>",
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
			"<div class='select2-result-repository clearfix'>" +
			"<div class='select2-result-repository__title'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kd_bhn.KD_BHN);
		return $container;
	}
	var kd_bhn = '';
	var na_bhn = '';
	var satuan = '';

	function formatSelection_kd_bhn(repo_kd_bhn) {
		kd_bhn = repo_kd_bhn.KD_BHN;
		na_bhn = repo_kd_bhn.NA_BHN;
		satuan = repo_kd_bhn.SATUAN;
		return repo_kd_bhn.text;
	}

	function kd_bhn(x) {
		var q = x.substring(6, 12);
		$('#KD_BHN' + q).val(kd_bhn);
		$('#NA_BHN' + q).val(na_bhn);
		$('#SATUAN' + q).val(satuan);
		console.log(q);
	}

</script>