<style>
	#myInput {
		background-image: url('<?php echo base_url() ?>assets/img/search-icon-blue.png');
		background-position: 10px 12px;
		background-repeat: no-repeat;
		width: 100%;
		font-size: 14px;
		padding: 12px 20px 12px 40px;
		border: 1px solid #ddd;
		margin-bottom: 12px;
	}

	#myTable {
		border-collapse: collapse;
		width: 100%;
		border: 1px solid #ddd;
		font-size: 14px;
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
		font-size: 14px;
		font-weight: bold;
		color: blue;
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

	.text_input {
		font-size: inherit;
		color: black;
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

	.DR {
		box-shadow: 0px 0px 5px 0px #347AC9;
		-webkit-box-shadow: 0px 0px 5px 0px #347AC9;
		-moz-box-shadow: 0px 0px 5px 0px #347AC9;
	}

	.ROW-BUDGET {
		background-color: #01BAEF;
		border-radius: 5px;
		border: 2px inset #1C6EA4;
	}

	.modal_cekbudget {
		font-size: inherit;
	}

	table td {
		padding: 5px !important;
	}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-container" role="alert">
		<i class="fas fa-university"></i> Input <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="pononbahan" name="pononbahan" action="<?php echo base_url('admin/Transaksi_PO_NonBahan/input_aksi'); ?>" class="form-horizontal" method="post">
		<div class="form-body">
			<div class="row">
				<div class="col-md-1">
					<label class="label">No Bukti </label>
				</div>
				<div class="input-group col-md-3">
					<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value='' readonly>
				</div>
				<div class="col-md-1">
					<label class="label">Dragon </label>
				</div>
				<div class="col-md-1">
					<select class="form-control BD text_input font-weight-bold" name="BD" id="BD" style="width: 100%;">
						<option value="I" selected>NB1</option>
						<option value="II">NB2</option>
						<option value="III">NB3</option>
					</select>
				</div>
				<div class="col-md-1">
					<label class="label">Supplier </label>
				</div>
				<div class="col-md-2">
					<select class="js-example-responsive-kodes form-control text_input KODES" name="KODES" id="KODES" onchange="kodes(this.id)"></select>
				</div>
				<div class="input-group col-md-3">
					<input class="form-control text_input NAMAS" id="NAMAS" name="NAMAS" type="text" value='' readonly>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1">
					<label class="label">Tanggal </label>
				</div>
				<div class="col-md-1">
					<input type="text" class="date form-control text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?= date('d-m-Y'); ?>" <?= ($this->session->userdata['ha_e_ttd'] != '1') ? "readonly" : "" ?>>
				</div>
				<div class="col-md-1">
					<label class="label">J Tempo </label>
				</div>
				<div class="col-md-1">
					<input type="text" class="date form-control text_input" id="JTEMPO" name="JTEMPO" data-date-format="dd-mm-yyyy" value="<?= $date = date('d-m-Y', strtotime('+1 month', strtotime(date('d-m-Y')))); ?>" onchange="cek_jtempo()">
				</div>
				<div class="col-md-1">
				</div>
				<div class="col-md-1 ">
					<select class="form-control DR text_input" name="DR" id="DR" style="width: 100%;">
						<option value="" selected></option>
						<option value="I">DR1</option>
						<option value="II">DR2</option>
						<option value="III">DR3</option>
						<option value="UM">UM</option>
						<option value="MAR">MAR</option>
						<option value="MBA">MBA</option>
						<option value="CNC">CNC</option>
						<option value="PDB">MBA</option>
						<option value="CNC">CNC</option>
						<option value="PBD">PBD</option>
						<option value=""></option>
					</select>
				</div>
				
				<div class="col-md-1"></div>
				<div class="input-group col-md-2">
					<input class="form-control text_input KOTA" id="KOTA" name="KOTA" type="text" value='' readonly>
				</div>
				<div class="input-group col-md-3">
					<input class="form-control text_input ALAMAT" id="ALAMAT" name="ALAMAT" type="text" value='' readonly>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1">
					<label class="label">Mata Uang </label>
				</div>
				<div class="col-md-1 ">
					<select class="form-control KURS text_input" name="KURS" id="KURS" style="width: 100%;">
						<option value="IDR" selected>IDR</option>
						<option value="USD">USD</option>
						<option value="SGD">SGD</option>
						<option value="CNY">CNY</option>
					</select>
				</div>
				<div class="col-md-1">
					<label class="label">Kurs </label>
				</div>
				<div class="col-md-1">
					<input class="form-control text_input RATE" id="RATE" name="RATE" type="text" value='1' placeholder="Kurs">
				</div>
				<div class="col-md-2"></div>
				<!-- <div class="col-md-1">
					<label class="label">Produk</label>
				</div>
				<div class="col-md-1">
					<select class="form-control PROD text_input" name="PROD" id="PROD" style="width: 100%;">
						<option value="Sepatu" selected>Sepatu</option>
						<option value="Sandal">Sandal</option>
					</select>
				</div> -->
				<div class="col-md-1"></div>
				<div class="input-group col-md-2">
					<input class="form-control text_input AN" id="AN" name="AN" type="text" value='' placeholder="Atas Nama">
				</div>
				<div class="col-md-1">
					<input name="PKP" id="PKP" type="checkbox" value="0" class="checkbox_container PKP" unchecked onclick="return false">
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<label class=" label">Notes Bayar </label>
					<input class="form-control text_input NOTESBL" id="NOTESBL" name="NOTESBL" type="text" value=''>
				</div>
				<div class="col-md-3">
					<label class=" label">Notes Kirim </label>
					<input class="form-control text_input NOTESKRM" id="NOTESKRM" name="NOTESKRM" type="text" value=''>
				</div>
				<div class="col-md-3">
					<label class=" label">Notes Cetak </label>
					<input class="form-control text_input NOTESCTK" id="NOTESCTK" name="NOTESCTK" type="text" value=''>
				</div>
				<div class="col-md-3">
					<label class=" label">Notes </label>
					<input class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" value=''>
				</div>
			</div>
			<div class="row" style="justify-content: center;">
				<div class="col-md-2">
					<label class="label">No pp </label>
					<span class="input-group-btn">
						<a class="btn default" onfocusout="hitung()" id="0" data-target="#mymodal_no_pp" data-toggle="modal" href=""><i class="fa fa-search"></i></a>
					</span>
				</div>
				<div class="col-md-2 my-auto">
					<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="ManualPP">
						<label class="custom-control-label" for="ManualPP" onmouseover="" style="color: #01BAEF; cursor: pointer;">MANUAL PP</label>
					</div>
				</div>
				<div class="col-md-2">
					<a type="button" class="btn btn-primary btn-center" data-target="#mymodal_cekbudget" data-toggle="modal" href="#lupCekBudget">
						<span style="color: black; font-weight: bold;" class="btn_info">
							<i class="fa fa-balance-scale"></i> CEK BUDGET
						</span>
					</a>
				</div>
				<div class="col-md-2">
					<a type="button" class="btn btn-primary btn-center" data-target="#mymodal_polubang" data-toggle="modal" href="#lupPoLubang">
						<span style="color: black; font-weight: bold;">
							<i class="fa fa-book"></i> CEK PO LUBANG
						</span>
					</a>
				</div>
				<div class="col-md-2">
					<a type="button" class="btn btn-primary btn-center" data-target="#mymodal_statuspo" data-toggle="modal" href="#lupStatusPo">
						<span style="color: black; font-weight: bold;">
							<i class="fa fa-info"></i> CEK STATUS PO
						</span>
					</a>
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
								<th width="150px">No PP</th>
								<th width="120px">Kd Bhn</th>
								<th width="100px">Uraian</th>
								<th width="100px">Stn PPC</th>
								<th width="120px">Qty PPC</th>
								<th width="120px">Stn</th>
								<th width="120px">Qty</th>
								<th width="120px">Harga</th>
								<th width="120px">Total</th>
								<th width="100px">DR</th>
								<th width="100px">Devisi</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody id="show-data">
							<tr>
								<td><input name="REC[]" id="REC0" type="text" value="1" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
								<td><div class='input-group no_ppdiv'><select value="" class="js-example-responsive-no_pp form-control NO_PP text_input" name="NO_PP[]" id="NO_PP0" onchange="no_pp(this.id), hitung(), isi_NOPOPPC()" onfocusout="hitung()"></select></div></td>
								<!-- <td>
									<div class="input-group">
                                        <input name="NO_PP[]"  id="NO_PP0"  maxlength="30" type="text" class="form-control text_input NO_PP" readonly>
										<span class="input-group-btn">
                                            <a class="btn default" id="0" data-target="#mymodal_no_pp" data-toggle="modal" href="#lupPP"  onfocusout="hitung()"><i class="fa fa-search"></i></a>
                                        </span>
                                    </div>
								</td> -->
								<td><div class='input-group kd_bhndiv' hidden><select value="" class="js-example-responsive-kode_bhn form-control KD_BHNX text_input" name="KD_BHNX[]" id="KD_BHNX0" onchange="kode_bhn(this.id), hitung()" onfocusout="hitung()" disabled></select></div> <input name="KD_BHN[]" id="KD_BHN0" type="text" class="form-control text_input KD_BHN" readonly></td>
								<td><input name="NA_BHN[]" id="NA_BHN0" type="text" class="form-control text_input NA_BHN" readonly></td>
								<td><input name="SATUANPP[]" id="SATUANPP0" type="text" class="form-control text_input SATUANPP" readonly></td>
								<td><input name="QTYPP[]" onchange="hitung()" value="0" id="QTYPP0" type="text" class="form-control QTYPP rightJustified text-primary" readonly></td>
								<td><input name="SATUAN[]" id="SATUAN0" type="text" class="form-control text_input SATUAN"></td>
								<td><input name="QTY[]" onclick="select()" onkeyup="hitung()" value="0" id="QTY0" type="text" class="form-control QTY rightJustified text-primary"></td>
								<td><input name="HARGA[]" onclick="select()" onchange="hitung()" value="0" id="HARGA0" type="text" class="form-control HARGA rightJustified text-primary"></td>
								<td><input name="TOTAL[]" onchange="hitung()" value="0" id="TOTAL0" type="text" class="form-control TOTAL rightJustified text-primary" readonly></td>
								<td><input name="DRD[]" id="DRD0" type="text" class="form-control text_input DRD" readonly></td>
								<td><input name="DEVISI[]" id="DEVISI0" type="text" class="form-control text_input DEVISI" readonly>
									<input name="SUB" onkeyup="hitung()" value="0" id="SUB0" type="hidden" class="form-control SUB rightJustified text-primary text_input" readonly>
								</td>
								<td>
									<button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
										<i class="fa fa-fw fa-trash"></i>
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
							<td><input class="form-control TOTAL_QTYPP rightJustified text-primary font-weight-bold" id="TOTAL_QTYPP" name="TOTAL_QTYPP" value="0" readonly></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="0" readonly></td>
							<td></td>
							<td><input class="form-control TTOTAL rightJustified text-primary font-weight-bold" id="TTOTAL" name="TTOTAL" value="0" readonly></td>
							<td></td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-3">
				<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
			</div>
			<div class="col-md-6">
				<textarea id="NOPOPPC" name="NOPOPPC" rows="2" class="form-control text_input" readonly></textarea>
			</div>
			<div class="col-md-1">
				<label class="label">PPN </label>
			</div>
			<div class="col-md-2 ">
				<input class="form-control PPN rightJustified text-danger font-weight-bold text_input" onfocusout="hitung()" onkeyup="hitung()" value="0" id="PPN" name="PPN" readonly>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="btn-group">
					<button type="submit" onclick="chekbox(),cektgl()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
					<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Close</a>
				</div>
			</div>
			<div class="col-md-1">
				<label class="label">Sisa Budget </label>
			</div>
			<div class="col-md-2">
				<input class="form-control text_input BUDGET text-primary rightJustified" id="BUDGET" name="BUDGET" type="text" value='0' readonly>
			</div>
			<div class="col-md-1">
				<label class="label">Nett </label>
			</div>
			<div class="col-md-2 ">
				<input class="form-control NETT rightJustified text-success font-weight-bold text_input" onkeyup="hitung()" value="0" id="NETT" name="NETT" readonly>
				<!-- <input class="form-control BUDGET rightJustified text-success font-weight-bold text_input" onkeyup="hitung()" value="0" id="BUDGET" name="BUDGET" hidden readonly> -->
			</div>
		</div>
	</form>
</div>

<!-- myModal No PP-->
<div id="mymodal_no_pp" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Data No PP</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_no_pp'>
					<thead>
						<th width="40px">No PP</th>
						<th>Kd Bhn</th>
						<th>Uraian</th>
						<th>Stn PPC</th>
						<th>Qty PPC</th>
						<th>DR</th>
						<th>SISA</th>
						<th></th>
					</thead>
					<tbody id="show-no_pp"> 
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
				<button type="button" class="btn btn-default btn-primary" data-dismiss="modal" id="save">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- myModal Cek Budget-->
<div id="mymodal_cekbudget" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Cek Budget</h4>
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="close">Close</button>
			</div>
			<div class="modal-body">
				<div class="row p-1 ROW-BUDGET">
					<div class="col-md-1">
						<label class="label">DR</label>
					</div>
					<div class="col-md-2">
						<select class="form-control DR_FILTER text_input BTN-BUDGET" name="DR_FILTER" id="DR_FILTER" style="width: 100%;">
							<option value="I" selected>I</option>
							<option value="II">II</option>
							<option value="III">III</option>
							<option value="LOG">LOGISTIK</option>
							<option value="IM">IMPORT</option>
							<option value="JHL">BANGUNAN</option>
							<option value="MBA">MEBA</option>
						</select>
					</div>
				</div>
				<table class='table table-bordered table-striped' id='modal_cekbudget'>
					<thead>
						<th width="40px">No PO</th>
						<th width="20px">Tanggal</th>
						<th width="100px">Nama Barang</th>
						<th width="20px">Qty</th>
						<th width="20px">Harga</th>
						<th width="20px">Total</th>
						<th width="70px">NO_PP</th>
						<th>Dr</th>
					</thead>
					<tbody>
						<?php
						$per = $this->session->userdata['periode'];
						$sql = "SELECT NO_BUKTI, 
								TGL,
								NA_BHN, 
								QTY,
								SATUAN,
								HARGA,
								TOTAL,
								NO_PP,
								DR
							FROM pod
							WHERE FLAG2='NB' AND per='$per'
							ORDER BY NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='text_input'><?php echo $b->NO_BUKTI; ?></td>
								<td class='text_input'><?php echo $b->TGL; ?></td>
								<td class='text_input'><?php echo $b->NA_BHN; ?></td>
								<td class='text_input rightJustified text-primary font-weight-bold'><?php echo number_format($b->QTY, 2, '.', ','); ?></td>
								<td class='text_input rightJustified text-primary font-weight-bold'><?php echo number_format($b->HARGA, 2, '.', ','); ?></td>
								<td class='text_input rightJustified text-primary font-weight-bold'><?php echo number_format($b->TOTAL, 2, '.', ','); ?></td>
								<td class='text_input'><?php echo $b->NO_PP; ?></td>
								<td class='text_input'><?php echo $b->DR; ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<!-- <tfoot>
						<tr>
							<th colspan="3">Total:</th>
							<th style="text-align:right" id="">Total:</th>
							<th></th>
							<th></th>
						</tr>
					</tfoot> -->
				</table>
				<div class="row p-1 ROW-BUDGET text-right text_input">
					<div class="col-10">
						<b>Total Pembelian : </b>
					</div>
					<div class="col-2">
						<input value="0" id="TPEMBELIAN" type="text" class="form-control rightJustified text-primary text_input font-weight-bold" readonly>
					</div>
					<div class="col-10">
						<b>Total Budget : </b>
					</div>
					<div class="col-2">
						<input value="0" id="TBUDGET" type="text" class="form-control rightJustified text-primary text_input font-weight-bold" readonly>
					</div>
					<div class="col-10">
						<b>Sisa Budget : </b>
					</div>
					<div class="col-2">
						<input value="0" id="TSISA" type="text" class="form-control rightJustified text-primary text_input font-weight-bold" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- myModal Cek Penawaran-->
<div id="mymodal_penawaran" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">History Penawaran</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_penawaran'>
					<thead>
						<th>Supplier</th>
						<th>Nota Bayar</th>
						<th>PPN</th>
						<th>Hrg Awal</th>
						<th>Hrg Akhir</th>
						<th>Na Brg</th>
						<th>Satuan</th>
						<th>Qty</th>
						<th>Catatan</th>
					</thead>
					<tbody>
						<?php
						$sql = "SELECT po.KODES, 
								po.NOTESBL,
								CASE 
									WHEN po.PKP = 1 THEN 'PPN AKTIF' 
									WHEN po.PKP = 0 THEN 'PPN TIDAK AKTIF'
									END 
								AS PKP,
								pod.HARGA_AW, 
								pod.HARGA_AK,
								pod.NA_BHN,
								pod.SATUAN,
								pod.QTY,
								po.NOTESTWR
							FROM po,pod
							WHERE po.NO_BUKTI = pod.NO_BUKTI 
							AND po.FLAG='PO' AND po.FLAG2='NB'
							ORDER BY po.KODES";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='text_input'><?php echo $b->KODES; ?></td>
								<td class='text_input'><?php echo $b->NOTESBL; ?></td>
								<td class='text_input'><?php echo $b->PKP; ?></td>
								<td class='text_input rightJustified text-primary font-weight-bold'><?php echo number_format($b->HARGA_AW, 2, '.', ','); ?></td>
								<td class='text_input rightJustified text-primary font-weight-bold'><?php echo number_format($b->HARGA_AK, 2, '.', ','); ?></td>
								<td class='text_input'><?php echo $b->NA_BHN; ?></td>
								<td class='text_input'><?php echo $b->SATUAN; ?></td>
								<td class='text_input rightJustified text-primary font-weight-bold'><?php echo number_format($b->QTY, 2, '.', ','); ?></td>
								<td class='text_input'><?php echo $b->NOTESTWR; ?></td>
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

<!-- myModal PO Lubang-->
<div id="mymodal_polubang" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Cek PO Lubang</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_polubang'>
					<thead>
						<th>No LPB</th>
						<th>Cek Urut</th>
						<th>Tanda</th>
					</thead>
					<tbody>
						<?php
						$sql = "SELECT po.NO_BUKTI, 
								'-' AS URUT,
								'-' AS TANDA
							FROM po
							WHERE po.FLAG='PO' AND po.FLAG2='NB'
							ORDER BY po.NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='text_input'><?php echo $b->NO_BUKTI; ?></td>
								<td class='text_input'><?php echo $b->URUT; ?></td>
								<td class='text_input'><?php echo $b->TANDA; ?></td>
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

<!-- myModal Status PO-->
<div id="mymodal_statuspo" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Cek Status PO</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='modal_statuspo'>
					<thead>
						<th>No PO</th>
						<th>Tgl</th>
						<th>Supplier</th>
						<th>Devisi</th>
						<th>Tgl Cetak</th>
					</thead>
					<tbody>
						<?php
						$sql = "SELECT po.NO_BUKTI, 
								po.TGL AS TGL,
								po.KODES AS KODES,
								pod.DEVISI AS DEVISI,
								po.TGL_CETAKPO AS TGL_CETAKPO
							FROM po, pod
							WHERE po.NO_BUKTI=pod.NO_BUKTI AND po.FLAG='PO' AND po.FLAG2='NB'
							ORDER BY po.NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='text_input'><?php echo $b->NO_BUKTI; ?></td>
								<td class='text_input'><?php echo $b->TGL; ?></td>
								<td class='text_input'><?php echo $b->KODES; ?></td>
								<td class='text_input'><?php echo $b->DEVISI; ?></td>
								<td class='text_input'><?php echo $b->TGL_CETAKPO; ?></td>
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


<script type="text/javascript">
	$(document).ready(function() {
		CekBudget();
		var dt = $('#modal_cekbudget').DataTable({
			footerCallback: function(row, data, start, end, display) {
				var api = this.api();

				// Remove the formatting to get integer data for summation
				var intVal = function(i) {
					return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
				};

				// Total over all pages
				total = api
					.column(5, {
						filter: 'applied'
					})
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				total = total.toFixed(2);

				// Update footer
				// $(api.column(2).footer()).html('TOTAL TRANSAKSI : ');
				// $(api.column(3).footer()).html(numberWithCommas(total));
				// $(api.column(3).footer()).addClass('TPEMBELIAN');
				$("#TPEMBELIAN").val(numberWithCommas(total));
			}
		});

		$.fn.dataTable.ext.search.push(
			function(settings, data, dataIndex) {
				var dr_filter = $('#DR_FILTER').val();
				var dr = data[7]; // use data for the jenis column

				if (dr_filter == dr &&
					settings.nTable.id == 'modal_cekbudget') {
					return true;
				}
				return false;
			}
		);

		ModalBudget();
		dt.draw();

		$('#DR_FILTER').change(function() {
			ModalBudget();
			dt.draw();
		});

		$('#modal_penawaran').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('#modal_polubang').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('#modal_statuspo').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('#modal_no_pp').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: [
				[0, "asc"]
			]
		});

		$('.modal-footer').on('click', '#close', function() {
			$('input[type=search]').val('').keyup(); // this line and next one clear the search dialog
		});

		$('input[type=search]').on('keyup', function() {
			data_pp();
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
	var manualPP = 0;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	function initNumber() {
		$("#TOTAL_QTYPP").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$("#TOTAL_QTY").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$("#TTOTAL").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$("#PPN").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$("#NETT").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$(".QTYPP").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$(".QTY").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$(".HARGA").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$(".TOTAL").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});

		$("#TPEMBELIAN").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$('#TPEMBELIAN').autoNumeric('update');
		$("#TBUDGET").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$('#TBUDGET').autoNumeric('update');
		$("#TSISA").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$('#TSISA').autoNumeric('update');
	}

	$(document).ready(function() {

		isi_NOPOPPC();
		data_pp()
		initNumber();
		CekBudget();
		hitung();	

		//MyModal No PP
		$('#mymodal_no_pp').on('show.bs.modal', function(e) {
			target = $(e.relatedTarget);
		});
		$('body').on('click', '.select_no_pp', function() {
			var val = $(this).parents("tr").find(".NPPVAL").text();
			target.parents("tr").find(".NO_PP").val(val);
			var val = $(this).parents("tr").find(".KBPVAL").text();
			target.parents("tr").find(".KD_BHNX").val(val);
			var val = $(this).parents("tr").find(".NBPVAL").text();
			target.parents("tr").find(".NA_BHN").val(val);
			var val = $(this).parents("tr").find(".SPPVAL").text();
			target.parents("tr").find(".SATUANPP").val(val);
			var val = $(this).parents("tr").find(".QPPVAL").text();
			target.parents("tr").find(".QTYPP").val(val);
			var val = $(this).parents("tr").find(".SAPVAL").text();
			target.parents("tr").find(".SATUAN").val(val);
			var val = $(this).parents("tr").find(".QTPVAL").text();
			target.parents("tr").find(".QTY").val(val);
			var val = $(this).parents("tr").find(".DRPVAL").text();
			target.parents("tr").find(".DRD").val(val);
			// var val = $(this).parents("tr").find(".DVPVAL").text();
			// target.parents("tr").find(".DEVISI").val(val);
			$('#mymodal_no_pp').modal('toggle');
		});

		$('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			idrow--;
			nomor();
		});
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		});

		init_checkbox();

		$('body').on('click', '#save', function() {
			$('#mymodal_no_pp').modal('toggle');
			console.log(NO_PP);
			$.ajax({
				type: 'GET',
				url: '<?php echo base_url('index.php/admin/Transaksi_PO_NonBahan/getDataAjax_no_pp3'); ?>',
				contentType: 'application/json; charset=utf-8',
				data: {
					"NO_PP": NO_PP
				},
				dataType: 'text json',
				cache: false,
				success: function(response) {
					// alert(response);
					console.log(response);
					console.log('x1');
					var html = '';
					var i;
					for (i = 0; i < response.length; i++) {
						console.log('x2');
						console.log(response.length);
						html += "<tr>" +
								"<td><input name='REC[]' id=REC" + i + " type='text' value='" + (i + 1) + "' class='form-control REC text_input' onkeypress='return tabE(this,event)' readonly></td>" +
								// "<td><div class='input-group no_ppdiv'><select value='" + response[i].NO_PP + "' class='js-example-responsive-no_pp form-control NO_PP text_input' name='NO_PP[]' id=NO_PP" + i + " onchange='no_pp(this.id), hitung(), isi_NOPOPPC()' onfocusout='hitung()'></select></div></td>" +
								"<td><input name='NO_PP[]' id=NO_PP" + i + " type='text' class='form-control text_input NO_PP' value='" + response[i].NO_PP + "' readonly></td>" +
								"<td><div class='input-group kd_bhndiv' hidden><select value='" + response[i].KD_BHN + "' class='js-example-responsive-kode_bhn form-control KD_BHNX text_input' name='KD_BHNX[]' id=KD_BHNX" + i + " onchange='kode_bhn(this.id), hitung()' onfocusout='hitung()' disabled></select></div> <input name='KD_BHN[]' value='" + response[i].KD_BHN + "' id=KD_BHN" + i + " type='text' class='form-control text_input KD_BHN' readonly></td>" +
								"<td><input name='NA_BHN[]' id=NA_BHN" + i + " type='text' class='form-control text_input NA_BHN' value='" + response[i].NA_BHN + "' readonly></td>" +
								"<td><input name='SATUANPP[]' id=SATUANPP" + i + " type='text' class='form-control text_input SATUANPP' value='" + response[i].SATUANPP + "' readonly></td>" +
								"<td><input name='QTYPP[]' onchange='hitung()' value='" + response[i].QTYPP + "' id=QTYPP" + i + " type='text' class='form-control QTYPP rightJustified text-primary' readonly></td>" +
								"<td><input name='SATUAN[]' id=SATUAN" + i + " type='text' class='form-control text_input SATUAN' value='" + response[i].SATUAN + "'></td>" +
								"<td><input name='QTY[]' onclick='select()' onkeyup='hitung()' value='" + response[i].QTY + "' id=QTY" + i + " type='text' class='form-control QTY rightJustified text-primary'></td>" +
								"<td><input name='HARGA[]' onclick='select()' onchange='hitung()' value='0' id=HARGA" + i + " type='text' class='form-control HARGA rightJustified text-primary'></td>" +
								"<td><input name='TOTAL[]' onchange='hitung()' value='0' id=TOTAL" + i + " type='text' class='form-control TOTAL rightJustified text-primary' readonly></td>" +
								"<td><input name='DRD[]' id=DRD" + i + " value='" + response[i].DRD + "' type='text' class='form-control text_input DRD' readonly></td>" +
								"<td><input name='DEVISI[]' id=DEVISI" + i + " value='' type='text' class='form-control text_input DEVISI' readonly>" +
									"<input name='SUB' onkeyup='hitung()' value='' id=SUB" + i + " type='hidden' class='form-control SUB rightJustified text-primary text_input' readonly>" +
									"<input name='KD_BAG' onkeyup='hitung()' value='" + response[i].KD_BAG + "' id=KD_BAG" + i + " type='hidden' class='form-control KD_BAG rightJustified text-primary text_input' readonly>" +
								"</td>" +
								"<td><button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i></button></td>" +
							"</tr>";
						idrow++;
					}
					$('#show-data').html(html);
					// fnum();
					initNumber();
					hitung();
					isi_NOPOPPC();
					// initNumber();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					// alert("Status: " + textStatus);
					// alert("Error: " + errorThrown);
					idrow = 0;
					console.log('error bro');
					console.log(textStatus);
					console.log(errorThrown);
					$('#show-data').html(`<tr>
								<td><input name='REC[]' id=REC0 type='text' value='1' class='form-control REC text_input' onkeypress='return tabE(this,event)' readonly></td>
								<td><div class='input-group no_ppdiv'><select value="" class='js-example-responsive-no_pp form-control NO_PP text_input' name='NO_PP[]' id=NO_PP0 onchange='no_pp(this.id), hitung(), isi_NOPOPPC()' onfocusout='hitung()'></select></div></td>
								<td><div class='input-group kd_bhndiv' hidden><select value='' class='js-example-responsive-kode_bhn form-control KD_BHNX text_input' name='KD_BHNX[]' id=KD_BHNX0 onchange='kode_bhn(this.id), hitung()' onfocusout='hitung()' disabled></select></div> <input name='KD_BHN[]' value='' id=KD_BHN0 type='text' class='form-control text_input KD_BHN' readonly></td>
								<td><input name='NA_BHN[]' id=NA_BHN0 type='text' class='form-control text_input NA_BHN' value='' readonly></td>
								<td><input name='SATUANPP[]'  id=SATUANPP0 type='text' class='form-control text_input SATUANPP' value='' readonly></td>
								<td><input name='QTYPP[]' onclick='select()' onchange='hitung()' value='' id=QTYPP0 type='text' class='form-control QTYPP rightJustified text-primary' readonly></td>
								<td><input name='SATUAN[]' id=SATUAN0 type='text' class='form-control text_input SATUAN' value=''></td>
								<td><input name='QTY[]' onclick='select()' onkeyup='hitung()' value='' id=QTY0 type='text' class='form-control QTY rightJustified text-primary'></td>
								<td><input name='HARGA[]' onclick='select()' onchange='hitung()' value='0' id=HARGA0 type='text' class='form-control HARGA rightJustified text-primary'></td>
								<td><input name='TOTAL[]' onchange='hitung()' value='0' id=TOTAL0 type='text' class='form-control TOTAL rightJustified text-primary' readonly></td>
								<td><input name='DRD[]' id=DRD0 value='' type='text' class='form-control text_input DRD' readonly></td>
								<td><input name='DEVISI[]' id=DEVISI0 value='' type='text' class='form-control text_input DEVISI' readonly>
									<input name='SUB' onkeyup='hitung()' value='' id=SUB0 type='hidden' class='form-control SUB rightJustified text-primary text_input' readonly>
									<input name='KD_BAG' value='' id=KD_BAG0 type='hidden' class='form-control KD_BAG rightJustified text-primary text_input' readonly>
								</td>
								<td><button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button></td>
							</tr>`);
				}
			});
		});

		$('#ManualPP').on('change', function() {
			this.value ^= 1;
			if (this.value == 1) {
				$('.NO_PP').prop('required', false);
				$('.NO_PP').val('').change();
				$('.NO_PP').prop('disabled', true);
				$('.kd_bhndiv').attr("hidden",false);
				$('.KD_BHN').attr("hidden",true);
				$('.NA_BHN').attr("readonly",false);
				manualPP = 1;
			} else {
				$('.NO_PP').prop('required', true);
				$('.NO_PP').removeAttr('disabled');
				$('.kd_bhndiv').attr("hidden",true);
				$('.KD_BHN').attr("hidden",false);
				$('.NA_BHN').attr("readonly",true);
				manualPP = 0;
			}
			cekManualPoRa();
			// console.log(this.value)
		});
	});

	function data_pp(){
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('index.php/admin/Transaksi_Po_NonBahan/getDataAjax_no_pp2'); ?>',
			contentType: 'application/json; charset=utf-8',
			data: {
				// "KODES": kodes
				cari: $('input[type=search]').val(),
			},
			dataType: 'text json',
			cache: false,
			success: function(response) {
				// alert(response);
				console.log(response);
				var html = '';
				var i;
				for (i = 0; i < response.length; i++) {
					html += "<tr>" +
						"<td class='text_input'>" + response[i].NO_PP + "</td>" +
						"<td class='text_input'>" + response[i].KD_BHN + "</td>" +
						"<td class='text_input'>" + response[i].NA_BHN + "</td>" +
						"<td class='text_input'>" + response[i].SATUANPP + "</td>" +
						"<td class='text_input'>" + response[i].QTYPP + "</td>" +
						"<td class='text_input'>" + response[i].DRD + "</td>" +
						"<td class='text_input'>" + response[i].SISA + "</td>" +
						"<td><input type='checkbox' id='checkedID" + i + "' class='checkedID' name='checkedID[]' value='" + response[i].NO_PP + "'></td>" +
						"</tr>";
					// idrow++;
				}
				$('#show-no_pp').html(html);
				init_checkbox();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				// alert("Status: " + textStatus);
				// alert("Error: " + errorThrown);
				// idrow = 0;
				console.log('error bro');
				console.log(textStatus);
				console.log(errorThrown);
				$('#show-no_pp').html(``);
			}
		});
	}

	function cekManualPoRa() {
		if (manualPP==1) {
			$(".NO_PP").each(function() {
				let z = $(this).closest('tr');
				// var barise = $(this).attr('id').substring(5, 9);
				z.find('.NO_PP').prop('required', false);
				if($(this).val()) z.find('.NO_PP').val('').change();
				z.find('.NO_PP').prop('disabled', true);
				z.find('.kd_bhndiv').attr("hidden",false);
				z.find('.KD_BHN').attr("hidden",true);
				z.find('.NA_BHN').attr("readonly",false);
			});
		}
		else {
			$(".KD_BHNX").each(function() {
				let z = $(this).closest('tr');
				z.find('.kd_bhndiv').attr("hidden",true);
				z.find('.KD_BHN').attr("hidden",false);
				z.find('.NA_BHN').attr("readonly",true);
			});
		}
	}

	function cek_jtempo() {
		var tgl = $('#TGL').val();
		var jtemp = $('#JTEMPO').val();
		var hari = tgl.substr(0, 2);
		var bulan = tgl.substr(3, 2);
		var tahun = tgl.substr(6, 4);
		podate = new Date(tahun + '-' + bulan + '-' + hari);
		var harijt = jtemp.substr(0, 2);
		var bulanjt = jtemp.substr(3, 2);
		var tahunjt = jtemp.substr(6, 4);
		jtdate = new Date(tahunjt + '-' + bulanjt + '-' + harijt);
		if (podate > jtdate) {
			$('#TGL').val(jtemp);
			alert("Jatuh Tempo Tidak boleh lebih kecil dari tanggal !!!");
		}
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
		var TOTAL_QTYPP = 0;
		var TTOTAL = 0;
		var PKP = parseFloat($('#PKP').val().replace(/,/g, ''));
		var PPN = parseFloat($('#PPN').val().replace(/,/g, ''));
		var NETT = 0;

		/*
		var total_row = idrow;
		for (i = 0; i < total_row; i++) {
			var qtypp = parseFloat($('#QTYPP' + i).val().replace(/,/g, ''));
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
			var harga = parseFloat($('#HARGA' + i).val().replace(/,/g, ''));
			// var na_bhn = $('#NA_BHN' + i.toString()).val();

			// console.log(manualPP);
			if (qty > qtypp && manualPP == 0) {
				alert("Qty tidak boleh lebih besar dari Qty PP");
				$('#QTY' + i).val(0);
				console.log('TIDAK OK !!!')
			} else {
				console.log('OK !!!')
			}

			var total = qty * harga;
			if (isNaN(total)) total = 0;
			$('#TOTAL' + i).val(numberWithCommas(total));
			$('#TOTAL' + i).autoNumeric('update');
		}; 
		*/

		$(".QTYPP").each(function() {
			let z = $(this).closest('tr');
			var qtypp = parseFloat($(this).val().replace(/,/g, ''));
			var qty = parseFloat(z.find('.QTY').val().replace(/,/g, ''));
			var harga = parseFloat(z.find('.HARGA').val().replace(/,/g, ''));

			if (qty > qtypp && manualPP == 0) {
				alert("Qty tidak boleh lebih besar dari Qty PP");
				// $('#QTY' + i).val(0);
				z.find('.QTY').val(0);
				z.find('.QTY').autoNumeric('update');
				console.log('TIDAK OK !!!')
			} else {
				console.log('OK !!!')
			}

			if (isNaN(qtypp)) qtypp = 0;
			TOTAL_QTYPP += qtypp;

			var total = qty * harga;
			if (isNaN(total)) total = 0;
			z.find('.TOTAL').val(total);
		    z.find('.TOTAL').autoNumeric('update');
		});
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});
		$(".TOTAL").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TTOTAL += val;
		});

		if (PKP == 1) {
			// var PPN = (Math.round(TTOTAL * 0.11 * 100) / 100).toFixed(0);
			var PPN = TTOTAL * 0.11 * 100 / 100;
			console.log('PPN Aktif');
		} else {
			var PPN = 0;
			console.log('PPN Tidak Aktif');
		}

		// var PPN = (Math.round(TTOTAL * 0.1 * 100) / 100).toFixed(0);
		var NETT = TTOTAL + parseFloat(PPN);

		var BUDGET = parseFloat($('#BUDGET').val().replace(/,/g, ''));
		if (NETT > BUDGET && manualPP == 0) {
			console.log('mpp'+manualPP);
			$(".HARGA").val(0);
			$(".TOTAL").val(0);
			alert("TOTAL MELEBIHI BUDGET !");
			// hitung();
		}

		if (isNaN(TOTAL_QTYPP)) TOTAL_QTYPP = 0;
		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;
		if (isNaN(TTOTAL)) TTOTAL = 0;
		if (isNaN(PPN)) PPN = 0;
		if (isNaN(NETT)) NETT = 0;

		$('#TOTAL_QTYPP').val(numberWithCommas(TOTAL_QTYPP));
		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));
		$('#TTOTAL').val(numberWithCommas(TTOTAL));
		$('#PPN').val(numberWithCommas(PPN));
		$('#NETT').val(numberWithCommas(NETT));

		$("#TOTAL_QTYPP").autoNumeric('update');
		$('#TOTAL_QTY').autoNumeric('update');
		$('#TTOTAL').autoNumeric('update');
		$('#PPN').autoNumeric('update');
		$('#NETT').autoNumeric('update');
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

		var no_pp0 = "<div class='input-group no_ppdiv'><select class='js-example-responsive-no_pp form-control NO_PP' name='NO_PP[]' value='' id=NO_PP" + idrow + " onchange='no_pp(this.id), isi_NOPOPPC()' onfocusout='hitung()' ></select></div>";
		var no_pp = no_pp0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = no_pp;
		// td3.innerHTML = "<input name='KD_BHN[]' id=KD_BHN" + idrow + " type='text' class='form-control KD_BHN text_input' readonly>";
		td3.innerHTML = "<div class='input-group kd_bhndiv'><select class='js-example-responsive-kode_bhn form-control KD_BHNX' name='KD_BHNX[]' value='' id=KD_BHNX" + idrow + " onchange='kode_bhn(this.id)' onfocusout='hitung()' disabled></select></div> <input name='KD_BHN[]' id=KD_BHN" + idrow + " type='text' class='form-control KD_BHN text_input' readonly hidden>";
		td4.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' readonly>";
		td5.innerHTML = "<input name='SATUANPP[]' id=SATUANPP" + idrow + " type='text' class='form-control SATUANPP text_input' readonly>";
		td6.innerHTML = "<input name='QTYPP[]' onclick='select()' onchange='hitung()' value='0' id=QTYPP" + idrow + " type='text' class='form-control QTYPP rightJustified text-primary' readonly>";
		td7.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input'>";
		td8.innerHTML = "<input name='QTY[]' onclick='select()' onchange='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary'>";
		td9.innerHTML = "<input name='HARGA[]' onclick='select()' onchange='hitung()' value='0' id=HARGA" + idrow + " type='text' class='form-control HARGA rightJustified text-primary'>";
		td10.innerHTML = "<input name='TOTAL[]' onkeyup='hitung()' value='0' id=TOTAL" + idrow + " type='text' class='text_input form-control TOTAL rightJustified text-primary' readonly><input name='SUB' value='0' id='SUB" + idrow + "' type='hidden' class='form-control SUB rightJustified text-primary text_input' readonly>";
		td11.innerHTML = "<input name='DRD[]'' id=DRD" + idrow + " type='text' class='form-control text_input DRD' readonly>";
		td12.innerHTML = "<input name='DEVISI[]' id=DEVISI" + idrow + " type='text' class='form-control text_input DEVISI' readonly>";
		td13.innerHTML = "<input type='hidden' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'  value='0'  >" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";

		idrow++;
		initNumber();
		nomor();
		select_no_pp();
		select_kd_bhn();
		cekManualPoRa();
		$(".ronly").on('keydown paste', function(e) {
			e.preventDefault();
			e.currentTarget.blur();
		});
	}

	function hapus() {
		if (idrow > 1) {
			var x = document.getElementById('datatable').deleteRow(idrow);
			idrow--;
			nomor();
		}
	}

	function init_checkbox() {
		$(".checkedID").change(function() {
				NO_PP = [];
				$('input[type=checkbox]:checked').each(function() {
					// str += $(this).val() + ", ";
					NO_PP.push($(this).val());
					console.log(NO_PP);
				});
			})
			.trigger('change');
	}

	function chekbox() {
		$(".PKP").each(function() {
			if ($(this).is(":checked") == true) {
				$(this).attr('value', '1');
			} else {
				$(this).prop('checked', true);
				$(this).attr('value', '0');
			}
		});
	}

	function isi_NOPOPPC() {
		const NOPOPPC = [];
		$(".NO_PP").each(function() {
			const x = $(this).val();
			if (!NOPOPPC.includes(x)) {
				NOPOPPC.push(x);
			}
		});
		const x = NOPOPPC.join(', ');
		$("#NOPOPPC").html(x.toString());
	};
</script>

<script>
	$(document).ready(function() {
		select_kodes();
		select_no_pp();
		select_kd_bhn();
	});

	function select_kodes() {
		$('.js-example-responsive-kodes').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_PO_NonBahan/getDataAjax_sup') ?>",
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
			placeholder: 'Pilih Supplier',
			minimumInputLength: 0,
			templateResult: format_kodes,
			templateSelection: formatSelection_kodes
		});
	}

	function format_kodes(repo_kodes) {
		if (repo_kodes.loading) {
			return repo_kodes.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kodes.KODES);
		return $container;
	}
	var namas = '';
	var alamat = '';
	var kota = '';
	var kontak = '';
	var aktif = '';
	var pkp = '';
	var notbay = '';

	function formatSelection_kodes(repo_kodes) {
		namas = repo_kodes.NAMAS;
		alamat = repo_kodes.ALAMAT;
		kota = repo_kodes.KOTA;
		kontak = repo_kodes.KONTAK;
		aktif = repo_kodes.AKTIF;
		pkp = repo_kodes.PKP;
		notbay = repo_kodes.NOTBAY;
		return repo_kodes.text;
	}

	function kodes(x) {
		if (aktif == '1') {
			$('.NAMAS').val(namas);
			$('.ALAMAT').val(alamat);
			$('.KOTA').val(kota);
			$('.AN').val(kontak);
			$('.NOTESBL').val(notbay);
			$('.PKP').val(pkp);
			if (pkp == '1') {
				document.getElementById('PKP').checked = true;
			} else {
				document.getElementById('PKP').checked = false;
			}
		} else {
			alert("Status Suplier TIDAK AKTIF");
			$('.NAMAS').val("");
			$('.ALAMAT').val("");
			$('.KOTA').val("");
			$('.AN').val("");
			$('.NOTESBL').val("");
			$('.PKP').val("");
			document.getElementById('PKP').checked = false;
		}
		hitung();
	}

	function select_no_pp() {
		var dr = $('#DR').val();
		$('.js-example-responsive-no_pp').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_PO_NonBahan/getDataAjax_no_pp') ?>",
				dataType: "json",
				type: "post",
				delay: 10,
				data: function(params) {
					return {
						search: params.term,
						page: params.page,
						dr: $('#DR').val(),
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
			placeholder: 'Pilih No PP',
			minimumInputLength: 0,
			templateResult: format_no_pp,
			templateSelection: formatSelection_no_pp
		});
	}

	function format_no_pp(repo_no_pp) {
		if (repo_no_pp.loading) {
			return repo_no_pp.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_no_pp.NO_PP);
		return $container;
	}
	var kd_bhn = '';
	var na_bhn = '';
	var satuanpp = '';
	var qtypp = '';
	var satuan = '';
	var qty = '';
	var drd = '';
	var sub = '';

	function formatSelection_no_pp(repo_no_pp) {
		kd_bhn = repo_no_pp.KD_BHN;
		na_bhn = repo_no_pp.NA_BHN;
		satuanpp = repo_no_pp.SATUANPP;
		qtypp = repo_no_pp.QTYPP;
		satuan = repo_no_pp.SATUAN;
		drd = repo_no_pp.DRD;
		qty = repo_no_pp.QTY;
		sub = repo_no_pp.SUB;
		return repo_no_pp.text;
	}

	function no_pp(xxx) {
		var qqq = xxx.substring(5, 9);
		$('#KD_BHN' + qqq).val(kd_bhn);
		$('#NA_BHN' + qqq).val(na_bhn);
		$('#SATUANPP' + qqq).val(satuanpp);
		$('#QTYPP' + qqq).val(qtypp);
		$('#SATUAN' + qqq).val(satuan);
		$('#DRD' + qqq).val(drd);
		$('#QTY' + qqq).val(qty);
		$('#SUB').val(sub);
		console.log('No PP :' + qqq);
	}

	function select_kd_bhn() {
		var dr = $('#DR').val();
		$('.js-example-responsive-kode_bhn').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_PO_NonBahan/getDataAjax_kd_bhn') ?>",
				dataType: "json",
				type: "post",
				delay: 10,
				data: function(params) {
					return {
						search: params.term,
						page: params.page,
						dr: $('#DR').val(),
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
			placeholder: 'Pilih Bahan',
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
		$container.find(".select2-result-repository__title").text(repo_kd_bhn.KET_BHN);
		return $container;
	}
	var kd_bhn = '';
	var na_bhn = '';
	var satuanpp = '';
	var satuan = '';
	var drd = '';
	var sub = '';

	function formatSelection_kd_bhn(repo_kd_bhn) {
		kd_bhn = repo_kd_bhn.KD_BHN;
		na_bhn = repo_kd_bhn.NA_BHN;
		satuanpp = repo_kd_bhn.SATUANPP;
		satuan = repo_kd_bhn.SATUAN;
		drd = repo_kd_bhn.DRD;
		sub = repo_kd_bhn.SUB;
		return repo_kd_bhn.text;
	}

	function kode_bhn(xxx) {
		var qqq = xxx.substring(7, 10);
		$('#KD_BHN' + qqq).val(kd_bhn);
		$('#NA_BHN' + qqq).val(na_bhn);
		$('#SATUANPP' + qqq).val(satuanpp);
		$('#SATUAN' + qqq).val(satuan);
		$('#DRD' + qqq).val(drd);
		$('#SUB').val(sub);
		console.log('Kd Bahan :' + qqq);
	}

	function ModalBudget() {
		$.ajax({
			type: 'get',
			url: '<?php echo base_url('index.php/admin/Transaksi_PO_NonBahan/modal_budget'); ?>',
			data: {
				DR: $('#DR_FILTER').val()
			},
			dataType: 'json',
			success: function(response) {
				var BUDGET = parseFloat(response[0].BUDGET.replace(/,/g, ''));
				var TPEMBELIAN = parseFloat($("#TPEMBELIAN").val().replace(/,/g, ''));
				$("#TBUDGET").val(numberWithCommas(BUDGET));
				$("#TSISA").val(numberWithCommas(BUDGET - TPEMBELIAN));

				initNumber();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$("#TBUDGET").val("0");
				$("#TSISA").val("0");
			}
		});
	}

	$('#DR').change(function() {
		CekBudget();
	});

	function CekBudget() {
		// console.log('tulisono' + $('#DR').val());

		$.ajax({
			type: 'get',
			url: '<?php echo base_url('index.php/admin/Transaksi_PO_NonBahan/cek_budget'); ?>',
			data: {
				// JENIS: $('#JENIS').val(),
				DR: $('#DR').val()
			},
			dataType: 'json',
			success: function(response) {
				$('#BUDGET').val(numberWithCommas(response[0].BUDGET));
				// hitung();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('error bro');
				console.log(textStatus);
				console.log(errorThrown);
				$('#BUDGET').val(0);
				console.log('xx');
			}
		});

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

		// if (NETT > BUDGET && manualPP == 0) {
		// 	console.log('mpp'+manualPP);
		// 	$(".HARGA").val(0);
		// 	$(".TOTAL").val(0);
		// 	alert("TOTAL MELEBIHI BUDGET !");
		// 	// hitung();
		// }
	}
</script>