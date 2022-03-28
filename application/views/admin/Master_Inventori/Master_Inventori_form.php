<style>
	#myInput {
		background-image: url('<?php echo base_url()?>assets/img/search-icon-blue.png'); 
		background-position: 10px 12px; 
		background-repeat: no-repeat; 
		width: 100%; 
		padding: 10px 20px 10px 40px;
		border: 1px solid #ddd; 
		margin-bottom: 10px; 
	}
	.pd-1 {padding: 1px;}
	#myTable { border-collapse: collapse;  width: 100%; border: 1px solid #ddd; }
	#myTable th, #myTable td { text-align: left;}
	#myTable tr { border-bottom: 1px solid #ddd; }
	#myTable tr.header, #myTable tr:hover { background-color: #f1f1f1; }
	input[type=text]:focus { width: 100%; }
	table { table-layout: fixed; }
	table th, table td { overflow: hidden;}
	.rightJustified { text-align: right; }
	.total{ font-weight: bold; color: blue; }
	.bodycontainer { width: 1280px; max-height: 300PX; margin: 0; overflow-y: auto; }
	.table-scrollable { margin: 0; padding: 0; }
	.modal-bodys { max-height:250px; overflow-y: auto; }
	.label { font-weight: bold; color: black; }
	.label_header { font-weight: bold; color: black; text-align: center; }
	.text_input { color: black; text-transform: uppercase; }
	.number_input { color: black; text-align: right; }
	.number_total { font-weight: bold; color: black; text-align: right; }
	.btn_back {color: black;}
	.btn_back:hover {transition: 0.4s; color: white;}
	.btn_cancel {color: black;}
	.btn_cancel:hover {transition: 0.4s; color: white;}
	.btn_save {background-color: #1b8526; color: black;}
	.btn_save:hover {transition: 0.4s; color: white;}
	/* Style tab */
	.tab { overflow: hidden; border: 1px solid #ccc; background-color: #f1f1f1; }
	.tab button { background-color: inherit; float: left; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.4s;}
	.tab button:hover { background-color: #9ae6ae;  transition: 0.4s; }
	.tab button.active { background-color: #9c774c; color: white; }
	.tabcontent { display: none; padding: 6px 12px; }
    .alert-container { background-color: #9c774c; color: black; font-weight: bolder;}
</style>

<div class="container-fluid">
    <br>
    <div class="alert alert-success alert-container" role="alert">
        <i class="fas fa-university"></i> Entry Master Inventori
    </div>
	<form id="masterinventori" name="masterinventori" action="<?php echo base_url('admin/Master_Inventori/input_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
        <br><br>
        <div class="form-body">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-1">
						<label class="label">No Bukti</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="NO_BUKTI" name="NO_BUKTI" class="form-control text_input NO_BUKTI">
					</div>
					<div class="col-md-1">
						<label class="label">Kode</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="KODE" name="KODE" class="form-control text_input KODE" required>
					</div>
					<div class="col-md-1">
						<label class="label">Nama </label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="NAMA" name="NAMA" class="form-control text_input NAMA" required>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-1">
						<label class="label">Bagian</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="BAGIAN" name="BAGIAN" class="form-control text_input BAGIAN" required>
					</div>
					<div class="col-md-1">
						<label class="label">J Barang</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="J_BARANG" name="J_BARANG" class="form-control text_input J_BARANG" required>
					</div>
					<div class="col-md-1">
						<label class="label">Merk</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="MERK" name="MERK" class="form-control text_input MERK" required>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-1">
						<label class="label">Tgl Masuk</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="TGL_MA" name="TGL_MA" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="<?php if(isset($_POST["tampilkan"])) { echo $_POST["TGL_MA"]; } else echo date('d-m-Y'); ?>" >
					</div>
					<div class="col-md-1">
						<label class="label">Tgl Keluar</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="TGL_KE" name="TGL_KE" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="<?php if(isset($_POST["tampilkan"])) { echo $_POST["TGL_KE"]; } else echo date('d-m-Y'); ?>" >
					</div>
					<div class="col-md-1">
						<label class="label">Tgl Mutasi</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="TGL_MUTASI" name="TGL_MUTASI" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="<?php if(isset($_POST["tampilkan"])) { echo $_POST["TGL_MUTASI"]; } else echo date('d-m-Y'); ?>" >
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-1">
						<label class="label">Jumlah</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="JUMLAH" name="JUMLAH" class="form-control number_input JUMLAH num" value="0">
					</div>
					<div class="col-md-1">
						<label class="label">Satuan</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="SATUAN" name="SATUAN" class="form-control text_input SATUAN">
					</div>
					<div class="col-md-1">
						<label class="label">Keterangan</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="KETER" name="KETER" class="form-control text_input KETER">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-1">
						<label class="label">Tempat</label>
					</div>
					<div class="col-md-3 ">
						<input type="text" id="TEMPAT" name="TEMPAT" class="form-control text_input TEMPAT">
					</div>
					<div class="col-md-1">
						<label class="label">Rec</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="REC" name="REC" class="form-control number_input REC num" value="0">
					</div>
					<div class="col-md-1">
						<label class="label">Kode Barang</label>
					</div>
					<div class="col-md-3">
						<input type="text" id="KD_BRG" name="KD_BRG" class="form-control text_input KD_BRG" required>
					</div>
				</div>
			</div>
        </div>
		<br><br>
        <div class="row">
			<div class="col-xs-9">
				<div class="wells">		
					<div class="btn-group">
						<button type="submit" class="btn btn_save"><i class="fa fa-save"></i> Save</button>										
						<a type="button" href="<?php echo base_url('admin/Master_Inventori/index_Master_Inventori') ?>" class="btn btn-danger btn_cancel">Cancel</a>
					</div>
					<h4>
                        <span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> 
                        <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span>
                    </h4>								
				</div>						
			</div>													
        </div>  
    </form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable({
			dom: 
				"<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
			],
			order: true,
		});
	});
</script> 

<script>

    (function() {
		'use strict';
		window.addEventListener('load', function() {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
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

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

    function fnum() {
		$(".num").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMax: '999999999999.99',
			vMin: '-999999999999.99'
		});
		$('.num').autoNumeric('update');
	};

	$(document).ready(function() {
		fnum();
		$('body').on('keyup', 'input.num', function() {
			if (event.which != 190) {
				if (event.which >= 37 && event.which <= 40) return;
			}
			this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            hitung();
		});
    });

	$(".date").datepicker({
		'dateFormat':'dd-mm-yy',
	});

    function hitung() {}

</script>

<script>
	$(document).ready(function() {});
</script>