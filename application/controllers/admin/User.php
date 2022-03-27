<?php

class user extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		if (!isset($this->session->userdata['username'])) {
			$this->session->set_flashdata('pesan', 
				'<div class="alert alert-danger alert-dismissible fade show" role="alert">
					Anda Belum Login
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>');
			redirect('admin/auth');
		}
		if ($this->session->userdata['menu_pemasaran'] != 'user') {
			$this->session->set_userdata('menu_pemasaran', 'user');
			$this->session->set_userdata('kode_menu', '110003');
			$this->session->set_userdata('keyword_user', '');
			$this->session->set_userdata('order_user', 'NO_ID');
		}
	}

	public function index_User(){
		$where = array(
			'EDIT' => 1,
			'EDIT_BY' => $this->session->userdata['user_id'],
		);
		$data = [
			'EDIT' => 0,
			'EDIT_BY' => ''
		];
		$this->master_model->update_data($where, $data, 'users');
		$this->session->set_userdata('judul', 'User Type');
		$this->load->library('pagination');
		if ($this->input->post('submit')) {
			$data['keyword'] = $this->input->post('keyword');
			$this->session->set_userdata('keyword_user', $data['keyword']);
		}
		$keyword = $this->session->userdata['keyword_user'];
		$order_by = $this->session->userdata['order_user'];
		if ($keyword != '') {
			$whr = '( USERNAME like "%' . $keyword . '%"  or AKSES like "%' . $keyword . '%"  ) ';
		} else {
			$whr = '';
		}
		$config['base_url'] = base_url('index.php/admin/user/index_user'); //sesuai lokasi controllers
		if ($order_by != '') {
			$config['total_rows'] = $this->master_model->tampil_total('users', $order_by, $keyword, $whr);
		} else
		$config['total_rows'] = $this->master_model->tampil_total('users', 'NO_ID', $keyword, $whr);
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
			$data['users'] = $this->master_model->tampil('users', $order_by, $config['per_page'], $data['start'], $keyword, $whr);
		} else {
			$data['users'] = $this->master_model->tampil('users', 'NO_ID', $config['per_page'], $data['start'], $keyword, $whr);
		}
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/user/user', $data);
		$this->load->view('templates_admin/footer');
	}

	public function getOrder(){
		$data['orderBy'] = $this->input->get('order');
		$this->session->set_userdata('order_user', $data['orderBy']);
	}

	public function input(){
		$q1 = "SELECT user_level FROM user_type ";
		$data = array(
			'AKSES'  => $this->db->query($q1)
		);
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/user/user_form', $data);
		$this->load->view('templates_admin/footer');
	}

	public function input_aksi(){
		$this->form_validation->set_rules('USERNAME', 'Username', 'required|trim|valid_email|is_unique[users.USERNAME]');
		$this->form_validation->set_rules('NAMA', 'Nama', 'required|trim');
		$this->form_validation->set_rules('PASSWORD', 'Password', 'required|trim|matches[PASSWORD2]|exact_length[8]');
		$this->form_validation->set_rules('PASSWORD2', 'Confirm Password', 'required|trim|matches[PASSWORD]|exact_length[8]');
		if ($this->form_validation->run() == false) {
			$q1 = "SELECT user_level FROM user_type ";
			$data = array(
				'AKSES'  => $this->db->query($q1)
			);
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/user/user_form', $data);
			$this->load->view('templates_admin/footer');
		} else {
			$nomerb = $this->db->query("SELECT USER FROM nomer WHERE BULAN=1 ")->result();
			$nomb = array_column($nomerb, 'USER');
			$value22b = $nomb[0] + 1;
			$urutb = str_pad($value22b, 4, "0", STR_PAD_LEFT);
			$this->db->query("UPDATE nomer set USER=$value22b WHERE BULAN=1 ");
			$data = array(
				'KODE' => $urutb,
				'USERNAME' => $this->input->post('USERNAME', TRUE),
				'PASSWORD' => MD5($this->input->post('PASSWORD', TRUE)),
				'NAMA' => $this->input->post('NAMA', TRUE),
				'TELPON' => $this->input->post('TELPON', TRUE),
				'HP' => $this->input->post('HP', TRUE),
				'AKSES' => $this->input->post('AKSES', TRUE),
				'CREATE_BY' => $this->session->userdata['user_id'],
				'CREATE_AT' => date('Y-m-d H:i:s')
			);
			$this->master_model->input_data('users', $data);
			$this->session->set_flashdata('pesan', 
				'<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
					Data succesfully Inserted.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> 
				</div>');
			redirect('admin/user/index_user');
		};
	}

	public function update($id) {
		$where = array(
			'NO_ID' => $id,
			'EDIT' => 1,
		);
		$cek = $this->master_model->edit_data(
			$where,
			'users'
		);
		if ($cek->num_rows() > 0) {
			$this->session->set_flashdata('pesan', 
				'<div class="alert alert-success alert-dismissible fade show" role="alert"> 
					Data masih di buka user lain...
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> 
				</div>');
			redirect('admin/user/index_user');
		} else {
			$where = array(
				'NO_ID' => $id
			);
			$data = [
				'EDIT' => 1,
				'EDIT_BY' => $this->session->userdata['user_id'],
				'EDIT_AT' => date('Y-m-d H:i:s')
			];
			$this->master_model->update_data($where, $data, 'users');
			$where = array('NO_ID' => $id);
			$ambildata = $this->master_model->edit_data(
				$where,
				'users'
			);
			$r = $ambildata->row_array();
			$q2 = "SELECT user_level FROM user_type ";
			$data = [
				'NO_ID' => $r['NO_ID'],
				'KODE' => $r['KODE'],
				'USERNAME' => $r['USERNAME'],
				'NAMA' => $r['NAMA'],
				'TELPON' => $r['TELPON'],
				'HP' => $r['HP'],
				'AKSES'  => $r['AKSES'],
				'AKSESD'  => $this->db->query($q2)
			];
			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/user/user_update', $data);
			$this->load->view('templates_admin/footer');
		}
	}

	public function lihat($id) {
		$where = array('NO_ID' => $id);
		$ambildata = $this->master_model->edit_data(
			$where,
			'users'
		);
		$r = $ambildata->row_array();
		$q2 = "SELECT user_level FROM user_type ";
		$data = [
			'NO_ID' => $r['NO_ID'],
			'KODE' => $r['KODE'],
			'USERNAME' => $r['USERNAME'],
			'NAMA' => $r['NAMA'],
			'TELPON' => $r['TELPON'],
			'HP' => $r['HP'],
			'AKSES'  => $r['AKSES'],
			'AKSESD'  => $this->db->query($q2)
		];
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/user/user_lihat', $data);
		$this->load->view('templates_admin/footer');
	}

	public function update_aksi() {
		$data = array(
			'USERNAME' => $this->input->post('USERNAME', TRUE),
			'NAMA' => $this->input->post('NAMA', TRUE),
			'TELPON' => $this->input->post('TELPON', TRUE),
			'HP' => $this->input->post('HP', TRUE),
			'AKSES' => $this->input->post('AKSES', TRUE),
			'UPDATE_BY' => $this->session->userdata['user_id'],
			'UPDATE_AT' => date('Y-m-d H:i:s')
		);
		$where = array(
			'NO_ID' => $this->input->post('ID', TRUE)
		);
		$this->master_model->update_data($where, $data, 'users');
		$this->session->set_flashdata('pesan', 
			'<div class="alert alert-success alert-dismissible fade show" role="alert"> 
				Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
			</div>');
		redirect('admin/user/index_user');
	}

	public function delete($id) {	
		$where = array('NO_ID' => $id);
		$this->master_model->hapus_data($where, 'users');
		$this->session->set_flashdata('pesan', 
			'<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
				Data succesfully Deleted.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>');
		redirect('admin/user/index_user');
	}

	function delete_multiple() {
		$this->master_model->remove_checked('users');
		redirect('admin/user/index_user');
	}

}
