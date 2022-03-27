<?php


class Akses extends CI_Controller{
    
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
		$data['akses'] = $this->db->query("SELECT * FROM hakakses")->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/akses',$data);
        $this->load->view('templates_admin/footer');
		}
		
		public function update_akses() {
			$get_data = $this->db->query("SELECT * from hakakses")->result();
			$banyak_data = count($get_data);
			$create = array();
			$delete = array();
			$edit = array();
			$print = array();
			$no_id = array();
			$checkC = $this->input->post("checkC");
			$checkD = $this->input->post("checkD");
			$checkE = $this->input->post("checkE");
			$checkP = $this->input->post("checkP");
			$id = $this->input->post("id");
			for($i=0; $i<$banyak_data;$i++){
					$create = $checkC[$i];
					$delete = $checkD[$i];
					$edit = $checkE[$i];
					$print = $checkP[$i];
					$no_id = $id[$i];
					// $query = "UPDATE hakakses SET 
					// 					`CREATE`='$create[$i]',
					// 					`DELETE`='$delete[$i]',
					// 					`EDIT`='$edit[$i]',
					// 					`PRINT`='$print[$i]'
					// 					WHERE NO_ID='$no_id[$i]' ";

					// $this->db->query($query);
			}
			echo json_encode($create);
			echo json_encode($delete);
			echo json_encode($edit);
			echo json_encode($print);
			echo json_encode($no_id);
			// redirect('admin/akses');
    }
}

?>