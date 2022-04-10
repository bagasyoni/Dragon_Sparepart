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
		<i class="fas fa-university"></i> Input <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="transaksipemesanan" name="transaksipemesanan" action="<?php echo base_url('admin/Transaksi_Verifikasi_Bon/input_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2 input-group">
                            <input name="NO_BUKTI" id="NO_BUKTI" maxlength="50" type="text" class="form-control NO_BUKTI text_input" onkeypress="return tabE(this,event)" readonly>
							<span class="input-group-btn">
								<a class="btn default" onfocusout="hitung()" id="0" data-target="#mymodal_nobukti_beli_bon" data-toggle="modal" href="#lupnobukti_blbeli" ><i class="fa fa-search"></i></a>
							</span>
						</div>
                        <div class="col-md-1">
							<label class="label">Total Qty </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input TOTAL_QTY" id="TOTAL_QTY" name="TOTAL_QTY" type="text" value='' readonly>
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
								<th width="425px">Nama Barang</th>
								<th width="425px">Qty</th>
							</tr>
						</thead>
						<tbody id="show-data">
							<tr>
								<td><input name="REC[]" id="REC0" type="text" value="1" class="form-control REC" onkeypress="return tabE(this,event)" readonly></td>
								<td><input name="NA_BRG[]" id="NA_BRG0" type="text" class="form-control NA_BRG" readonly></td>
								<td><input name="QTY[]" onkeyup="hitung()" value="0" id="QTY0" type="text" class="form-control QTY rightJustified text-primary" readonly></td>
								<td>
									<!-- <button style="visibility: hidden;" type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
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
				<h4 class="modal-title" style="font-weight: bold; color: black;">Data Beli Bon</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_beli'>
					<thead>	
						<th>No Bukti</th>
						<th>Total Qty</th>
					</thead>
					<tbody>
					<?php
						$sp= $this->session->userdata['sub'];
						$dr= $this->session->userdata['dr'];
						$sql = "SELECT NO_BUKTI AS NO_BUKTI, 
								TOTAL_QTY AS TOTAL_QTY
							FROM bl_bon 
							WHERE VERIFIKASI_PO_SP='1'
							ORDER BY NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach($a as $b ) { 
					?>
						<tr>
							<td class='NBBVAL'><a href="#" class="select_nobukti_beli_bon"><?php echo $b->NO_BUKTI;?></a></td>
							<td class='TQBVAL text_input'><?php echo $b->TOTAL_QTY;?></td>
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
			target.parents("div").find(".NO_BUKTI").val(val);
			$('#mymodal_nobukti_beli_bon').modal('toggle');
			var no_bukti = $(this).parents("tr").find(".NBBVAL").text();
			$.ajax({
				type:'get',
				url : '<?php echo base_url('index.php/admin/Transaksi_Pemesanan/filter_beli_bon'); ?>',
				data:{ no_bukti : no_bukti},
				dataType: 'json',
				success:function(response) {
				// alert(response);
					var html = '';
                    var i;
                    for(i=0; i<response.length; i++){
                        html += '<tr>'+
									'<td><input name="REC[]" id=REC'+i+' type="text" class="form-control REC" onkeypress="return tabE(this,event)" readonly value='+(i+1)+' ></td>'+
									'<td><select class="js-example-responsive-kd_brg form-control KD_BRG" name="KD_BRG[]" id=KD_BRG'+i+' onchange="kd_brg(this.id)" onfocusout="hitung()"></select></td>'+
									'<td><input name="NA_BRG[]" value="'+response[i].NA_BRG+'" id=NA_BRG'+i+' type="text" class="form-control NA_BRG" readonly></td>'+
									'<td><input name="QTY[]" onclick="select()" value="'+numberWithCommas(response[i].QTY)+'" onkeyup="hitung()" id=QTY'+i+' type="text" class="form-control QTY rightJustified text-primary"></td>'+
									'<td><input type="hidden" name="NO_ID[]" id=NO_ID'+i+'  class="form-control"  value="'+response[i].NO_ID+'"  >'+
									'<button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" style="visibility: hidden;" onclick=""> <i class="fa fa-fw fa-trash-alt"></i> </button></td>'+
								'</tr>';
                    }
					idrow=i;
					$('#show-data').html(html);
					jumlahdata = 100 ;
					for(i=0; i<=jumlahdata; i++){
						$("#QTY" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
						$("#SISA" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					}
					$('input[type="checkbox"]').on('change', function(){
						this.value ^= 1;
						console.log( this.value )
					});
					select_kd_brg();
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
		td10.innerHTML = "<input name='SISA[]' onclick='select()' onkeyup='hitung()' value='0' id=SISA" + idrow + " type='text' class='form-control SISA rightJustified text-primary' readonly>";
		td11.innerHTML = "<input name='URGENT[]' id=URGENT0" + idrow + " type='checkbox' class='checkbox_container' value='1' checked>";
		td12.innerHTML = "<input type='hidden' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
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
				url: "<?= base_url('admin/Transaksi_Pemesanan/getDataAjax_Barang') ?>",
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