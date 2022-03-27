<?php  

class Login_model extends CI_Model {

    public function cek_login($username , $password) {
        $this->db->where("username", $username);
        $this->db->where("password", $password);        
        return $this->db->get('users');
    }

    public function getLoginData($user,$pass) {
        $u = $user;
        $p = MD5($pass);
		$month = date('m');
		$year = date('Y');
		$periode = $month . '/' . $year;
        $query_cekLogin = $this->db->get_where('users',
        array('username' =>$u ,'password' => $p));
        if (count($query_cekLogin->result())>0) {
            foreach ($query_cekLogin->result() as $qck){
                foreach ($query_cekLogin->result() as $ck) {
                    $sess_data ['logged_in'] = TRUE;
                    $sess_data ['username'] = $ck->USERNAME;
                    $sess_data ['password'] = $ck->PASSWORD;
                    $sess_data ['level'] = $ck->AKSES;
                    $sess_data ['periode'] = $periode;
                    // session sparepart
                    $sess_data ['super_admin'] = $ck->SUPER_ADMIN;
                    $sess_data ['dr'] = $ck->DR;
                    $sess_data ['sub'] = $ck->SUB;
                    $sess_data ['pin'] = $ck->PIN;
                    $this->session->set_userdata($sess_data);
                }
                redirect('admin/dashboard');
            }
        }else{
            $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Username atau Password Anda Salah!!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('admin/auth');
        }
    }
}
