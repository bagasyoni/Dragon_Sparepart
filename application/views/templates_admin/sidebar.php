
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <!-- <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar"> -->

    <!-- Sidebar - Brand -->
    <!-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="sidebar-brand-icon">
       <i class="fas fa-university"></i>
      </div>
      <div class="sidebar-brand-text mx-1">INVENTORY</div>
    </a> -->

    <!-- Divider -->
    <!-- <hr class="sidebar-divider my-0"> -->

    <!-- Nav Item - Dashboard -->
    <!-- <li class="nav-item active">
      <a class="nav-link" href="<?php echo base_url('admin/dashboard') ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li> -->

    

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Inventory</span>
      </a>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
					<h6 class="collapse-header">Sub Menu Master:</h6>
					
						<ul class="navbar-nav" id="accordi">
							<li>
								<a class="collapse-item" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
								<span>Inventory</span>
								</a>
								<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordi">
									<div>
						 			 <a class="collapse-item" href="<?php echo base_url('admin/sup') ?>">Supplier</a>
									 <a class="collapse-item" href="<?php echo base_url('admin/relasi') ?>">Relasi</a>
									</div>
								</div>
							</li>
						</ul>

						 <a class="collapse-item" href="<?php echo base_url('admin/sup') ?>">Supplier</a>
             <a class="collapse-item" href="<?php echo base_url('admin/relasi') ?>">Relasi</a>
        </div>
      </div>
    </li>


    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi" aria-expanded="true" aria-controls="collapseTransaksi">
        <i class="fas fa-fw fa-cog"></i>
        <span>Transaksi</span>
      </a>
      <div id="collapseTransaksi" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Sub Menu Accounting:</h6>
             <a class="collapse-item" href="<?php echo base_url('admin/memo') ?>">Memo</a>
             <a class="collapse-item" href="<?php echo base_url('admin/kas') ?>">Kas</a>
             <a class="collapse-item" href="<?php echo base_url('admin/po') ?>">PO</a>

        </div>
      </div>
		</li>
		 -->
		<!-- Nav Item - Laporan Collapse Menu -->
		<!-- <li class="nav-item">
					<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true" aria-controls="collapseLaporan">
						<i class="fas fa-fw fa-wrench"></i>
						<span>Laporan</span>
					</a>
					<div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
						<div class="bg-white py-2 collapse-inner rounded">
							<h6 class="collapse-header">Sub-Menu Laporan:</h6>
							<a class="collapse-item" href="<?php echo base_url('admin/laporan') ?>">Check Filter</a>
				
						</div>
					</div>
				</li>    -->

    <!-- Nav Item - Utilities Collapse Menu -->
    <!-- <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Pengaturan</span>
      </a>
      <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Sub-Menu Pengatur:</h6>
          <a class="collapse-item" href="utilities-color.html">User</a>
          <a class="collapse-item" href="utilities-border.html">Menu</a>
    
        </div>
      </div>
    </li>    -->

    <!-- Nav Item - Tables -->
    <!-- <li class="nav-item">
      <a class="nav-link" href="<?php echo base_url('admin/auth/logout') ?>">
      <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span></a>
    </li> -->

    <!-- Divider -->
    <!-- <hr class="sidebar-divider d-none d-md-block"> -->

    <!-- Sidebar Toggler (Sidebar) -->
    <!-- <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> -->

  <!-- </ul> -->
  <!-- End of Sidebar -->

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

      <!-- Topbar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 sticky-top ">
        <!-- Brand -->
      <a class="navbar-brand" href="<?php echo base_url('admin/dashboard') ?>">
					<i class="fas fa-university"></i>
					INVENTORY
			</a>
        <!-- Navbar Toggle (Topbar) -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"     aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
 
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <!-- Topbar Navbar -->
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

          <!-- Nav Item - Dashboard -->
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo base_url('admin/dashboard') ?>">
              <i class="fas fa-fw fa-tachometer-alt"></i>
              <span>Dashboard</span></a>
          </li>

          <!-- Nav Item - Pages Collapse Menu -->
          <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="headingTwo" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
              <i class="fas fa-fw fa-cog"></i>
              <span>Inventory</span>
						</a>
							<ul class="dropdown-menu dropdown-menu-right shadow animated--grow-in" id="accordi">
								<a class="dropdown-item" href="<?php echo base_url('admin/sup') ?>">Supplier</a>
								<!-- <a class="dropdown-item" href="<?php //echo base_url('admin/relasi') ?>">Relasi</a> -->
								<li class="dropdown-submenu dropdown-menu-right animated--grow-in"><a class="dropdown-item dropdown-toggle" href="#">Submenu</a>
           				 <ul class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
										<a class="dropdown-item" href="<?php echo base_url('admin/sup') ?>">Supplier</a>
                       <!-- <a class="dropdown-item" href="<?php //echo base_url('admin/relasi') ?>">Relasi</a> -->
									<li class="dropdown-submenu dropdown-menu-right animated--grow-in"><a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                <ul class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                  <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                  <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                </ul>
              </li>
              <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                  <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                </ul>
              </li>
								</ul>
							</li>
						</ul>
    			</li>

					
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="flase" id="headingTransaksi">
        <i class="fas fa-fw fa-cog"></i>
        <span>Transaksi</span>
      </a>
      <div id="collapseTransaksi" class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="headingTransaksi" >
             <a class="dropdown-item" href="<?php echo base_url('admin/memo') ?>">Memo</a>
             <a class="dropdown-item" href="<?php echo base_url('admin/kas') ?>">Kas</a>
             <a class="dropdown-item" href="<?php echo base_url('admin/po') ?>">PO</a>
      </div>
		</li>
		
		<!-- Nav Item - Laporan Collapse Menu -->
		<li class="nav-item dropdown no-arrow">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="flase" id="headingLaporan">
						<i class="fas fa-fw fa-wrench"></i>
						<span>Laporan</span>
					</a>
					<div id="collapseLaporan" class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="headingLaporan">
							<a class="dropdown-item" href="<?php echo base_url('admin/laporan') ?>">Check Filter</a>
					</div>
				</li>   

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" id="headingUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Pengaturan</span>
      </a>
      <div id="collapseUtilities" class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="headingUtilities" >
          <a class="dropdown-item" href="utilities-color.html">User</a>
          <a class="dropdown-item" href="utilities-border.html">Menu</a>
      </div>
    </li>   
		
		<!-- Nav Item - Tables -->
		<li class="nav-item">
      <a class="nav-link" href="#" data-toggle="modal" data-target="#periodeModal">
      <i class="fas fa-fw fa-wrench"></i>
        <span>Ganti Periode</span></a>
		</li>
		
    <!-- Nav Item - Tables -->
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

          <!-- Nav Item - Alerts -->
          <!-- <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-bell fa-fw"></i> -->
              <!-- Counter - Alerts -->
              <!-- <span class="badge badge-danger badge-counter">3+</span>
            </a> -->
            <!-- Dropdown - Alerts -->
            <!-- <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
              <h6 class="dropdown-header">
                Alerts Center
              </h6>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                  <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                  </div>
                </div>
                <div>
                  <div class="small text-gray-500">December 12, 2019</div>
                  <span class="font-weight-bold">A new monthly report is ready to download!</span>
                </div>
              </a>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                  <div class="icon-circle bg-success">
                    <i class="fas fa-donate text-white"></i>
                  </div>
                </div>
                <div>
                  <div class="small text-gray-500">December 7, 2019</div>
                  $290.29 has been deposited into your account!
                </div>
              </a>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                  <div class="icon-circle bg-warning">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                  </div>
                </div>
                <div>
                  <div class="small text-gray-500">December 2, 2019</div>
                  Spending Alert: We've noticed unusually high spending for your account.
                </div>
              </a>
              <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
          </li> -->

          <!-- Nav Item - Messages -->
          <!-- <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-envelope fa-fw"></i> -->
              <!-- Counter - Messages -->
              <!-- <span class="badge badge-danger badge-counter">7</span>
            </a> -->
            <!-- Dropdown - Messages -->
            <!-- <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
              <h6 class="dropdown-header">
                Message Center
              </h6>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                  <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                  <div class="status-indicator bg-success"></div>
                </div>
                <div class="font-weight-bold">
                  <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                  <div class="small text-gray-500">Emily Fowler · 58m</div>
                </div>
              </a>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                  <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                  <div class="status-indicator"></div>
                </div>
                <div>
                  <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                  <div class="small text-gray-500">Jae Chun · 1d</div>
                </div>
              </a>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                  <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                  <div class="status-indicator bg-warning"></div>
                </div>
                <div>
                  <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                  <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                </div>
              </a>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                  <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                  <div class="status-indicator bg-success"></div>
                </div>
                <div>
                  <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                  <div class="small text-gray-500">Chicken the Dog · 2w</div>
                </div>
              </a>
              <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
          </li>

          <div class="topbar-divider d-none d-sm-block"></div> -->

          <!-- Nav Item - User Information -->
          <!-- <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small">Valerie Luna</span>
              <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
            </a> -->
            <!-- Dropdown - User Information -->
            <!-- <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="#">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
              </a>
              <a class="dropdown-item" href="#">
                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                Settings
              </a>
              <a class="dropdown-item" href="#">
                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                Activity Log
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
              </a>
            </div>
          </li> -->

        </ul>
			
			</nav>
			
			<!-- Modal -->
			<div class="modal fade" id="periodeModal" tabindex="-1" role="dialog" aria-labelledby="periodeLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="max-width: 250px">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="periodeLabel"> <i class="fas fa-cogs"></i>  Ganti Periode</h5>
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

      <!-- End of Topbar -->









