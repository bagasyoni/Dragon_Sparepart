<?php
foreach ($po_nonbahan as $rowh) {
};
?>

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

	hr {
		border: 0;
		clear: both;
		display: block;
		width: 100%;
		background-color: black;
		height: 1px;
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
		<i class="fas fa-university"></i> Update <?php echo $this->session->userdata['judul']; ?>
		<strong> Jika val 1 = OK, maka sudah tidak bisa diedit </strong>
	</div>
    <?php echo $this->session->flashdata('pesan') ?>
	<form id="pononbahan" name="pononbahan" action="<?php echo base_url('admin/Transaksi_PO_NonBahan/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="row">
			<div class="col-md-1">
				<label class="label">No Bukti </label>
			</div>
			<div class="col-md-3">
				<input type="hidden" id="ID" name="ID" class="form-control ID" value="<?php echo $rowh->ID ?>">
				<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value="<?php echo $rowh->NO_BUKTI ?>" readonly>
			</div>
			<div class="col-md-1">
				<label class="label">Dragon </label>
			</div>
			<div class="col-md-1 ">
				<input class="form-control text_input BD" id="BD" name="BD" type="text" value="<?php echo $rowh->BD ?>" readonly>
			</div>
			<div class="col-md-1">
				<label class="label">Supplier </label>
			</div>
			<div class="col-md-2">
				<select <?php if ($rowh->TTD1 == !0) echo 'disabled'; ?> class="js-example-responsive-kodes form-control KODES text_input" name="KODES" id="KODES" onchange="kodes(this.id)">
					<option value="<?php echo $rowh->KODES; ?>" selected id="KODES"><?php echo $rowh->KODES; ?></option>
				</select>
			</div>
			<div class="col-md-3">
				<input class="form-control text_input NAMAS" id="NAMAS" name="NAMAS" type="text" value="<?php echo $rowh->NAMAS ?>" readonly>
			</div>
		</div>
		<div class="row">
			<div class="col-md-1">
				<label class="label">Tanggal </label>
			</div>
			<div class="col-md-1">
				<input <?php if ($rowh->TTD1 == !0) echo 'class="form-control TGL text_input" readonly'; ?> type="text" class="date form-control text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>" <?= ($this->session->userdata['ha_e_ttd'] != '1') ? "" : "" ?>>
			</div>
			<div class="col-md-1">
				<label class="label">J Tempo </label>
			</div>
			<div class="col-md-1">
				<input <?php if ($rowh->TTD1 == !0) echo 'class="form-control JTEMPO text_input" readonly'; ?> type="text" class="date form-control text_input" id="JTEMPO" name="JTEMPO" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->JTEMPO, TRUE)); ?>" onchange="cek_jtempo()">
			</div>
			<div class="col-md-1">
			</div>
			<div class="col-md-1 ">
				<input class="form-control text_input DR" id="DR" name="DR" type="text" value="<?php echo $rowh->DR ?>" readonly>
			</div>
			<!-- <div class="col-md-2"></div>
			<div class="col-md-1">
				<label class="label">BD</label>
			</div>
			<div class="col-md-1">
				<select class="form-control BD text_input" name="BD" id="BD" style="width: 100%;">
					<option value="NB1" <?= ($rowh->BD == 'NB1') ? "selected" : "" ?>>NB1</option>
					<option value="NB2" <?= ($rowh->BD == 'NB2') ? "selected" : "" ?>>NB2</option>
					<option value="NB3" <?= ($rowh->BD == 'NB3') ? "selected" : "" ?>>NB3</option>
				</select>
			</div> -->
			<div class="col-md-1"></div>
			<div class="col-md-2">
				<input class="form-control text_input KOTA" id="KOTA" name="KOTA" type="text" value="<?php echo $rowh->KOTA ?>" readonly>
			</div>
			<div class="col-md-3">
				<input class="form-control text_input ALAMAT" id="ALAMAT" name="ALAMAT" type="text" value="<?php echo $rowh->ALAMAT ?>" readonly>
			</div>
		</div>
		<div class="row">
			<div class="col-md-1">
				<label class="label">Mata Uang </label>
			</div>
			<div class="col-md-1 ">
				<input class="form-control text_input KURS" id="KURS" name="KURS" type="text" value="<?php echo $rowh->KURS ?>" readonly>
			</div>
			<div class="col-md-1">
				<label class="label">Kurs </label>
			</div>
			<div class="col-md-1">
				<input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> class="form-control text_input RATE" id="RATE" name="RATE" type="text" value="<?php echo $rowh->RATE ?>" placeholder="Kurs">
			</div>
			<div class="col-md-2"></div>
			<!-- <div class="col-md-1">
				<label class="label">Produk</label>
			</div>
			<div class="col-md-1">
				<select class="form-control PROD text_input" name="PROD" id="PROD" style="width: 100%;">
					<option value="SEPATU" <?= ($rowh->PROD == 'SEPATU') ? "selected" : "" ?>>Sepatu</option>
					<option value="SANDAL" <?= ($rowh->PROD == 'SANDAL') ? "selected" : "" ?>>Sandal</option>
				</select>
			</div> -->
			<div class="col-md-1"></div>
			<div class="input-group col-md-2">
				<input class="form-control text_input AN" id="AN" name="AN" type="text" value='<?php echo $rowh->AN ?>' placeholder="Atas Nama">
			</div>
			<div class="col-md-2">
				<input <?php
						if ($rowh->PKP == "1") echo 'checked '; ?> name="PKP" id="PKP" type="checkbox" value="<?= $rowh->PKP ?>" class="checkbox_container" <?php if ($rowh->TTD1 == !0) echo 'onclick="return false;"'; ?> onclick="return false;">
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<label class="label">Notes Bayar </label>
				<input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> class="form-control text_input NOTESBL" id="NOTESBL" name="NOTESBL" type="text" value="<?php echo $rowh->NOTESBL ?>">
			</div>
			<div class="col-md-2">
				<label class="label">Notes Kirim </label>
				<input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> class="form-control text_input NOTESKRM" id="NOTESKRM" name="NOTESKRM" type="text" value="<?php echo $rowh->NOTESKRM ?>">
			</div>
			<div class="col-md-2">
				<label class="label">Notes Cetak</label>
				<input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> class="form-control text_input NOTESCTK" id="NOTESCTK" name="NOTESCTK" type="text" value="<?php echo $rowh->NOTESCTK ?>">
			</div>
			<div class="col-md-3">
				<label class="label">Notes </label>
				<input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" value="<?php echo $rowh->NOTES ?>">
			</div>
			<div class="col-md-3">
				<label class="label">Update Status</label>
				<div class="input-group">
					<input class="form-control text_input NOTESUPDATE" id="NOTESUPDATE" name="NOTESUPDATE" type="text" value=''>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-primary" id="BTN_MEMO"><i class="far fa-save"></i></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group row" style="justify-content: center;">
			<div class="col-md-2">
				<a type="button" class="btn btn-primary btn-center" data-target="#mymodal_cekbudget" data-toggle="modal" href="#lupCekBudget">
					<span style="color: black; font-weight: bold;" class="btn_info">
						<i class="fa fa-balance-scale"></i> CEK BUDGET
					</span>
				</a>
			</div>
			<!-- <div class="col-md-2">
							<a type="button" class="btn btn-primary btn-center" data-target="#mymodal_penawaran" data-toggle="modal" href="#lupPenawaran">
								<span style="color: black; font-weight: bold;">
									<i class="fa fa-file-text"></i> CEK PENAWARAN
								</span>
							</a>
						</div> -->
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

		<hr>
		<div class="col-md-12" style="justify-content: center;text-align: center;">
			<div class="form-group row" style="justify-content: center;">
				<div class="col-md-2">
					<?php
					if ($rowh->TTD1 == 0)
						echo '<a 
										type="button" 
										class="btn btn-warning btn-center"
										onclick="btVerifikasi(1)" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> VAL 1</span>
									</a>';
					else echo '<a 
									type="button" 
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> VAL 1</span>
								</a>';
					?>
				</div>
				<div class="col-md-2">
					<?php
					if ($rowh->TTD2 == 0)
						echo '<a 
										type="button" 
										class="btn btn-warning btn-center"
										onclick="btVerifikasi(2)" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> VAL 2</span>
									</a>';
					else echo '<a 
									type="button" 
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> VAL 2</span>
								</a>';
					?>
				</div>
				<div class="col-md-2">
					<?php
					if ($rowh->TTD3 == 0)
						echo '<a 
										type="button" 
										class="btn btn-warning btn-center"
										onclick="btVerifikasi(3)" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> VAL 3</span>
									</a>';
					else echo '<a 
									type="button" 
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> VAL 3</span>
								</a>';
					?>
				</div>
				<div class="col-md-2">
					<?php
					if ($rowh->TTD4 == 0)
						echo '<a 
										type="button" 
										class="btn btn-warning btn-center"
										onclick="btVerifikasi(4)" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> VAL 4</span>
									</a>';
					else echo '<a 
									type="button" 
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> VAL 4</span>
								</a>';
					?>
				</div>
				<div class="col-md-2">
					<?php
					if ($rowh->TTD5 == 0)
						echo '<a 
										type="button" 
										class="btn btn-warning btn-center"
										onclick="btVerifikasi(5)" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> VAL 5</span>
									</a>';
					else echo '<a 
									type="button" 
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> VAL 5</span>
								</a>';
					?>
				</div>
				<div class="col-md-2">
					<?php
					// if ($rowh->TTD6 == 0)
					if ($rowh->CEO == 0)
						echo '<a 
										type="button" 
										class="btn btn-warning btn-center"
										onclick="btVerifikasi(6)" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> VAL 6</span>
									</a>';
					else echo '<a 
									type="button" 
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> VAL 6</span>
								</a>';
					?>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="justify-content: center;">
			<div class="form-group row" style="justify-content: center;">
				<div class="col-md-2">
					<input <?php if ($rowh->TTD1 == !0) echo 'hidden'; ?> class="form-control text_input PIN1" id="PIN1" name="PIN1" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->TTD1 == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTD1_SMP ?></label>
				</div>
				<div class="col-md-2">
					<input <?php if ($rowh->TTD2 == !0 || $rowh->TTD1 == 0) echo 'hidden'; ?> class="form-control text_input PIN2" id="PIN2" name="PIN2" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->TTD2 == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTD2_SMP ?></label>
				</div>
				<div class="col-md-2">
					<input <?php if ($rowh->TTD3 == !0 || $rowh->TTD2 == 0) echo 'hidden'; ?> class="form-control text_input PIN3" id="PIN3" name="PIN3" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->TTD3 == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTD3_SMP ?></label>
				</div>
				<div class="col-md-2">
					<input <?php if ($rowh->TTD4 == !0 || $rowh->TTD3 == 0) echo 'hidden'; ?> class="form-control text_input PIN4" id="PIN4" name="PIN4" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->TTD4 == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTD4_SMP ?></label>
				</div>
				<div class="col-md-2">
					<input <?php if ($rowh->TTD5 == !0 || $rowh->TTD4 == 0) echo 'hidden'; ?> class="form-control text_input PIN4" id="PIN4" name="PIN4" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->TTD5 == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTD5_SMP ?></label>
				</div>
				<div class="col-md-2">
					<!-- <input <?php if ($rowh->TTD6 == !0 || $rowh->TTD5 == 0) echo 'hidden'; ?> class="form-control text_input PIN4" id="PIN4" name="PIN4" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->TTD6 == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTD6_SMP ?></label> -->
					<input <?php if ($rowh->CEO == !0 || $rowh->TTD5 == 0) echo 'hidden'; ?> class="form-control text_input PIN4" id="PIN4" name="PIN4" type="password" maxlength="6" value="" placeholder="PIN ...">
					<label <?php if ($rowh->CEO == 0) echo 'hidden'; ?> id="x2" style="color: blue; font-weight: bold;"><?php echo $rowh->TTDCEO_SMP ?></label>
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
						<tbody>
							<?php
							$no = 0;
							foreach ($po_nonbahan as $row) :
							?>
								<tr>
									<td><input name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
									<!-- <td>
									<div class="input-group">
                                        <input name="NO_PP[]" id="NO_PP<?php echo $no; ?>" value="<?= $row->NO_PP ?>" type="text" class="form-control text_input NO_PP" readonly>
										<span <?php if ($rowh->TTD1 == !0) echo 'style="visibility: hidden;"'; ?> class="input-group-btn">
                                            <a class="btn default" id="0" data-target="#mymodal_no_pp" data-toggle="modal" href="#lupPP"  onfocusout="hitung()"><i class="fa fa-search"></i></a>
                                        </span>
                                    </div>
								</td> -->
									<td><input name="NO_PP[]" id="NO_PP<?php echo $no; ?>" value="<?= $row->NO_PP ?>" type="text" class="form-control text_input NO_PP" readonly></td>
									<td><input name="KD_BHN[]" id="KD_BHN<?php echo $no; ?>" value="<?= $row->KD_BHN ?>" type="text" class="form-control text_input KD_BHN" readonly></td>
									<td><input name="NA_BHN[]" id="NA_BHN<?php echo $no; ?>" value="<?= $row->NA_BHN ?>" type="text" class="form-control text_input NA_BHN" readonly></td>
									<td><input name="SATUANPP[]" id="SATUANPP<?php echo $no; ?>" value="<?= $row->SATUANPP ?>" type="text" class="form-control text_input SATUANPP" readonly></td>
									<td><input name="QTYPP[]" onchange="hitung()" id="QTYPP<?php echo $no; ?>" value="<?php echo number_format($row->QTYPP, 2, '.', ','); ?>" type="text" class="form-control QTYPP rightJustified text-primary" readonly></td>
									<td><input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control text_input SATUAN"></td>
									<td><input <?php if ($rowh->TTD1 == !0) echo 'readonly'; ?> name="QTY[]" onclick="select()" onchange="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary"></td>
									<td><input <?php echo (($this->session->userdata['level'] != 'BL1' && $rowh->TTD1 == !0) ?'readonly':(($this->session->userdata['level'] == 'BL1' && $rowh->TTD2 == !0)?'readonly':'')); ?> name="HARGA[]" onclick="select()" onchange="hitung()" id="HARGA<?php echo $no; ?>" value="<?php echo number_format($row->HARGA, 2, '.', ','); ?>" type="text" class="form-control HARGA rightJustified text-primary"></td>
									<td><input name="TOTAL[]" onchange="hitung()" id="TOTAL<?php echo $no; ?>" value="<?php echo number_format($row->TOTAL, 2, '.', ','); ?>" type="text" class="form-control TOTAL rightJustified text-primary" readonly></td>
									<td><input name="DRD[]" id="DRD<?php echo $no; ?>" value="<?= $row->DRD ?>" type="text" class="form-control text_input DRD" readonly></td>
									<td><input name="DEVISI[]" id="DEVISI<?php echo $no; ?>" value="<?= $row->DEVISI ?>" type="text" class="form-control text_input DEVISI" readonly>
										<input name="SUB[]" id="SUB<?php echo $no; ?>" value="<?= $row->SUB ?>" type="hidden" class="form-control text_input SUB" readonly>
									</td>
									<td>
										<input name="NO_ID[]" id="NO_ID<?php echo $no; ?>" value="<?= $row->NO_ID ?>" class="form-control" type="hidden">
										<button <?php if ($rowh->TTD1 == !0) echo 'style="visibility: hidden;"'; ?> type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
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
							<td></td>
							<td><input class="form-control TOTAL_QTYPP rightJustified text-primary font-weight-bold" id="TOTAL_QTYPP" name="TOTAL_QTYPP" value="<?php echo number_format($rowh->TOTAL_QTYPP, 2, '.', ','); ?>" readonly></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="<?php echo number_format($rowh->TOTAL_QTY, 2, '.', ','); ?>" readonly></td>
							<td></td>
							<td><input class="form-control TTOTAL rightJustified text-primary font-weight-bold" id="TTOTAL" name="TTOTAL" value="<?php echo number_format($rowh->TTOTAL, 2, '.', ','); ?>" readonly></td>
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
				<input class="form-control PPN rightJustified text-danger font-weight-bold text_input" onfocusout="hitung()" onkeyup="hitung()" value="<?php echo number_format($rowh->PPN, 2, '.', ','); ?>" id="PPN" name="PPN" readonly>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="btn-group">
					<button type="submit" onclick="chekbox(),cektgl()" class="btn btn-success" <?php if ($rowh->TTD2 == !0) echo 'style="visibility: hidden;"'; ?>><i class="fa fa-save"></i> Save</button>
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
				<input class="form-control NETT rightJustified text-success font-weight-bold text_input" onkeyup="hitung()" value="<?php echo number_format($rowh->NETT, 2, '.', ','); ?>" id="NETT" name="NETT" readonly>
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
						<th>No PP</th>
						<th>Kd Bhn</th>
						<th>Na Bhn</th>
						<th>Stn PPC</th>
						<th>Qty PPC</th>
						<th>Flag</th>
						<th>DR</th>
						<th>Devisi</th>
					</thead>
					<tbody>
						<?php
						$dr = $this->input->get('DR');
						$sql = "SELECT NO_BUKTI AS NO_PP, 
								KD_BHN AS KD_BHN,
								NA_BHN AS NA_BHN, 
								SATUAN AS SATUANPP,
								QTY-KIRIM AS QTYPP,
								SATUAN AS SATUAN,
								QTY-KIRIM AS QTY,
								DR AS DRD,
								DEVISI AS DEVISI,
								FLAG AS FLAG
							FROM ppd
							WHERE POSTED = 1
							AND FLAG2='SP' AND QTY - KIRIM <> 0
							ORDER BY NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach ($a as $b) {
						?>
							<tr>
								<td class='NPPVAL'><a href="#" class="select_no_pp"><?php echo $b->NO_PP; ?></a></td>
								<td class='KBPVAL text_input'><?php echo $b->KD_BHN; ?></td>
								<td class='NBPVAL text_input'><?php echo $b->NA_BHN; ?></td>
								<td class='SPPVAL text_input'><?php echo $b->SATUANPP; ?></td>
								<td class='QPPVAL text_input'><?php echo $b->QTYPP; ?></td>
								<td class='text_input'><?php echo $b->FLAG; ?></td>
								<td class='DRPVAL text_input'><?php echo $b->DRD; ?></td>
								<td class='DVPVAL text_input'><?php echo $b->DEVISI; ?></td>
								<td style="display: none;" class='SAPVAL text_input'><?php echo $b->SATUAN; ?></td>
								<td style="display: none;" class='QTPVAL text_input'><?php echo $b->QTY; ?></td>
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
							AND po.FLAG='PP' AND po.FLAG2='SP'
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
							WHERE po.FLAG='PP' AND po.FLAG2='SP'
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
							WHERE po.NO_BUKTI=pod.NO_BUKTI AND po.FLAG='PP' AND po.FLAG2='SP'
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
		initNumber();
		CekBudget();

		//MyModal No PP
		$('#mymodal_no_pp').on('show.bs.modal', function(e) {
			target = $(e.relatedTarget);
		});
		$('body').on('click', '.select_no_pp', function() {
			var val = $(this).parents("tr").find(".NPPVAL").text();
			target.parents("tr").find(".NO_PP").val(val);
			var val = $(this).parents("tr").find(".KBPVAL").text();
			target.parents("tr").find(".KD_BHN").val(val);
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
			var val = $(this).parents("tr").find(".DVPVAL").text();
			target.parents("tr").find(".DEVISI").val(val);
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
		$('input[type="checkbox"]').on('change', function() {
			this.value ^= 1;
			console.log(this.value)
		});
	});

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

	function btVerifikasi(vald) {
		if (vald==1 && $('#PIN'+vald).val() == '<?= $this->session->userdata['pin'] ?>')
		{
			if (confirm("Yakin Posting "+vald+"? \n Posting hanya bisa Posting Data, tidak untuk edit data.")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_PO_NonBahan/verifikasi_ttd1?VAL=1&NO_ID=' . $rowh->ID) ?>");
			}
		}
		else if (vald==2 && $('#PIN'+vald).val() == '<?= $this->session->userdata['pin'] ?>')
		{
			if (confirm("Yakin Posting "+vald+"? \n Posting hanya bisa Posting Data, tidak untuk edit data.")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_PO_NonBahan/verifikasi_ttd1?VAL=2&NO_ID=' . $rowh->ID) ?>");
			}
		}
		else if (vald==3 && $('#PIN'+vald).val() == '<?= $this->session->userdata['pin'] ?>')
		{
			if (confirm("Yakin Posting "+vald+"? \n Posting hanya bisa Posting Data, tidak untuk edit data.")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_PO_NonBahan/verifikasi_ttd1?VAL=3&NO_ID=' . $rowh->ID) ?>");
			}
		}
		else if (vald==4 && $('#PIN'+vald).val() == '<?= $this->session->userdata['pin'] ?>')
		{
			if (confirm("Yakin Posting "+vald+"? \n Posting hanya bisa Posting Data, tidak untuk edit data.")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_PO_NonBahan/verifikasi_ttd1?VAL=4&NO_ID=' . $rowh->ID) ?>");
			}
		} 
		else if (vald==5 && $('#PIN'+vald).val() == '<?= $this->session->userdata['pin'] ?>')
		{
			if (confirm("Yakin Posting "+vald+"? \n Posting hanya bisa Posting Data, tidak untuk edit data.")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_PO_NonBahan/verifikasi_ttd1?VAL=5&NO_ID=' . $rowh->ID) ?>");
			}
		} 
		else if (vald==6 && $('#PIN'+vald).val() == '<?= $this->session->userdata['pin'] ?>')
		{
			if (confirm("Yakin Posting "+vald+"? \n Posting hanya bisa Posting Data, tidak untuk edit data.")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_PO_NonBahan/verifikasi_ttd1?VAL=6&NO_ID=' . $rowh->ID) ?>");
			}
		} 
		else {
			alert("Verifikasi Gagal!");
		}
	}

	function hitung() {
		var TOTAL_QTY = 0;
		var TOTAL_QTYPP = 0;
		var TTOTAL = 0;
		var PKP = parseFloat($('#PKP').val().replace(/,/g, ''));
		var PPN = parseFloat($('#PPN').val().replace(/,/g, ''));
		var NETT = 0;

		var total_row = idrow;
		for (i = 0; i < total_row; i++) {
			var qtypp = parseFloat($('#QTYPP' + i).val().replace(/,/g, ''));
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
			var harga = parseFloat($('#HARGA' + i).val().replace(/,/g, ''));
			// var na_bhn = $('#NA_BHN' + i.toString()).val();

			if (qty > qtypp) {
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

		$(".QTYPP").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTYPP += val;
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
			var PPN = (Math.round(TTOTAL * 0.11 * 100) / 100).toFixed(0);
			console.log('PPN Aktif');
		} else {
			var PPN = 0;
			console.log('PPN Tidak Aktif');
		}

		//		var PPN = (Math.round(TTOTAL * 0.1 * 100) / 100).toFixed(0);
		var NETT = TTOTAL + parseFloat(PPN);

		// BATASAN BUDGET
		var BUDGET = parseFloat($('#BUDGET').val().replace(/,/g, ''));
		if (NETT > BUDGET) {
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

		var no_pp0 = "<div class='input-group'><select class='js-example-responsive-no_pp form-control NO_PP' name='NO_PP[]' value='' id=NO_PP" + idrow + " onchange='no_pp(this.id)' onfocusout='hitung()' required></select></div>";
		var no_pp = no_pp0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = no_pp;
		td3.innerHTML = "<input name='KD_BHN[]' id=KD_BHN" + idrow + " type='text' class='form-control KD_BHN text_input' readonly>";
		td4.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' readonly>";
		td5.innerHTML = "<input name='SATUANPP[]' id=SATUANPP" + idrow + " type='text' class='form-control SATUANPP text_input' readonly>";
		td6.innerHTML = "<input name='QTYPP[]' onclick='select()' onchange='hitung()' value='0' id=QTYPP" + idrow + " type='text' class='form-control QTYPP rightJustified text-primary' readonly>";
		td7.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input'>";
		td8.innerHTML = "<input name='QTY[]' onclick='select()' onchange='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary'>";
		td9.innerHTML = "<input name='HARGA[]' onclick='select()' onchange='hitung()' value='0' id=HARGA" + idrow + " type='text' class='form-control HARGA rightJustified text-primary'>";
		td10.innerHTML = "<input name='TOTAL[]' onkeyup='hitung()' value='0' id=TOTAL" + idrow + " type='text' class='text_input form-control TOTAL rightJustified text-primary' readonly><input name='SUB' value='0' id='SUB" + idrow + "' type='hidden' class='form-control SUB rightJustified text-primary text_input' readonly>";
		td11.innerHTML = "<input name='DRD[]'' id=DRD" + idrow + " type='text' class='form-control text_input DRD' readonly>";
		td12.innerHTML = "<input name='DEVISI[]' id=DEVISI" + idrow + " type='text' class='form-control text_input DEVISI' readonly><input name='SUB' onkeyup='hitung()' value='0' id='SUB0' type='hidden' class='form-control SUB rightJustified text-primary text_input' readonly>";
		td13.innerHTML = "<input type='hidden' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'  value='0'  >" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";

		idrow++;
		initNumber();
		nomor();
		select_no_pp();
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
</script>

<script>
	$(document).ready(function() {
		select_kodes();
		select_no_pp();
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

	function formatSelection_kodes(repo_kodes) {
		namas = repo_kodes.NAMAS;
		alamat = repo_kodes.ALAMAT;
		kota = repo_kodes.KOTA;
		kontak = repo_kodes.KONTAK;
		aktif = repo_kodes.AKTIF;
		pkp = repo_kodes.PKP;
		return repo_kodes.text;
	}

	function kodes(x) {
		if (aktif == '1') {
			$('.NAMAS').val(namas);
			$('.ALAMAT').val(alamat);
			$('.KOTA').val(kota);
			$('.AN').val(kontak);
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
	var sub = '';

	function formatSelection_no_pp(repo_no_pp) {
		kd_bhn = repo_no_pp.KD_BHN;
		na_bhn = repo_no_pp.NA_BHN;
		satuanpp = repo_no_pp.SATUANPP;
		qtypp = repo_no_pp.QTYPP;
		satuan = repo_no_pp.SATUAN;
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
		$('#QTY' + qqq).val(qty);
		$('#SUB' + qqq).val(sub);
		console.log('No PP :' + qqq);
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
		// console.log($('#JENIS').val());
		console.log($('#DR').val());
		console.log($('#ID').val());
		$.ajax({
			type: 'get',
			url: '<?php echo base_url('index.php/admin/Transaksi_PO_NonBahan/cek_budget'); ?>',
			data: {
				// JENIS: $('#JENIS').val(),
				DR: $('#DR').val(),
				ID: $('#ID').val()
			},
			dataType: 'json',
			success: function(response) {
				$("#BUDGET").val(numberWithCommas(response[0].BUDGET));
				// console.log("gg");
				// hitung();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('error bro');
				console.log(textStatus);
				console.log(errorThrown);
				$("#BUDGET").val("0");
			}
		});
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

		// if (NETT > BUDGET) {
		// 	$(".HARGA").val(0);
		// 	$(".TOTAL").val(0);
		// 	alert("TOTAL MELEBIHI BUDGET !");
		// 	// hitung();
		// }
</script>