<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Dragon - Sparepart</title>

		<!-- coba select2 -->
		<script src="<?php echo base_url() . 'assets/js/jquery-3.5.1.min.js' ?>" type="text/javascript"></script>
		<link href="<?php echo base_url() ?>assets/vendor/select2/css/select2.css" rel="stylesheet">
		<script src="<?php echo base_url() ?>assets/vendor/select2/js/select2.min.js"></script>
		<!-- end select2 -->
	<!-- Custom fonts for this template-->
	<link href="<?php echo base_url() ?>assets/img/tiara1.ico" rel="icon" type="image/png">
	<link href="<?php echo base_url() ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="shortcut icon" href="https://intidragonst.com/verifikasi/assets/dist/img/dr.png">
	<!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->
	<!-- Custom styles for this template-->
	<link href="<?php echo base_url() ?>assets/css/sb-admin-2.min.css" rel="stylesheet">

	<link href="<?php echo base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<!-- Row Group Css -->
	<link href="<?php echo base_url() ?>assets/vendor/datatables/RowGroup-1.1.1/css/rowGroup.bootstrap4.min.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>assets/vendor/datatables/RowGroup-1.1.1/css/rowGroup.jqueryui.min.css" rel="stylesheet">

	<link href="<?php echo base_url() ?>assets/vendor/datatables/Scroller-2.0.1/css/scroller.bootstrap4.min.css" rel="stylesheet">

	<!-- Filter Group Css -->
	<!-- <link href="<?php echo base_url() ?>assets/vendor/Excel-like-Bootstrap-Table-Sorting-Filtering-Plugin/dist/excel-bootstrap-table-filter-style.css" rel="stylesheet"> -->

	<!-- <script src="<?php echo base_url() ?>assets/vendor/jquery/jquery.min.js"></script> -->

	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
	<!-- <script>
		setInterval(onl, 7000);

		function onl() {
		$.ajax({
			type: 'post',
			url: '<?php echo base_url('index.php/admin/online/in'); ?>'
		});
		}
	</script> -->

	<!-- <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
	<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"  rel="stylesheet"> -->


	<style type="text/css">
		@keyframes chartjs-render-animation {
			from {
				opacity: .99
			}

			to {
				opacity: 1
			}
		}

		.chartjs-render-monitor {
			animation: chartjs-render-animation 1ms
		}

		.chartjs-size-monitor,
		.chartjs-size-monitor-expand,
		.chartjs-size-monitor-shrink {
			position: absolute;
			direction: ltr;
			left: 0;
			top: 0;
			right: 0;
			bottom: 0;
			overflow: hidden;
			pointer-events: none;
			visibility: hidden;
			z-index: -1
		}

		.chartjs-size-monitor-expand>div {
			position: absolute;
			width: 1000000px;
			height: 1000000px;
			left: 0;
			top: 0
		}

		.chartjs-size-monitor-shrink>div {
			position: absolute;
			width: 200%;
			height: 200%;
			left: 0;
			top: 0
		}

		input[data-readonly] {
			pointer-events: none;
			background-color: #eaecf4;
		}

		/* set css dengan sesi font dan size */
		p, a, span, h4, div, label, th, td,thead {
		font-family: <?= $this->session->userdata('font') !== NULL ? $this->session->userdata('font') : 'Lucida, sans-serif;'; ?>
			font-size: <?= $this->session->userdata('size_font') ?>px
		}

		.dropdown-submenu {
			position: relative;
		}

		.dropdown-submenu a::after {
			transform: rotate(-90deg);
			position: absolute;
			right: 6px;
			top: .8em;
		}

		.dropdown-submenu .dropdown-menu {
			top: 0;
			left: 100%;
			margin-left: .1rem;
			margin-right: .1rem;
		}

		html,
		body { height: 100%; }

		.wrapper { height: 100%; }

		.input-group { flex-wrap: unset !important; }
		/* select2 */
		.select2-container .select2-selection--single .select2-selection__rendered {
			padding: .275rem .75rem !important;
		}

		.select2-container .select2-selection--single {
			height: auto !important;
		}

		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 100% !important;
		}

		/* .select2-dropdown {
			width: 500px !important;
		} */
	</style>
	</head>