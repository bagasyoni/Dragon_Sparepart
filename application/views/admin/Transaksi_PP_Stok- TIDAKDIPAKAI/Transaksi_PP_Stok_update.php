<?php
	foreach ($transaksi_pp_stok as $rowh) {};
?>

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
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Update <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="transaksippstok" name="transaksippstok" action="<?php echo base_url('admin/Transaksi_PP_Stok/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input type="hidden" name="ID" class="form-control" value="<?php echo $rowh->ID ?>">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value="<?php echo $rowh->NO_BUKTI ?>" readonly>
						</div>
						<div class="col-md-1">
							<label class="label">No Bukti Bon </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_BUKTI_BELI_BON" id="NO_BUKTI_BELI_BON" name="NO_BUKTI_BELI_BON" type="text" value="<?php echo $rowh->NO_BUKTI_BELI_BON ?>" readonly>
						</div>
						<div class="col-md-3"></div>
						<div class="col-md-1">
							<?php
								if ($rowh->TTD1 == 0) 
									echo '<a 
										type="button" 
										class="btn btn-warning"
										onclick="btPosting()" 
										href="#"
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> POSTING</span>
									</a>';
								else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> POSTING</span>
								</a>';
							?>
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
								class="date form-control TGL" 
								id="TGL" 
								name="TGL"
								data-date-format="dd-mm-yyyy" 
								value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>"
								onclick="select()" 
							>
						</div>
						<div class="col-md-1">
							<label class="label">Ket </label>
						</div>
						<div class="col-md-3">
							<input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> class="form-control text_input KET" id="KET" name="KET" type="text" value="<?php echo $rowh->KET ?>">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanda Tangan </label>
						</div>
						<div class="col-md-1">
							<?php
								if ($rowh->TTD1 == 0) 
									echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> ADMIN</span>
									</a>';
								else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> ADMIN</span>
								</a>';
							?>
						</div>
						<div class="col-md-1">
							<?php
								if ($rowh->TTD2 == 0) 
									echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> KABAG</span>
									</a>';
								else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> KABAG</span>
								</a>';
							?>
						</div>
						<div class="col-md-1">
							<?php
								if ($rowh->TTD3 == 0) 
									echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> FM</span>
									</a>';
								else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> FM</span>
								</a>';
							?>
						</div>
						<div class="col-md-1">
							<?php
								if ($rowh->TTD4 == 0) 
									echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> PURCH</span>
									</a>';
								else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> PURCH</span>
								</a>';
							?>
						</div>
						<div class="col-md-1">
							<?php
								if ($rowh->TTD5 == 0) 
									echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> DIREKT</span>
									</a>';
								else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> DIREKT</span>
								</a>';
							?>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-2">
							<label class="label">Kuning = Belum tanda tangan </label>
						</div>
						<div class="col-md-2">
							<label class="label">Hijau = Sudah tanda tangan </label>
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
								<th width="150px">Kode</th>
								<th width="125px">Uraian</th>
								<th width="175px">Ket Barang</th>
								<th width="100px">Qty</th>
								<th width="100px">Satuan</th>
								<th width="100px">Devisi</th>
								<th width="120px">Keterangan</th>
								<th width="100px">Tgl Diminta</th>
								<th width="100px">Sisa</th>
								<th width="90px">Urgent</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
						<?php
							$no = 0;
							foreach ($transaksi_pp_stok as $row) : 
						?>
							<tr>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC" onkeypress="return tabE(this,event)" readonly></td>
								<td>
									<div class="input-group">
										<select class="js-example-responsive-kd_brg form-control KD_BRG" name="KD_BRG[]" id="KD_BRG<?php echo $no; ?>" onchange="kd_brg(this.id)" <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> required>
											<option value="<?php echo $row->KD_BRG; ?>" selected id="KD_BRG<?php echo $no; ?>"><?php echo $row->KD_BRG; ?></option>
										</select>
									</div>
								</td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="NA_BRG[]" id="NA_BRG<?php echo $no; ?>" value="<?= $row->NA_BRG ?>" type="text" class="form-control NA_BRG" readonly></td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="TIPE[]" id="TIPE<?php echo $no; ?>" value="<?= $row->TIPE ?>" type="text" class="form-control TIPE"></td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="QTY[]" onchange="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary"></td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control SATUAN"></td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="DEVISI[]" id="DEVISI<?php echo $no; ?>" value="<?= $row->DEVISI ?>" type="text" class="form-control DEVISI"></td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="KET1[]" id="KET1<?php echo $no; ?>" value="<?= $row->KET1 ?>" type="text" class="form-control KET1"></td>
								<td>
									<input 
										<?php if ($rowh->TTD3 == 1) echo 'readonly class="form-control" style="color: black;"'; ?>
										name="TGL_DIMINTA[]" 
										id="TGL_DIMINTA<?php echo $no; ?>"
										type="text" 
										class="date form-control" 
										data-date-format="dd-mm-yyyy" 
										value="<?php echo date('d-m-Y', strtotime($row->TGL_DIMINTA, TRUE)); ?>"
										onclick="select()" 
									>
								</td>
								<td><input <?php if ($rowh->TTD3 == 1) echo 'readonly'; ?> name="SISA[]" onkeyup="hitung()" id="SISA<?php echo $no; ?>" value="<?php echo number_format($row->SISA, 2, '.', ','); ?>" type="text" class="form-control SISA rightJustified text-primary"></td>
								<td>
									<!-- <input name="URGENT[]" id="URGENT<?php echo $no; ?>" value="<?php if ($row->URGENT == "1") echo 'readonly'; ?>" type="text" class="form-control URGENT"> -->
									<input <?php if ($row->URGENT == "1") echo 'checked onclick="return false;"'; ?> name="URGENT[]" id="URGENT<?php echo $no; ?>" type="checkbox" value="<?= $row->URGENT ?>" class="checkbox_container">
								</td>
								<td>
									<input name="NO_ID[]" id="NO_ID<?php echo $no; ?>" value="<?= $row->NO_ID ?>" class="form-control" type="hidden">
									<button style="visibility: hidden;" type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
										<i class="fa fa-fw fa-trash-alt"></i>
									</button>
								</td>
							</tr>
							<?php $no++; ?>
						<?php endforeach; ?>
						</tbody>
						<tfoot>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="<?php echo number_format($row->TOTAL_QTY, 2, '.', ','); ?>" readonly></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tfoot>
					</table>
				</div>
            </div>
		</div>
		<br><br>
		<!--tab-->
		<div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div>
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
		$('#modal_beli').DataTable({
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

<!-- myModal No Bukti BL BON-->
<div id="mymodal_nobukti_beli_bon" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Data Bon</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_beli'>
					<thead>	
						<th>No Bukti</th>
						<th>Periode</th>
						<th>TTD 1</th>
						<th>TTD2</th>
						<th>TTD3</th>
						<th>TTD4</th>
						<th>TTD5</th>
						<th>OK</th>
						<th>Tujuan</th>
						<th>DR</th>
					</thead>
					<tbody>
					<?php
						$sql = "SELECT NO_BUKTI AS NO_BUKTI_BELI_BON, 
								PER AS PER, 
								TTD1 AS TTD1, 
								TTD2 AS TTD2, 
								TTD3 AS TTD3,
								TTD4 AS TTD4,
								TTD5 AS TTD5,
								OK AS OK,
								TUJUAN AS TUJUAN,
								DR AS DR
							FROM bl_beli  
							ORDER BY PER, NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach($a as $b ) { 
					?>
						<tr>
							<td class='NBBVAL'><a href="#" class="select_nobukti_beli_bon"><?php echo $b->NO_BUKTI_BELI_BON;?></a></td>
							<td class='PRBVAL text_input'><?php echo $b->PER;?></td>
							<td class='T1BVAL text_input'><?php echo $b->TTD1;?></td>
							<td class='T2BVAL text_input'><?php echo $b->TTD2;?></td>
							<td class='T3BVAL text_input'><?php echo $b->TTD3;?></td>
							<td class='T4BVAL text_input'><?php echo $b->TTD4;?></td>
							<td class='T5BVAL text_input'><?php echo $b->TTD5;?></td>
							<td class='OKBVAL text_input'><?php echo $b->OK;?></td>
							<td class='TJBVAL text_input'><?php echo $b->TUJUAN;?></td>
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
	var idrow = <?php echo $no ?>;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		$("#TOTAL_QTY").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#SISA" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}
		//mymoodal no bukti beli
			$('#mymodal_nobukti_beli_bon').on('show.bs.modal', function (e) {
			target = $(e.relatedTarget);
		});
		$('body').on('click', '.select_nobukti_beli_bon', function() {
			var val = $(this).parents("tr").find(".NBBVAL").text();
			target.parents("div").find(".NO_BUKTI_BELI_BON").val(val);
			$('#mymodal_nobukti_beli_bon').modal('toggle');
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
		var TOTAL_QTY = 0;
		var total_row = idrow;
		for (i=0;i<total_row;i++) {

		};
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if(isNaN(val)) val = 0;
			TOTAL_QTY+=val;
		});

		if(isNaN(TOTAL_QTY)) TOTAL_QTY = 0;

		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));

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
		var td10 = x.insertCell(9);
		var td11 = x.insertCell(10);
		var td12 = x.insertCell(11);
		
		var kd_brg0 = "<div class='input-group'><select class='js-example-responsive-kd_brg form-control KD_BRG0' name='KD_BRG[]' id=KD_BRG0" + idrow + " onchange='kd_brg(this.id)' onfocusout='hitung()'></select></div>";

		var kd_brg = kd_brg0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = kd_brg;
		td3.innerHTML = "<input name='NA_BRG[]' id=NA_BRG0" + idrow + " type='text' class='form-control NA_BRG' readonly>";
		td4.innerHTML = "<input name='TIPE[]' id=TIPE0" + idrow + " type='text' class='form-control TIPE'>";
		td5.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary'>";
		td6.innerHTML = "<input name='SATUAN[]' id=SATUAN0" + idrow + " type='text' class='form-control SATUAN'>";
		td7.innerHTML = "<input name='DEVISI[]' id=DEVISI0" + idrow + " type='text' class='form-control DEVISI'>";
		td8.innerHTML = "<input name='KET1[]' id=KET10" + idrow + " type='text' class='form-control KET1'>";
		td9.innerHTML = "<input name='TGL_DIMINTA[]' ocnlick='select()' id=TGL_DIMINTA0" + idrow + " type='text' class='date form-control TGL_DIMINTA' data-date-format='dd-mm-yyyy' value='<?php if (isset($_POST["tampilkan"])) { echo $_POST["TGLSG"]; } else echo date('d-m-Y'); ?>'>";
		td10.innerHTML = "<input name='SISA[]' onclick='select()' onkeyup='hitung()' value='0' id=SISA" + idrow + " type='text' class='form-control SISA rightJustified text-primary'>";
		td11.innerHTML = "<input name='URGENT[]' id=URGENT0" + idrow + " type='checkbox' class='checkbox_container' value='0' unchecked>";
		td12.innerHTML = "<input value='0' type='hidden' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#SISA" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
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
		select_kd_brg();
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
		select_kd_brg();
	});

	function select_kd_brg() {
		$('.js-example-responsive-kd_brg').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_PP_Stok/getDataAjax_Barang') ?>",
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
			templateResult: format_kd_brg,
			templateSelection: formatSelection_kd_brg
		});
	}

	function format_kd_brg(repo_kd_brg) {
		if (repo_kd_brg.loading) {
			return repo_kd_brg.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix'>" +
			"<div class='select2-result-repository__title'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kd_brg.kd_brg);
		return $container;
	}
	var na_brg = '';
	var satuan = '';

	function formatSelection_kd_brg(repo_kd_brg) {
		na_brg = repo_kd_brg.na_brg;
		satuan = repo_kd_brg.satuan;
		return repo_kd_brg.text;
	}

	function kd_brg(x) {
		var q = x.substring(6, 12);
		$('#NA_BRG' + q).val(na_brg);
		$('#SATUAN' + q).val(satuan);
		console.log(q);
	}

</script>