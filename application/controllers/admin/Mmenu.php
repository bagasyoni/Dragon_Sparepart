<?php

// <!-- Ganti 0 -->
class Mmenu extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['username'])) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Anda Belum Login
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>');
			redirect('admin/auth');
		}
		if ($this->session->userdata['menu_pemasaran'] != 'mmenu') {
			$this->session->set_userdata('menu_pemasaran', 'mmenu');
			$this->session->set_userdata('kode_menu', '110001');
			$this->session->set_userdata('keyword_mmenu', '');
			$this->session->set_userdata('order_mmenu', 'NO_ID');
		}
	}

	public function index_mmenu(){
		$where = array(
			'EDIT' => 1,
			'EDIT_BY' => $this->session->userdata['username'],
		);
		$data = [
			'EDIT' => 0,
			'EDIT_BY' => ''
		];
		$this->master_model->update_data($where, $data, 'menu');

		// <!-- Ganti 2 -->
		$this->session->set_userdata('judul', 'User Type');
		//pagination
		$this->load->library('pagination');

		//ambil cari
		if ($this->input->post('submit')) {
			$data['keyword'] = $this->input->post('keyword');
			$this->session->set_userdata('keyword_mmenu', $data['keyword']);
		}

		$keyword = $this->session->userdata['keyword_mmenu'];
		$order_by = $this->session->userdata['order_mmenu'];



		if ($keyword != '') {
			$whr = '( KODE_MENU like "%' . $keyword . '%"  or NAMA_MENU like "%' . $keyword . '%" or LEVEL like "%' . $keyword . '%" or PARENT_MENU like "%' . $keyword . '%" or URL_MENU like "%' . $keyword . '%" )';
		} else {
			$whr = '';
		}

		$config['base_url'] = base_url('index.php/admin/mmenu/index_mmenu'); //sesuai lokasi controllers

		if ($order_by != '') {
			$config['total_rows'] = $this->master_model->tampil_total('menu', $order_by, $keyword, $whr);
		} else
			$config['total_rows'] = $this->master_model->tampil_total('menu', 'NO_ID', $keyword, $whr);

		$data['total_rows'] = $config['total_rows'];
		$config['per_page'] = 10;


		$config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul></nav>';

		$config['first_link'] = 'First';
		$config['first_tag_open'] = ' <li class="page-item">';
		$config['first_tag_close'] = '</li>';


		$config['last_link'] = 'Last';
		$config['last_tag_open'] = ' <li class="page-item">';
		$config['last_tag_close'] = '</li>';


		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = ' <li class="page-item">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&raquo';
		$config['prev_tag_open'] = ' <li class="page-item">';
		$config['prev_tag_close'] = '</li>';


		$config['cur_tag_open'] = ' <li class="page-item active"> <a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';


		$config['num_tag_open'] = ' <li class="page-item">';
		$config['num_tag_close'] = '</li>';



		$config['attributes'] = array('class' => 'page-link');


		$this->pagination->initialize($config);
		$data['start'] = $this->uri->segment(4); //angka 4 diambil dari urutal dari nama folder alamat yg ada angka




		if ($order_by != '') {
			$data['mmenu'] = $this->master_model->tampil('menu', $order_by, $config['per_page'], $data['start'], $keyword, $whr);
		} else {
			$data['mmenu'] = $this->master_model->tampil('menu', 'NO_ID', $config['per_page'], $data['start'], $keyword, $whr);
		}
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/mmenu/mmenu', $data);
		$this->load->view('templates_admin/footer');
	}

	public function getOrder()
	{

		$data['orderBy'] = $this->input->get('order');

		$this->session->set_userdata('order_mmenu', $data['orderBy']);

		// redirect('admin/po/index_po');

	}

	public function input(){
		$q2 = "SELECT KODE_MENU,NAMA_MENU from menu where LEVEL=0 ";

		$data = array(
			'MENU'  => $this->db->query($q2)
		);
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/mmenu/mmenu_form', $data);
		$this->load->view('templates_admin/footer');
	}


	public function input_aksi()
	{

		$data = array(
			'KODE_MENU' => $this->input->post('KODE_MENU', TRUE),
			'NAMA_MENU' => $this->input->post('NAMA_MENU', TRUE),
			'LEVEL' => $this->input->post('LEVEL', TRUE),
			'PARENT_MENU' => $this->input->post('PARENT_MENU', TRUE),
			'URL_MENU' => $this->input->post('URL_MENU', TRUE),
			'CREATE_BY' => $this->session->userdata['username'],
			'CREATE_AT' => date('Y-m-d H:i:s')
		);
		$this->master_model->input_data('menu', $data);

		$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data succesfully Inserted.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button> </div>');

		redirect('admin/mmenu/index_mmenu');
	}

	public function update($id)
	{
		$where = array(
			'NO_ID' => $id,
			'EDIT' => 1,

		);

		$cek = $this->master_model->edit_data(
			$where,
			'menu'
		);

		if ($cek->num_rows() > 0) {


			$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data masih di buka user lain...
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			 </button> </div>');


			redirect('admin/mmenu/index_mmenu');
		} else {
			$where = array(
				'NO_ID' => $id
			);
			$data = [
				'EDIT' => 1,
				'EDIT_BY' => $this->session->userdata['username'],
				'EDIT_AT' => date('Y-m-d H:i:s')
			];
			$this->master_model->update_data($where, $data, 'menu');
			$q1 = "SELECT * from menu WHERE NO_ID='$id' ";
            $q2 = "SELECT KODE_MENU,NAMA_MENU from menu where LEVEL=0 ";
			$data = array();
			$data = array(
				'MMENU'  => $this->db->query($q1)->result(),
				'MENU'  => $this->db->query($q2)
			);
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/mmenu/mmenu_update', $data);
			$this->load->view('templates_admin/footer');
		}
	}

	public function lihat($id){
		$q1 = "SELECT * from menu WHERE NO_ID='$id' ";
            $q2 = "SELECT KODE_MENU,NAMA_MENU from menu where LEVEL=0 ";
			$data = array();
			$data = array(
				'MMENU'  => $this->db->query($q1)->result(),
				'MENU'  => $this->db->query($q2)
			);
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/mmenu/mmenu_lihat', $data);
			$this->load->view('templates_admin/footer');
	}


	// <!-- Ganti 7 -->

	public function update_aksi()
	{
		$datah = array(
			'KODE_MENU' => $this->input->post('KODE_MENU', TRUE),
			'NAMA_MENU' => $this->input->post('NAMA_MENU', TRUE),
			'LEVEL' => $this->input->post('LEVEL', TRUE),
			'PARENT_MENU' => $this->input->post('PARENT_MENU', TRUE),
			'URL_MENU' => $this->input->post('URL_MENU', TRUE),
			'UPDATE_BY' => $this->session->userdata['user_id'],
			'UPDATE_AT' => date('Y-m-d H:i:s')
		);
		$where = array(
			'NO_ID' => $this->input->post('ID', TRUE)
		);

		$this->transaksi_model->update_data($where, $datah, 'menu');

		$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data succesfully Updated.
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                          </button> </div>');

		redirect('admin/mmenu/index_mmenu');
	}

	public function delete($id)
	{

		//  <!-- Ganti 9 -->

		$where = array('NO_ID' => $id);
		$this->master_model->hapus_data($where, 'menu');
		$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data succesfully Deleted.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
            </button> </div>');

		//  <!-- Ganti 10 -->

		redirect('admin/mmenu/index_mmenu');
	}

	//  <!-- Ganti 11 -->

	function delete_multiple()
	{

		$this->master_model->remove_checked('menu');
		redirect('admin/mmenu/index_mmenu');
	}

	function cek_crm()
	{
		$crm = $this->input->get('crm');

		$link = mysqli_connect("10.10.30.225", "Root", "crmProdTG", "tiara_gatsu_production");
		$sql = "SELECT M.nama
				FROM members AS M JOIN Jenis_pelanggans AS J ON (M.id = J.idmember)
				WHERE m.kode = '$crm'
				AND J.idf_jenis_pelanggan = 5
				AND J.is_active = 1 ";
		$q2 = mysqli_query($link, $sql);
		if (mysqli_num_rows($q2) > 0) {
			foreach ($q2 as $row) {
				$hasil[] = $row;
			}
		};
		echo json_encode($q2);
	}
}
