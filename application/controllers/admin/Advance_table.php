<?php


class Advance_table extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        if(!isset($this->session->userdata['username'])){
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Anda Belum Login
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>');
           redirect('admin/auth');
        }
    }
    
    public function index()
    {
		
				// $data = $this->user_model->ambil_data($this->session->userdata['username']); 
				// $data = array (
				// 		'username' => $data->username,
				// 		'level'   => $data->level,
				// 		'periode' => $this->session->userdata['periode'],
				// );

        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/advance_table');
        $this->load->view('templates_admin/footer');
		}
		
		public function ganti_periode()
    {
			
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
					
			
			redirect('admin/advance_table');

    }
}

?>
