<?php $user_level = $this->session->userdata["level"]; ?>
<?php $super_admin = $this->session->userdata["super_admin"]; ?>

<body id="page-top">
	<!-- Page Wrapper -->
	<div id="wrapper">
		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">
			<!-- Main Content -->
			<div id="content">
				<!-- Topbar -->
				<nav class="navbar navbar-expand-lg navbar-light bg-white">
					<!-- Brand -->
					<a class="navbar-brand" href="<?php echo base_url('admin/dashboard') ?>">
						<i class="fas fa-chart-line"></i>
						SPAREPART
					</a>
					<!-- Navbar Toggle (Topbar) -->
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<!-- Topbar Navbar -->
						<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
							<?php
							if ($user_level == 'super_admin' || $super_admin == '1') {
								$query = "SELECT menu_sparepart.KODE_MENU, 
												menu_sparepart.NAMA_MENU,
												menu_sparepart.URL_MENU,
												menu_sparepart.PARENT_MENU,
												menu_sparepart.`LEVEL` 
												from menu_sparepart
												WHERE menu_sparepart.`LEVEL`='0' ";
							} else {
								$query = " SELECT menu_sparepart.KODE_MENU, 
												menu_sparepart.NAMA_MENU, 
												menu_sparepart.URL_MENU, 
												menu_sparepart.PARENT_MENU, 
												menu_sparepart.`LEVEL`  
												from menu_sparepart,(select menu_sparepart.PARENT_MENU from user_typed_sparepart,menu_sparepart where user_typed_sparepart.kode_menu=menu_sparepart.KODE_MENU and  user_typed_sparepart.user_level='$user_level' and (user_typed_sparepart.lihat='1' or user_typed_sparepart.baru='1' or user_typed_sparepart.edit='1' ) GROUP BY menu_sparepart.PARENT_MENU ) A where menu_sparepart.level=0 AND menu_sparepart.KODE_MENU=A.PARENT_MENU";
							}
							$result0 = $this->db->query($query)->result();
							foreach ($result0 as $row0) {
							?>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" id="headingTwo" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
										<i class="fas fa-fw fa-cog"></i>
										<span><?php echo $row0->NAMA_MENU ?></span>
									</a>
									<?php
									if ($user_level == 'super_admin' || $super_admin == '1') {
										$query = "SELECT menu_sparepart.KODE_MENU,
													menu_sparepart.NAMA_MENU, 
													menu_sparepart.URL_MENU, 
													menu_sparepart.PARENT_MENU, 
													menu_sparepart.`LEVEL` 
													from menu_sparepart
													WHERE menu_sparepart.`LEVEL`='1' AND menu_sparepart.PARENT_MENU='$row0->KODE_MENU' ";
									} else {
										//cek ada child atau tidak
										$query = "SELECT menu_sparepart.KODE_MENU, 
													menu_sparepart.NAMA_MENU, 
													menu_sparepart.URL_MENU, 
													menu_sparepart.PARENT_MENU, 
													menu_sparepart.`LEVEL`, 
													user_typed_sparepart.kode_menu  
													from menu_sparepart
													INNER JOIN user_typed_sparepart ON user_typed_sparepart.kode_menu=menu_sparepart.KODE_MENU 
													WHERE menu_sparepart.`LEVEL`='1' and user_typed_sparepart.user_level='" . $this->session->userdata["level"] . "' and menu_sparepart.PARENT_MENU='$row0->KODE_MENU' and  (user_typed_sparepart.lihat='1' or user_typed_sparepart.baru='1' or user_typed_sparepart.edit='1' ) ";
									}
									$result1 = $this->db->query($query)->result();
									if (count($result1) > 0) {
									?>
										<ul class="dropdown-menu dropdown-menu-right shadow animated--grow-in" id="accordi">
											<?php
											foreach ($result1 as $row1) {
												if ($user_level == 'super_admin' || $super_admin == '1') {
													$query = "SELECT menu_sparepart.KODE_MENU, 
														menu_sparepart.NAMA_MENU, 
														menu_sparepart.URL_MENU, 
														menu_sparepart.PARENT_MENU, 
														menu_sparepart.`LEVEL`  
														from menu_sparepart
														WHERE menu_sparepart.`LEVEL`='2' AND menu_sparepart.PARENT_MENU='$row1->KODE_MENU' ";
												} else {
													$query = "SELECT menu_sparepart.KODE_MENU, 
														menu_sparepart.NAMA_MENU, 
														menu_sparepart.URL_MENU, 
														menu_sparepart.PARENT_MENU, 
														menu_sparepart.`LEVEL`, 
														user_typed_sparepart.kode_menu 
														from menu_sparepart
														INNER JOIN user_typed_sparepart ON user_typed_sparepart.kode_menu=menu_sparepart.KODE_MENU 
														WHERE menu_sparepart.`LEVEL`='2' and user_typed_sparepart.user_level='" . $this->session->userdata["level"] . "' and menu_sparepart.PARENT_MENU='$row1->KODE_MENU' and (user_typed_sparepart.lihat='1' or user_typed_sparepart.baru='1' or user_typed_sparepart.edit='1' ) ";
												}
												$result2 = $this->db->query($query)->result();
												if (count($result2) <= 0) {
											?>
													<a class="dropdown-item" href="<?php echo base_url($row1->URL_MENU); ?>"><i class="fas fa-fw fa-cog"></i><?php echo $row1->NAMA_MENU; ?> </a>
												<?php
												} else {
												?>
													<li class="dropdown-submenu dropdown-menu-right animated--grow-in">
														<a class="dropdown-item dropdown-toggle" href="#">
															<i class="fas fa-fw fa-cog"></i><?php echo $row1->NAMA_MENU; ?>
														</a>
														<ul class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
															<?php
															foreach ($result2 as $row2) {
															?>
																<a class="dropdown-item" href="<?php echo base_url($row2->URL_MENU); ?>"><i class="fas fa-fw fa-cog"></i><?php echo $row2->NAMA_MENU; ?> </a>
															<?php
															}
															?>
														</ul>
													</li>
											<?php
												}
											}
											?>
										</ul>
								<?php
									}
								}
								?>
								<li class="nav-item">
									<a class="nav-link" href="#" data-toggle="modal" data-target="#periodeModal">
										<i class="fas fa-fw fa-wrench"></i>
										<span>Ganti Periode</span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#" data-toggle="modal" data-target="#fontModal">
										<i class="fas fa-fw fa-font"></i>
										<span>Ganti Font</span></a>
								</li>
								<!-- Nav Item - Tables -->
								<!-- <li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" id="headingTwo" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
										<i class="fas fa-fw fa-cog"></i>
										<span>Lain-lain</span>
									</a>
								</li> -->
								<li class="nav-item">
									<a class="nav-link" href="<?php echo base_url('admin/auth/logout') ?>">
										<i class="fas fa-sign-out-alt"></i>
										<span>Logout</span></a>
								</li>
								<!-- Nav Item - Search Dropdown (Visible Only XS) -->
								<li class="nav-item dropdown no-arrow d-sm-none">
									<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-search fa-fw"></i>
									</a>
									<!-- Dropdown - Messages -->
									<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
										<form class="form-inline mr-auto w-100 navbar-search">
											<div class="input-group">
												<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
												<div class="input-group-append">
													<button class="btn btn-primary" type="button">
														<i class="fas fa-search fa-sm"></i>
													</button>
												</div>
											</div>
										</form>
									</div>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-fw fa-bell"></i>
										<span>Notifikasi (<b>99</b>)</span>
									</a>
								</li>
						</ul>
						<span>Periode <?php echo $this->session->userdata['periode']; ?></span>
				</nav>

				<!-- Modal -->
				<div class="modal fade" id="periodeModal" tabindex="-1" role="dialog" aria-labelledby="periodeLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document" style="max-width: 250px">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="periodeLabel"> <i class="fas fa-cogs"></i> Ganti Periode</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" action="<?php echo base_url('admin/dashboard/ganti_periode') ?>" class="user">
									<div class="form-group">
										<input type="text" class="form-control form-control-user" list="month" id="bulanPeriode" placeholder="Pilih Bulan..." name="bulan">
										<datalist id="month">
											<option value='01'>01</option>
											<option value='02'>02</option>
											<option value='03'>03</option>
											<option value='04'>04</option>
											<option value='05'>05</option>
											<option value='06'>06</option>
											<option value='07'>07</option>
											<option value='08'>08</option>
											<option value='09'>09</option>
											<option value='10'>10</option>
											<option value='11'>11</option>
											<option value='12'>12</option>
										</datalist>
									</div>
									<div class="form-group">
										<input type="text" class="form-control form-control-user" id="tahunPeriode" placeholder="Tahun..." name="tahun">
									</div>

									<button class="btn btn-primary btn-user btn-block">Ubah Periode</button>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Modal font -->
				<div class="modal fade" id="fontModal" tabindex="-1" role="dialog" aria-labelledby="fontLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"> <i class="fas fa-cogs"></i> Ganti Font</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<!-- form mengambil 1 parameter, yaitu id font yang sudah di set dalam sesi -->
							<form method="post" action="<?php echo base_url('admin/dashboard/ganti_font/' . $this->session->userdata('id_font')) ?>" class="user">
								<div class="modal-body">
									<div class="form-group">
										<label>Font</label>
										<!-- dropdown font, akan selected dari data sesi saat ini -->
										<select name="font" id="font_check" class="form-control">
											<option value="Arial, sans-serif;" <?= $this->session->userdata('font') == 'Arial, sans-serif;' ? 'selected' : NULL ?>>Arial</option>
											<option value="Times New Roman, serif;" <?= $this->session->userdata('font') == 'Times New Roman, serif;' ? 'selected' : NULL ?>>Times New Roman</option>
											<option value="Tahoma, serif;" <?= $this->session->userdata('font') == 'Tahoma, serif;' ? 'selected' : NULL ?>>Tahoma</option>
											<option value="Impact, fantasy;" <?= $this->session->userdata('font') == 'Impact, fantasy;' ? 'selected' : NULL ?>>Impact</option>
										</select>
									</div>
									<div class="form-group">
										<!-- ukuran, value berdasarkan data sesi -->
										<label>Ukuran Font</label>
										<input type="number" class="form-control" name="size" value="<?= $this->session->userdata('size_font') ?>">
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary">Ganti</button>
									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End of Topbar -->
			<script>
				jQuery.extend(jQuery.expr[':'], {
					focusable: function(el, index, selector) {
						return $(el).is(':input[type="text"], select, [tabindex]');
					}
				});

				$(document).on('keydown', ':focusable', function(e) {
					if (e.which == 13) {
						e.preventDefault();
						// Get all focusable elements on the page
						var $canfocus = $(':focusable');
						var index = $canfocus.index(this) + 1;
						if (index >= $canfocus.length) index = 0;
						$canfocus.eq(index).focus();
						$(":focus").select();
					}
				});
			</script>