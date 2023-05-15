<?php

class Transaksi_BonBelumValidasi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
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
        if ($this->session->userdata['menu_sparepart'] != 'pakai') {
            $this->session->set_userdata('menu_sparepart', 'pakai');
            $this->session->set_userdata('kode_menu', 'T0034');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'DEVISI', 'KET', 'PESAN');
    var $column_search = array('NO_BUKTI', 'TGL', 'DEVISI', 'KET', 'PESAN');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            // 'PER' => $per,
            // 'DR' => $dr,
            'FLAG' => '0',
            // 'SUB' => 'MB',
            // 'FLAG2' => 'SP',
            'OK' => '0',
            // 'TYP' => 'RND_LBBA',
        );
        $this->db->select('*');
        $this->db->from('bon');
        $this->db->where($where);
        $i = 0;
        foreach ($this->column_search as $item) {
            if (@$_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            // 'PER' => $per,
            // 'DR' => $dr,
            'FLAG' => '0',
            // 'SUB' => 'MB',
            // 'FLAG2' => 'SP',
            'OK' => '0',
            // 'TYP' => 'RND_LBBA',
        );
        $this->db->from('bon');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_pakai()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $pakai) {
            // $JASPER = "window.open('JASPER/" . $pakai->NO_ID . "','', 'width=1000','height=900');";
            // <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
            // if($pakai->TTD7 == 0){
            //     $hidden = '';
            // }else{
            //     $hidden = 'hidden';
            // }
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $pakai->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a  class="dropdown-item" href="' . site_url('admin/Transaksi_BonBelumValidasi/update/' . $pakai->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_BonBelumValidasi/validasi/' . $pakai->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_BonBelumValidasi/delete/' . $pakai->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pakai->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pakai->TGL));
            $row[] = $pakai->TUJUAN;
            $row[] = $pakai->USRNM;
            $row[] = $pakai->DR;
            $row[] = $pakai->TTD1_SMP;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function index_Transaksi_BonBelumValidasi()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            // 'PER' => $per,
            // 'DR' => $dr,
            'FLAG' => '0',
            // 'SUB' => 'MB',
            // 'FLAG2' => 'SP',
            'OK' => '0',
            // 'TYP' => 'RND_LBBA',
        );
        $data['pakai'] = $this->transaksi_model->tampil_data($where, 'bon', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT COALESCE(MAX(NO_BUKTI), 0) as NO_BUKTI FROM pakai WHERE SUB='MB' AND PER='$per' AND FLAG='PK' AND FLAG2='SP'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        if($nom[0]=='0'){
            $value11 = 0;
        }else{
            $value11 = substr($nom[0], 3, 4);
        }
        $value22 = STRVAL($value11) + 1;
        $urut = str_pad($value22, 4, "0", STR_PAD_LEFT);
        $tahun = substr($this->session->userdata['periode'], -4);
        if (substr($this->session->userdata['periode'], 0, 2) == 1) {
            $romawi = 'I';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 2) {
            $romawi = 'II';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 3) {
            $romawi = 'III';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 4) {
            $romawi = 'IV';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 5) {
            $romawi = 'V';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 6) {
            $romawi = 'VI';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 7) {
            $romawi = 'VII';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 8) {
            $romawi = 'VIII';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 9) {
            $romawi = 'IX';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 10) {
            $romawi = 'X';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 11) {
            $romawi = 'XI';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 12) {
            $romawi = 'XII';
        }
        // PK / NOMER / DR / BULAN / TAHUN / CNC
        // $bukti = 'PK' . '/' . $urut . '/' . "LB" . '/' . $dr . '/' . $romawi . '/' . $tahun;
        $bukti = 'PK' . '/' . $urut . '/' . $dr . '/' . "MB" .  '/' . $romawi . '/' . $tahun;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form');
        $this->load->view('templates_admin/footer');
    }

    public function resizeImage($filename)
    {
        $source_path = './gambar/' . $filename;
        $target_path = './gambar/thumbnail/';
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => 150,
            'height' => 150
        );


        $this->load->library('image_lib', $config_manip);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }


        $this->image_lib->clear();
    }

    public function resizeImaged($filename)
    {
        $source_path = './gambar/' . $filename;
        $target_path = './gambar/thumbnail/';
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => 150,
            'height' => 150
        );


        $this->load->library('image_lib', $config_manip);
        $this->image_lib->initialize($config_manip);
        // if (!$this->image_lib->resize()) {
        //     echo $this->image_lib->display_errors();
        // }


        $this->image_lib->resize();
        $this->image_lib->clear();
    }

    public function input_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		// $config['max_size']             = 1000;
		// $config['max_width']            = 3024;
		// $config['max_height']           = 3680;
        // $config['width']            = 1000;
		// $config['height']           = 800;
        $new_name = 'IMG'.$bukti;
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
            // var_dump($data['upload_data']['file_name']);
            $this->resizeImaged($data['upload_data']['file_name']);
			$this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
		}
// die;
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'FLAG3' => $this->input->post('FLAG3', TRUE),
            'GAMBAR1' => $this->upload->data('file_name'),
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'TYP' => 'RND_MELBBA',
            'SUB' => 'MB',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pakai', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM pakai WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $KD_BHN = $this->input->post('KD_BHN');
        $WARNA = $this->input->post('WARNA');
        $SERI = $this->input->post('SERI');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET = $this->input->post('KET');
        $KET = $this->input->post('KET');
        $TGL_DIMINTAX = date("Y-m-d", strtotime($this->input->post('TGL_DIMINTAX', TRUE)));
        $i = 0;
        foreach ($REC as $a) {
            $configx['upload_path']          = './gambar/';
            $configx['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
            // $configx['max_size']             = 1000;
            // $configx['max_width']            = 3024;
            // $configx['max_height']           = 3680;
            // $config['width']            = 1000;
		    // $config['height']           = 800;
            $new_name = 'IMGD'.$bukti.'-'.$REC[$i];
            $configx['file_name']            = $new_name; 

            $this->load->library('upload', $configx);
            $this->upload->initialize($configx);

            if ( ! $this->upload->do_upload('GAMBAR1X'.$i)){
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form', $error);
            }else{
                $data = array('upload_data' => $this->upload->data());
                // var_dump($data['upload_data']['file_name']);
                $this->resizeImaged($data['upload_data']['file_name']);
                $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
            }
// die;
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'REC' => $REC[$i],
                'NA_BHN' => $NA_BHN[$i],
                'KD_BHN' => $KD_BHN[$i],
                'WARNA' => $WARNA[$i],
                'SERI' => $SERI[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET' => $KET[$i],
                'GAMBAR1' => $this->upload->data('file_name'),
                'TGL_DIMINTA' => $TGL_DIMINTAX[$i],
                'FLAG' => 'PK',
                'SUB' => 'MB',
                'TYP' => 'RND_LBBA',
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('pakaid', $datad);
            $i++;
            // die;

        }
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonBelumValidasi/index_Transaksi_BonBelumValidasi');
    }

    public function update($NO_ID)
    {
        // $where = array('NO_ID' => $NO_ID);
        // $ambildata = $this->master_model->edit_data($where, 'pakai');
        // $q1 ="SELECT a.NO_ID, a.NO_BUKTI, a.TGL, a.TGL_DIMINTA, a.DEVISI, a.ARTICLE, a.PESAN, 
        //         a.JO, a.FLAG3, a.GAMBAR1, a.VAL, a.TOTAL_QTY, b.GAMBAR1 AS GDETAIL, b.REC, b.NA_BHN, 
        //         b.KD_BHN, b.WARNA, b.SERI, b.QTY, b.SATUAN, DATE_FORMAT(b.TGL_DIMINTA, '%d-%m-%Y') AS TGL_DIMINTAD, b.KET AS KET,
        //         b.NO_ID AS NO_IDX
        //         FROM pakai a,pakaid b WHERE a.NO_ID = '$NO_ID' AND a.NO_BUKTI = b.NO_BUKTI";
        $q1 ="SELECT a.NO_ID, a.NO_BUKTI, a.DR, DATE_FORMAT(a.TGL, '%d-%m-%Y') AS TGL, a.DR, a.TUJUAN, a.NOTES, b.NA_BHN, b.KET1, b.QTY, b.KET2, b.SP
                FROM pakai a, pakaid b
                WHERE a.NO_ID=$NO_ID
                -- AND a.NO_BUKTI = b.NO_BUKTI;
                AND a.NO_ID=b.ID 
                ORDER BY b.REC";
        // $r = $query->row_array();
        // $data = [
        //     'NO_ID' => $r['NO_ID'],
        //     'NO_BUKTI' => $r['NO_BUKTI'],
        //     'TGL' => $r['TGL'],
        //     'TGL_DIMINTA' => $r['TGL_DIMINTA'],
        //     'DEVISI' => $r['DEVISI'],
        //     'ARTICLE' => $r['ARTICLE'],
        //     'PESAN' => $r['PESAN'],
        //     'JO' => $r['JO'],
        //     'FLAG3' => $r['FLAG3'],
        //     'GAMBAR1' => $r['GAMBAR1'],
        //     'VAL' => $r['VAL'],
        //     'GDETAIL' => $r['GDETAIL'],
        //     'REC' => $r['REC'],
        //     'NA_BHN' => $r['NA_BHN'],
        //     'KD_BHN' => $r['KD_BHN'],
        //     'WARNA' => $r['WARNA'],
        //     'SERI' => $r['SERI'],
        //     'QTY' => $r['QTY'],
        //     'SATUAN' => $r['SATUAN'],
        //     'TGL_DIMINTAD' => $r['TGL_DIMINTAD'],
        // ];
        $data['rnd'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		$config['max_size']             = 1000;
		$config['max_width']            = 3024;
		$config['max_height']           = 3680;
        $new_name = 'IMG'.$bukti;
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
            $GAMBAR1 = $this->input->post('G1', TRUE);
			$this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form', $error);
		}else{
            $G1 = $this->input->post('G1', TRUE);
            unlink(FCPATH."gambar/".$G1);
            $data = array('upload_data' => $this->upload->data());
            $GAMBAR1 = $this->upload->data('file_name');
			// $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            // 'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            // 'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            // 'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            // 'DEVISI' => $this->input->post('DEVISI', TRUE),
            // 'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            // 'PESAN' => $this->input->post('PESAN', TRUE),
            // 'JO' => $this->input->post('JO', TRUE),
            // 'FLAG3' => $this->input->post('FLAG3', TRUE),
            // 'GAMBAR1' => $GAMBAR1,
            'NOTES' => $this->input->post('NOTES', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'TUJUAN' => $this->input->post('TUJUAN', TRUE),
            'DR' => $this->input->post('DR', TRUE),
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pakai');
##############UPDATE
        $id = $NO_ID;
        // $q1 = "SELECT pakai.NO_ID as ID,
        //         pakai.NO_BUKTI AS NO_BUKTI,
        //         pakai.TGL AS TGL,
        //         pakai.ARTICLE AS ARTICLE,
        //         pakai.TOTAL_QTY AS TOTAL_QTY,
        //         pakai.TYP AS TYP,
        //         pakai.VAL AS VAL,

        //         pakaid.NO_ID AS NO_ID,
        //         pakaid.REC AS REC,
        //         pakaid.NA_BHN AS NA_BHN,
        //         pakaid.WARNA AS WARNA,
        //         pakaid.SERI AS SERI,
        //         pakaid.QTY AS QTY,
        //         pakaid.SATUAN AS SATUAN,
        //         pakaid.KET AS KET,
        //         pakaid.TYP AS TYP
        //     FROM pakai,pakaid 
        //     WHERE pakai.NO_ID=$id 
        //     AND pakai.NO_ID=pakaid.ID 
        //     ORDER BY pakaid.REC";
        $q1 = "SELECT a.NO_ID, a.NO_BUKTI, a.DR, DATE_FORMAT(a.TGL, '%d-%m-%Y') AS TGL, a.DR, a.TUJUAN, a.NOTES, b.NA_BHN, b.KET1, b.QTY, b.KET2, b.SP
               FROM pakai a, pakaid b
               WHERE a.NO_ID=$NO_ID
            -- AND a.NO_BUKTI = b.NO_BUKTI;
               AND a.NO_ID=b.ID 
               ORDER BY b.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_IDX = $this->input->post('NO_IDX');
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        // $KD_BHN = $this->input->post('KD_BHN');
        // $WARNA = $this->input->post('WARNA');
        // $SERI = $this->input->post('SERI');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        // $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $KET2 = $this->input->post('KET2');
        $SP = $this->input->post('SP');
        // $TGL_DIMINTAX = date("Y-m-d", strtotime($this->input->post('TGL_DIMINTAX', TRUE)));
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_IDX);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_IDX)) {
                $URUT = array_search($ID[$i], $NO_IDX);
                    $configx['upload_path']          = './gambar/';
                    $configx['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
                    $configx['max_size']             = 1000;
                    $configx['max_width']            = 3024;
                    $configx['max_height']           = 3680;
                    $new_name = 'IMGD'.$bukti.'-'.$REC[$URUT];
                    $configx['file_name']            = $new_name; 

                    $this->load->library('upload', $configx);
                    $this->upload->initialize($configx);

                    if ( ! $this->upload->do_upload('GAMBAR1X'.$URUT)){
                        $error = array('error' => $this->upload->display_errors());
                        $DGAMBAR = $this->input->post('G2'.$URUT, TRUE);
                        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form', $error);
                    }else{
                        $G2 = $this->input->post('G2'.$URUT, TRUE);
                        unlink(FCPATH."gambar/".$G2);
                        $data = array('upload_data' => $this->upload->data());
                        $DGAMBAR = $this->upload->data('file_name');
                        // $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
                    }

                $datad = array(
                    // 'TGL_DIMINTA' =>  $TGL_DIMINTAX[$URUT],
                    'REC' => $REC[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    // 'KD_BHN' => $KD_BHN[$URUT],
                    // 'WARNA' => $WARNA[$URUT],
                    // 'SERI' => $SERI[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    // 'SATUAN' => $SATUAN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'KET2' => $KET2[$URUT],
                    'SP' => $SP[$URUT],
                    // 'GAMBAR1' => $DGAMBAR
                    // 'FLAG' => 'PK',
                    // 'SUB' => 'MB',
                    // 'TYP' => 'RND_MELBBA',
                    // 'DR' => $this->session->userdata['dr'],
                    // 'PER' => $this->session->userdata['periode'],
                    // 'USRNM' => $this->session->userdata['username'],
                    // 'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_IDX[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'pakaid');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'pakaid');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_IDX[$i] == "0") {
                    $configz['upload_path']          = './gambar/';
                    $configz['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
                    $configz['max_size']             = 1000;
                    $configz['max_width']            = 3024;
                    $configz['max_height']           = 3680;
                    $new_name = 'IMGD'.$bukti.'-'.$REC[$i];
                    $configz['file_name']            = $new_name; 

                    $this->load->library('upload', $configz);
                    $this->upload->initialize($configz);

                    if ( ! $this->upload->do_upload('GAMBAR1X'.$i)){
                        $error = array('error' => $this->upload->display_errors());
                        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form', $error);
                    }else{
                        $data = array('upload_data' => $this->upload->data());
                        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
                    }

                $datad = array(
                    'ID' => $this->input->post('NO_ID', TRUE),
                    'REC' => $REC[$i],
                    'NO_BUKTI' => $bukti,
                    'TGL_DIMINTA' => $TGL_DIMINTAX[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'WARNA' => $WARNA[$i],
                    'SERI' => $SERI[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET' => $KET[$i],
                    'GAMBAR1' => $this->upload->data('file_name'),
                    'FLAG' => 'PK',
                    'SUB' => 'MB',
                    'TYP' => 'RND_MELBBA',
                    'DR' => $this->session->userdata['dr'],
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('pakaid', $datad);
            }
            $i++;
        }
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonBelumValidasi/index_Transaksi_BonBelumValidasi');
    }

    public function validasi($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'pakai');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NO_BUKTI' => $r['NO_BUKTI'],
            'TGL' => $r['TGL'],
            'TGL_DIMINTA' => $r['TGL_DIMINTA'],
            'DEVISI' => $r['DEVISI'],
            'ARTICLE' => $r['ARTICLE'],
            'PESAN' => $r['PESAN'],
            'JO' => $r['JO'],
            'FLAG3' => $r['FLAG3'],
            'GAMBAR1' => $r['GAMBAR1'],
            'VAL' => $r['VAL'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_validasi', $data);
        $this->load->view('templates_admin/footer');
    }

    public function validasi_aksi()
    {

        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		$config['max_size']             = 1000;
		$config['max_width']            = 3024;
		$config['max_height']           = 3680;
        $new_name = time().$_FILES['name'];
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_BonBelumValidasi/Transaksi_BonBelumValidasi', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'FLAG3' => $this->input->post('FLAG3', TRUE),
            'GAMBAR1' => "IMG".$this->upload->data('file_name'),
            'VAL' => '1',
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pakai');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Validasi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonBelumValidasi/index_Transaksi_BonBelumValidasi');
    }

    public function delete($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $this->transaksi_model->hapus_data($where, 'pakai');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonBelumValidasi/index_Transaksi_BonBelumValidasi');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pakai');
        redirect('admin/Transaksi_BonBelumValidasi/index_Transaksi_BonBelumValidasi');
    }

    public function getDataAjax_bhn()
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
        $results = $this->db->query("SELECT bhn.NO_ID, bhn.KD_BHN, bhn.NA_BHN, bhn.SATUAN
            FROM bhn, bhnd
            WHERE bhn.KD_BHN=bhnd.KD_BHN AND bhnd.FLAG='SP' AND bhnd.DR = '$dr' AND bhnd.SUB='$sub' AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%')
            GROUP BY bhn.KD_BHN
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['KD_BHN'],
                'text' => $row['KD_BHN'],
                'KD_BHN' => $row['KD_BHN'] . " - " . $row['NA_BHN'] . " - " . $row['SATUAN'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUAN' => $row['SATUAN'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function getDataAjax_dragon()
    {
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT KD_DEV, NM_DEV AS DEVISI, NAMA, AREA
            FROM rn_dev
            WHERE FLAG='CNC' AND (AREA LIKE '%$search%' OR NM_DEV LIKE '%$search%')
            GROUP BY AREA
            ORDER BY AREA LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['AREA'],
                'text' => $row['AREA'],
                'DR' => $row['AREA'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    function JASPER($id)
    {
        $CI = &get_instance();
        $CI->load->database();
        $servername = $CI->db->hostname;
        $username = $CI->db->username;
        $password = $CI->db->password;
        $database = $CI->db->database;
        $conn = mysqli_connect($servername, $username, $password, $database);
        error_reporting(E_ALL);
        ob_start();
        include_once('phpjasperxml/class/tcpdf/tcpdf.php');
        include_once("phpjasperxml/class/PHPJasperXML.inc.php");
        include_once("phpjasperxml/setting.php");
        $PHPJasperXML = new \PHPJasperXML();
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_MELLBBA.jrxml");
        $no_id = $id;
        $query = "SELECT a.GAMBAR1 AS GHEAD, b.GAMBAR1 AS GDETAIL FROM pakai a,pakaid b WHERE a.NO_ID = '$no_id' AND a.NO_BUKTI = b.NO_BUKTI";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "GHEAD" => $row1["GHEAD"],
                "GDETAIL" => $row1["GDETAIL"],
                "TGL_SP" => $row1["TGL_SP"],
                "KODECUS" => $row1["KODECUS"],
                "ARTICLE" => $row1["ARTICLE"],
                "LUSIN" => $row1["LUSIN"],
                "PAIR" => $row1["PAIR"],
                "REC" => $row1["REC"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
