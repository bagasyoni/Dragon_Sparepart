<?php

class Transaksi_PesananPisau extends CI_Controller
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
            $this->session->set_userdata('kode_menu', 'T0029');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'TGL_DIMINTA', 'DEVISI', 'KET', 'TS', 'PESAN', 'TUJUAN');
    var $column_search = array('NO_BUKTI', 'TGL', 'TGL_DIMINTA', 'DEVISI', 'KET', 'TS', 'PESAN', 'TUJUAN');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => '1R&',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_PISAU',
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
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => '1R&',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_PISAU',
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
            //                 <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananPisau/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
            //                 <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananPisau/validasi/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
            //                 <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananPisau/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
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
                            <a '.$hidden.' class="dropdown-item" href="' . site_url('admin/Transaksi_PesananPisau/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananPisau/validasi/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananPisau/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
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
							data-no="' . $pp->NO_BUKTI . '" class="dropdown-item" href="#" data-toggle="modal" data-target="#pisauModal";"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pp->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pp->TGL));
            $row[] = date("d-m-Y", strtotime($pp->TGL_DIMINTA));
            $row[] = $pp->DEVISI;
            $row[] = $pp->ARTICLE;
            $row[] = $pp->TS;
            $row[] = $pp->PESAN;
            $row[] = $pp->TUJUAN;
            $row[] = "<img src='/Dragon_Sparepart_baru/gambar/pesananpisau/$pp->GAMBAR' width='auto' height='60'>";
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

    public function index_Transaksi_PesananPisau()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $this->session->set_userdata('judul', 'Transaksi Pesanan Pisau');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => '1R&',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_PISAU',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='1R&' AND FLAG='' AND FLAG2='SP'")->result();
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
        $bukti = 'PP' . '/' . $urut . '/' . $dr . '/' . "PS" . '/' . $romawi . '/' . $tahun;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form');
        $this->load->view('templates_admin/footer');
    }

    public function resizeImaged($filename)
    {
        $source_path = './gambar/pesananpisau/' . $filename;
        $target_path = './gambar/pesananpisau/thumbnail/';
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
        $config['upload_path']          = './gambar/pesananpisau/';
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

        if ( ! $this->upload->do_upload('GAMBAR')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
            $this->resizeImaged($data['upload_data']['file_name']);
			$this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau', $data);
		}

        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $datah = array(
            'NO_BUKTI' => $bukti,
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA_H', TRUE))),
            'TS' => $this->input->post('TS', TRUE),
            'GAMBAR' => $this->upload->data('file_name'),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => '',
            'FLAG2' => 'SP',
            'TYP' => 'RND_PISAU',
            'SUB' => '1R&',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        
        $this->transaksi_model->input_datah('pp', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM pp WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $SIZE = $this->input->post('SIZE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $TGL_DIMINTA_D = $this->input->post('TGL_DIMINTA_D');
        $GAMBAR1 = "IMG".$this->upload->data('file_name');
        $i = 0;
        foreach ($REC as $a) {
            $configx['upload_path']          = './gambar/pesananpisau/';
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
                $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form', $error);
            }else{
                $data = array('upload_data' => $this->upload->data());
                // var_dump($data['upload_data']['file_name']);
                $this->resizeImaged($data['upload_data']['file_name']);
                $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau', $data);
            }

            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'REC' => $REC[$i],
                'NA_BHN' => $NA_BHN[$i],
                'SIZE' => $SIZE[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET1' => $KET1[$i],
                'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA_D[$i])),
                'GAMBAR1' => $this->upload->data('file_name'),
                'FLAG' => '',
                'FLAG2' => 'SP',
                'TYP' => 'RND_PISAU',
                'SUB' => '1R&',
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('ppd', $datad);
            $i++;
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
        redirect('admin/Transaksi_PesananPisau/index_Transaksi_PesananPisau');
    }

    public function update($id)
    {
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.DEVISI AS DEVISI,
                pp.ARTICLE AS ARTICLE,
                pp.PESAN AS PESAN,
                pp.JO AS JO,
                pp.TGL_DIMINTA AS TGL_DIMINTA_H,
                pp.TS AS TS,
                pp.GAMBAR AS GAMBAR,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.VAL AS VAL,
                
                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NA_BHN AS NA_BHN,
                ppd.SIZE AS SIZE,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                IF(ppd.TGL_DIMINTA='0000-00-00','2001-01-01',ppd.TGL_DIMINTA) AS TGL_DIMINTA_D,
                ppd.KET1 AS KET1,
                ppd.GAMBAR1 AS GAMBAR1
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data['rnd'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $config['upload_path']          = './gambar/pesananpisau/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		$config['max_size']             = 1000;
		$config['max_width']            = 3024;
		$config['max_height']           = 3680;
        $new_name = 'IMG'.$bukti;
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('GAMBAR')){
			$error = array('error' => $this->upload->display_errors());
            $GAMBAR1 = $this->input->post('G1', TRUE);
			$this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form', $error);
		}else{
            $G1 = $this->input->post('G1', TRUE);
            unlink(FCPATH."gambar/melbba/".$G1);
            $data = array('upload_data' => $this->upload->data());
            $GAMBAR1 = $this->upload->data('file_name');
			// $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
		}

        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA_H', TRUE))),
            'TS' => $this->input->post('TS', TRUE),
            'GAMBAR' => $GAMBAR1,
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => '',
            'FLAG2' => 'SP',
            'TYP' => 'RND_PISAU',
            'SUB' => '1R&',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.DEVISI AS DEVISI,
                pp.ARTICLE AS ARTICLE,
                pp.PESAN AS PESAN,
                pp.JO AS JO,
                pp.TGL_DIMINTA,
                pp.TS AS TS,
                pp.GAMBAR1 AS GAMBAR,
                pp.TOTAL_QTY AS TOTAL_QTY,
                
                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NA_BHN AS NA_BHN,
                ppd.SIZE AS SIZE,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.TGL_DIMINTA,
                ppd.KET1 AS KET1,
                ppd.GAMBAR1 AS GAMBAR1
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $SIZE = $this->input->post('SIZE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $TGL_DIMINTA_D = $this->input->post('TGL_DIMINTA_D');
        $GAMBAR1 = $this->input->post('GAMBAR1');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                    $configx['upload_path']          = './gambar/pesananpisau/';
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
                        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form', $error);
                    }else{
                        $G2 = $this->input->post('G2'.$URUT, TRUE);
                        unlink(FCPATH."gambar/pesananpisau/".$G2);
                        $data = array('upload_data' => $this->upload->data());
                        $DGAMBAR = $this->upload->data('file_name');
                        // $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
                    }
                $datad = array(
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'SIZE' => $SIZE[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA_D[$URUT])),
                    'GAMBAR1' => $DGAMBAR,
                    'FLAG' => '',
                    'FLAG2' => 'SP',
                    'TYP' => 'RND_PISAU',
                    'SUB' => '1R&',
                    'DR' => $this->session->userdata['dr'],
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
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
            if ($NO_ID[$i] == "0") {
                    $configz['upload_path']          = './gambar/pesananpisau/';
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
                        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form', $error);
                    }else{
                        $data = array('upload_data' => $this->upload->data());
                        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau', $data);
                    }
                $datad = array(
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'SIZE' => $SIZE[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET1' => $KET1[$i],
                    'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA_D[$i])),
                    'GAMBAR1' => $this->upload->data('file_name'),
                    'FLAG' => 'PP',
                    'FLAG2' => 'SP',
                    'TYP' => 'RND_PISAU',
                    'SUB' => '1R&',
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
        redirect('admin/Transaksi_PesananPisau/index_Transaksi_PesananPisau');
    }

    public function validasi($id)
    {
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.DEVISI AS DEVISI,
                pp.ARTICLE AS ARTICLE,
                pp.PESAN AS PESAN,
                pp.JO AS JO,
                pp.TGL_DIMINTA AS TGL_DIMINTA_H,
                pp.TS AS TS,
                pp.GAMBAR1 AS GAMBAR,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.VAL AS VAL,
                
                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NA_BHN AS NA_BHN,
                ppd.SIZE AS SIZE,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                IF(ppd.TGL_DIMINTA='0000-00-00','2001-01-01','ppd.TGL_DIMINTA') AS TGL_DIMINTA_D,
                ppd.KET1 AS KET1,
                ppd.GAMBAR1 AS GAMBAR1
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data['rnd'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_validasi', $data);
        $this->load->view('templates_admin/footer');
    }

    public function validasi_aksi()
    {
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA_H', TRUE))),
            'TS' => $this->input->post('TS', TRUE),
            'GAMBAR' => $this->input->post('GAMBAR', TRUE),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => '',
            'FLAG2' => 'SP',
            'TYP' => 'RND_PISAU',
            'SUB' => '1R&',
            'VAL' => '1',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.DEVISI AS DEVISI,
                pp.ARTICLE AS ARTICLE,
                pp.PESAN AS PESAN,
                pp.JO AS JO,
                pp.TGL_DIMINTA,
                pp.TS AS TS,
                pp.GAMBAR1 AS GAMBAR,
                pp.TOTAL_QTY AS TOTAL_QTY,
                
                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NA_BHN AS NA_BHN,
                ppd.SIZE AS SIZE,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.TGL_DIMINTA,
                ppd.KET1 AS KET1,
                ppd.GAMBAR1 AS GAMBAR1
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $SIZE = $this->input->post('SIZE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $TGL_DIMINTA_D = $this->input->post('TGL_DIMINTA_D');
        $GAMBAR1 = $this->input->post('GAMBAR1');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'SIZE' => $SIZE[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA_D[$URUT])),
                    'GAMBAR1' => $GAMBAR1[$URUT],
                    'FLAG' => '',
                    'FLAG2' => 'SP',
                    'TYP' => 'RND_PISAU',
                    'SUB' => '1R&',
                    'DR' => $this->session->userdata['dr'],
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
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
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'SIZE' => $SIZE[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET1' => $KET1[$i],
                    'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA_D[$i])),
                    'GAMBAR1' => $GAMBAR1[$i],
                    'FLAG' => 'PP',
                    'FLAG2' => 'SP',
                    'TYP' => 'RND_PISAU',
                    'SUB' => '1R&',
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
                Data Berhasil Di Validasi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananPisau/index_Transaksi_PesananPisau');
    }

    public function delete($id)
    {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pp');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'ppd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananPisau/index_Transaksi_PesananPisau');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp', 'ppd');
        redirect('admin/Transaksi_PesananPisau/index_Transaksi_PesananPisau');
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

    public function getDataAjax_dr()
    {
        $dr = $this->session->userdata['dr'];
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT KD_DEV, NM_DEV, FLAG, NAMA, AREA
            FROM rn_dev
            WHERE (KD_DEV LIKE '%$search%' OR NM_DEV LIKE '%$search%')
            AND KD_DEV='$dr'
            ORDER BY KD_DEV 
            LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['NM_DEV'],
                'text' => $row['NM_DEV'],
                'DR' => $row['NM_DEV'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
}
