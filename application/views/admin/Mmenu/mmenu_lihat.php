<?php $kode_menu = $this->session->userdata['kode_menu']; ?>
<?php $level = $this->session->userdata['level']; ?>
<?php
foreach ($MMENU as $rowh) {
};
?>
<div class="container-fluid">
	<div class="alert alert-success" role="alert">

		<!-- Ganti 1 -->

		<i class="fas fa-university"></i> Update Master User Type
	</div>
	<br>

	<!-- Ganti 2 -->

	<form method="post" action="">
		<div class="form-group">
			<label>Kode Menu</label>
			<input type="hidden" name="ID" class="form-control" value="<?php echo $rowh->NO_ID ?>">
			<input type="text" name="KODE_MENU" class="form-control" value="<?php echo $rowh->KODE_MENU ?>" readonly>
			<?php echo form_error('KODE_MENU', '<div class="text-danger small" ml-3> ') ?>
		</div>
		<div class="form-group">
			<label>Nama Menu</label>
			<input type="text" name="NAMA_MENU" class="form-control" value="<?php echo $rowh->NAMA_MENU ?> " readonly>
			<?php echo form_error('NAMA_MENU', '<div class="text-danger small" ml-3> ') ?>
		</div>
		<div class="form-group">
			<label>Level Menu</label>
			<input type="text" name="LEVEL" class="form-control" value="<?php echo $rowh->LEVEL ?>" readonly>
			<?php echo form_error('LEVEL', '<div class="text-danger small" ml-3> ') ?>
		</div>
		<div class="form-group">
			<label class="col-sm-2 col-form-label">Parent Menu</label>
			<div class="col-sm-4 nopadding">
				<select class="form-control" name="PARENT_MENU" id="PARENT_MENU" style="width: 100%;" readonly>
					<?php
					echo '<option value="">Please Select</option>';
					foreach ($MENU->result_array() as $k) {
						if ($rowh->PARENT_MENU == $k['KODE_MENU']) {
							echo '<option value="' . $k['KODE_MENU'] . '" selected >' . $k['KODE_MENU'] . ' - ' . $k['NAMA_MENU'] . '</option>';
						} else
							echo '<option value="' . $k['KODE_MENU'] . '" >' . $k['KODE_MENU'] . ' - ' . $k['NAMA_MENU'] . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label>URL Menu</label>
			<input type="text" name="URL_MENU" class="form-control" value="<?php echo $rowh->URL_MENU ?>" readonly>
			<?php echo form_error('URL_MENU', '<div class="text-danger small" ml-3> ') ?>
		</div>

		<div class="form-group">
			<a type="button" href="<?php echo base_url('admin/mmenu/index_mmenu') ?>" class="act-btn btn btn-danger">Cancel</a>
		</div>

	</form>
</div>