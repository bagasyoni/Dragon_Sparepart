<?php
error_reporting(E_ALL);
include_once('class/tcpdf/tcpdf.php');

include "class/PHPJasperXML.inc.php";
// Creating a workbook
include ('setting.php');
//$xml =  simplexml_load_file("sample9.jrxml");





$PHPJasperXML = new PHPJasperXML("en","XLS");
echo "7";
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter=array("parameter1"=>0);
echo "8";
$PHPJasperXML->load_xml_file("sample4.jrxml");
echo "9";

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
echo "10";
$PHPJasperXML->outpage("D","sample4.xls");    //page output method I:standard output  D:Download file

echo "11";
echo "A";
die(0);
/*

// sending HTTP headers

// Creating a worksheet

// The actual data
$worksheet->write(0, 0, 'Name');
$worksheet->write(0, 1, 'Age');
$worksheet->write(1, 0, 'John Smith');
$worksheet->write(1, 1, 30);
$worksheet->write(2, 0, 'Johann Schmidt');
$worksheet->write(2, 1, 31);
$worksheet->write(3, 0, 'Juan Herrera');
$worksheet->write(3, 1, 32);

// Let's send the file
$workbook->close();
*/
 
?>
