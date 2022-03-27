<div class="container-fluid">
	<div class="alert alert-success" role="alert">

		<!-- Ganti 1 -->

		<i class="fas fa-university"></i> Input Master Menu
	</div>
	<br>

	<form method="post" action="<?php echo base_url('admin/mmenu/input_aksi') ?>">
		<div class="form-group">
			<label>Kode Menu</label>
			<input type="text" name="KODE_MENU" class="form-control">
			<?php echo form_error('KODE_MENU', '<div class="text-danger small" ml-3> ') ?>
		</div>
		<div class="form-group">
			<label>Nama Menu</label>
			<input type="text" name="NAMA_MENU" class="form-control">
			<?php echo form_error('NAMA_MENU', '<div class="text-danger small" ml-3> ') ?>
		</div>
		<div class="form-group">
			<label>Level Menu</label>
			<input type="text" name="LEVEL" class="form-control">
			<?php echo form_error('LEVEL', '<div class="text-danger small" ml-3> ') ?>
		</div>
		<div class="form-group">
			<label class="col-sm-2 col-form-label">Parent Menu</label>
			<div class="col-sm-4 nopadding">
				<select class="form-control" name="PARENT_MENU" id="PARENT_MENU" style="width: 100%;">
					<?php
					echo '<option value="">Please Select</option>';
					foreach ($MENU->result_array() as $k) {
						if ($_POST["KODE_MENU"] == $k['KODE_MENU']) {
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
			<input type="text" name="URL_MENU" class="form-control">
			<?php echo form_error('URL_MENU', '<div class="text-danger small" ml-3> ') ?>
		</div>

		<div class="form-group">
			<button type="submit" class="act-btn2 btn btn-success">Simpan</button>
			<a type="button" href="<?php echo base_url('admin/mmenu/index_mmenu') ?>" class="act-btn btn btn-danger">Cancel</a>
		</div>

	</form>
</div>
<script>
	$(document).ready(function() {});
</script>