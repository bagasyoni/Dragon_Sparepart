<?php

		include('phpjasperxml/class/tcpdf/tcpdf.php');
		include('phpjasperxml/class/PHPJasperXML.inc.php');
		include('phpjasperxml/setting.php');
		
		// $path1 = 'phpjasperxml/class/tcpdf/trcpdf.php';
		// $path2 = 'phpjasperxml/class/PHPJasperXML.inc.php';
		// $path3 = 'phpjasperxml/setting.php';
		
		$PHPJasperXML = new PHPJasperXML();
		
		$PHPJasperXML->load_xml_file("phpjasperxml/example.jrxml");
		$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
		$PHPJasperXML->outpage("I"); 
		
		// echo $path1;
		// echo "Path : $path2";
		// echo "Path : $path3";
		// include_once($path1);

?>
