<html lang="en">

<style>
	#myInput {
		background-image: url('<?php echo base_url() ?>assets/img/search-icon-blue.png');
		background-position: 10px 12px;
		background-repeat: no-repeat;
		width: 100%;
		padding: 12px 20px 12px 40px;
		border: 1px solid #ddd;
		margin-bottom: 12px;
	}

	#myTable {
		border-collapse: collapse;
		width: 100%;
		border: 1px solid #ddd;
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

	.text_input {
		color: black;
		text-transform: uppercase;
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
		font-weight: bold;
		color: blue;
	}

	.form-control {
		font-size: small;
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

	.alert-container {
		background-color: #00b386;
		color: black;
		font-weight: bolder;
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

	/* .container { text-align: center; vertical-align: middle;} */
	.checkbox_container {
		width: 25px;
		height: 25px;
	}

	td input[type="checkbox"] {
		float: left;
		margin: 0 auto;
		width: 100%;
	}

	.text_input {
		font-size: small;
		color: black;
	}
</style>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Dummy</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <link rel="shortcut icon" href="https://intidragonst.com/verifikasi/assets/dist/img/dr.png">

</head>

<body class="bg-gradient-primary">

  <div class="container"> <br><br><br>

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-5 col-lg-6 col-md-5">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">

              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login Administrator</h1>
                    <?php echo $this->session->flashdata('pesan') ?>
                    <?php echo $this->session->unset_userdata('pesan') ?>
                  </div>
                  <form method="post" action="<?php echo base_url('admin/auth/proses_login') ?>" class="user">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="User Name" name="username">
                      <?php echo form_error('username', '<div class="text-danger small ml-3">', '</div>') ?>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" name="password">
                      <?php echo form_error('password', '<div class="text-danger small ml-3">', '</div>') ?>
                    </div>

                    <button class="btn btn-primary btn-user btn-block">Login</button>
                    <a class="btn btn-danger btn-user btn-block" onfocusout="hitung()" data-target="#modal_list_user" data-toggle="modal" href="#list_user">List User</a>

                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#list_user').DataTable({
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

<!-- Modal List User-->
<div id="modal_list_user" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">List User Sparepart</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='list_user'>
					<thead>	
						<th>Username</th>
						<th>Program</th>
						<th>Keterangan</th>
						<th>Dragon</th>
						<th>Sub Devisi</th>
					</thead>
					<tbody>
					<?php
						$sql = "SELECT USERNAME,
								PROG,
								KET,
								DR,
								SUB
							FROM users
							WHERE PROG = 'SPAREPART'
							ORDER BY NO_ID";
						$a = $this->db->query($sql)->result();
						foreach($a as $b ) { 
					?>
						<tr>
							<td class='USERNAME text_input'><?php echo $b->USERNAME;?></td>
							<td class='PROG text_input'><?php echo $b->PROG;?></td>
							<td class='KET text_input'><?php echo $b->KET;?></td>
							<td class='DR text_input'><?php echo $b->DR;?></td>
							<td class='SUB text_input'><?php echo $b->SUB;?></td>
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

<script>
	$(document).ready(function() {
		//MyModal List User
		$('#modal_list_user').on('show.bs.modal', function(e) {
			target = $(e.relatedTarget);
		});
	});

	function hitung() {
		$('body').on('click', '.select_list_user', function() {
			var val = $(this).parents("tr").find(".USERNAME").text();
			target.parents("div").find(".USERNAME").val(val);
			var val = $(this).parents("tr").find(".PROG").text();
			target.parents("div").find(".PROG").val(val);
			var val = $(this).parents("tr").find(".KET").text();
			target.parents("div").find(".KET").val(val);
			var val = $(this).parents("tr").find(".DR").text();
			target.parents("div").find(".DR").val(val);
			var val = $(this).parents("tr").find(".SUB").text();
			target.parents("div").find(".SUB").val(val);
		});
	}

</script>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>




</body>

</html>