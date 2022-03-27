<?php
error_reporting(E_ALL);
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// echo "A";
include_once('class/tcpdf/tcpdf.php');
 //echo "B";
include_once("class/PHPJasperXML.inc.php");
 //echo "C";
include_once ('setting.php');
 //echo "D";





$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;

 //echo "E";
$PHPJasperXML->arrayParameter=array("parameter1"=>1);

 //echo "F";
$PHPJasperXML->load_xml_file("custn.jrxml");

// echo "G";

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);

 //echo "L";
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file



// echo "M";
?>
