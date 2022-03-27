<body>
	<div class="container-fluid">
		<div class="alert alert-success" role="alert">
			<i class="fas fa-tachometer-alt"></i>  Pengaturan Akses
		</div>

	<form id="frmhp" action="<?php echo base_url('admin/akses/update_akses');?>" class="form-horizontal" method="post">
	<button type="submit" class="btn btn-md btn-secondary" id="proses" name="proses" > Proses </button>
	<br>
	<br>
	<table id="example" class="table table-bordered table-striped table-hover " style="width:100%; font-size: 13px">
        <thead>
        <tr>

            <th>Username</th>
            <th>Create</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Print</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
         <?php
			$no=0;
			foreach ($akses as $aksesp ): ?>
        <tr>
            <td><?php echo $aksesp->USERNAME ?><input type="hidden" name="id[]" value="<?php echo $aksesp->NO_ID ?>" ></td>
			<td>
				<input type='hidden' name='checkC[]' value="0">
				<input type='checkbox' name='checkC[]' value="1" <?php if($aksesp->CREATE == '1') echo 'checked'; ?>>
			</td>
			<td>
				<input type='hidden' name='checkE[]' value="0">
				<input type='checkbox' name='checkE[]' value="1" <?php if($aksesp->EDIT == '1') echo 'checked'; ?>>
			</td>
            <td>
				<input type='hidden' name='checkD[]' value="0">
				<input type='checkbox' name='checkD[]' value="1" <?php if($aksesp->DELETE == '1') echo 'checked'; ?>>
			</td>			
            <td>
				<input type='hidden' name='checkP[]' value="0">
				<input type='checkbox' name='checkP[]' value="1" <?php if($aksesp->PRINT == '1') echo 'checked'; ?>>
			</td>			
        </tr>
		<?php 
			$no++;
			endforeach; ?> 
        </tbody>
	</table>

	</form>
	</div>
<body>

