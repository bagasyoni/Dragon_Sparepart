<script src="https://unpkg.com/@develoka/angka-terbilang-js/index.min.js"></script>

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

	.checkbox_container {
		width: 25px;
		height: 25px;
	}

	td input[type="checkbox"] {
		float: left;
		margin: 0 auto;
		width: 100%;
	}

	.text_input {
		font-size: small;
		color: black;
	}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Input <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="pemesanan" name="pemesanan" action="<?php echo base_url('admin/Transaksi_Pemesanan/input_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" placeholder='<?php echo $this->session->userdata['bukti']; ?>' readonly>
						</div>
						<div class="col-md-1">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanggal </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {
																																						echo $_POST["TGL"];
																																					} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
						<div class="col-md-1">
							<label class="label">Keterangan </label>
						</div>
						<div class="col-md-3">
							<input class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" value=''>
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
								<th width="125px">Bon</th>
								<th width="50px"></th>
								<th width="75px">Kode</th>
								<th width="50px"></th>
								<th width="175px">Uraian</th>
								<th width="175px">Keterangan Barang</th>
								<th width="50px">Qty</th>
								<th width="125px">Bilangan</th>
								<th width="75px">Satuan</th>
								<th width="100px">Devisi</th>
								<th width="200px">Keterangan</th>
								<th width="100px">Tgl Diminta</th>
								<th width="50px">Sisa</th>
								<th width="90px">Urgent</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input name="REC[]" id="REC0" type="text" value="1" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
								<td>
									<input name="NO_BON[]" id="NO_BON0" maxlength="500" type="text" class="form-control NO_BON text_input" onkeypress="return tabE(this,event)" readonly>
								</td>
								<td>
									<span class="input-group-btn">
										<a class="btn default modal-NO_BON" onfocusout="hitung()" id="0"><i class="fa fa-search"></i></a>
									</span>
								</td>
								<!-- <td>
									<div class='input-group'>
										<select value="" class="js-example-responsive-no_bon form-control NO_BON" name="NO_BON[]" id="NO_BON0" onchange="no_bon(this.id)" onfocusout="hitung()" required></select>
									</div>
								</td> -->
								<td>
									<input name="KD_BHN[]" id="KD_BHN0" maxlength="500" type="text" class="form-control KD_BHN text_input" onkeypress="return tabE(this,event)" readonly>
								</td>
								<td>
									<span class="input-group-btn">
										<a class="btn default modal-KD_BHN" onfocusout="hitung()" id="0"><i class="fa fa-search"></i></a>
									</span>
								</td>
								<!-- <td><input name="KD_BHN[]" id="KD_BHN0" type="text" class="form-control KD_BHN text_input" required></td> -->
								<td><input name="NA_BHN[]" id="NA_BHN0" type="text" class="form-control NA_BHN text_input" required></td>
								<td><input name="TIPE[]" id="TIPE0" type="text" class="form-control TIPE text_input" required></td>
								<td><input name="QTY[]" onclick="select()" onkeyup="hitung()" value="0" id="QTY0" type="text" class="form-control QTY rightJustified text-primary" required></td>
								<td><input name="BILANGAN[]" id="BILANGAN0" type="text" class="form-control BILANGAN text_input" readonly></td>
								<td><input name="SATUAN[]" id="SATUAN0" type="text" class="form-control SATUAN text_input"></td>
								<td><input name="DEVISI[]" id="DEVISI0" type="text" class="form-control DEVISI text_input" required></td>
								<td><input name="KET[]" id="KET0" type="text" class="form-control KET text_input"></td>
								<td>
									<input name="TGL_DIMINTA[]" id="TGL_DIMINTA0" type="text" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {
																																										} else echo date('d-m-Y'); ?>" onclick="select()">
								</td>
								<td>
									<input name="SISABON[]" onkeyup="hitung()" value="0" id="SISABON0" type="text" class="form-control SISABON rightJustified text-primary">
								</td>
								<td>
									<input name="URGENT[]" id="URGENT" type="checkbox" value="0" class="checkbox_container URGENT" unchecked>
								</td>
								<td>
									<button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
										<i class="fa fa-fw fa-trash-alt"></i>
									</button>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="0" readonly></td>
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
						<button type="submit" onclick="chekbox()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
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
		$('#no_bon').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" +
				"<'row'<'col-md-6'f><'col-md-6'l>>" +
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>",
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('.modal-footer').on('click', '#close', function() {
			$('input[type=search]').val('').keyup();
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#kd_bhn').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" +
				"<'row'<'col-md-6'f><'col-md-6'l>>" +
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>",
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('.modal-footer').on('click', '#close', function() {
			$('input[type=search]').val('').keyup();
		});
	});
</script>

<div id="modal_no_bon" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">List Bon</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='no_bon'>
					<thead>
						<th>No Bukti</th>
						<th width="30px">Article</th>
						<th width="30px">Tipe</th>
						<th>Qty.</th>
						<th>Sisa</th>
						<th>Devisi</th>
					</thead>
					<tbody>
						<?php
						$dr = $this->session->userdata['dr'];
						$sub = $this->session->userdata['sub'];
						$sql = "SELECT bond.NO_BUKTI AS NO_BON,
							bond.KD_BHN, 
							bond.NA_BHN,
							bond.TYPE,
							bond.KET,
							bond.NOTES AS TIPE,
							(bond.QTY-bond.KIRIM) AS QTY,
							(bond.QTY-bond.KIRIM) AS SISABON,
							bond.SATUAN,
			
							bon.NM_BAG AS DEVISI,
							date_format(bon.TGL ,'%d-%m-%Y') AS TGL_DIMINTA,
							bon.SUB
						FROM bon, bond
						WHERE bon.NO_BUKTI=bond.NO_BUKTI AND bon.TTD2<>'' AND bon.TUJUAN='$sub' AND bon.DR='$dr' AND bond.OK=0 AND bond.QTY - bond.KIRIM <> 0
						ORDER BY bond.NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='NBBVAL'><a href="#" class="select_no_bon"><?php echo $b->NO_BON; ?></a></td>
								<td class='NABVAL text_input'><?php echo $b->NA_BHN; ?></td>
								<td class='TIBVAL text_input'><?php echo $b->TIPE; ?></td>
								<td class='QTBVAL text_input'><?php echo $b->QTY; ?></td>
								<td class='SIBVAL text_input'><?php echo $b->SISABON; ?></td>
								<td class='DEBVAL text_input'><?php echo $b->DEVISI; ?></td>
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

<div id="modal_kd_bhn" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">List Kode Bahan</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='kd_bhn'>
					<thead>
						<th>Kode Barang</th>
						<th width="30px">Nama Barang</th>
						<th width="30px">Satuan</th>
					</thead>
					<tbody>
						<?php
						$dr = $this->session->userdata['dr'];
						$sub = $this->session->userdata['sub'];
						$sql = "SELECT bhn.KD_BHN, 
							bhn.NA_BHN,
							bhn.SATUAN
						FROM bhn, bhnd
						WHERE bhn.KD_BHN=bhnd.KD_BHN AND bhn.SUB='$sub' AND bhn.FLAG='SP' AND bhn.FLAG2='SP' AND bhnd.DR='$dr'
						ORDER BY bhn.KD_BHN";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='KDBVAL'><a href="#" class="select_kd_bhn"><?php echo $b->KD_BHN; ?></a></td>
								<td class='NABVAL text_input'><?php echo $b->NA_BHN; ?></td>
								<td class='SATVAL text_input'><?php echo $b->SATUAN; ?></td>
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
	var x = 0;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		modalClick();

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

		// $('#modal_no_bon').on('show.bs.modal', function(e) {
		// 	target = $(e.relatedTarget);
		// });
		$('body').on('click', '.select_no_bon', function() {
			console.log(x);
			var val = $(this).parents("tr").find(".NBBVAL").text();
			$("#NO_BON" + x).val(val);
			var val = $(this).parents("tr").find(".NABVAL").text();
			$("#NA_BHN" + x).val(val);
			var val = $(this).parents("tr").find(".TIBVAL").text();
			$("#TIPE" + x).val(val);
			var val = $(this).parents("tr").find(".QTBVAL").text();
			$("#QTY" + x).val(val);
			var val = $(this).parents("tr").find(".SIBVAL").text();
			$("#SISABON" + x).val(val);
			var val = $(this).parents("tr").find(".DEBVAL").text();
			$("#DEVISI" + x).val(val);
			$('#modal_no_bon').modal('toggle');
			var no_bon = $(this).parents("tr").find(".NBBVAL").text();
		});

		$('#modal_kd_bhn').on('show.bs.modal', function(e) {
			target = $(e.relatedTarget);
		});
		$('body').on('click', '.select_kd_bhn', function() {
			console.log(x);
			var val = $(this).parents("tr").find(".KDBVAL").text();
			$("#KD_BHN" + x).val(val);
			var val = $(this).parents("tr").find(".SATVAL").text();
			$("#SATUAN" + x).val(val);
			$('#modal_kd_bhn').modal('toggle');
			var kd_bhn = $(this).parents("tr").find(".KDBVAL").text();
		});


		$('body').on('click', '.btn-delete', function() {
			var r = confirm("Yakin dihapus?");
			if (r == true) {
				if (idrow > 1) {
					var val = $(this).parents("tr").remove();
					idrow--;
					nomor();
				}
			} else {}
		});
		$('input[type="checkbox"]').on('change', function() {
			this.value ^= 1;
			console.log(this.value)
		});
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
	});

	function modalClick() {
		$('.modal-NO_BON').click(function() {
			x = $(this).attr('id');
			$('#modal_no_bon').modal('toggle');
		});
		$('.modal-KD_BHN').click(function() {
			x = $(this).attr('id');
			$('#modal_kd_bhn').modal('toggle');
		});
	}

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
		for (i = 0; i < total_row; i++) {
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
			var sisabon = parseFloat($('#SISABON' + i).val().replace(/,/g, ''));

			$('#BILANGAN' + i).val(angkaTerbilang(qty));

			// if (qty > sisabon) {
			// 	alert("Qty tidak boleh lebih besar dari Sisa Bon");
			// 	$('#QTY' + i).val(0);
			// 	// console.log('TIDAK OK !!!')
			// } else {
			// 	// console.log('OK !!!')
			// }
		};
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});

		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;

		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));

		$('#TOTAL_QTY').autoNumeric('update');
	}

	function chekbox() {
		$(".URGENT").each(function() {
			if ($(this).is(":checked") == true) {
				$(this).attr('value', '1');
			} else {
				$(this).prop('checked', true);
				$(this).attr('value', '0');
			}
		});
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
		var td13 = x.insertCell(12);
		var td14 = x.insertCell(13);
		var td15 = x.insertCell(14);
		var td16 = x.insertCell(15);

		// var no_bon0 = "<div class='input-group'><select class='js-example-responsive-no_bon form-control NO_BON text_input' name='NO_BON[]' id=NO_BON" + idrow + " onchange='no_bon(this.id)' onfocusout='hitung()' required></select></div>";
		// var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN text_input' name='KD_BHN[]' id=KD_BHN" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()' required></select></div>";

		var no_bon0 = "<input name='NO_BON[]' id=NO_BON" + idrow + " maxlength='500' type='text' class='form-control NO_BON text_input' onkeypress='return tabE(this,event)' readonly>";
		var btn_bon0 = "<span class='input-group-btn'><a class='btn default modal-NO_BON' onfocusout='hitung()' id=" + idrow + "><i class='fa fa-search'></i></a></span>";

		var kd_bhn0 = "<input name='KD_BHN[]' id=KD_BHN" + idrow + " maxlength='500' type='text' class='form-control KD_BHN text_input' onkeypress='return tabE(this,event)' readonly>";
		var btn_bhn0 = "<span class='input-group-btn'><a class='btn default modal-KD_BHN' onfocusout='hitung()' id=" + idrow + "><i class='fa fa-search'></i></a></span>";

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = no_bon0;
		td3.innerHTML = btn_bon0;
		td4.innerHTML = kd_bhn0;
		td5.innerHTML = btn_bhn0;
		td6.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' required>";
		td7.innerHTML = "<input name='TIPE[]' id=TIPE" + idrow + " type='text' class='form-control TIPE text_input' required>";
		td8.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
		td9.innerHTML = "<input name='BILANGAN[]' id=BILANGAN" + idrow + " type='text' class='form-control BILANGAN text_input' readonly>";
		td10.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input'>";
		td11.innerHTML = "<input name='DEVISI[]' id=DEVISI" + idrow + " type='text' class='form-control DEVISI text_input' required>";
		td12.innerHTML = "<input name='KET[]' id=KET" + idrow + " type='text' class='form-control KET text_input'>";
		td13.innerHTML = "<input name='TGL_DIMINTA[]' ocnlick='select()' id=TGL_DIMINTA" + idrow + " type='text' class='date form-control TGL_DIMINTA text_input' data-date-format='dd-mm-yyyy' value='<?php if (isset($_POST["tampilkan"])) {
																																																		} else echo date('d-m-Y'); ?>'>";
		td14.innerHTML = "<input name='SISABON[]' onclick='select()' onkeyup='hitung()' value='0' id=SISABON" + idrow + " type='text' class='form-control SISABON rightJustified text-primary'>";


		td15.innerHTML = "<input name='URGENT[]' id=URGENT" + idrow + " type='checkbox' class='checkbox_container URGENT' value='0' unchecked>";
		td16.innerHTML = "<input type='hidden' value='0' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
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
		idrow++;
		nomor();
		$(".ronly").on('keydown paste', function(e) {
			e.preventDefault();
			e.currentTarget.blur();
		});
		$('input[type="checkbox"]').on('change', function() {
			this.value ^= 1;
			console.log(this.value)
		});
		select_no_bon();
		select_kd_bhn();
		modalClick();
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
		select_no_bon();
		select_kd_bhn();
	});

	function select_no_bon() {
		$('.js-example-responsive-no_bon').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Pemesanan/getDataAjax_bond') ?>",
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
			placeholder: 'Pilih No Bon',
			minimumInputLength: 0,
			templateResult: format_no_bon,
			templateSelection: formatSelection_no_bon
		});
	}

	function format_no_bon(repo_no_bon) {
		if (repo_no_bon.loading) {
			return repo_no_bon.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_no_bon.NO_BON);
		return $container;
	}

	var tipe = '';
	var na_bhn = '';
	var ket = '';
	var satuan = '';
	var qty = '';
	var sisabon = '';
	var devisi = '';
	var tgl_diminta = '';

	function formatSelection_no_bon(repo_no_bon) {
		tipe = repo_no_bon.TIPE;
		na_bhn = repo_no_bon.NA_BHN;
		ket = repo_no_bon.KET;
		satuan = repo_no_bon.SATUAN;
		qty = repo_no_bon.QTY;
		sisabon = repo_no_bon.SISABON;
		devisi = repo_no_bon.DEVISI;
		tgl_diminta = repo_no_bon.TGL_DIMINTA;
		return repo_no_bon.text;
	}

	function no_bon(x) {
		var q = x.substring(6, 10);
		$('#TIPE' + q).val(tipe);
		$('#NA_BHN' + q).val(na_bhn);
		$('#KET' + q).val(ket);
		$('#QTY' + q).val(qty);
		$('#SISABON' + q).val(sisabon);
		$('#DEVISI' + q).val(devisi);
		$('#TGL_DIMINTA' + q).val(tgl_diminta);
	}

	function select_kd_bhn() {
		$('.js-example-responsive-kd_bhn').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Pemesanan/getDataAjax_bhn') ?>",
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
		$container.find(".select2-result-repository__title").text(repo_kd_bhn.KD_BHN);
		return $container;
	}

	// var na_bhn = '';
	var satuan = '';

	function formatSelection_kd_bhn(repo_kd_bhn) {
		// na_bhn = repo_kd_bhn.NA_BHN;
		satuan = repo_kd_bhn.SATUAN;
		return repo_kd_bhn.text;
	}

	function kd_bhn(xx) {
		var qq = xx.substring(6, 10);
		// $('#NA_BHN' + qq).val(na_bhn);
		$('#SATUAN' + qq).val(satuan);
	}
</script>