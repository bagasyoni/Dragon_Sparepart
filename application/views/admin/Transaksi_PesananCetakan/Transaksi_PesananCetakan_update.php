<?php
foreach ($rnd as $rowh) {
};
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
	#myTable td {
		text-align: left;
		padding: 5px;
	}

	#myTable tr {
		border-bottom: 1px solid #ddd;
	}

	#myTable tr.header,
	#myTable tr:hover {
		background-color: #f1f1f1;
	}

	input[type=text]:focus {
		width: 100%;
	}

	.text_input {
		color: black;
		text-transform: uppercase;
	}

	table {
		table-layout: fixed;
	}

	table th {
		color: black;
		text-align: center;
	}

	table td {
		overflow: hidden;
	}

	.label {
		color: black;
		font-weight: bold;
	}

	.rightJustified {
		text-align: right;
	}

	.total {
		font-weight: bold;
		color: blue;
	}

	.form-control {
		font-size: small;
	}

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

	.alert-container {
		background-color: #00b386;
		color: black;
		font-weight: bolder;
	}

	.table-scrollable {
		margin: 0;
		padding: 0;
	}

	.modal-bodys {
		max-height: 250px;
		overflow-y: auto;
	}

	.select2-dropdown {
		width: 500px !important;
	}

	/* .container { text-align: center; vertical-align: middle;} */
	.checkbox_container {
		width: 25px;
		height: 25px;
	}

	td input[type="checkbox"] {
		float: left;
		margin: 0 auto;
		width: 100%;
	}

	hr {
		border: 0;
		clear: both;
		display: block;
		width: 100%;
		background-color: black;
		height: 1px;
	}

	*/ .text_input {
		font-size: small;
		color: black;
	}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Update Pesanan Cetakan
	</div>
	<form id="cnc" name="cnc" action="<?php echo base_url('admin/Transaksi_PesananCetakan/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" enctype="multipart/form-data" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input type="hidden" id="NO_ID" name="NO_ID" class="form-control" value="<?php echo $rowh->NO_ID ?>">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value="<?php echo $rowh->NO_BUKTI ?>" readonly>
						</div>
						<div class="col-md-2">
						<input <?php if ($rowh->VAL == !0) echo 'class="form-control TGL text_input" readonly'; ?> type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>" onclick="select()">
						</div>
						<div class="col-md-1">
							<label class="label">DR </label>
						</div>
						<div class="col-md-1">
							<select <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="js-example-responsive-dragon form-control DEVISI text_input" name="DEVISI" id="DEVISI" onchange="dragon(this.id)" required>
								<option value="<?php echo $rowh->DEVISI ?>" selected id="DEVISI"><?php echo $rowh->DEVISI ?></option>
							</select>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-2">
							<?php
							if ($rowh->VAL == 0)
								echo '<a 
										type="button" 
										class="btn btn-danger" 
									>
										<span style="color: white; font-weight: bold;"> BELUM DIVALIDASI</span>
									</a>';
							else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> SUDAH DIVALIDASI</span>
								</a>';
							?>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Article </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input ARTICLE" id="ARTICLE" name="ARTICLE" type="text" value="<?php echo $rowh->ARTICLE ?>" required>
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Tujuan </label>
						</div>
						<div class="col-md-2">
							<select <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input TUJUAN" id="TUJUAN" name="TUJUAN" type="text">
								<option selected><?php echo $rowh->TUJUAN ?></option>
								<option value="CNC">CNC</option>
								<option value="PBL">PBL</option>
							</select>
						</div>
						<div class="col-md-2">
							<select <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input TIPE" id="TIPE" name="TIPE" type="text">
								<option selected><?php echo $rowh->TIPE ?></option>
								<option value="MRL">MRL</option>
								<option value="MRE">MRE</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Keterangan </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" value="<?php echo $rowh->NOTES ?>" required>
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Tipe Cetakan </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input TIPE_CETAK" id="TIPE_CETAK" name="TIPE_CETAK" type="text" value="<?php echo $rowh->TIPE_CETAK ?>">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">M Lasting </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input M_LASTING" id="M_LASTING" name="M_LASTING" type="text" value="<?php echo $rowh->M_LASTING ?>" required>
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Jenis </label>
						</div>
						<div class="col-md-2">
							<select <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input JENIS" id="JENIS" name="JENIS" type="text">
								<option selected><?php echo $rowh->JENIS ?></option>
								<option value="Sample">Sample</option>
								<option value="Seri">Seri</option>
								<option value="Reparasi">Reparasi</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanggal Diminta </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> type="text" class="date form-control TGL_DIMINTA text_input" id="TGL_DIMINTA" name="TGL_DIMINTA" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Flag </label>
						</div>
						<div class="col-md-2">
							<select <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input FLAG" id="FLAG" name="FLAG" type="text">
								<option selected><?php echo $rowh->FLAG ?></option>
								<option value="LOKAL">LOKAL</option>
								<option value="IMPORT">IMPORT</option>
							</select>
						</div>
						<div class="col-md-2">
							<select <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> class="form-control text_input PROSES" id="PROSES" name="PROSES" type="text">
								<option selected><?php echo $rowh->PROSES ?></option>
								<option value="CETAKAN">CETAKAN</option>
								<option value="MATRAS">MATRAS</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-4">
							<label class="label">Gambar Cetakan Maksimal 1 MB ekstensi yang diperbolehkan .jpg .png .jpeg .bmp </label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-2">
						<img src="<?= base_url('gambar/pesanancetakan/'.$rowh->GAMBAR1)?>" style="width: 120px;float: left;margin-bottom: 5px;">
						<input type="text" name="G1" id="G1" value="<?php echo $rowh->GAMBAR1?>" hidden>
						<input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> type="file" name="GAMBAR1" id="GAMBAR1" accept="image/png, image/jpeg, image/jpg, image/bmp">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-4">
							<label class="label">Gambar Cetakan Maksimal 1 MB ekstensi yang diperbolehkan .jpg .png .jpeg .bmp </label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-2">
						<img src="<?= base_url('gambar/pesanancetakan/'.$rowh->GAMBAR2)?>" style="width: 120px;float: left;margin-bottom: 5px;">
						<input type="text" name="G2" id="G2" value="<?php echo $rowh->GAMBAR2?>" hidden>
						<input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> type="file" name="GAMBAR2" id="GAMBAR2" accept="image/png, image/jpeg, image/jpg, image/bmp">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
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
								<th width="40px">No</th>
								<th width="150px">Komponen</th>
								<th width="100px">Jenis Bahan</th>
								<th width="150px">Size</th>
								<th width="75px">Qty</th>
								<th width="75px">Satuan</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 0;
							foreach ($rnd as $row) :
							?>
								<tr>
									<td><input name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> name="NA_BHN[]" id="NA_BHN<?php echo $no; ?>" value="<?= $row->NA_BHN ?>" type="text" class="form-control NA_BHN text_input"></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> name="KET1[]" id="KET1<?php echo $no; ?>" value="<?= $row->KET1 ?>" type="text" class="form-control KET1 text_input"></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> name="SIZE[]" id="SIZE<?php echo $no; ?>" value="<?= $row->SIZE ?>" type="text" class="form-control SIZE text_input"></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> name="QTY[]" onclick="select()" onkeyup="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary"></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control text_input" readonly'; ?> name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control SATUAN text_input"></td>
									<td>
										<input name="NO_IDX[]" id="NO_IDX<?php echo $no; ?>" value="<?= $row->NO_IDX ?>" class="form-control" type="hidden">
										<button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
											<i class="fa fa-fw fa-trash"></i>
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
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="<?php echo number_format($rowh->TOTAL_QTY, 2, '.', ','); ?>" readonly></td>
							<td></td>
							<td></td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<br><br>
		<div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div>
		<!--tab-->
		<br>
		<div class="col-md-12">
			<div class="col-xs-9">
				<div class="wells">
					<div class="btn-group cxx">
						<button type="submit" onclick="chekbox(),cektgl()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
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
			dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('.modal-footer').on('click', '#close', function() {
			$('input[type=search]').val('').keyup(); // this line and next one clear the search dialog
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
	var idrow = <?php echo $no ?>;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		$("#TOTAL_QTY").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#SISABON" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
		}
		$('body').on('click', '.btn-delete', function() {
			var r = confirm("Yakin dihapus?");
			if (r == true) {
				// txt = "Dihapus";
				if (idrow > 1) {
					var val = $(this).parents("tr").remove();
					idrow--;
					nomor();
				}
			} else {
				// txt = "Batal Hapus";
			}
		});
		$('input[type="checkbox"]').on('change', function() {
			this.value ^= 1;
			console.log(this.value)
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

	$('#TGL').change(function() {
		cektgl();
	});
	function cektgl() {
		var TGLCEK = $("#TGL").val();
		var hariCek = TGLCEK.substr(0,2);
		var bulanCek = TGLCEK.substr(3,2);
		var tahunCek = TGLCEK.substr(6,4);
		var bulanSesi = <?= substr($this->session->userdata['periode'],0,2)?>.toString().padStart(2,'0');
		var tahunSesi = <?= substr($this->session->userdata['periode'],-4)?>;
		if(bulanCek != bulanSesi){
			$("#TGL").val(hariCek+'-'+bulanSesi+'-'+tahunSesi);
			alert("Bulan tidak sesuai Periode!");
			$(this).submit(function() {
				return false;
			})
		}
		if(tahunCek != tahunSesi){
			$("#TGL").val(hariCek+'-'+bulanSesi+'-'+tahunSesi);
			alert("Tahun tidak sesuai Periode!");
			$(this).submit(function() {
				return false;
			})
		}
	}

	function hitung() {
		var TOTAL_QTY = 0;
		var TOTAL = 0;
		var total_row = idrow;
		for (i = 0; i < total_row; i++) {
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
		};
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});

		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;
		if (isNaN(TOTAL)) TOTAL = 0;

		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));
		$('#TOTAL').val(numberWithCommas(TOTAL));

		$('#TOTAL_QTY').autoNumeric('update');
		$('#TOTAL').autoNumeric('update');
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

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' required>";
		td3.innerHTML = "<input name='KET1[]' id=KET1" + idrow + " type='text' class='form-control KET1 text_input' required>";
		td4.innerHTML = "<input name='SIZE[]' id=SIZE" + idrow + " type='text' class='form-control SIZE text_input' required>";
		td5.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
		td6.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input'>";
		td7.innerHTML = "<input type='hidden' value='0' name='NO_IDX[]' id=NO_IDX" + idrow + "  class='form-control'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
		}
		idrow++;
		nomor();
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
		select_dragon();
	});

	function select_dragon() {
		$('.js-example-responsive-dragon').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_BoronganCNC/getDataAjax_dragon') ?>",
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
			placeholder: 'Pilih Dragon',
			minimumInputLength: 0,
			templateResult: format_dragon,
			templateSelection: formatSelection_dragon
		});
	}

	function format_dragon(repo_dragon) {
		if (repo_dragon.loading) {
			return repo_dragon.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_dragon.DR);
		return $container;
	}

	function formatSelection_dragon(repo_dragon) {
		return repo_dragon.text;
	}

	function dragon(x) {
		var q = x.substring(2, 10);
	}
</script>