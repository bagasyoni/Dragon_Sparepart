<?php

class Auth extends CI_Controller
{

    public function index()
    {
        $this->load->view('templates_admin/header');
        $this->load->view('admin/login');
        $this->load->view('templates_admin/footer');
    }

    public function proses_login()
    {
        // function get_client_ip() {
        // 	$ipaddress = '';
        // 	if (isset($_SERVER['HTTP_CLIENT_IP']))
        // 		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        // 	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        // 		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // 	else if(isset($_SERVER['HTTP_X_FORWARDED']))
        // 		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        // 	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        // 		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        // 	else if(isset($_SERVER['HTTP_FORWARDED']))
        // 		$ipaddress = $_SERVER['HTTP_FORWARDED'];
        // 	else if(isset($_SERVER['REMOTE_ADDR']))
        // 		$ipaddress = $_SERVER['REMOTE_ADDR'];
        // 	else
        // 		$ipaddress = 'UNKNOWN';
        // 	return $ipaddress;
        // }
        $this->form_validation->set_rules('username', 'username', 'required', ['required' => 'Username wajib di isi!!']);
        $this->form_validation->set_rules('password', 'password', 'required', ['required' => 'Password wajib di isi!!']);
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates_admin/header');
            $this->load->view('admin/login');
            $this->load->view('templates_admin/footer');
        } else {
            $month = date('m');
            $year = date('Y');
            $periode = $month . '/' . $year;
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $username;
            $pass = MD5($password);
            $cek = $this->login_model->cek_login($user, $pass);
            if ($cek->num_rows() > 0) {
                foreach ($cek->result() as $ck) {
                    $sess_data['id'] = $ck->NO_ID;
                    // set data font dari model
                    $font       = $this->font_model->get_font($sess_data['id']);
                    $id_font    = $this->font_model->get_id($sess_data['id']);
                    $size_font  = $this->font_model->get_size($sess_data['id']);
                    // masukan ke sesi
                    $sess_data['font'] = $font;
                    $sess_data['id_font'] = $id_font;
                    $sess_data['size_font'] = $size_font;
                    $sess_data['username'] = $ck->USERNAME;
                    $sess_data['level'] = $ck->AKSES;
                    $sess_data['super_admin'] = $ck->SUPER_ADMIN;
                    $sess_data['periode'] = $periode;
                    $sess_data['dr'] = $ck->DR;
                    $sess_data['sub'] = $ck->SUB;
                    $sess_data['pin'] = $ck->PIN;
                    $sess_data['flag'] = '';
                    $sess_data['judul'] = '';
                    $sess_data['menu_sparepart'] = '';
                    $this->session->set_userdata($sess_data);
                }
                if ($sess_data['level'] != '') {
                    redirect('admin/dashboard');
                } else {
                    $this->session->set_flashdata(
                        'pesan',
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Username atau Password Anda Salah!!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>'
                    );
                    redirect('admin/auth');
                }
            } else {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Username atau Password Anda Salah!!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'
                );
                redirect('admin/auth');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/auth');
    }
}
