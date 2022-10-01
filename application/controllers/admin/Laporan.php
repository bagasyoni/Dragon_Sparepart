<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require FCPATH.'/vendor/autoload.php';
include BASEPATH . "/../koolreport/core/autoload.php";

use PHPJasper\PHPJasper;
use \koolreport\processes\ColumnMeta;
use \koolreport\processes\DateTimeFormat;
use \koolreport\processes\CopyColumn;
use \koolreport\processes\Group;
use \koolreport\processes\Filter;
use \koolreport\processes\ValueMap;
use \koolreport\pivot\processes\Pivot;
use \koolreport\pivot\PivotExcelExport;
use \koolreport\pivot\processes\PivotExtract;

class MyReport extends \koolreport\KoolReport
{
	use \koolreport\export\Exportable;
}

class Laporan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		$this->load->helper('file');
		if (!isset($this->session->userdata['username'])) {
			$this->session->set_flashdata(
				'pesan',
				'<div class="alert alert-danger alert-dismissible fade show" role="alert">
					Anda Belum Login
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>'
			);
			redirect('admin/auth');
		}
	}

	public function index()
	{
		$tgl1 = date("Y-m-d", strtotime($this->input->post('TGL1', TRUE)));
		$tgl2 = date("Y-m-d", strtotime($this->input->post('TGL2', TRUE)));
		$q1 = "select A.NO_ID AS ID,A.NO_BUKTI,A.TGL,A.MERK,A.NAMA,A.TOTAL AS TTOTAL, A.PPN, B.REC,B.NO_FAKTUR, 
		B.TOTAL,B.NO_ID  from piu_copy A,piud_copy B where A.NO_ID=B.ID and A.TGL>='$tgl1' and A.TGL<='$tgl2' ";
		$data['piu'] = $this->db->query($q1)->result();
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/report/laporan', $data);
		$this->load->view('templates_admin/footer_report');
	}

	public function index_MasterBarang()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Master_Barang.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$kd_bhn_1 = $this->input->post('KD_BHN_1');
			$kd_bhn_2 = $this->input->post('KD_BHN_2');
			$query = "SELECT bhn.KD_BHN AS KD_BHN,
					bhn.NA_BHN AS NA_BHN,
					bhn.SATUAN AS SATUAN,
					CASE bhn.AKTIF
						WHEN '0' THEN 'TIDAK AKTIF'
						WHEN '1' THEN 'AKTIF'
					END AS 'STATUS'
				FROM bhn
				WHERE bhn.DR='$dr'
				AND bhn.FLAG='SP'
				AND bhn.SUB='$sub'
				-- AND bhn.KD_BHN BETWEEN '$kd_bhn_1' AND '$kd_bhn_2'
				ORDER BY bhn.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'KD_BHN_1' => set_value('KD_BHN_1'),
				'KD_BHN_2' => set_value('KD_BHN_2'),
			);
			$data['master_barang'] = $this->laporan_model->tampil_data_master_barang()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Master_Barang', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_MasterBagian()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Master_Bagian.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$query = "SELECT no_bukti AS NO_BUKTI,
				kode AS KD_BAG,
				bagian AS NA_BAG,
				nama AS NAMA,
				total_qty AS TOTAL_QTY
				FROM sp_bagian 
				WHERE dr = '$dr'";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BAG" => $row1["KD_BAG"],
					"NA_BAG" => $row1["NA_BAG"],
					"NAMA" => $row1["NAMA"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'KD_BHN_1' => set_value('KD_BHN_1'),
				'KD_BHN_2' => set_value('KD_BHN_2'),
			);
			$data['master_bagian'] = $this->laporan_model->tampil_data_master_bagian()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Master_Bagian', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Triwulan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Triwulan_Inventaris.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$query = "SELECT
					inventaris.NO_BUKTI,
					inventaris.NA_BAGIAN AS BAGIAN,
					inventaris.KD_BAGIAN AS KODE,
					inventaris.NAMA,
					inventaris.TGL,
					inventarisd.JENIS,
					inventarisd.MERK,
					inventarisd.SATUAN,
					inventarisd.QTY,
					inventarisd.KET
				FROM
					inventaris,
					inventarisd
				WHERE
					inventaris.NO_BUKTI = inventarisd.NO_BUKTI
				AND
					inventaris.FLAG = 'INV'
				ORDER BY
					inventarisd.NO_BUKTI,
					inventarisd.JENIS";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"BAGIAN" => $row1["BAGIAN"],
					"KODE" => $row1["KODE"],
					"NAMA" => $row1["NAMA"],
					"TGL" => $row1["TGL"],
					"JENIS" => $row1["JENIS"],
					"MERK" => $row1["MERK"],
					"SATUAN" => $row1["SATUAN"],
					"QTY" => $row1["QTY"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array();
			$data['triwulan'] = $this->laporan_model->tampil_data_triwulan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Triwulan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_BarangPerRuangan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Triwulan_inventaris.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$jenis_1 = $this->input->post('JENIS_1');
			$query = "SELECT
				inventaris.KD_BAGIAN,
				inventaris.NAMA,
				inventaris.NA_BAGIAN,
				inventarisd.JENIS,
				inventarisd.MERK,
				inventarisd.SATUAN,
				inventarisd.QTY,
				inventarisd.KET,
				DATE(NOW()) as TGL
			FROM
				inventaris,
				inventarisd
			WHERE
				inventaris.NO_BUKTI = inventarisd.NO_BUKTI
			AND
				inventaris.FLAG = 'INV'
			AND
				inventarisd.JENIS <> ' '
			ORDER BY
				inventarisd.NO_BUKTI,
				inventarisd.JENIS";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"KODE" => $row1["KD_BAGIAN"],
					"NAMA" => $row1["NAMA"],
					"TGL" => date("Y-m-d", strtotime($row1["TGL"])),
					"JENIS" => $row1["JENIS"],
					"MERK" => $row1["MERK"],
					"SATUAN" => $row1["SATUAN"],
					"QTY" => $row1["QTY"],
					"KET" => $row1["KET"],
					"BAGIAN" => $row1["NA_BAGIAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array();
			$data['barang_per_ruangan'] = $this->laporan_model->tampil_data_barang_per_ruangan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_BarangPerRuangan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_InventarisCetakan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Inventaris_Cetakan.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$cetak_1 = $this->input->post('CETAK_1');
			$query = "SELECT * FROM sp_invenc WHERE DR='$dr' AND CETAK='$cetak_1'";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"CETAK" => $row1["CETAK"],
					"NAMA" => $row1["NAMA"],
					"KODE" => $row1["KODE"],
					"N1" => $row1["N1"],
					"J1" => $row1["J1"],
					"N2" => $row1["N2"],
					"J2" => $row1["J2"],
					"N3" => $row1["N3"],
					"J3" => $row1["J3"],
					"N4" => $row1["N4"],
					"J4" => $row1["J4"],
					"N5" => $row1["N5"],
					"J5" => $row1["J5"],
					"N6" => $row1["N6"],
					"J6" => $row1["J6"],
					"N7" => $row1["N7"],
					"J7" => $row1["J7"],
					"N8" => $row1["N8"],
					"J8" => $row1["J8"],
					"N9" => $row1["N9"],
					"J9" => $row1["J9"],
					"N10" => $row1["N10"],
					"J10" => $row1["J10"],
					"N11" => $row1["N11"],
					"J11" => $row1["J11"],
					"N12" => $row1["N12"],
					"J12" => $row1["J12"],
					"N13" => $row1["N13"],
					"J13" => $row1["J13"],
					"N14" => $row1["N14"],
					"J14" => $row1["J14"],
					"N15" => $row1["N15"],
					"J15" => $row1["J15"],
					"N16" => $row1["N16"],
					"J16" => $row1["J16"],
					"N17" => $row1["N17"],
					"J17" => $row1["J17"],
					"N18" => $row1["N18"],
					"J18" => $row1["J18"],
					"N19" => $row1["N19"],
					"J19" => $row1["J19"],
					"N20" => $row1["N20"],
					"J20" => $row1["J20"],
					"KET1" => $row1["KET1"],
					"KET2" => $row1["KET2"],
					"KET3" => $row1["KET3"],
					"JUMLAH" => $row1["JUMLAH"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'CETAK_1' => set_value('CETAK_1'),
			);
			$data['inventaris_cetakan'] = $this->laporan_model->tampil_data_inventaris_cetakan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_InventarisCetakan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_InventarisCetakanPerNama()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Inventaris_Cetakan_Per_Nama.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$cetak_1 = $this->input->post('CETAK_1');
			$query = "SELECT * FROM sp_invenc WHERE DR='$dr' AND CETAK='$cetak_1'";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"CETAK" => $row1["CETAK"],
					"NAMA" => $row1["NAMA"],
					"KODE" => $row1["KODE"],
					"N1" => $row1["N1"],
					"J1" => $row1["J1"],
					"N2" => $row1["N2"],
					"J2" => $row1["J2"],
					"N3" => $row1["N3"],
					"J3" => $row1["J3"],
					"N4" => $row1["N4"],
					"J4" => $row1["J4"],
					"N5" => $row1["N5"],
					"J5" => $row1["J5"],
					"N6" => $row1["N6"],
					"J6" => $row1["J6"],
					"N7" => $row1["N7"],
					"J7" => $row1["J7"],
					"N8" => $row1["N8"],
					"J8" => $row1["J8"],
					"N9" => $row1["N9"],
					"J9" => $row1["J9"],
					"N10" => $row1["N10"],
					"J10" => $row1["J10"],
					"N11" => $row1["N11"],
					"J11" => $row1["J11"],
					"N12" => $row1["N12"],
					"J12" => $row1["J12"],
					"N13" => $row1["N13"],
					"J13" => $row1["J13"],
					"N14" => $row1["N14"],
					"J14" => $row1["J14"],
					"N15" => $row1["N15"],
					"J15" => $row1["J15"],
					"N16" => $row1["N16"],
					"J16" => $row1["J16"],
					"N17" => $row1["N17"],
					"J17" => $row1["J17"],
					"N18" => $row1["N18"],
					"J18" => $row1["J18"],
					"N19" => $row1["N19"],
					"J19" => $row1["J19"],
					"N20" => $row1["N20"],
					"J20" => $row1["J20"],
					"KET1" => $row1["KET1"],
					"KET2" => $row1["KET2"],
					"KET3" => $row1["KET3"],
					"JUMLAH" => $row1["JUMLAH"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'CETAK_1' => set_value('CETAK_1'),
			);
			$data['inventaris_cetakan_pernama'] = $this->laporan_model->tampil_data_inventaris_cetakan_pernama()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_InventarisCetakanPerNama', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_GlobalCetakan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Inventaris_Cetakan_SP2.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$cetak_1 = $this->input->post('CETAK_1');
			$filter_cetak = " ";
			if ($this->input->post('CETAK_1', TRUE) != '') {
				$filter_cetak = "AND sp_invenc.cetak = '$cetak_1'";
			}
			$query = "SELECT *	FROM sp_invenc
			WHERE DR = '$dr'
			$filter_cetak
			ORDER BY sp_invenc.CETAK";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"CETAK" => $row1["CETAK"],
					"NAMA" => $row1["NAMA"],
					"KODE" => $row1["KODE"],
					"N1" => $row1["N1"],
					"J1" => $row1["J1"],
					"N2" => $row1["N2"],
					"J2" => $row1["J2"],
					"N3" => $row1["N3"],
					"J3" => $row1["J3"],
					"N4" => $row1["N4"],
					"J4" => $row1["J4"],
					"N5" => $row1["N5"],
					"J5" => $row1["J5"],
					"N6" => $row1["N6"],
					"J6" => $row1["J6"],
					"N7" => $row1["N7"],
					"J7" => $row1["J7"],
					"N8" => $row1["N8"],
					"J8" => $row1["J8"],
					"N9" => $row1["N9"],
					"J9" => $row1["J9"],
					"N10" => $row1["N10"],
					"J10" => $row1["J10"],
					"N11" => $row1["N11"],
					"J11" => $row1["J11"],
					"N12" => $row1["N12"],
					"J12" => $row1["J12"],
					"N13" => $row1["N13"],
					"J13" => $row1["J13"],
					"N14" => $row1["N14"],
					"J14" => $row1["J14"],
					"N15" => $row1["N15"],
					"J15" => $row1["J15"],
					"N16" => $row1["N16"],
					"J16" => $row1["J16"],
					"N17" => $row1["N17"],
					"J17" => $row1["J17"],
					"N18" => $row1["N18"],
					"J18" => $row1["J18"],
					"N19" => $row1["N19"],
					"J19" => $row1["J19"],
					"N20" => $row1["N20"],
					"J20" => $row1["J20"],
					"KET1" => $row1["KET1"],
					"KET2" => $row1["KET2"],
					"KET3" => $row1["KET3"],
					"JUMLAH" => $row1["JUMLAH"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'CETAK_1' => set_value('CETAK_1'),
			);
			$data['global_cetakan'] = $this->laporan_model->tampil_data_globalcetakan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_GlobalCetakan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_KartuStok()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Kartu_Stok.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$rak_1 = $this->input->post('RAK_1');
			$per_1 = $this->input->post('PER_1');
			if ($per_1 == '') {
				$per_1 = $this->session->userdata['periode'];
			} else {
				$per_1 = $this->input->post('PER_1');
			}
			$query = "CALL spp_kartustok('$rak_1', '$dr', '$sub', '$per_1')";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"RAK" => $row1["RAK"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL" => $row1["TGL"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"AW" => $row1["AW"],
					"MA" => $row1["MA"],
					"KE" => $row1["KE"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'RAK_1' => set_value('RAK_1'),
				'PER_1' => set_value('PER_1'),
			);
			$data['kartustok'] = $this->laporan_model->tampil_data_kartustok()->result();
			mysqli_next_result($this->db->conn_id);
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/KartuStok', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_KartuStokAtk()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Kartu_Stok_ATK.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$rak_1 = $this->input->post('RAK_1');
			$per = $this->session->userdata['periode'];
			$query = "CALL spp_kartustok_atk('$rak_1', '$dr', '$sub', '$per')";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"RAK" => $row1["RAK"],
					"KD_BHN" => $row1["KD_BHN"],
					"TGL" => $row1["TGL"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"AW" => $row1["AW"],
					"MA" => $row1["MA"],
					"KE" => $row1["KE"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'RAK_1' => set_value('RAK_1'),
			);
			$data['kartustok_atk'] = $this->laporan_model->tampil_data_kartustok_atk()->result();
			mysqli_next_result($this->db->conn_id);
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/KartuStok_Atk', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_LPB_Harian()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_LPB_Harian.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$query = "SELECT beli.PER AS PER,
					belid.TGL AS TGL,
					belid.NO_BELI AS NO_BUKTI_BL_BELI,
					CONCAT(belid.KD_BHN,' - ',belid.NA_BHN) AS BARANG,
					belid.SATUAN AS SATUAN,
					belid.QTY AS QTY,
					belid.NO_PO AS NO_PO,
					belid.NO_PP AS NO_PP,
					belid.NO_BELI AS NO_BUKTI,
					belid.REC AS REC
				FROM beli, belid
				WHERE beli.NO_BELI = belid.NO_BELI
				AND belid.TGL='$tgl_1'
				AND beli.SUB='$sub'
				AND beli.FLAG2='SP'
				ORDER BY belid.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"NO_BUKTI_BL_BELI" => $row1["NO_BUKTI_BL_BELI"],
					"BARANG" => $row1["BARANG"],
					"SATUAN" => $row1["SATUAN"],
					"QTY" => $row1["QTY"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"PER" => $row1["PER"],
					"TGL" => $row1["TGL"],
					"NO_PO" => $row1["NO_PO"],
					"REC" => $row1["REC"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
			);
			$data['lpb_harian'] = $this->laporan_model->tampil_data_lpb_harian()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/LPB_Harian', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Harian()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Harian.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$tgl_1 = $this->input->post('TGL_1');
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			if ($tgl_1 == '') {
				$bulan = Date('m');
			} else {
				$bulan = date("m", strtotime($tgl_1));
			}
			$tahun = substr($this->input->post('TGL_1'), -4);
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$query = "SELECT TGL, RAK, NA_BHN, SATUAN, AW, NO_BUKTI_MA, MA, NO_BUKTI_KE, KE, NO_BUKTI_RKE, RKE,(MA-KE) T_AK,(MA+RKE) T_RAK,(AW+MA) T_MA,(AW-KE) T_KE, (MA+RKE) T_RKE
		FROM (
			SELECT '$tgl_1' AS TGL,
				bhnd.RAK AS RAK,
				bhnd.NA_BHN AS NA_BHN,
				bhn.SATUAN AS SATUAN,
				bhnd.AW$bulan+(belid.QTY-pakaid.QTY) AS AW,
				'' AS NO_BUKTI_MA,
				0 AS MA,
				'' AS NO_BUKTI_KE,
				0 AS KE,
				'' AS NO_BUKTI_RKE,
				0 AS RKE,
				0 AS AK
			FROM bhn, bhnd, belid, pakaid
			WHERE bhn.KD_BHN = bhnd.KD_BHN
			AND bhn.KD_BHN = belid.KD_BHN
			AND bhn.KD_BHN = pakaid.KD_BHN
			AND bhnd.YER = '$tahun'
			AND bhn.FLAG2 = '$sub'
			AND bhn.DR = '$dr'
			-- AND belid.PER = '$per'
			AND belid.TGL < '$tgl_1'
			AND pakaid.PER = '$per'
			AND pakaid.TGL < '$tgl_1'
			GROUP BY bhnd.NA_BHN
			UNION ALL
			SELECT belid.TGL AS TGL, 
				belid.RAK AS RAK,
				belid.NA_BHN AS NA_BHN,
				belid.SATUAN AS SATUAN,
				0 AS AW,
				belid.NO_BUKTI AS NO_BUKTI_MA,
				belid.QTY AS MA,
				'' AS NO_BUKTI_KE,
				0 AS KE,
				'' AS NO_BUKTI_RKE,
				0 AS RKE,
				0 AS AK
			FROM belid, bhnd
			WHERE bhnd.kd_bhn = belid.kd_bhn
			-- AND belid.PER = '$per'
			AND belid.DR = '$dr'
			AND bhnd.DR = '$dr'
			AND belid.SP = '$sub'
			AND belid.TGL = '$tgl_1'
			-- GROUP BY belid.NO_BUKTI
			UNION ALL
			SELECT pakaid.TGL AS TGL, 
				pakaid.RAK AS RAK,
				pakaid.NA_BHN AS NA_BHN,
				pakaid.SATUAN AS SATUAN,
				0 AS AW,
				'' AS NO_BUKTI_MA,
				0 AS MA,
				pakaid.NO_BUKTI AS NO_BUKTI_KE,
				pakaid.QTY AS KE,
				'' AS NO_BUKTI_RKE,
				0 AS RKE,
				0 AS AK
			FROM pakaid, bhnd
			WHERE bhnd.kd_bhn = pakaid.KD_BHN
			-- AND pakaid.PER = '$per'
			AND pakaid.DR = '$dr'
			AND bhnd.DR = '$dr'
			AND pakaid.SUB = '$sub'
			AND pakaid.TGL = '$tgl_1'
			AND pakaid.FLAG = 'PK'
			AND pakaid.FLAG2 = 'SP'
			-- GROUP BY pakaid.NO_BUKTI
			UNION ALL
			SELECT pakaid.TGL AS TGL, 
				pakaid.RAK AS RAK,
				pakaid.NA_BHN AS NA_BHN,
				pakaid.SATUAN AS SATUAN,
				0 AS AW,
				'' AS NO_BUKTI,
				0 AS MA,
				'' AS NO_BUKTI_KE,
				0 AS KE,
				pakaid.NO_BUKTI AS NO_BUKTI_RKE,
				pakaid.QTY AS RKE,
				0 AS AK
			FROM pakaid, bhnd
			WHERE bhnd.kd_bhn = pakaid.KD_BHN
			-- AND pakaid.PER = '$per'
			AND pakaid.DR = '$dr'
			AND bhnd.DR = '$dr'
			AND pakaid.SUB = '$sub'
			AND pakaid.TGL = '$tgl_1'
			AND pakaid.FLAG = 'KP'
			AND pakaid.FLAG2 = 'SP'
			-- GROUP BY pakaid.NO_BUKTI
		) AS AAA
		ORDER BY NA_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"PER" => $row1["PER"],
					"REC" => $row1["REC"],
					"RAK" => $row1["RAK"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"AW" => $row1["AW"],
					"NO_BUKTI_MA" => $row1["NO_BUKTI_MA"],
					"MA" => $row1["MA"],
					"NO_BUKTI_KE" => $row1["NO_BUKTI_KE"],
					"KE" => $row1["KE"],
					"AK" => $row1["AK"],
					"NO_BUKTI_RKE" => $row1["NO_BUKTI_RKE"],
					"RKE" => $row1["RKE"],
					"T_MA" => $row1["T_MA"],
					"T_KE" => $row1["T_KE"],
					"T_RKE" => $row1["T_RKE"],
					"T_AK" => $row1["T_AK"],
					"T_RAK" => $row1["T_RAK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
			);
			$data['harian'] = $this->laporan_model->tampil_data_harian()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Harian', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_HarianAtk()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Harian_ATK.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$tgl_1 = $this->input->post('TGL_1');
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			if ($tgl_1 == '') {
				$bulan = Date('m');
			} else {
				$bulan = date("m", strtotime($tgl_1));
			}
			$tahun = substr($this->input->post('TGL_1'), -4);
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$query = "SELECT TGL, RAK, NA_BHN, SATUAN, AW, NO_BUKTI_MA, MA, NO_BUKTI_KE, KE, NO_BUKTI_RKE, RKE, AK
				FROM (
					SELECT '$tgl_1' AS TGL,
						'$per' AS PER,
						bhnd.RAK AS RAK,
						bhnd.NA_BHN AS NA_BHN,
						bhn.SATUAN AS SATUAN,
						bhnd.AW$bulan+(belid.QTY-pakaid.QTY) AS AW,
						'' AS NO_BUKTI_MA,
						0 AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM bhn, bhnd, belid, pakaid
					WHERE bhn.KD_BHN = bhnd.KD_BHN
					AND bhn.KD_BHN = belid.KD_BHN
					AND bhn.KD_BHN = pakaid.KD_BHN
					AND bhnd.YER = '$tahun'
					AND bhn.FLAG2 = '$sub'
					AND bhn.DR = '$dr'
					-- AND belid.PER = '$per'
					AND belid.TGL < '$tgl_1'
					AND pakaid.PER = '$per'
					AND pakaid.TGL < '$tgl_1'
					GROUP BY bhnd.NA_BHN
					UNION ALL
					SELECT belid.TGL AS TGL,
						'$per' AS PER,
						belid.RAK AS RAK,
						belid.NA_BHN AS NA_BHN,
						belid.SATUAN AS SATUAN,
						0 AS AW,
						belid.NO_BUKTI AS NO_BUKTI_MA,
						belid.QTY AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM belid, bhnd
					WHERE bhnd.kd_bhn = belid.kd_bhn
					-- AND belid.PER = '$per'
					AND belid.DR = '$dr'
					AND belid.SP = '$sub'
					AND belid.TGL = '$tgl_1'
					GROUP BY belid.NA_BHN
					UNION ALL
					SELECT pakaid.TGL AS TGL,
						'$per' AS PER,
						pakaid.RAK AS RAK,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.SATUAN AS SATUAN,
						0 AS AW,
						'' AS NO_BUKTI_MA,
						0 AS MA,
						pakaid.NO_BUKTI AS NO_BUKTI_KE,
						pakaid.QTY AS KE,
						'' AS NO_BUKTI_RKE,
						0 AS RKE,
						0 AS AK
					FROM pakaid, bhnd
					WHERE bhnd.kd_bhn = pakaid.KD_BHN
					-- AND pakaid.PER = '$per'
					AND pakaid.DR = '$dr'
					AND pakaid.SUB = '$sub'
					AND pakaid.TGL = '$tgl_1'
					AND pakaid.FLAG2 = 'SP'
					GROUP BY pakaid.NA_BHN
					UNION ALL
					SELECT pakaid.TGL AS TGL, 
						'$per' AS PER,
						pakaid.RAK AS RAK,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.SATUAN AS SATUAN,
						0 AS AW,
						'' AS NO_BUKTI,
						0 AS MA,
						'' AS NO_BUKTI_KE,
						0 AS KE,
						pakaid.NO_BUKTI AS NO_BUKTI_RKE,
						pakaid.QTY AS RKE,
						0 AS AK
					FROM pakaid, bhnd
					WHERE bhnd.kd_bhn = pakaid.KD_BHN
					-- AND pakaid.PER = '$per'
					AND pakaid.DR = '$dr'
					AND pakaid.SUB = '$sub'
					AND pakaid.TGL = '$tgl_1'
					AND pakaid.FLAG2 = 'SP'
					GROUP BY pakaid.NA_BHN
				) AS AAA
				ORDER BY NA_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"PER" => $row1["PER"],
					"REC" => $row1["REC"],
					"RAK" => $row1["RAK"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"AW" => $row1["AW"],
					"NO_BUKTI_MA" => $row1["NO_BUKTI_MA"],
					"MA" => $row1["MA"],
					"NO_BUKTI_KE" => $row1["NO_BUKTI_KE"],
					"KE" => $row1["KE"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
			);
			$data['harian_atk'] = $this->laporan_model->tampil_data_harian_atk()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Harian_Atk', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Bulanan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Bulanan.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$bulan = substr($this->session->userdata['periode'], 0, -5);
			$tahun = substr($this->session->userdata['periode'], -4);
			$query = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
					FROM (
						SELECT bhnd.KD_BHN AS KD_BHN,
							bhnd.NA_BHN AS NA_BHN,
							bhn.SATUAN AS SATUAN,
							'$per' AS PER,
							bhnd.AW$bulan AS AW,
							bhnd.MA$bulan AS MA,
							bhnd.KE$bulan AS KE,
							bhnd.LN$bulan AS LN,
							bhnd.AK$bulan AS AK
						FROM bhnd, bhn
						WHERE bhnd.KD_BHN = bhn.KD_BHN
						AND bhnd.YER = '$tahun'
						AND bhn.DR = '$dr'
						AND bhn.FLAG = 'SP'
						AND bhn.SUB = '$sub'
						GROUP BY bhn.KD_BHN
					) AS KD_BHN
					ORDER BY KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AW" => $row1["AW"],
					"MA" => $row1["MA"],
					"KE" => $row1["KE"],
					"LN" => $row1["LN"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['bulanan'] = $this->laporan_model->tampil_data_bulanan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Bulanan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_AlatTulisKantor()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Bulanan_ATK.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$bulan = substr($this->session->userdata['periode'], 0, -5);
			$tahun = substr($this->session->userdata['periode'], -4);
			$query = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
				FROM (
					SELECT bhnd.KD_BHN AS KD_BHN,
						bhnd.NA_BHN AS NA_BHN,
						bhn.SATUAN AS SATUAN,
						'$per' AS PER,
						bhnd.AW$bulan AS AW,
						bhnd.MA$bulan AS MA,
						bhnd.KE$bulan AS KE,
						bhnd.LN$bulan AS LN,
						bhnd.AK$bulan AS AK
					FROM bhnd, bhn
					WHERE bhnd.KD_BHN = bhn.KD_BHN
					AND bhnd.YER = '$tahun'
					AND bhn.DR = '$dr'
					AND bhn.FLAG = 'SP'
					AND bhn.SUB = '$sub'
					GROUP BY bhn.KD_BHN
				) AS KD_BHN
				ORDER BY KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AW" => $row1["AW"],
					"MA" => $row1["MA"],
					"KE" => $row1["KE"],
					"LN" => $row1["LN"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['laporan_atk'] = $this->laporan_model->tampil_data_bulanan_atk()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_ATK', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Pemeliharaan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Pemeliharaan.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$na_gol = $this->input->post('NA_GOL_1');
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
			$query = "SELECT pakaid.NO_ID AS ID,
					pakaid.RAK,
					'$per' AS PER,
					pakaid.NA_BHN,
					pakaid.KD_BHN,
					pakaid.SATUAN,
					pakaid.KET2,
					pakaid.NO_BUKTI,
					pakaid.TGL,
					pakaid.QTY,
					pakaid.NA_GOL,
					pakaid.GRUP
				FROM pakaid
				WHERE pakaid.TGL >='$tgl_1'
				AND pakaid.TGL <='$tgl_2'
				AND pakaid.DR = '$dr'
				AND pakaid.SUB = '$sub'
				AND pakaid.FLAG ='PK'
				AND pakaid.FLAG2 ='SP'
				AND pakaid.KET2 ='$na_gol'
				ORDER BY pakaid.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"PER" => $row1["PER"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"QTY" => $row1["QTY"],
					"NA_GOL" => $row1["NA_GOL"],
					"RAK" => $row1["RAK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
				'TGL_2' => set_value('TGL_2'),
				'NA_GOL_1' => set_value('NA_GOL_1'),
			);
			$data['pemeliharaan'] = $this->laporan_model->tampil_data_pemeliharaan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Pemeliharaan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_LPB()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_LPB.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
			$query = "SELECT belid.rak AS RAK,
						belid.kd_bhn AS KD_BHN,
						belid.na_bhn AS NA_BHN,
						CONCAT(belid.kd_bhn,' - ',belid.na_bhn) AS BARANG,
						beli.no_beli AS NO_BUKTI,
						beli.tgl AS TGL,
						belid.satuan AS SATUAN,
						belid.qty AS QTY,
						'$tgl_1' AS TGL_1,
						'$tgl_2' AS TGL_2
					FROM belid, beli
					WHERE beli.no_bukti=belid.no_bukti 
					AND beli.TGL >='$tgl_1'
					AND beli.TGL <='$tgl_2'
					AND beli.SUB = '$sub'
					AND beli.DR='$dr'
					AND belid.FLAG2='SP'
					GROUP BY belid.na_bhn
					ORDER BY belid.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"RAK" => $row1["RAK"],
					"BARANG" => $row1["BARANG"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL_1" => $row1["TGL_1"],
					"TGL_2" => $row1["TGL_2"],
					"SATUAN" => $row1["SATUAN"],
					"QTY" => $row1["QTY"],
					"TGL" => $row1["TGL"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
				'TGL_2' => set_value('TGL_2'),
			);
			$data['lpb'] = $this->laporan_model->tampil_data_lpb()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/LPB', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Pemakaian()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Pemakaian.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$tgl_2 = date("Y-m-d", strtotime($this->input->post('TGL_2', TRUE)));
			$query = "SELECT pakai.NOTES AS NOTES,
						pakaid.TGL AS TGL,
						pakaid.NO_BUKTI AS NO_BUKTI,
						pakaid.KD_BHN AS KD_BHN,
						pakaid.NA_BHN AS NA_BHN,
						pakaid.QTY AS QTY,
						pakaid.SATUAN AS SATUAN,
						pakaid.KET1 AS KET1,
						pakaid.RAK AS RAK,
						'$tgl_1' AS TGL_1,
						'$tgl_2' AS TGL_2
					FROM pakaid, pakai
					WHERE pakai.NO_BUKTI = pakaid.NO_BUKTI
					AND pakai.TGL BETWEEN '$tgl_1' AND '$tgl_2'
					AND pakai.DR = '$dr'
					AND pakai.SUB = '$sub'
					-- AND pakai.PER = '$per'
					AND pakai.FLAG = 'PK'
					AND pakai.FLAG2 = 'SP'
					ORDER BY pakaid.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"NOTES" => $row1["NOTES"],
					"TGL_1" => $row1["TGL_1"],
					"TGL_2" => $row1["TGL_2"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"SATUAN" => $row1["SATUAN"],
					"QTY" => $row1["QTY"],
					"NOTES" => $row1["NOTES"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'KD_BHN_1' => set_value('KD_BHN_1'),
				'TGL_1' => set_value('TGL_1'),
				'TGL_2' => set_value('TGL_2'),
			);
			$data['pemakaian'] = $this->laporan_model->tampil_data_pemakaian()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Pemakaian', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Pesanan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Pesanan.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$sub = $this->session->userdata['sub'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.NA_BRG,
					pp.KET,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN,				
					ppd.KET1 AS KET1,

					pod.NO_BUKTI AS NO_PO,
					pod.TGL AS TGL_PO,
					pod.NO_PP,

					belid.NO_BUKTI AS NO_BELI,
					belid.NO_PO AS NO_PO_BELI,
					belid.TGL AS TGL_BELI
				FROM pp, ppd, pod, belid
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = '$sub'
				AND pp.PER = '$per'
				AND belid.FLAG2 = 'SP'
				GROUP BY belid.NO_PO
			ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"NO_PO" => $row1["NO_PO"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"DEVISI" => $row1["DEVISI"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO_BELI" => $row1["NO_PO_BELI"],
					"KET" => $row1["KET"],
					"STAT" => $row1["STAT"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'KD_BHN_1' => set_value('KD_BHN_1'),
			);
			$data['pesanan'] = $this->laporan_model->tampil_data_laporan_pesanan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Pesanan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_UsiaStokIA()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Usia.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$hari_1 = substr($this->input->post('TGL_1'), 0.2);
			$bulan = substr(date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE))), 5, 2);
			$tahun = substr($this->input->post('TGL_1'), 6, 4);
			$query = "SELECT bhnd.RAK, 
				bhnd.KD_BHN,
				bhnd.NA_BHN, 
				bhn.SATUAN,
				bhnd.AK$bulan AS AK, 
				DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
				CASE 
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
					THEN '> 24 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
					THEN '> 21 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
					THEN '> 18 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
					THEN '> 15 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
					THEN '> 12 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
					THEN '> 9 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
					THEN '> 6 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
					THEN '> 3 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
						THEN '> 1 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
					THEN '< 1 Bulan'
				END AS KET
			FROM bhn, bhnd
			WHERE bhn.KD_BHN = bhnd.KD_BHN
			AND bhnd.DR='$dr'
			AND bhnd.FLAG='SP'
			AND bhnd.TG_BL < '$tgl_1'
			AND bhnd.TG_PK < '$tgl_1'
			AND bhnd.YER = '$tahun'
			GROUP BY bhnd.KD_BHN
			ORDER BY bhnd.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"AK" => $row1["AK"],
					"HARI" => $row1["HARI"],
					"KET" => $row1["KET"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"RAK" => $row1["RAK"],
					"TGL" => $row1["TGL"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
			);
			$data['usiastokia'] = $this->laporan_model->tampil_data_usiastokia()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/UsiaStokIA', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Usia()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Usia.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$bulan = substr(date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE))), 5, 2);
			$tahun = substr($this->input->post('TGL_1'), -4);
			$tgl_1 = date("Y-m-d", strtotime($this->input->post('TGL_1', TRUE)));
			$masa = $this->input->post('MASA');
			$query = "SELECT bhnd.RAK, 
				bhnd.KD_BHN,
				bhnd.NA_BHN, 
				bhn.SATUAN,
				bhnd.AK$bulan AS AK, 
				DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
				CASE 
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
					THEN '> 24 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
					THEN '> 21 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
					THEN '> 18 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
					THEN '> 15 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
					THEN '> 12 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
					THEN '> 9 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
					THEN '> 6 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
					THEN '> 3 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
						THEN '> 1 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
					THEN '< 1 Bulan'
				END AS KET
			FROM bhn, bhnd
			WHERE bhn.KD_BHN = bhnd.KD_BHN
			AND bhnd.DR='$dr'
			AND bhnd.FLAG='SP'
			AND bhnd.TG_BL < '$tgl_1'
			AND bhnd.TG_PK < '$tgl_1'
			AND bhnd.YER = '$tahun'
			GROUP BY bhnd.KD_BHN
			ORDER BY bhnd.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"AK" => $row1["AK"],
					"HARI" => $row1["HARI"],
					"KET" => $row1["KET"],
					"KD_BHN" => $row1["KD_BHN"],
					"NA_BHN" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"RAK" => $row1["RAK"],
					"TGL" => $row1["TGL"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
				'MASA' => set_value('MASA'),
			);
			$data['usia'] = $this->laporan_model->tampil_data_usia()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Usia', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Global()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Global_Inventaris.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$jenis_1 = $this->input->post('JENIS_1');
			$filter_jenis = " ";
			if ($this->input->post('JENIS_1', TRUE) != '') {
				$filter_jenis = "AND inventarisd.JENIS = '$jenis_1'";
			}
			$query = "SELECT inventaris.NO_BUKTI AS NO_BUKTI,
				inventaris.NA_BAGIAN AS NA_BAGIAN,
				inventarisd.JENIS AS JENIS,
				inventarisd.MERK AS MERK,
				inventarisd.QTY AS QTY,
				inventarisd.SATUAN AS SATUAN
			FROM inventaris, inventarisd
			WHERE inventaris.NO_BUKTI = inventarisd.NO_BUKTI 
			AND inventaris.DR='$dr'
			AND inventaris.FLAG='INV'
			$filter_jenis
			ORDER BY inventarisd.JENIS";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"JENIS" => $row1["JENIS"],
					"MERK" => $row1["MERK"],
					"QTY" => $row1["QTY"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'JENIS_1' => set_value('JENIS_1'),
			);
			$data['global'] = $this->laporan_model->tampil_data_global()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Global', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_InventarisGlobal()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Global_Inventaris.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$jenis_1 = $this->input->post('JENIS_1');
			$filter_jenis = " ";
			if ($this->input->post('JENIS_1', TRUE) != '') {
				$filter_jenis = "AND inventarisd.JENIS = '$jenis_1'";
			}
			$query = "SELECT inventaris.NO_BUKTI AS NO_BUKTI,
				inventaris.NA_BAGIAN AS NA_BAGIAN,
				inventarisd.JENIS AS JENIS,
				inventarisd.MERK AS MERK,
				inventarisd.QTY AS QTY,
				inventarisd.SATUAN AS SATUAN,
				inventarisd.KET AS KET
			FROM inventaris, inventarisd
			WHERE inventaris.NO_BUKTI = inventarisd.NO_BUKTI 
			AND inventaris.DR='$dr'
			AND inventaris.FLAG='INV'
			$filter_jenis
			ORDER BY inventarisd.JENIS";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"JENIS" => $row1["JENIS"],
					"MERK" => $row1["MERK"],
					"QTY" => $row1["QTY"],
					"PER" => $row1["PER"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'JENIS_1' => set_value('JENIS_1'),
			);
			$data['inventarisglobal'] = $this->laporan_model->tampil_data_inventarisglobal()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/InventarisGlobal', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Stok_Sparepart()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Stok_Sparepart_IA.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = date("Y-m-d");
			$bulan = substr(date("Y-m-d"), 5, 2);
			$tahun = substr($this->input->post('PER'), -4);
			$tahun_1 = $this->input->post('PER');
			$query = "SELECT bhnd.KD_BHN,
			bhnd.NA_BHN,
			bhn.SATUAN,
			'$tahun_1' AS PER,
			SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
			SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
			SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
			SUM(bhnd.AK$bulan) AS TOTAL,
			DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
			CASE 
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
				THEN '> 24 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
				THEN '> 21 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
				THEN '> 18 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
				THEN '> 15 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
				THEN '> 12 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
				THEN '> 9 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
				THEN '> 6 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
				THEN '> 3 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
					THEN '> 1 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
				THEN '< 1 Bulan'
			END AS KET
		FROM bhnd, bhn
		WHERE bhnd.KD_BHN = bhn.KD_BHN
		-- AND bhnd.YER = '$tahun'
		AND bhnd.FLAG = 'SP'
		AND bhnd.SUB = 'SP'
		GROUP BY bhn.KD_BHN
		ORDER BY bhn.NA_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"DR1" => $row1["DR1"],
					"DR2" => $row1["DR2"],
					"DR3" => $row1["DR3"],
					"TOTAL" => $row1["TOTAL"],
					"HARI" => $row1["HARI"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['stok_sparepart'] = $this->laporan_model->tampil_data_stok_sparepart()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Stok_Sparepart', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Stok_Inventaris()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Stok_Inventaris_IA.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = date("Y-m-d");
			$bulan = substr(date("Y-m-d"), 5, 2);
			$tahun = substr($this->input->post('PER'), -4);
			$tahun_1 = $this->input->post('PER');
			$query = "SELECT bhnd.KD_BHN,
				bhnd.NA_BHN,
				bhn.SATUAN,
				'$tahun_1' AS PER,
				SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
				SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
				SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
				SUM(bhnd.AK$bulan) AS TOTAL,
				DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
				CASE 
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
					THEN '> 24 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
					THEN '> 21 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
					THEN '> 18 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
					THEN '> 15 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
					THEN '> 12 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
					THEN '> 9 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
					THEN '> 6 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
					THEN '> 3 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
						THEN '> 1 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
					THEN '< 1 Bulan'
				END AS KET
			FROM bhnd, bhn
			WHERE bhnd.KD_BHN = bhn.KD_BHN
			-- AND bhnd.YER = '$tahun'
			AND bhnd.FLAG = 'SP'
			AND bhnd.SUB = 'INV'
			GROUP BY bhn.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"DR1" => $row1["DR1"],
					"DR2" => $row1["DR2"],
					"DR3" => $row1["DR3"],
					"TOTAL" => $row1["TOTAL"],
					"HARI" => $row1["HARI"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['stok_inventaris'] = $this->laporan_model->tampil_data_stok_inventaris()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Stok_Inventaris', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Stok_ATK()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Stok_ATK_IA.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = date("Y-m-d");
			$bulan = substr(date("Y-m-d"), 5, 2);
			$tahun = substr($this->input->post('PER'), -4);
			$tahun_1 = $this->input->post('PER');
			$query = "SELECT bhnd.KD_BHN,
				bhnd.NA_BHN,
				bhn.SATUAN,
				'$tahun_1' AS PER,
				SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
				SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
				SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
				SUM(bhnd.AK$bulan) AS TOTAL,
				DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
				CASE 
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
					THEN '> 24 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
					THEN '> 21 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
					THEN '> 18 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
					THEN '> 15 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
					THEN '> 12 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
					THEN '> 9 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
					THEN '> 6 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
					THEN '> 3 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
						THEN '> 1 Bulan'
					WHEN 
						DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
					THEN '< 1 Bulan'
				END AS KET
			FROM bhnd, bhn
			WHERE bhnd.KD_BHN = bhn.KD_BHN
			-- AND bhnd.YER = '$tahun'
			AND bhnd.FLAG = 'SP'
			AND bhnd.SUB = 'ATK'
			GROUP BY bhn.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"DR1" => $row1["DR1"],
					"DR2" => $row1["DR2"],
					"DR3" => $row1["DR3"],
					"TOTAL" => $row1["TOTAL"],
					"HARI" => $row1["HARI"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['stok_atk'] = $this->laporan_model->tampil_data_stok_atk()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Stok_ATK', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Stok_Umum()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Stok_Umum_IA.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = date("Y-m-d");
			$bulan = substr(date("Y-m-d"), 5, 2);
			$tahun = substr($this->input->post('PER'), -4);
			$tahun_1 = $this->input->post('PER');
			$query = "SELECT bhnd.KD_BHN,
				bhnd.NA_BHN,
				bhn.SATUAN,
				'$tahun_1' AS PER,
				SUM(if(bhnd.DR='I',bhnd.AK$bulan,0)) AS DR1,
				SUM(if(bhnd.DR='II',bhnd.AK$bulan,0)) AS DR2,
				SUM(if(bhnd.DR='III',bhnd.AK$bulan,0)) AS DR3,
				SUM(bhnd.AK$bulan) AS TOTAL,
				DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) as HARI,
			CASE 
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 720 
				THEN '> 24 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 630 
				THEN '> 21 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 540 
				THEN '> 18 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 450 
				THEN '> 15 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 360
				THEN '> 12 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 270
				THEN '> 9 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 180 
				THEN '> 6 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 90 
				THEN '> 3 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) >= 30 
					THEN '> 1 Bulan'
				WHEN 
					DATEDIFF(DATE('$tgl_1'),bhnd.TG_USIA) < 30 
				THEN '< 1 Bulan'
			END AS KET
			FROM bhnd, bhn
			WHERE bhnd.KD_BHN = bhn.KD_BHN
			-- AND bhnd.YER = '$tahun'
			AND bhnd.FLAG = 'SP'
			AND bhnd.SUB = 'UM'
			GROUP BY bhn.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
					"HARI" => $row1["HARI"],
					"KET" => $row1["KET"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['stok_umum'] = $this->laporan_model->tampil_data_stok_umum()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Stok_Umum', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Laporan_Sparepart()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Laporan_Sparepart.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$bulan = substr($this->session->userdata['periode'], 0, -5);
			$tahun = substr($this->session->userdata['periode'], -4);
			$query = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
						FROM (
							SELECT bhnd.KD_BHN AS KD_BHN,
								bhnd.NA_BHN AS NA_BHN,
								bhn.SATUAN AS SATUAN,
								'$per' AS PER,
								bhnd.AW$bulan AS AW,
								bhnd.MA$bulan AS MA,
								bhnd.KE$bulan AS KE,
								bhnd.LN$bulan AS LN,
								bhnd.AK$bulan AS AK
							FROM bhnd, bhn
							WHERE bhnd.KD_BHN = bhn.KD_BHN
							AND bhnd.YER = '$tahun'
							AND bhn.DR = '$dr'
							AND bhn.FLAG = 'SP'
							AND bhn.SUB = '$sub'
							GROUP BY bhn.KD_BHN
							ORDER BY bhnd.TG_USIA DESC
						) AS KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'TGL_1' => set_value('TGL_1'),
				'TGL_2' => set_value('TGL_2'),
			);
			$data['laporan_sparepart'] = $this->laporan_model->tampil_data_laporan_sparepart()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_Sparepart', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Laporan_Inventaris()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Laporan_Inventaris.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$bulan = substr($this->session->userdata['periode'], 0, -5);
			$tahun = substr($this->session->userdata['periode'], -4);
			$query = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
						FROM (
							SELECT bhnd.KD_BHN AS KD_BHN,
								bhnd.NA_BHN AS NA_BHN,
								bhn.SATUAN AS SATUAN,
								'$per' AS PER,
								bhnd.AW$bulan AS AW,
								bhnd.MA$bulan AS MA,
								bhnd.KE$bulan AS KE,
								bhnd.LN$bulan AS LN,
								bhnd.AK$bulan AS AK
							FROM bhnd, bhn
							WHERE bhnd.KD_BHN = bhn.KD_BHN
							AND bhnd.YER = '$tahun'
							AND bhn.DR = '$dr'
							AND bhn.FLAG = 'SP'
							AND bhn.SUB = '$sub'
							GROUP BY bhn.KD_BHN
							ORDER BY bhnd.TG_USIA DESC
						) AS KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['laporan_inventaris'] = $this->laporan_model->tampil_data_laporan_inventaris()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_Inventaris', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Laporan_ATK()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Bulanan_ATK.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$sub = $this->session->userdata['sub'];
			$per = $this->session->userdata['periode'];
			$bulan = substr($this->session->userdata['periode'], 0, -5);
			$tahun = substr($this->session->userdata['periode'], -4);
			$query = "SELECT KD_BHN, NA_BHN, SATUAN, PER, AW, MA, KE, LN, AK
						FROM (
							SELECT bhnd.KD_BHN AS KD_BHN,
								bhnd.NA_BHN AS NA_BHN,
								bhn.SATUAN AS SATUAN,
								'$per' AS PER,
								bhnd.AW$bulan AS AW,
								bhnd.MA$bulan AS MA,
								bhnd.KE$bulan AS KE,
								bhnd.LN$bulan AS LN,
								bhnd.AK$bulan AS AK
							FROM bhnd, bhn
							WHERE bhnd.KD_BHN = bhn.KD_BHN
							AND bhnd.YER = '$tahun'
							AND bhn.DR = '$dr'
							AND bhn.FLAG = 'SP'
							AND bhn.SUB = '$sub'
							GROUP BY bhn.KD_BHN
							ORDER BY bhnd.TG_USIA DESC
						) AS KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AW" => $row1["AW"],
					"MA" => $row1["MA"],
					"KE" => $row1["KE"],
					"LN" => $row1["LN"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['laporan_atk'] = $this->laporan_model->tampil_data_laporan_atk()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_ATK', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_LapLPB()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = $this->input->post('TGL_1');
			$query = "SELECT beli.NO_BUKTI,
					beli.KD_BAG,
					belid.TGL,
					belid.NA_BRG,
					belid.QTY,
					belid.NO_PO,
					belid.SATUAN
				FROM beli, belid
				WHERE beli.NO_BUKTI = belid.NO_BUKTI
				AND beli.TGL = '$tgl_1'
				ORDER BY belid.KD_BHN";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['laporan_lpb'] = $this->laporan_model->tampil_data_laporan_lpb()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_Lpb', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_MonitorOrderLasting()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Monitor_Order_Lasting.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = $this->input->post('TGL_1');
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					'-' AS TGLSLS,
					'-' AS SLS,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN,				
					ppd.KET1 AS KET

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.DR ='$dr'
				AND pp.SUB ='LS'
				-- AND pp.TYP = 'RND_LASTING'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['monitor_orderlasting'] = $this->laporan_model->tampil_data_monitor_order_lasting()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_MonitorOrderLasting', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_MonitorOrderCetakan()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Monitor_Order_Cetakan.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$dr = $this->session->userdata['dr'];
			$tgl_1 = $this->input->post('TGL_1');
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					'-' AS TGLSLS,
					'-' AS SLS,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN,				
					ppd.KET1 AS KET

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.DR ='$dr'
				AND pp.SUB ='CT'
				-- AND pp.TYP = 'RND_LASTING'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['monitor_ordercetakan'] = $this->laporan_model->tampil_data_monitor_order_cetakan()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_MonitorOrderCetakan', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_MonitorOrderPisau()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Monitor_Order_Pisau.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = $this->input->post('TGL_1');
			$dr = $this->session->userdata['dr'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					'-' AS TGLSLS,
					'-' AS SLS,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN,				
					ppd.KET1 AS KET

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.DR ='$dr'
				AND pp.SUB ='1R&'
				-- AND pp.TYP = 'RND_LASTING'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['monitor_orderpisau'] = $this->laporan_model->tampil_data_monitor_order_pisau()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_MonitorOrderPisau', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_MonitorOrderMeba()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Monitor_Order_Meba.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$tgl_1 = $this->input->post('TGL_1');
			$dr = $this->session->userdata['dr'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					'-' AS TGLSLS,
					'-' AS SLS,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN,				
					ppd.KET1 AS KET

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.DR ='$dr'
				AND pp.SUB ='MB'
				-- AND pp.TYP = 'RND_LASTING'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['monitor_ordermeba'] = $this->laporan_model->tampil_data_monitor_order_meba()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_MonitorOrderMeba', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_ProsesOrderPembelian()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Proses_Order_Pembelian.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$sub = $this->session->userdata['sub'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.DEVISI,
					pp.KD_DEV AS KD_DEVISI,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.TGL_DIMINTA,					
					ppd.NA_BHN,
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = '$sub'
				AND pp.PER = '$per'
				-- AND pp.TYP = 'BL_CNC'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['proses_order_pembelian'] = $this->laporan_model->tampil_data_proses_order_pembelian()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_ProsesOrderPembelian', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_ProsesOrderPembelianCOR()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Proses_Order_Pembelian(COR).jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$sub = $this->session->userdata['sub'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN,			
					ppd.HARGA,			
					ppd.TOTAL		

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd, pod, belid
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = '$sub'
				AND pp.PER = '$per'
				-- AND pp.TYP = 'BOR_CNC'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['proses_order_pembelian_cor'] = $this->laporan_model->tampil_data_proses_order_pembelian_cor()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_ProsesOrderPembelianCOR', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_ProsesOrderPembelianCORPerNoPP()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Proses_Order_Pembelian(COR)_Per_NOPP.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$sub = $this->session->userdata['sub'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN			

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = '$sub'
				AND pp.PER = '$per'
				-- AND pp.TYP = 'BOR_CNC'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['proses_order_pembelian_cor_pp'] = $this->laporan_model->tampil_data_proses_order_pembelian_cor_per_pp()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_ProsesOrderPembelianCORPerNoPP', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_ProsesOrderInternal()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Proses_Order_Intern.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$sub = $this->session->userdata['sub'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.ARTICLE AS NA_BRG,
					pp.KET,
					pp.TOTAL_QTY,
					'-' AS AREA,
					'-' AS KABAG,
					'-' AS HARI,
					if(pp.VAL = 1, 'SELESAI', 'BELUM SELESAI') AS STAT,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN				

					-- pod.NO_BUKTI AS NO_PO,
					-- pod.TGL AS TGL_PO,
					-- pod.NO_PP,

					-- belid.NO_BUKTI AS NO_BELI,
					-- belid.NO_PO,
					-- belid.TGL AS TGL_BELI
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = '$sub'
				AND pp.PER = '$per'
				-- AND pp.TYP = 'IN_CNC'
				-- AND belid.FLAG2 = 'SP'
				GROUP BY pp.NO_BUKTI
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
					"KET" => $row1["KET"],
					"QTY" => $row1["QTY"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['proses_order_internal'] = $this->laporan_model->tampil_data_proses_order_internal()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_ProsesOrderInternal', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_MonitorPO()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Monitor_PO.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$sub = $this->session->userdata['sub'];
			$dr = $this->session->userdata['dr'];
			$query = "SELECT po.NO_BUKTI AS NO_PO,
					po.KODES,
					po.NAMAS,
					po.DR,
					po.TGL,
					po.JTEMPO,
					po.NOTESKRM,		

					pod.NO_BUKTI AS NO_PO,
					pod.TGL AS TGL_PO,
					pod.NO_PP AS NO_PP
				FROM po, pod
				WHERE po.NO_BUKTI = pod.NO_BUKTI
				AND po.DR = '$dr'
				AND po.PER = '$per'
				AND po.KD_TTD1 <> ''
				AND po.KD_TTD2 <> ''
				AND po.FLAG2 = 'NB'
				GROUP BY po.NO_BUKTI
				ORDER BY po.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"DEVISI" => $row1["DEVISI"],
					"AREA" => $row1["AREA"],
					"KABAG" => $row1["KABAG"],
					"NO_BUKTI" => $row1["NO_BUKTI"],
					"TGL" => $row1["TGL"],
					"TGL_DIMINTA" => $row1["TGL_DIMINTA"],
					"NA_BHN" => $row1["NA_BHN"],
					"TGL_PO" => $row1["TGL_PO"],
					"NO_PO" => $row1["NO_PO"],
					"HARI" => $row1["HARI"],
					"STAT" => $row1["STAT"],
					"NA_BRG" => $row1["NA_BRG"],
					"TOTAL_QTY" => $row1["TOTAL_QTY"],
					"HARGA" => $row1["HARGA"],
					"TOTAL" => $row1["TOTAL"],
					"SATUAN" => $row1["SATUAN"],
					"KET" => $row1["KET"],
					"QTY" => $row1["QTY"],
					"NAMAS" => $row1["NAMAS"],
					"ALAMAT" => $row1["ALAMAT"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['monitor_po'] = $this->laporan_model->tampil_data_monitor_po()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_MonitorPO', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_InventarisDR1()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'INV'
				AND pp.DR = 'I'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['inventarisdr1'] = $this->laporan_model->tampil_data_inventarisdr1()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_InventarisDR1', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_SparepartDR1()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'SP'
				AND pp.DR = 'I'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['sparepartdr1'] = $this->laporan_model->tampil_data_sparepartdr1()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_SparepartDR1', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_InventarisDR2()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'INV'
				AND pp.DR = 'II'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['inventarisdr2'] = $this->laporan_model->tampil_data_inventarisdr2()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_InventarisDR2', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_SparepartDR2()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'SP'
				AND pp.DR = 'I'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['sparepartdr2'] = $this->laporan_model->tampil_data_sparepartdr2()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_SparepartDR2', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_InventarisDR3()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'INV'
				AND pp.DR = 'III'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['inventarisdr3'] = $this->laporan_model->tampil_data_inventarisdr3()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_InventarisDR3', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_SparepartDR3()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'SP'
				AND pp.DR = 'III'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['sparepartdr3'] = $this->laporan_model->tampil_data_sparepartdr3()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_SparepartDR3', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	public function index_Umum()
	{
		if (isset($_POST["print"])) {
			$CI = &get_instance();
			$CI->load->database();
			$servername = $CI->db->hostname;
			$username = $CI->db->username;
			$password = $CI->db->password;
			$database = $CI->db->database;
			$conn = mysqli_connect($servername, $username, $password, $database);
			error_reporting(E_ALL);
			ob_start();
			include('phpjasperxml/class/tcpdf/tcpdf.php');
			include('phpjasperxml/class/PHPJasperXML.inc.php');
			include('phpjasperxml/setting.php');
			$PHPJasperXML = new \PHPJasperXML();
			$PHPJasperXML->load_xml_file("phpjasperxml/Laporan_Lpb.jrxml");
			$PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
			$per = $this->session->userdata['periode'];
			$query = "SELECT pp.NO_BUKTI,
					pp.TGL,
					pp.TGL_DIMINTA,
					pp.DEVISI,
					pp.KD_DEV,
					pp.NA_BRG,
					pp.KET,

					ppd.NA_BHN,					
					ppd.QTY,					
					ppd.SATUAN
				FROM pp, ppd
				WHERE pp.NO_BUKTI = ppd.NO_BUKTI
				AND pp.SUB = 'UM'
				AND pp.PER = '$per'
				ORDER BY pp.TGL";
			$result1 = mysqli_query($conn, $query);
			while ($row1 = mysqli_fetch_assoc($result1)) {
				array_push($PHPJasperXML->arraysqltable, array(
					"ID" => $row1["ID"],
					"KD_BRG" => $row1["KD_BHN"],
					"NA_BRG" => $row1["NA_BHN"],
					"SATUAN" => $row1["SATUAN"],
					"PER" => $row1["PER"],
					"AK" => $row1["AK"],
				));
			}
			ob_end_clean();
			$PHPJasperXML->outpage("I");
		} else {
			$data = array(
				'PER' => set_value('PER'),
			);
			$data['umum'] = $this->laporan_model->tampil_data_umum()->result();
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/laporan/Laporan_Umum', $data);
			$this->load->view('templates_admin/footer_report');
		}
	}

	//////		AJAX GLOBAL		/////
	public function getData_grup_mesin_1()
	{
		$dr = $this->session->userdata['dr'];
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT NO_ID, KD_GOL AS KD_GOL_1, NA_GOL AS NA_GOL_1, GRUP AS GRUP_1
			FROM sp_mesin
			WHERE (KD_GOL LIKE '%$search%' OR NA_GOL LIKE '%$search%' OR GRUP LIKE '%$search%')
			AND DR='$dr'
			GROUP BY NA_GOL
			ORDER BY KD_GOL LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['NA_GOL_1'],
				'text' => $row['NA_GOL_1']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	public function getData_grup_mesin_2()
	{
		$dr = $this->session->userdata['dr'];
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT NO_ID, KD_GOL AS KD_GOL_2, NA_GOL AS NA_GOL_2, GRUP AS GRUP_2
			FROM sp_mesin
			WHERE (KD_GOL LIKE '%$search%' OR NA_GOL LIKE '%$search%' OR GRUP LIKE '%$search%')
			AND DR='$dr'
			GROUP BY GRUP
			ORDER BY KD_GOL LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['GRUP_2'],
				'text' => $row['KD_GOL_2'] . " - " . $row['NA_GOL_2'] . " - " . $row['GRUP_2']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	public function getData_master_barang_1()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT NO_ID as NO_ID, KD_BHN as KD_BHN_1, NA_BHN as NM_BHN_1, DR as DR_1
			FROM bhn
			WHERE FLAG='SP' AND SUB='$sub' AND (KD_BHN LIKE '%$search%' OR NA_BHN LIKE '%$search%' OR DR LIKE '%$search%')
			ORDER BY KD_BHN LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['KD_BHN_1'],
				'text' => $row['KD_BHN_1'] . " - " . $row['NM_BHN_1'] . " - " . $row['DR_1']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	public function getData_master_barang_2()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT no_id as NO_ID, KD_BHN as KD_BHN_2, NA_BHN as NM_BHN_2, DR as DR_2
			FROM bhn
			WHERE (KD_BHN LIKE '%$search%' OR NA_BHN LIKE '%$search%' OR DR LIKE '%$search%')
			AND bhn.FLAG='SP'
			AND bhn.SUB='$sub'
			AND bhn.DR='$dr'
			ORDER BY KD_BHN LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['KD_BHN_2'],
				'text' => $row['KD_BHN_2'] . " - " . $row['NM_BHN_2'] . " - " . $row['DR_2']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	public function getData_brg_rak_1()
	{
		$dr = $this->session->userdata['dr'];
		$sub = $this->session->userdata['sub'];
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT no_id as NO_ID, RAK as RAK_1
			FROM brg
			WHERE DR='$dr' AND SP='$sub' AND (RAK LIKE '%$search%')
			GROUP BY RAK
			ORDER BY RAK LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['RAK_1'],
				'text' => $row['RAK_1']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	//////		AJAX GLOBAL		/////
	public function getData_jenis_barang_1()
	{
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT NO_ID as NO_ID, JENIS as JENIS_1
			FROM sp_jenis_inv
			WHERE JENIS LIKE '%$search%'
			GROUP BY JENIS
			ORDER BY JENIS LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['JENIS_1'],
				'text' => $row['JENIS_1']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	//////		BATAS AJAX GLOBAL		/////

	//////		AJAX GLOBAL		/////
	public function getData_cetak_1()
	{
		$search = $this->input->post('search');
		$page = ((int)$this->input->post('page'));
		if ($page == 0) {
			$xa = 0;
		} else {
			$xa = ($page - 1) * 10;
		}
		$perPage = 10;
		$results = $this->db->query("SELECT NO_ID as NO_ID, CETAK as CETAK_1
			FROM sp_invenc
			WHERE CETAK LIKE '%$search%'
			GROUP BY CETAK
			ORDER BY CETAK LIMIT $xa,$perPage");
		$selectajax = array();
		foreach ($results->RESULT_ARRAY() as $row) {
			$selectajax[] = array(
				'id' => $row['CETAK_1'],
				'text' => $row['CETAK_1']
			);
		}
		$select['total_count'] =  $results->NUM_ROWS();
		$select['items'] = $selectajax;
		$this->output->set_content_type('application/json')->set_output(json_encode($select));
	}

	//////		BATAS AJAX GLOBAL		/////


}
