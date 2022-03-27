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
					<th>Kode</th>
					<th>Nama Menu </th>
					<th>View </th>
					<th>New </th>
					<th>Update </th>

				</tr>
			</thead>
			<tbody>
				<?php

				$no = 0;
				foreach ($user_type as $rowh) {
				?>

					<?php
					if ($rowh->LEVEL == 0) {
					?>

						<tr>
							<td width="20px"> <?php echo $no; ?></td>
							<input type='hidden' class='form-control' id="no_id<?php echo $no; ?>" name='no_id[]' value="<?php echo $rowh->no_id; ?>">
							<input type='hidden' class='form-control' id="kode_menu<?php echo $no; ?>" name='kode_menu[]' value="<?php echo $rowh->kode_menu; ?>">
							<input type='hidden' class='form-control' id="nama_menu<?php echo $no; ?>" name='nama_menu[]' value="<?php echo $rowh->nama_menu; ?>">

							<td width="20px"> <?php echo $rowh->kode_menu; ?></td>
							<td><?php echo $rowh->nama_menu; ?> </td>
							<td></td>
							<td></td>
							<td></td>

						</tr>

					<?php
					} else {

					?>
						<tr>
							<td width="20px"> <?php echo $no; ?></td>
							<input type='hidden' class='form-control' id="no_id<?php echo $no; ?>" name='no_id[]' value="<?php echo $rowh->no_id; ?>">
							<input type='hidden' class='form-control' id="kode_menu<?php echo $no; ?>" name='kode_menu[]' value="<?php echo $rowh->kode_menu; ?>">
							<input type='hidden' class='form-control' id="nama_menu<?php echo $no; ?>" name='nama_menu[]' value="<?php echo $rowh->nama_menu; ?>">

							<td width="20px"> <?php echo $rowh->kode_menu; ?></td>
							<td> <?php echo $rowh->nama_menu; ?> </td>
							<td><input type="checkbox" name="lihat[]" id="lihat<?php echo $no; ?>" value="<?php echo $rowh->kode_menu; ?>" <?php if ($rowh->lihat == 1) {
																																				echo "checked";
																																			}; ?>></td>
							<td><input type="checkbox" name="baru[]" id="baru<?php echo $no; ?>" value="<?php echo $rowh->kode_menu; ?>" <?php if ($rowh->baru == 1) {
																																				echo "checked";
																																			}; ?>></td>

							<td><input type="checkbox" name="edit[]" id="edit<?php echo $no; ?>" value="<?php echo $rowh->kode_menu; ?>" <?php if ($rowh->edit == 1) {
																																				echo "checked";
																																			}; ?>></td>


						</tr>

				<?php
					}
					$no++;
				};
				?>





			</tbody>
		</table>

		<div class="form-group">
			<button type="submit" class="act-btn2 btn btn-success">Simpan</button>
			<a type="button" href="<?php echo base_url('admin/user_type/index_user_type') ?>" class="act-btn btn btn-danger">Cancel</a>
		</div>
	</form>
</div>