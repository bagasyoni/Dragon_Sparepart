<?php $kode_menu = $this->session->userdata['kode_menu']; ?>
<?php $level = $this->session->userdata['level']; ?>
<?php
foreach ($user_type as $rowh) {
};
?>
<div class="container-fluid">
	<div class="alert alert-success" role="alert">

		<!-- Ganti 1 -->

		<i class="fas fa-university"></i> Update Master User Type
	</div>
	<br>

	<!-- Ganti 2 -->

	<form method="post" action="<?php echo base_url('admin/user_type/update_aksi') ?>">
		<div class="form-group">
			<label>User Type</label>
			<input type="hidden" name="id" class="form-control" value="<?php echo $rowh->id ?>">
			<input type="text" name="USER_TYPE" class="form-control" value="<?php echo $rowh->user_level ?>" readonly>
			<?php echo form_error('USER_TYPE', '<div class="text-danger small" ml-3> ') ?>
		</div>

		<table id="example" class="table table-bordered table-striped table-hover " style="width:100%; font-size: 13px">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Menu </th>
					<th>View </th>
					<th>New </th>
					<th>Update </th>
					<!--	<th>Delete </th> 	-->
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="20px">1</td>
					<td>Master
						<input type='hidden' class='form-control' id="kode_menu_master" name='kode_menu_master' value="<?php echo $menu[0]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_master" name='nama_menu_master' value="<?php echo $menu[0]->NAMA_MENU; ?>">
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">1.1</td>
					<td>Customer
						<input type='hidden' class='form-control' id="kode_menu_customer" name='kode_menu_customer' value="<?php echo $menu[5]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_customer" name='nama_menu_customer' value="<?php echo $menu[5]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[5]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_cust_view' name='check_cust_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="cust_view" name='cust_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[5]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_cust_new' name='check_cust_new' onclick="cek()" <?php if ($rowh->baru == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="cust_new" name='cust_new' value="<?php echo $rowh->baru; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[5]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_cust_upd' name='check_cust_upd' onclick="cek()" <?php if ($rowh->edit == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="cust_upd" name='cust_upd' value="<?php echo $rowh->edit; ?>">
						<?php
							}
						};
						?>
					</td>
					<!--<td>
					<?php
					foreach ($user_type as $rowh) {
						if ($rowh->kode_menu == $menu[5]->KODE_MENU) {
					?>
					<input type='checkbox' class='singlechkbox' id='check_cust_del' name='check_cust_del' onclick="cek()" <?php if ($rowh->hapus == '1') echo 'checked'; ?>>
					<input type='hidden' class='form-control' id="cust_del" name='cust_del' value="<?php echo $rowh->hapus; ?>"  >
					<?php
						}
					};
					?>
				</td> -->
				</tr>
				<tr>
					<td width="20px">1.2</td>
					<td>Menu
						<input type='hidden' class='form-control' id="kode_menu_mmenu" name='kode_menu_mmenu' value="<?php echo $menu[12]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_mmenu" name='nama_menu_mmenu' value="<?php echo $menu[12]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[12]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_mmenu_view' name='check_mmenu_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="mmenu_view" name='mmenu_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[12]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_mmenu_new' name='check_mmenu_new' onclick="cek()" <?php if ($rowh->baru == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="mmenu_new" name='mmenu_new' value="<?php echo $rowh->baru; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[12]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_mmenu_upd' name='check_mmenu_upd' onclick="cek()" <?php if ($rowh->edit == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="mmenu_upd" name='mmenu_upd' value="<?php echo $rowh->edit; ?>">
						<?php
							}
						};
						?>
					</td>
					<!--<td>
					<?php
					foreach ($user_type as $rowh) {
						if ($rowh->kode_menu == $menu[12]->KODE_MENU) {
					?>
					<input type='checkbox' class='singlechkbox' id='check_mmenu_del' name='check_mmenu_del' onclick="cek()" <?php if ($rowh->hapus == '1') echo 'checked'; ?>>
					<input type='hidden' class='form-control' id="mmenu_del" name='mmenu_del' value="<?php echo $rowh->hapus; ?>"  >
					<?php
						}
					};
					?>
				</td> -->
				</tr>
				<tr>
					<td width="20px">2</td>
					<td>Transaksi
						<input type='hidden' class='form-control' id="kode_menu_transaksi" name='kode_menu_transaksi' value="<?php echo $menu[1]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_transaksi" name='nama_menu_transaksi' value="<?php echo $menu[1]->NAMA_MENU; ?>">
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">2.1</td>
					<td>Penambahan Saldo
						<input type='hidden' class='form-control' id="kode_menu_tmbh_saldo" name='kode_menu_tmbh_saldo' value="<?php echo $menu[6]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_tmbh_saldo" name='nama_menu_tmbh_saldo' value="<?php echo $menu[6]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[6]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_tmbh_saldo_view' name='check_tmbh_saldo_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="tmbh_saldo_view" name='tmbh_saldo_view' value="<?php echo $rowh->lihat; ?>" onclick="cek()">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[6]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_tmbh_saldo_new' name='check_tmbh_saldo_new' onclick="cek()" <?php if ($rowh->baru == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="tmbh_saldo_new" name='tmbh_saldo_new' value="<?php echo $rowh->baru; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[6]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_tmbh_saldo_upd' name='check_tmbh_saldo_upd' onclick="cek()" <?php if ($rowh->edit == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="tmbh_saldo_upd" name='tmbh_saldo_upd' value="<?php echo $rowh->edit; ?>">
						<?php
							}
						};
						?>
					</td>
					<!--<td>
					<?php
					foreach ($user_type as $rowh) {
						if ($rowh->kode_menu == $menu[6]->KODE_MENU) {
					?>
					<input type='checkbox' class='singlechkbox' id='check_tmbh_saldo_del' name='check_tmbh_saldo_del' onclick="cek()" <?php if ($rowh->hapus == '1') echo 'checked'; ?>>
					<input type='hidden' class='form-control' id="tmbh_saldo_del" name='tmbh_saldo_del' value="<?php echo $rowh->hapus; ?>"  >
					<?php
						}
					};
					?>
				</td>-->
				</tr>
				<tr>
					<td width="20px">2.2</td>
					<td>Pengurangan Saldo
						<input type='hidden' class='form-control' id="kode_menu_krg_saldo" name='kode_menu_krg_saldo' value="<?php echo $menu[7]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_krg_saldo" name='nama_menu_krg_saldo' value="<?php echo $menu[7]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[7]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_krg_saldo_view' name='check_krg_saldo_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="krg_saldo_view" name='krg_saldo_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[7]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_krg_saldo_new' name='check_krg_saldo_new' onclick="cek()" <?php if ($rowh->baru == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="krg_saldo_new" name='krg_saldo_new' value="<?php echo $rowh->baru; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[7]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_krg_saldo_upd' name='check_krg_saldo_upd' onclick="cek()" <?php if ($rowh->edit == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="krg_saldo_upd" name='krg_saldo_upd' value="<?php echo $rowh->edit; ?>">
						<?php
							}
						};
						?>
					</td>
					<!--	<td>
					<?php
					foreach ($user_type as $rowh) {
						if ($rowh->kode_menu == $menu[7]->KODE_MENU) {
					?>
					<input type='checkbox' class='singlechkbox' id='check_krg_saldo_del' name='check_krg_saldo_del' onclick="cek()" <?php if ($rowh->hapus == '1') echo 'checked'; ?>>
					<input type='hidden' class='form-control' id="krg_saldo_del" name='krg_saldo_del' value="<?php echo $rowh->hapus; ?>"  >
					<?php
						}
					};
					?>
				</td> -->
				</tr>
				<tr>
					<td width="20px">3</td>
					<td>Laporan
						<input type='hidden' class='form-control' id="kode_menu_laporan" name='kode_menu_laporan' value="<?php echo $menu[2]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_laporan" name='nama_menu_laporan' value="<?php echo $menu[2]->NAMA_MENU; ?>">
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">3.1</td>
					<td>Lihat Saldo
						<input type='hidden' class='form-control' id="kode_menu_lht_saldo" name='kode_menu_lht_saldo' value="<?php echo $menu[8]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_lht_saldo" name='nama_menu_lht_saldo' value="<?php echo $menu[8]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[8]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_lht_saldo_view' name='check_lht_saldo_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="lht_saldo_view" name='lht_saldo_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">3.2</td>
					<td>Lihat Transaksi
						<input type='hidden' class='form-control' id="kode_menu_lht_transaksi" name='kode_menu_lht_transaksi' value="<?php echo $menu[9]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_lht_transaksi" name='nama_menu_lht_transaksi' value="<?php echo $menu[9]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[9]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_lht_transaksi_view' name='check_lht_transaksi_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="lht_transaksi_view" name='lht_transaksi_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">3.3</td>
					<td>Lihat Penggunaan E-Dompet
						<input type='hidden' class='form-control' id="kode_menu_lht_edompet" name='kode_menu_lht_edompet' value="<?php echo $menu[10]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_lht_edompet" name='nama_menu_lht_edompet' value="<?php echo $menu[10]->NAMA_MENU; ?>">
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[10]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_lht_edompet_view' name='check_lht_edompet_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="lht_edompet_view" name='lht_edompet_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">4</td>
					<td>Administrator
						<input type='hidden' class='form-control' id="kode_menu_administrator" name='kode_menu_administrator' value="<?php echo $menu[3]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_administrator" name='nama_menu_administrator' value="<?php echo $menu[3]->NAMA_MENU; ?>">
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">4.1</td>
					<td>Kelola User Type
						<input type='hidden' class='form-control' id="kode_menu_kelola_usertype" name='kode_menu_kelola_usertype' value="<?php echo $menu[11]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_kelola_usertype" name='nama_menu_kelola_usertype' value="<?php echo $menu[11]->NAMA_MENU; ?>">
					</td>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[11]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_kelola_usertype_view' name='check_kelola_usertype_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="kelola_usertype_view" name='kelola_usertype_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[11]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_kelola_usertype_new' name='check_kelola_usertype_new' onclick="cek()" <?php if ($rowh->baru == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="kelola_usertype_new" name='kelola_usertype_new' value="<?php echo $rowh->baru; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[11]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_kelola_usertype_upd' name='check_kelola_usertype_upd' onclick="cek()" <?php if ($rowh->edit == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="kelola_usertype_upd" name='kelola_usertype_upd' value="<?php echo $rowh->edit; ?>">
						<?php
							}
						};
						?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="20px">4.2</td>
					<td>Kelola User
						<input type='hidden' class='form-control' id="kode_menu_user" name='kode_menu_user' value="<?php echo $menu[13]->KODE_MENU; ?>">
						<input type='hidden' class='form-control' id="nama_menu_user" name='nama_menu_user' value="<?php echo $menu[13]->NAMA_MENU; ?>">
					</td>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[13]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_user_view' name='check_user_view' onclick="cek()" <?php if ($rowh->lihat == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="user_view" name='user_view' value="<?php echo $rowh->lihat; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[13]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_user_new' name='check_user_new' onclick="cek()" <?php if ($rowh->baru == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="user_new" name='user_new' value="<?php echo $rowh->baru; ?>">
						<?php
							}
						};
						?>
					</td>
					<td>
						<?php
						foreach ($user_type as $rowh) {
							if ($rowh->kode_menu == $menu[13]->KODE_MENU) {
						?>
								<input type='checkbox' class='singlechkbox' id='check_user_upd' name='check_user_upd' onclick="cek()" <?php if ($rowh->edit == '1') echo 'checked'; ?>>
								<input type='hidden' class='form-control' id="user_upd" name='user_upd' value="<?php echo $rowh->edit; ?>">
						<?php
							}
						};
						?>
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>

		<div class="form-group">
			<button type="submit" class="act-btn2 btn btn-success">Simpan</button>
			<a type="button" href="<?php echo base_url('admin/user_type/index_user_type') ?>" class="act-btn btn btn-danger">Cancel</a>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		// menu
		if (document.getElementById("check_mmenu_view").checked == true) $('#mmenu_view').val(1);
		else $('#mmenu_view').val(0);
		if (document.getElementById("check_mmenu_new").checked == true) $('#mmenu_new').val(1);
		else $('#mmenu_new').val(0);
		if (document.getElementById("check_mmenu_upd").checked == true) $('#mmenu_upd').val(1);
		else $('#mmenu_upd').val(0);
		//	if(document.getElementById("check_mmenu_del").checked == true) $('#mmenu_del').val(1);
		//	else $('#mmenu_del').val(0);

		// customer
		if (document.getElementById("check_cust_view").checked == true) $('#cust_view').val(1);
		else $('#cust_view').val(0);
		if (document.getElementById("check_cust_new").checked == true) $('#cust_new').val(1);
		else $('#cust_new').val(0);
		if (document.getElementById("check_cust_upd").checked == true) $('#cust_upd').val(1);
		else $('#cust_upd').val(0);
		//	if(document.getElementById("check_cust_del").checked == true) $('#cust_del').val(1);
		//	else $('#cust_del').val(0);

		// Penambahan Saldo
		if (document.getElementById("check_tmbh_saldo_view").checked == true) $('#tmbh_saldo_view').val(1);
		else $('#tmbh_saldo_view').val(0);
		if (document.getElementById("check_tmbh_saldo_new").checked == true) $('#tmbh_saldo_new').val(1);
		else $('#tmbh_saldo_new').val(0);
		if (document.getElementById("check_tmbh_saldo_upd").checked == true) $('#tmbh_saldo_upd').val(1);
		else $('#tmbh_saldo_upd').val(0);
		//	if(document.getElementById("check_tmbh_saldo_del").checked == true) $('#tmbh_saldo_del').val(1);
		//	else $('#tmbh_saldo_del').val(0);

		// Pengurangan Saldo
		if (document.getElementById("check_krg_saldo_view").checked == true) $('#krg_saldo_view').val(1);
		else $('#krg_saldo_view').val(0);
		if (document.getElementById("check_krg_saldo_new").checked == true) $('#krg_saldo_new').val(1);
		else $('#krg_saldo_new').val(0);
		if (document.getElementById("check_krg_saldo_upd").checked == true) $('#krg_saldo_upd').val(1);
		else $('#krg_saldo_upd').val(0);
		//	if(document.getElementById("check_krg_saldo_del").checked == true) $('#krg_saldo_del').val(1);
		//	else $('#krg_saldo_del').val(0);

		// Lihat Saldo
		if (document.getElementById("check_lht_saldo_view").checked == true) $('#lht_saldo_view').val(1);
		else $('#lht_saldo_view').val(0);

		// Lihat Transaksi
		if (document.getElementById("check_lht_transaksi_view").checked == true) $('#lht_transaksi_view').val(1);
		else $('#lht_transaksi_view').val(0);

		// Lihat Penggunaaan E-dompet
		if (document.getElementById("check_lht_edompet_view").checked == true) $('#lht_edompet_view').val(1);
		else $('#lht_edompet_view').val(0);

		// Kelola User Type
		if (document.getElementById("check_kelola_usertype_view").checked == true) $('#kelola_usertype_view').val(1);
		else $('#kelola_usertype_view').val(0);
		if (document.getElementById("check_kelola_usertype_new").checked == true) $('#kelola_usertype_new').val(1);
		else $('#kelola_usertype_new').val(0);
		if (document.getElementById("check_kelola_usertype_upd").checked == true) $('#kelola_usertype_upd').val(1);
		else $('#kelola_usertype_upd').val(0);

		// Kelola User
		if (document.getElementById("check_user_view").checked == true) $('#user_view').val(1);
		else $('#user_view').val(0);
		if (document.getElementById("check_user_new").checked == true) $('#user_new').val(1);
		else $('#user_new').val(0);
		if (document.getElementById("check_user_upd").checked == true) $('#user_upd').val(1);
		else $('#user_upd').val(0);
	});

	function cek() {
		// menu
		if (document.getElementById("check_mmenu_view").checked == true) $('#mmenu_view').val(1);
		else $('#mmenu_view').val(0);
		if (document.getElementById("check_mmenu_new").checked == true) $('#mmenu_new').val(1);
		else $('#mmenu_new').val(0);
		if (document.getElementById("check_mmenu_upd").checked == true) $('#mmenu_upd').val(1);
		else $('#mmenu_upd').val(0);
		//	if(document.getElementById("check_mmenu_del").checked == true) $('#mmenu_del').val(1);
		//	else $('#mmenu_del').val(0);

		// customer
		if (document.getElementById("check_cust_view").checked == true) $('#cust_view').val(1);
		else $('#cust_view').val(0);
		if (document.getElementById("check_cust_new").checked == true) $('#cust_new').val(1);
		else $('#cust_new').val(0);
		if (document.getElementById("check_cust_upd").checked == true) $('#cust_upd').val(1);
		else $('#cust_upd').val(0);
		//	if(document.getElementById("check_cust_del").checked == true) $('#cust_del').val(1);
		//	else $('#cust_del').val(0);

		// Penambahan Saldo
		if (document.getElementById("check_tmbh_saldo_view").checked == true) $('#tmbh_saldo_view').val(1);
		else $('#tmbh_saldo_view').val(0);
		if (document.getElementById("check_tmbh_saldo_new").checked == true) $('#tmbh_saldo_new').val(1);
		else $('#tmbh_saldo_new').val(0);
		if (document.getElementById("check_tmbh_saldo_upd").checked == true) $('#tmbh_saldo_upd').val(1);
		else $('#tmbh_saldo_upd').val(0);
		//	if(document.getElementById("check_tmbh_saldo_del").checked == true) $('#tmbh_saldo_del').val(1);
		//	else $('#tmbh_saldo_del').val(0);

		// Pengurangan Saldo
		if (document.getElementById("check_krg_saldo_view").checked == true) $('#krg_saldo_view').val(1);
		else $('#krg_saldo_view').val(0);
		if (document.getElementById("check_krg_saldo_new").checked == true) $('#krg_saldo_new').val(1);
		else $('#krg_saldo_new').val(0);
		if (document.getElementById("check_krg_saldo_upd").checked == true) $('#krg_saldo_upd').val(1);
		else $('#krg_saldo_upd').val(0);
		//	if(document.getElementById("check_krg_saldo_del").checked == true) $('#krg_saldo_del').val(1);
		//	else $('#krg_saldo_del').val(0);

		// Lihat Saldo
		if (document.getElementById("check_lht_saldo_view").checked == true) $('#lht_saldo_view').val(1);
		else $('#lht_saldo_view').val(0);

		// Lihat Transaksi
		if (document.getElementById("check_lht_transaksi_view").checked == true) $('#lht_transaksi_view').val(1);
		else $('#lht_transaksi_view').val(0);

		// Lihat Penggunaaan E-dompet
		if (document.getElementById("check_lht_edompet_view").checked == true) $('#lht_edompet_view').val(1);
		else $('#lht_edompet_view').val(0);

		// Kelola User Type
		if (document.getElementById("check_kelola_usertype_view").checked == true) $('#kelola_usertype_view').val(1);
		else $('#kelola_usertype_view').val(0);
		if (document.getElementById("check_kelola_usertype_new").checked == true) $('#kelola_usertype_new').val(1);
		else $('#kelola_usertype_new').val(0);
		if (document.getElementById("check_kelola_usertype_upd").checked == true) $('#kelola_usertype_upd').val(1);
		else $('#kelola_usertype_upd').val(0);

		// Kelola User
		if (document.getElementById("check_user_view").checked == true) $('#user_view').val(1);
		else $('#user_view').val(0);
		if (document.getElementById("check_user_new").checked == true) $('#user_new').val(1);
		else $('#user_new').val(0);
		if (document.getElementById("check_user_upd").checked == true) $('#user_upd').val(1);
		else $('#user_upd').val(0);
	}
</script>