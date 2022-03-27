<div class="container-fluid">
	<div class="alert alert-success" role="alert">

		<!-- Ganti 1 -->

		<i class="fas fa-university"></i> Input Master User
	</div>
	<br>

	<form method="post" action="<?php echo base_url('admin/user/input_aksi') ?>">
		<div class="form-group">
			<label>Username</label>
			<input type="email" id="USERNAME" name="USERNAME" class="form-control" placeholder="Email Address" value=<?= set_value('USERNAME'); ?>>
			<?= form_error('USERNAME', '<small class="text-danger pl-3" ?>', '</small>'); ?>
		</div>

		<div class="form-group row">

			<!-- pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" -->
			<div class="form-group col-sm-6">
				<label>Password ( Password ada Simbul,Huruf Besar,Huruf Kecil,Jumlah 8 )</label>
				<input type="password" id="PASSWORD" name="PASSWORD" class="form-control" placeholder="Password" value=<?= set_value('PASSWORD'); ?> pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
				<?= form_error('PASSWORD', '<small class="text-danger pl-3" ?>', '</small>'); ?>
			</div>

			<div class=" form-group col-sm-6">
				<label>Confirm Password</label>
				<input type="password" id="PASSWORD2" name="PASSWORD2" class="form-control" placeholder="Password" value=<?= set_value('PASSWORD2'); ?> pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
				<?= form_error('PASSWORD2', '<small class="text-danger pl-3" ?>', '</small>'); ?>
			</div>

		</div>

		<div class=" form-group">
			<label>Nama</label>
			<input type="text" id="NAMA" name="NAMA" class="form-control" placeholder="Nama" value=<?= set_value('NAMA'); ?>>
			<?= form_error('NAMA', '<small class="text-danger pl-3" ?>', '</small>'); ?>
		</div>

		<div class="form-group">
			<label>Telpon</label>
			<input type="text" id="TELPON" name="TELPON" class="form-control" placeholder="Telpon" value=<?= set_value('TELPON'); ?>>
			<?= form_error('TELPON', '<small class="text-danger pl-3" ?>', '</small>'); ?>
		</div>

		<div class="form-group">
			<label>HP</label>
			<input type="text" id="HP" name="HP" class="form-control" placeholder="Hp" value=<?= set_value('HP'); ?>>
			<?= form_error('HP', '<small class="text-danger pl-3" ?>', '</small>'); ?>
		</div>

		<div class="form-group">
			<label class="col-sm-2 col-form-label">Akses</label>
			<div class="col-sm-4 nopadding">
				<select class="form-control" name="AKSES" id="AKSES" style="width: 100%;" value=<?= set_value('AKSES'); ?>>
					<?php
					echo '<option value="">Please Select</option>';
					foreach ($AKSES->result_array() as $k) {
						if ($_POST["AKSES"] == $k['user_level']) {
							echo '<option value="' . $k['user_level'] . '" selected >' . $k['user_level'] . '</option>';
						} else
							echo '<option value="' . $k['user_level'] . '" >' . $k['user_level'] . '</option>';
					}
					?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<button type="submit" class="act-btn2 btn btn-success">Simpan</button>
			<a type="button" href="<?php echo base_url('admin/user/index_user') ?>" class="act-btn btn btn-danger">Cancel</a>
		</div>

	</form>
</div>
<script>
	$(document).ready(function() {});
</script>