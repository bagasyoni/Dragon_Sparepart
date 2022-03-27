<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(__FILE__) . "/../../../koolreport/core/autoload.php";

class Dashboard extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        if(!isset($this->session->userdata['username'])){
            $this->session->set_flashdata('pesan',
				'<div class="alert alert-danger alert-dismissible fade show" role="alert">
					Anda Belum Login
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>');
			redirect('admin/auth');
        }
    }
    
    public function index() {
		$data = $this->user_model->ambil_data($this->session->userdata['username']);
		$data = array (
			'na_dev' => $data->NA_DEV,
			'username' => $data->USERNAME,
			'level' => $data->AKSES,
			'dr' => $data->DR,
			'sub' => $data->SUB,
			'pin' => $data->PIN,
			'periode' => $this->session->userdata['periode'],
		);

		// Hitung Bon Beli untuk Pesanan yang belum diverifikasi
		// $VERIFIKASI_PO_SP = "SELECT count(VERIFIKASI_PO_SP) AS VERIFIKASI_PO_SP FROM bl_bon WHERE OK=1 AND TUJUAN='SP' AND VERIFIKASI_PO_SP=0 ";
		// $result_submit = $this->db->query($VERIFIKASI_PO_SP)->result();
		// $data['VERIFIKASI_PO_SP'] = $result_submit[0]->VERIFIKASI_PO_SP;
		// Hitung total Bon Beli untuk Pesanan
		// $TOTAL_VERIFIKASI_PO_SP = "SELECT count(VERIFIKASI_PO_SP) AS TOTAL_VERIFIKASI_PO_SP FROM bl_bon WHERE OK=1 AND TUJUAN='SP'";
		// $result_submit = $this->db->query($TOTAL_VERIFIKASI_PO_SP)->result();
		// $data['TOTAL_VERIFIKASI_PO_SP'] = $result_submit[0]->TOTAL_VERIFIKASI_PO_SP;

		// Hitung Bon Beli untuk Pesanan yang sudah diverifikasi tapi belum dikerjakan di form pesanan
		// $VERIFIKASI_PO_SP_1 = "SELECT count(VERIFIKASI_PO_SP) AS VERIFIKASI_PO_SP_1 FROM bl_bon WHERE OK=1 AND TUJUAN='SP' AND VERIFIKASI_PO_SP=1 ";
		// $result_submit = $this->db->query($VERIFIKASI_PO_SP_1)->result();
		// $data['VERIFIKASI_PO_SP_1'] = $result_submit[0]->VERIFIKASI_PO_SP_1;
		// Hitung total Bon Beli untuk Pesanan
		// $TOTAL_VERIFIKASI_PO_SP_1 = "SELECT count(VERIFIKASI_PO_SP) AS TOTAL_VERIFIKASI_PO_SP_1 FROM bl_bon WHERE OK=1 AND TUJUAN='SP'";
		// $result_submit = $this->db->query($TOTAL_VERIFIKASI_PO_SP_1)->result();
		// $data['TOTAL_VERIFIKASI_PO_SP_1'] = $result_submit[0]->TOTAL_VERIFIKASI_PO_SP_1;

		$this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/dashboard',$data);
        $this->load->view('templates_admin/footer');
	}

	public function ganti_periode() {
		$month = $this->input->post('bulan');
		$year = $this->input->post('tahun');
		$periode = $month . '/' . $year;
		$this->session->set_userdata('periode',$periode);
		$data = $this->user_model->ambil_data($this->session->userdata['username']); 
		$data = array (
			'username' => $data->username,
			'level'   => $data->level,
			'periode' => $this->session->userdata['periode'],
		);
		redirect('admin/dashboard');
	}

    // fungsi untuk ganti font
	public function ganti_font($id) {
		$font = $this->input->post('font');
		$size = $this->input->post('size');
		$data = [
			'FONT' => $font,
			'SIZE' => $size
		];
		$this->font_model->update($id, $data);
		redirect('admin/dashboard');
	}
}
