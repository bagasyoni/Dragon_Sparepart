<?php

use \koolreport\widgets\koolphp\Card;
use \koolreport\amazing\ProgressCard;
use \koolreport\clients\Bootstrap;
?>

<style>
	.title-style {
		color: white;
		font-weight: bold;
	}

	.infoText-style {
		color: white;
		font-weight: bold;
	}
</style>

<br>

<body>
	<div class="container-fluid">
		<br>
		<div class="alert alert-success" role="alert">
			<i class="fas fa-tachometer-alt"></i> Dashboard
		</div>
		<div class="alert alert-success" role="alert">
			<h4 class="alert-heading">Selamat Datang</h4>
			<p>Selamat Datang <strong><?php echo $na_dev; ?> </strong> di Sistem Informasi kami</p>
			<p> Anda login sebagai <strong><?php echo $level; ?> </strong> </p>
			<p> Dragon <strong><?php echo $dr; ?> </strong> </p>
			<p> Sub <strong><?php echo $sub; ?> </strong> </p>
			<p> Periode <strong> <?php echo $periode; ?> </strong> </p>
			<p> PIN <strong> <?php echo $pin; ?> </strong> </p>
			<hr>
			<div class="row">
				<div class="col-xl-3 col-md-3">
					<div class="card shadow mb-5">
						<div class="card-header py-3" style="text-align: center; background-color: #f6c23e;">
							<i class="fas fa-exclamation-circle" style="color: white;"></i>
						</div>
						<div style="text-align: center;">
							<div class="report-content">
								<?php
								ProgressCard::create(array(
									"title" => "Bon Belum Diverifikasi",
									// "value"=>$VERIFIKASI_PO_SP,
									"preset" => "warning",
									// "baseValue"=>$TOTAL_VERIFIKASI_PO_SP,
									// "infoText"=>"Dari total '$TOTAL_VERIFIKASI_PO_SP' Bon keseluruhan",
									"format" => array(
										"indicator" => array(
											"decimals" => 0
										)
									),
									"cssClass" => array(
										// "icon"=>"fa fa-euro"
										"title" => "title-style",
										"infoText" => "title-style",
									),
								));
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-3">
					<div class="card shadow mb-5">
						<div class="card-header py-3" style="text-align: center; background-color: #e74a3b;">
							<i class="fas fa-exclamation-circle" style="color: white;"></i>
						</div>
						<div style="text-align: center;">
							<div class="report-content">
								<?php
								ProgressCard::create(array(
									"title" => "Bon Belum Dikerjakan Pesanan",
									// "value"=>$VERIFIKASI_PO_SP_1,
									"preset" => "danger",
									// "baseValue"=>$TOTAL_VERIFIKASI_PO_SP_1,
									// "infoText"=>"Dari total '$TOTAL_VERIFIKASI_PO_SP_1' Bon keseluruhan",
									"format" => array(
										"indicator" => array(
											"decimals" => 0
										)
									),
									"cssClass" => array(
										// "icon"=>"fa fa-euro"
										"title" => "title-style",
										"infoText" => "title-style",
									),
								));
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<body>