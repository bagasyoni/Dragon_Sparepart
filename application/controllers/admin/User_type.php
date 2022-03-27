<?php

// <!-- Ganti 0 -->
class user_type extends CI_Controller
{

	function __construct() {
		parent::__construct();
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
		if ($this->session->userdata['menu_pemasaran'] != 'user_type') {
			$this->session->set_userdata('menu_pemasaran', 'user_type');
            $this->session->set_userdata('kode_menu', '110002');
			$this->session->set_userdata('keyword_user_type', '');
			$this->session->set_userdata('order_user_type', 'no_id');
		}
	}

	public function index_user_type() {
		$where = array(
			'EDIT' => 1,
			'EDIT_BY' => $this->session->userdata['username'],
		);
		$data = [
			'EDIT' => 0,
			'EDIT_BY' => ''
		];
		$this->master_model->update_data($where, $data, 'user_type');
		// <!-- Ganti 2 -->
		$this->session->set_userdata('judul', 'User Type');
		//pagination
		$this->load->library('pagination');
		//ambil cari
		if ($this->input->post('submit')) {
			$data['keyword'] = $this->input->post('keyword');
			$this->session->set_userdata('keyword_user_type', $data['keyword']);
		}
		$keyword = $this->session->userdata['keyword_user_type'];
		$order_by = $this->session->userdata['order_user_type'];
		if ($keyword != '') {
			$whr = '( user_level like "%' . $keyword . '%"   )';
		} else {
			$whr = '';
		}
		$config['base_url'] = base_url('index.php/admin/user_type/index_user_type'); //sesuai lokasi controllers
		if ($order_by != '') {
			$config['total_rows'] = $this->master_model->tampil_total('user_type', $order_by, $keyword, $whr);
		} else
			$config['total_rows'] = $this->master_model->tampil_total('user_type', 'no_id', $keyword, $whr);
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
		//////////////////
		if ($order_by != '') {
			$data['user_type'] = $this->master_model->tampil('user_type', $order_by, $config['per_page'], $data['start'], $keyword, $whr);
		} else {
			$data['user_type'] = $this->master_model->tampil('user_type', 'no_id', $config['per_page'], $data['start'], $keyword, $whr);
		}
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/user_type/user_type', $data);
		$this->load->view('templates_admin/footer');
	}

	public function getOrder(){
		$data['orderBy'] = $this->input->get('order');
		$this->session->set_userdata('order_user_type', $data['orderBy']);
		// redirect('admin/po/index_po');
	}

	public function input(){
		$q1 = " select 0 no_id,kode_menu,nama_menu,1 baru,1 edit,1 hapus,1 lihat,IF(PARENT_MENU='',KODE_MENU,PARENT_MENU) URUT,LEVEL from menu ORDER BY URUT,LEVEL ";
		$data = array(
			'user_type'  => $this->db->query($q1)->result()
		);
		$this->load->view('templates_admin/header');
		$this->load->view('templates_admin/navbar');
		$this->load->view('admin/user_type/user_type_form', $data);
		$this->load->view('templates_admin/footer');
	}


	public function input_aksi() {
		$user_level = $this->input->post('USER_TYPE', TRUE);
		$datah = array(
			'user_level' => $user_level,
			'create_by' => $this->session->userdata['username'],
			'create_at' => date('Y-m-d H:i:s')
		);
		$this->transaksi_model->input_datah('user_type', $datah);
		$data = $this->db->query("SELECT * from user_type WHERE user_level='$user_level' ")->result();
		//      batas background proses
		$NO_ID = $this->input->post('no_id');
		$KODE_MENU = $this->input->post('kode_menu');
		$NAMA_MENU = $this->input->post('nama_menu');
		$LIHAT = $this->input->post('lihat');
		$BARU = $this->input->post('baru');
		$EDIT = $this->input->post('edit');
		$ID = $data[0]->no_id;
		$jumy = count($NO_ID);
		$i = 0;
		while ($i < $jumy) {
			if ($NO_ID[$i] == "0") {
				$datad = array(
					'id' => $ID,
					'user_level' => $user_level,
					'kode_menu' => $KODE_MENU[$i],
					'nama_menu' => $NAMA_MENU[$i],
					'lihat' => 0,
					'edit' => 0,
					'baru' => 0
				);
				$this->transaksi_model->input_datad('user_typed', $datad);
			}
			$i++;
		}
		$jumy = count($LIHAT);
		$i = 0;
		while ($i < $jumy) {
			$datad = array(
				'lihat' => 1
			);
			$where = array(
				'kode_menu' => $LIHAT[$i],
				'id' => $ID,
			);
			$this->transaksi_model->update_data($where, $datad, 'user_typed');
			$i++;
		}
		$jumy = count($BARU);
		$i = 0;
		while ($i < $jumy) {
			$datad = array(
				'baru' => 1
			);
			$where = array(
				'kode_menu' => $BARU[$i],
				'id' => $ID
			);
			$this->transaksi_model->update_data($where, $datad, 'user_typed');
			$i++;
		}
		$jumy = count($EDIT);
		$i = 0;
		while ($i < $jumy) {
			$datad = array(
				'edit' => 1
			);
			$where = array(
				'kode_menu' => $EDIT[$i],
				'id' => $ID
			);
			$this->transaksi_model->update_data($where, $datad, 'user_typed');
			$i++;
		}
		$this->session->set_flashdata('pesan', 
			'<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
				Data succesfully Inserted.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button> 
			</div>');
		redirect('admin/user_type/index_user_type');
	}

	public function update($id) {
		$where = array(
			'no_id' => $id,
			'EDIT' => 1,
		);
		$cek = $this->master_model->edit_data(
			$where,
			'user_type'
		);
		if ($cek->num_rows() > 0) {
			$this->session->set_flashdata('pesan', 
				'<div class="alert alert-success alert-dismissible fade show" role="alert"> 
					Data masih di buka user lain...
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> 
				</div>');
			redirect('admin/user_type/index_user_type');
		} else {
			$where = array(
				'no_id' => $id
			);
			$data = [
				'EDIT' => 1,
				'EDIT_BY' => $this->session->userdata['username'],
				'EDIT_AT' => date('Y-m-d H:i:s')
			];
			$this->master_model->update_data($where, $data, 'user_type');




			$user_level = $this->db->query("SELECT user_level FROM user_type where no_id='$id' ")->result();

			if (!$user_level) {
				$resul = '';
			} else {
				$resul = $user_level[0]->user_level;
			}


			$q1 = "SELECT  user_typed.no_id, '$id' id, user_typed.kode_menu, user_typed.nama_menu,'$resul' user_level, user_typed.baru, user_typed.edit, user_typed.hapus, user_typed.lihat,IF(MENU.PARENT_MENU='',MENU.KODE_MENU,MENU.PARENT_MENU) URUT ,MENU.LEVEL FROM user_typed LEFT JOIN MENU ON user_typed.KODE_MENU=MENU.KODE_MENU where id='$id' union ALL
				select 0 no_id,'$id' id,kode_menu,nama_menu,'$resul' user_level,0 baru,0 edit,0 hapus,0 lihat,IF(PARENT_MENU='',KODE_MENU,PARENT_MENU) URUT,LEVEL from menu where kode_menu not in (select KODE_MENU FROM user_typed where id='$id') ORDER BY URUT,LEVEL ";



			$data = array(
				'user_type'  => $this->db->query($q1)->result()
			);



			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/user_type/user_type_update', $data);
			$this->load->view('templates_admin/footer');
		}
	}


	public function lihat($id)
	{

		$where = array(
			'no_id' => $id,
			'EDIT' => 1,

		);

		$cek = $this->master_model->edit_data(
			$where,
			'user_type'
		);

		if ($cek->num_rows() > 0) {


			$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data masih di buka user lain...
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			 </button> </div>');


			redirect('admin/user_type/index_user_type');
		} else {

			$where = array(
				'no_id' => $id
			);

			$data = [
				'EDIT' => 1,
				'EDIT_BY' => $this->session->userdata['username'],
				'EDIT_AT' => date('Y-m-d H:i:s')

			];

			$this->master_model->update_data($where, $data, 'user_type');




			$user_level = $this->db->query("SELECT user_level FROM user_type where no_id='$id' ")->result();

			if (!$user_level) {
				$resul = '';
			} else {
				$resul = $user_level[0]->user_level;
			}


			$q1 = "SELECT  user_typed.no_id, '$id' id, user_typed.kode_menu, user_typed.nama_menu,'$resul' user_level, user_typed.baru, user_typed.edit, user_typed.hapus, user_typed.lihat,IF(MENU.PARENT_MENU='',MENU.KODE_MENU,MENU.PARENT_MENU) URUT ,MENU.LEVEL FROM user_typed LEFT JOIN MENU ON user_typed.KODE_MENU=MENU.KODE_MENU where id='$id' union ALL
				select 0 no_id,'$id' id,kode_menu,nama_menu,'$resul' user_level,0 baru,0 edit,0 hapus,0 lihat,IF(PARENT_MENU='',KODE_MENU,PARENT_MENU) URUT,LEVEL from menu where kode_menu not in (select KODE_MENU FROM user_typed where id='$id') ORDER BY URUT,LEVEL ";



			$data = array(
				'user_type'  => $this->db->query($q1)->result()
			);



			$this->load->view('templates_admin/header');
			$this->load->view('templates_admin/navbar');
			$this->load->view('admin/user_type/user_type_lihat', $data);
			$this->load->view('templates_admin/footer');
		}
	}

	// <!-- Ganti 7 -->

	public function update_aksi()
	{
		$id = $this->input->post('id', TRUE);
		$user_level = $this->input->post('USER_TYPE', TRUE);


		$datah = array(
			'user_level' => $user_level,
			'update_by' => $this->session->userdata['username'],
			'update_at' => date('Y-m-d H:i:s')
		);


		$where = array(
			'no_id' => $id
		);



		$this->master_model->update_data($where, $datah, 'user_type');



		$data = $this->db->query("SELECT * from user_typed WHERE id='$id' ")->result();

		//      batas background proses
		$NO_ID = $this->input->post('no_id');
		$KODE_MENU = $this->input->post('kode_menu');
		$NAMA_MENU = $this->input->post('nama_menu');
		$LIHAT = $this->input->post('lihat');
		$BARU = $this->input->post('baru');
		$EDIT = $this->input->post('edit');



		$jum = count($data);
		$ID = array_column($data, 'no_id');


		if ($jum > 0) {
			$i = 0;
			while ($i < $jum) {
				if (in_array($ID[$i], $NO_ID)) {

					$URUT = array_search($ID[$i], $NO_ID);

					$datad = array(

						'kode_menu' => $KODE_MENU[$i],
						'nama_menu' => $NAMA_MENU[$i],
						'lihat' => 0,
						'edit' => 0,
						'baru' => 0

					);



					$where = array(
						'no_id' => $NO_ID[$URUT]
					);
					$this->transaksi_model->update_data($where, $datad, 'user_typed');
				} else {
					$where = array(
						'no_id' => $ID[$i]
					);
					$this->transaksi_model->hapus_data($where, 'user_typed');
				}
				$i++;
			}
		}

		$jumy = count($NO_ID);

		$i = 0;
		while ($i < $jumy) {

			if ($NO_ID[$i] == "0") {

				$datad = array(
					'id' => $this->input->post('id', TRUE),
					'user_level' => $user_level,
					'kode_menu' => $KODE_MENU[$i],
					'nama_menu' => $NAMA_MENU[$i],
					'lihat' => 0,
					'edit' => 0,
					'baru' => 0

				);
				$this->transaksi_model->input_datad('user_typed', $datad);
			}
			$i++;
		}


		$jumy = count($LIHAT);

		$i = 0;
		while ($i < $jumy) {


			$datad = array(

				'lihat' => 1
			);

			$where = array(
				'kode_menu' => $LIHAT[$i],
				'id' => $this->input->post('id', TRUE),
			);
			$this->transaksi_model->update_data($where, $datad, 'user_typed');


			$i++;
		}

		$jumy = count($BARU);

		$i = 0;
		while ($i < $jumy) {


			$datad = array(

				'baru' => 1
			);

			$where = array(
				'kode_menu' => $BARU[$i],
				'id' => $this->input->post('id', TRUE),
			);
			$this->transaksi_model->update_data($where, $datad, 'user_typed');


			$i++;
		}

		$jumy = count($EDIT);

		$i = 0;
		while ($i < $jumy) {


			$datad = array(

				'edit' => 1
			);

			$where = array(
				'kode_menu' => $EDIT[$i],
				'id' => $this->input->post('id', TRUE),
			);
			$this->transaksi_model->update_data($where, $datad, 'user_typed');


			$i++;
		}




		$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data succesfully Updated.
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                          </button> </div>');

		redirect('admin/user_type/index_user_type');
	}

	public function delete($id)
	{

		//  <!-- Ganti 9 -->

		$where = array('NO_ID' => $id);
		$this->master_model->hapus_data($where, 'dompet_tgz');
		$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data succesfully Deleted.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
            </button> </div>');

		//  <!-- Ganti 10 -->

		redirect('admin/user_type/index_user_type');
	}

	//  <!-- Ganti 11 -->

	function delete_multiple()
	{

		$this->master_model->remove_checked('dompet_tgz');
		redirect('admin/user_type/index_user_type');
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
