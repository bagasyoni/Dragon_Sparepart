<?php

class Transaksi_PesananCetakan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
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
        if ($this->session->userdata['menu_sparepart'] != 'pp') {
            $this->session->set_userdata('menu_sparepart', 'pp');
            $this->session->set_userdata('kode_menu', 'T0033');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'KET', 'NOTES', 'TIPE');
    var $column_search = array('NO_BUKTI', 'TGL', 'KET', 'NOTES', 'TIPE');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'SUB' => 'CT'
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_CETAK',
        );
        $this->db->select('*');
        $this->db->from('pp');
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
            'PER' => $per,
            'DR' => $dr,
            'SUB' => 'CT',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_CETAK',
        );
        $this->db->from('pp');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_pp()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $pp) {
            // $JASPER = "window.open('JASPER/" . $pp->NO_ID . "','', 'width=1000','height=900');";
            // $no++;
            // $row = array();
            // $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $pp->NO_ID . "'>";
            // $row[] = '<div class="dropdown">
            //             <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            //                 <i class="fa fa-bars icon" style="font-size: 13px;"></i>
            //             </a>
            //             <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            //                 <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakan/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
            //                 <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakan/validasi/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
            //                 <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakan/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
            //                 <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
            //             </div>
            //         </div>';
            if($pp->TTD7 == 0){
                $hidden = '';
            }else{
                $hidden = 'hidden';
            }
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $pp->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a '.$hidden.' class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakan/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakan/validasi/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakan/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" data-ttd1 = "' . $pp->TTD1_USR . '" 
							data-ttd1d = "' . $pp->TTD1_SMP . '"
							data-ttd2 = "' . $pp->TTD2_USR . '" 
							data-ttd2d = "' . $pp->TTD2_SMP . '"
							data-ttd3 = "' . $pp->TTD3_USR . '" 
							data-ttd3d = "' . $pp->TTD3_SMP . '"
							data-ttd4 = "' . $pp->TTD4_USR . '" 
							data-ttd4d = "' . $pp->TTD4_SMP . '"
							data-ttd5 = "' . $pp->TTD5_USR . '" 
							data-ttd5d = "' . $pp->TTD5_SMP . '"
							data-ttd6 = "' . $pp->TTD6_USR . '" 
							data-ttd6d = "' . $pp->TTD6_SMP . '"
							data-ttd7 = "' . $pp->TTD7_USR . '" 
							data-ttd7d = "' . $pp->TTD7_SMP . '"
							data-id = "' . $pp->NO_ID . '" 
							data-no="' . $pp->NO_BUKTI . '" class="dropdown-item" href="#" data-toggle="modal" data-target="#cetakanModal";"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pp->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pp->TGL));
            $row[] = date("d-m-Y", strtotime($pp->TGL_DIMINTA));
            $row[] = $pp->ARTICLE;
            $row[] = $pp->KET;
            $row[] = $pp->TIPE;
            $row[] = $pp->TIPE_CETAK;
            $row[] = $pp->M_LASTING;
            $row[] = $pp->JENIS;
            $row[] = $pp->FLAG;
            $row[] = $pp->TUJUAN;
            $row[] = "<img src='/Dragon_Sparepart_baru/gambar/pesanancetakan/$pp->GAMBAR1' width='auto' height='120'>";
            $row[] = "<img src='/Dragon_Sparepart_baru/gambar/pesanancetakan/$pp->GAMBAR2' width='auto' height='120'>";
            if($pp->VAL==1){
                $row[] = "<button type='button' class='btn btn-block btn-warning' fdprocessedid='fbns9l'>Belum Selesai</button>";
            }else{
                $row[] = "<button type='button' class='btn btn-block btn-danger' fdprocessedid='fbns9l'>Belum Validasi</button>";
            }
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

    public function index_Transaksi_PesananCetakan()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'SUB' => 'CT',
            // 'FLAG' => 'LOKAL',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_CETAK',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='CT'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        if($nom[0]==NULL){
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
        // PP / NOMER / DR / BULAN / TAHUN / CNC
        $bukti = 'PP' . '/' . $urut . '/' . "CT" . '/' . $dr . '/' . $romawi . '/' . $tahun;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_form');
        $this->load->view('templates_admin/footer');
    }

    public function resizeImaged($filename)
    {
        $source_path = './gambar/pesanancetakan/' . $filename;
        $target_path = './gambar/pesanancetakan/thumbnail/';
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


        $this->image_lib->resize();
        $this->image_lib->clear();
    }

    public function input_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $config['upload_path']          = './gambar/pesanancetakan/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		// $config['max_size']             = 1000;
		// $config['max_width']            = 3024;
		// $config['max_height']           = 3680;
        $new_name1 = 'IMG'.$bukti.'-1';
        $config['file_name']            = $new_name1; 

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
            $gambar1 = $this->upload->data('file_name');
            $this->resizeImaged($data['upload_data']['file_name']);
			$this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan', $data);
		}
#########################################################################################################
        $configx['upload_path']          = './gambar/pesanancetakan/';
        $configx['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        // $configx['max_size']             = 1000;
        // $configx['max_width']            = 3024;
        // $configx['max_height']           = 3680;
        // $config['width']            = 1000;
        // $config['height']           = 800;
        $new_name2 = 'IMG'.$bukti.'-2';
        $configx['file_name']            = $new_name2; 

        $this->load->library('upload', $configx);
        $this->upload->initialize($configx);

        if ( ! $this->upload->do_upload('GAMBAR2')){
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_form', $error);
        }else{
            $data = array('upload_data' => $this->upload->data());
            $gambar2 = $this->upload->data('file_name');
            $this->resizeImaged($data['upload_data']['file_name']);
            $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
        }

        $datah = array(
            'NO_BUKTI' => $bukti,
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'M_LASTING' => $this->input->post('M_LASTING', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'TUJUAN' => $this->input->post('TUJUAN', TRUE),
            'TIPE' => $this->input->post('TIPE', TRUE),
            'TIPE_CETAK' => $this->input->post('TIPE_CETAK', TRUE),
            'JENIS' => $this->input->post('JENIS', TRUE),
            'FLAG' => $this->input->post('FLAG', TRUE),
            'PROSES' => $this->input->post('PROSES', TRUE),
            'GAMBAR1' => $gambar1,
            'GAMBAR2' => $gambar2,
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            // 'FLAG' => 'LOKAL',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_CETAK',
            'DR' => $this->session->userdata['dr'],
            'SUB' => 'CT',
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pp', $datah);

        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM pp WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $KET1 = $this->input->post('KET1');
        $SIZE = $this->input->post('SIZE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'REC' => $REC[$i],
                'NA_BHN' => $NA_BHN[$i],
                'KET1' => $KET1[$i],
                'SIZE' => $SIZE[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('ppd', $datad);
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
        redirect('admin/Transaksi_PesananCetakan/index_Transaksi_PesananCetakan');
    }

    public function update($NO_ID)
    {
        // $where = array('NO_ID' => $NO_ID);
        // $ambildata = $this->master_model->edit_data($where, 'pp');
        // $r = $ambildata->row_array();
        // $data = [
        //     'NO_ID' => $r['NO_ID'],
        //     'NO_BUKTI' => $r['NO_BUKTI'],
        //     'ARTICLE' => $r['ARTICLE'],
        //     'TGL' => $r['TGL'],
        //     'NOTES' => $r['NOTES'],
        //     'M_LASTING' => $r['M_LASTING'],
        //     'TGL_DIMINTA' => $r['TGL_DIMINTA'],
        //     'DEVISI' => $r['DEVISI'],
        //     'TUJUAN' => $r['TUJUAN'],
        //     'TIPE' => $r['TIPE'],
        //     'TIPE_CETAK' => $r['TIPE_CETAK'],
        //     'JENIS' => $r['JENIS'],
        //     'FLAG' => $r['FLAG'],
        //     'PROSES' => $r['PROSES'],
        //     'GAMBAR1' => $r['GAMBAR1'],
        //     'GAMBAR2' => $r['GAMBAR2'],
        //     'GAMBAR3' => $r['GAMBAR3'],
        //     'VAL' => $r['VAL'],
        // ];

        $q1 = "SELECT a.NO_ID,a.NO_BUKTI,a.ARTICLE,a.TGL,a.NOTES,a.M_LASTING,a.TGL_DIMINTA,a.DEVISI,a.TOTAL_QTY,
                    a.TUJUAN,a.TIPE,a.TIPE_CETAK,a.JENIS,a.FLAG,a.PROSES,a.GAMBAR1,a.GAMBAR2,a.GAMBAR3,a.VAL,
                    b.NO_ID AS NO_IDX,b.REC,b.NA_BHN,b.KET1,b.SIZE,b.QTY,b.SATUAN
                FROM pp a,ppd b 
                WHERE a.NO_BUKTI = b.NO_BUKTI AND a.NO_ID='$NO_ID'";

        $data['rnd'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $config['upload_path']          = './gambar/pesanancetakan/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		// $config['max_size']             = 1000;
		// $config['max_width']            = 3024;
		// $config['max_height']           = 3680;
        $new_name1 = 'IMG'.$bukti.'-1';
        $config['file_name']            = $new_name1; 

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
            $GAMBAR1 = $this->input->post('G1', TRUE);
			$this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_form', $error);
		}else{
            $G1 = $this->input->post('G1', TRUE);
            unlink(FCPATH."gambar/pesanancetakan/".$G1);
            unlink(FCPATH."gambar/pesanancetakan/thumbnail/".$G1);
			$data = array('upload_data' => $this->upload->data());
            $GAMBAR1 = $this->upload->data('file_name');
            $this->resizeImaged($data['upload_data']['file_name']);
			// $this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan', $data);
		}
##########################################################################################################endregion
        $configx['upload_path']          = './gambar/pesanancetakan/';
        $configx['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        // $configx['max_size']             = 1000;
        // $configx['max_width']            = 3024;
        // $configx['max_height']           = 3680;
        // $config['width']            = 1000;
        // $config['height']           = 800;
        $new_name2 = 'IMG'.$bukti.'-2';
        $configx['file_name']            = $new_name2; 

        $this->load->library('upload', $configx);
        $this->upload->initialize($configx);

        if ( ! $this->upload->do_upload('GAMBAR2')){
            $error = array('error' => $this->upload->display_errors());
            $GAMBAR2 = $this->input->post('G2', TRUE);
            $this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_form', $error);
        }else{
            $G2 = $this->input->post('G2', TRUE);
            unlink(FCPATH."gambar/pesanancetakan/".$G2);
            unlink(FCPATH."gambar/pesanancetakan/thumbnail/".$G2);
            $data = array('upload_data' => $this->upload->data());
            $GAMBAR2 = $this->upload->data('file_name');
            $this->resizeImaged($data['upload_data']['file_name']);
            // $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
        }

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'M_LASTING' => $this->input->post('M_LASTING', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'TUJUAN' => $this->input->post('TUJUAN', TRUE),
            'TIPE' => $this->input->post('TIPE', TRUE),
            'TIPE_CETAK' => $this->input->post('TIPE_CETAK', TRUE),
            'JENIS' => $this->input->post('JENIS', TRUE),
            'FLAG' => $this->input->post('FLAG', TRUE),
            'PROSES' => $this->input->post('PROSES', TRUE),
            'GAMBAR1' =>  $GAMBAR1,
            'GAMBAR2' => $GAMBAR2,
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE))
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');

        $id = $NO_ID;
        $q1 = "SELECT a.NO_ID AS ID, a.NO_BUKTI, a.TGL, a.TGL_DIMINTA, a.DEVISI, a.ARTICLE, a.PESAN, 
                a.JO, a.FLAG3, a.GAMBAR1, a.VAL, a.TOTAL_QTY, b.GAMBAR1 AS GDETAIL, b.REC, b.NA_BHN, 
                b.KD_BHN, b.WARNA, b.SERI, b.QTY, b.SATUAN, DATE_FORMAT(b.TGL_DIMINTA, '%d-%m-%Y') AS TGL_DIMINTAD, b.KET AS KET,
                b.NO_ID AS NO_ID
                FROM pp a,ppd b WHERE a.NO_ID = '$id' AND a.NO_BUKTI = b.NO_BUKTI ORDER BY b.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_IDX = $this->input->post('NO_IDX');
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $KET1 = $this->input->post('KET1');
        $SIZE = $this->input->post('SIZE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_IDX);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_IDX)) {
                $URUT = array_search($ID[$i], $NO_IDX);
                $datad = array(
                    'REC' => $REC[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'SIZE' => $SIZE[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT]
                    // 'FLAG' => 'PP',
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
                $this->transaksi_model->update_data($where, $datad, 'ppd');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'ppd');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_IDX[$i] == "0") {
                $datad = array(
                    'ID' => $this->input->post('NO_ID', TRUE),
                    'REC' => $REC[$i],
                    'NO_BUKTI' => $bukti,
                    'NA_BHN' => $NA_BHN[$i],
                    'KET1' => $KET1[$i],
                    'SIZE' => $SIZE[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'DR' => $this->session->userdata['dr'],
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('ppd', $datad);
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
        redirect('admin/Transaksi_PesananCetakan/index_Transaksi_PesananCetakan');
    }

    public function validasi($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'pp');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NO_BUKTI' => $r['NO_BUKTI'],
            'ARTICLE' => $r['ARTICLE'],
            'TGL' => $r['TGL'],
            'NOTES' => $r['NOTES'],
            'M_LASTING' => $r['M_LASTING'],
            'TGL_DIMINTA' => $r['TGL_DIMINTA'],
            'DEVISI' => $r['DEVISI'],
            'TUJUAN' => $r['TUJUAN'],
            'TIPE' => $r['TIPE'],
            'TIPE_CETAK' => $r['TIPE_CETAK'],
            'JENIS' => $r['JENIS'],
            'FLAG' => $r['FLAG'],
            'PROSES' => $r['PROSES'],
            'GAMBAR1' => $r['GAMBAR1'],
            'GAMBAR2' => $r['GAMBAR2'],
            'VAL' => $r['VAL'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_validasi', $data);
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

        if ( ! $this->upload->do_upload('GAMBAR1') && ! $this->upload->do_upload('GAMBAR2') && ! $this->upload->do_upload('GAMBAR3') ){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananCetakan/Transaksi_PesananCetakan', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'M_LASTING' => $this->input->post('M_LASTING', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'TUJUAN' => $this->input->post('TUJUAN', TRUE),
            'TIPE' => $this->input->post('TIPE', TRUE),
            'TIPE_CETAK' => $this->input->post('TIPE_CETAK', TRUE),
            'JENIS' => $this->input->post('JENIS', TRUE),
            'FLAG' => $this->input->post('FLAG', TRUE),
            'PROSES' => $this->input->post('PROSES', TRUE),
            'GAMBAR1' => "IMG".$this->upload->data('file_name'),
            'GAMBAR2' => "IMG".$this->upload->data('file_name'),
            'VAL' => '1',
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Validasi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananCetakan/index_Transaksi_PesananCetakan');
    }

    public function delete($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $this->transaksi_model->hapus_data($where, 'pp');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananCetakan/index_Transaksi_PesananCetakan');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp');
        redirect('admin/Transaksi_PesananCetakan/index_Transaksi_PesananCetakan');
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Pesanan_Catak.jrxml");
        $no_id = $id;
        $query = "SELECT a.NO_ID, a.ARTICLE, a.NO_BUKTI, a.TGL, a.PESAN, a.TS, a.GAMBAR1 AS GAMBAR, a.JENIS, a.GAMBAR2 AS GAMBAR1,
                a.M_LASTING, a.TYP, a.NOTES, a.TIPE_CETAK,
                a.TTD1_USR,a.TTD2_USR,a.TTD3_USR,a.TTD4_USR,a.TTD5_USR,a.TTD6_USR,
                a.TTD1_SMP,a.TTD2_SMP,a.TTD3_SMP,a.TTD4_SMP,a.TTD5_SMP,a.TTD6_SMP,
                b.NA_BHN, b.SIZE, b.QTY, b.JENIS AS JENIS2, b.SATUAN, b.TGL_DIMINTA
        FROM pp a, ppd b
        WHERE a.NO_ID = '$no_id'
        AND a.NO_BUKTI = b.NO_BUKTI";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_ID" => $row1["NO_ID"],
                "ARTICLE" => $row1["ARTICLE"],
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "TGL" => $row1["TGL"],
                "PESAN" => $row1["PESAN"],
                "JENIS" => $row1["JENIS"],
                "M_LASTING" => $row1["M_LASTING"],
                "TYP" => $row1["TYP"],
                "NOTES" => $row1["NOTES"],
                "TIPE_CETAK" => $row1["TIPE_CETAK"],
                "TS" => $row1["TS"],
                "GAMBAR" => $row1["GAMBAR"],
                "NA_BHN" => $row1["NA_BHN"],
                "SIZE" => $row1["SIZE"],
                "JENIS2" => $row1["JENIS2"],
                "QTY" => $row1["QTY"],
                "TGL_DIMINTA" => $row1["TGL_DIMINTA"],
                "GAMBAR1" => $row1["GAMBAR1"],
                "REC" => $row1["REC"],
                "CEO" => $row1["TTD1_USR"],
                "GM" => $row1["TTD2_USR"],
                "PBL" => $row1["TTD3_USR"],
                "CNC" => $row1["TTD4_USR"],
                "MARKET" => $row1["TTD5_USR"],
                "RND" => $row1["TTD6_USR"],
                "TG_CEO" => $row1["TTD1_SMP"],
                "TG_GM" => $row1["TTD2_SMP"],
                "TG_PBL" => $row1["TTD3_SMP"],
                "TG_CNC" => $row1["TTD4_SMP"],
                "TG_MARKET" => $row1["TTD5_SMP"],
                "TG_RND" => $row1["TTD6_SMP"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
