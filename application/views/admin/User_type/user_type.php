<?php $kode_menu = $this->session->userdata['kode_menu']; ?>
<?php $level = $this->session->userdata['level']; ?>
<?php $super_admin = $this->session->userdata['super_admin']; ?>
<section>
	<div class="container-fluid">

		<div class="alert alert-success" role="alert">
			<i class="fas fa-university"></i> <?php echo $this->session->userdata['judul']; ?>
		</div>

		<?php echo $this->session->flashdata('pesan') ?>

		<!-- Ganti 1 -->

		<div class="row">
			<div class="col-md-5">
				<h5>Result : <?= $total_rows; ?> </h5>
			</div>
			<div class="col-md-5">
				<form action="<?= base_url('admin/user_type/index_user_type'); ?>" method="post">
					<div class="input-group mb-3">
						<input type="text" class="form-control" placeholder="Search keyword.." name="keyword" value="<?php echo $this->session->userdata['keyword_user_type']; ?>" autocomplate="off" autofocus>
						<div class="input-group-append">
							<input class="btn btn-primary" type="submit" name="submit" value="search">
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-2">

				<?php
				// hak akses untuk create baru
				$user = $this->session->userdata['username'];
				$query = " SELECT * from user_typed where kode_menu='$kode_menu' and user_level='$level' and baru='1' ";
				$result0 = $this->db->query($query)->result();
				if (count($result0) == 1 || $level == 'super_admin' || $super_admin == '1') {
				?>
					<a class="btn  btn-md btn-success" href="<?php echo base_url('admin/user_type/input') ?>"> <i class="fas fa-plus fa-sm md-3"></i></a>
				<?php
				}
				?>

				<?php
				// hak akses untuk delete
				// $query1 = " SELECT * from user_typed where kode_menu='$kode_menu' and user_level='$level' and hapus='1' ";
				// $result1 = $this->db->query($query1)->result();
				// if(count($result1)==1){
				?>
				<!-- <button  type="submit" class="btn btn-md btn-danger" onclick="return confirm(' Are Your Sure? ')"> <i class="fas fa-trash fa-sm md-3"></i></button>  -->
				<?php
				// } 
				?>
			</div>

		</div>

		<form method="post" action="<?php echo base_url('admin/user_type/delete_multiple') ?>">

			<table id="example" class="table table-bordered table-striped table-hover " style="width:100%; font-size: 13px">
				<thead>
					<tr>
						<th id='no_id'>No</th>
						<th></th>
						<th id='user_level'>User Level</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($user_type)) : ?>

						<div class="alert alert-danger" role="alert">
							Data Not Found!!
						</div>


					<?php endif ?>
					<?php
					foreach ($user_type as $user_typep) : ?>
						<tr>
							<td><?php echo $user_typep->no_id ?></td>
							<td>
								<div class="dropdown">
									<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									</a>

									<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
										<?php
										// hak akses untuk edit
										$query2 = " SELECT * from user_typed where kode_menu='$kode_menu' and user_level='$level' and edit='1' ";
										$result2 = $this->db->query($query2)->result();
										if (count($result2) == 1 || $level == 'super_admin' || $super_admin == '1') {
										?>
											<a class="dropdown-item" href="update/<?php echo $user_typep->no_id ?>"> <i class="fa fa-edit"></i> Edit</a>
										<?php } ?>
										<?php
										// hak akses untuk edit
										$query2 = " SELECT * from user_typed where kode_menu='$kode_menu' and user_level='$level' and lihat='1' ";
										$result2 = $this->db->query($query2)->result();
										if (count($result2) == 1 || $level == 'super_admin' || $super_admin == '1') {
										?>
											<a class="dropdown-item" href="lihat/<?php echo $user_typep->no_id ?>"> <i class="fa fa-edit"></i> Lihat</a>
										<?php } ?>
									</div>
								</div>

							</td>
							<td><?php echo $user_typep->user_level ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?= $this->pagination->create_links(); ?>

		</form>


	</div>

	<?php

	//<!-- Ganti 6 -->

	foreach ($user_type as $user_typep) :
	?>
		<!-- ============ MODAL HAPUS =============== -->

		<!-- Ganti 7 -->

		<div class="modal fade" id="modal_hapus<?php echo $user_typep->no_id; ?>" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">

					<!-- Ganti 8 -->

					<form class="form-horizontal" method="post" action="<?php echo base_url('admin/po/delete/' . $user_typep->no_id) ?>">
						<div class="modal-body">
							<p>Delete this record ? </p>
						</div>
						<div class="modal-footer">

							<!-- Ganti 9 -->
							<input type="hidden" name="no_id" value="<?php echo $user_typep->no_id; ?>">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
							<button class="btn btn-danger">Delete</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<!--END MODAL HAPUS -->
</section>

<script type="text/javascript">
	$(document).ready(function() {

		var table = document.getElementById("example");
		var thead = table.getElementsByTagName("thead")[0];
		var tbody = table.getElementsByTagName("tbody")[0];

		thead.onclick = function(e) {
			e = e || window.event;
			var th = e.target || e.srcElement; //assumes there are no other elements in the th
			//    alert(th.id);
			var order = th.id;

			$.ajax({
				type: 'get',
				url: '<?php echo base_url('index.php/admin/user_type/getOrder'); ?>',
				data: {
					order: order
				},
				success: function(response) {
					console.log(response);
					window.location.href = '<?php echo base_url('index.php/admin/user_type/index_user_type'); ?>';

				}
			});

		}

		var ord = '<?php echo $this->session->userdata['order_user_type'] ?>';
		document.getElementById(ord).style.backgroundColor = 'yellow';

		$('.collapse').on('show.bs.collapse', function() {
			$('.collapse.in').collapse('hide');
		});

		$(".clickable-row").click(function() {
			window.location = $(this).data("href");
		});

	});
</script>