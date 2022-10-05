<?php

class Master_Inventaris extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'inventaris') {
            $this->session->set_userdata('menu_sparepart', 'inventaris');
            $this->session->set_userdata('kode_menu', 'T0005');
            $this->session->set_userdata('keyword_inventaris', '');
            $this->session->set_userdata('order_inventaris', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NA_BAGIAN', 'NAMA', 'TOTAL_QTY', 'DR');
    var $column_search = array('NO_BUKTI', 'TGL', 'NA_BAGIAN', 'NAMA', 'TOTAL_QTY', 'DR');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            'DR' => $dr,
            // 'PER' => $per,
            'FLAG' => 'INV',
        );
        $this->db->select('*');
        $this->db->from('inventaris');
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
            // 'PER' => $per,
            'FLAG' => 'INV',
        );
        $this->db->from('inventaris');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_inventaris()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $inventaris) {
            $JASPER = "window.open('JASPER/" . $inventaris->NO_ID . "','', 'width=1000','height=900');";
            $JASPER2 = "window.open('JASPER2/" . $inventaris->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $inventaris->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Master_Inventaris/update/' . $inventaris->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Master_Inventaris/delete/' . $inventaris->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER2 . '");"><i class="fa fa-print"></i> Print 2</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $inventaris->NO_BUKTI;
            $row[] = $inventaris->TGL;
            $row[] = $inventaris->NA_BAGIAN;
            $row[] = $inventaris->NAMA;
            $row[] = $inventaris->TOTAL_QTY;
            $row[] = $inventaris->DR;
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

    public function index_Master_Inventaris()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $this->session->set_userdata('judul', 'Master Inventaris');
        $where = array(
            'DR' => $dr,
            // 'PER' => $per,
            'FLAG' => 'INV',
        );
        // $data['inventaris'] = $this->transaksi_model->tampil_data($where, 'inventaris', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        // $this->load->view('admin/Master_Inventaris/Master_Inventaris', $data);
        $this->load->view('admin/Master_Inventaris/Master_Inventaris');
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM inventaris WHERE FLAG='INV' AND PER='$per' AND DR='$dr'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 4, 7);
        $value22 = STRVAL($value11) . 1;
        $urut = str_pad($value22, 3, "0", STR_PAD_LEFT);
        $bukti = 'INV' . '/' . $urut . '/' . $per . '/' . 'DR' . $dr;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Inventaris/Master_Inventaris_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM inventaris WHERE FLAG='INV' AND PER='$per' AND DR='$dr'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 4, 7);
        $value22 = STRVAL($value11) + 1;
        $urut = str_pad($value22, 3, "0", STR_PAD_LEFT);
        $bukti = 'INV' . '/' . $urut . '/' . $per . '/' . 'DR' . $dr;
        $datah = array(
            'NO_BUKTI' => $bukti,
            'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
            'NAMA' => $this->input->post('NAMA', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'INV',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('inventaris', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS no_id FROM inventaris WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $JENIS = $this->input->post('JENIS');
        $MERK = $this->input->post('MERK');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET = $this->input->post('KET');
        $TGL_MA = $this->input->post('TGL_MA');
        $TGL_KE = $this->input->post('TGL_KE');
        $TGL_MU = $this->input->post('TGL_MU');
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->no_id,
                'NO_BUKTI' => $bukti,
                'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
                'NAMA' => $this->input->post('NAMA', TRUE),
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                'REC' => $REC[$i],
                'JENIS' => $JENIS[$i],
                'MERK' => $MERK[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET' => $KET[$i],
                'TGL_MA' => date("Y-m-d", strtotime($TGL_MA[$i])),
                'TGL_KE' => date("Y-m-d", strtotime($TGL_KE[$i])),
                'TGL_MU' => date("Y-m-d", strtotime($TGL_MU[$i])),
                'FLAG' => 'INV',
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'DR' => $this->session->userdata['dr'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('inventarisd', $datad);
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
        redirect('admin/Master_Inventaris/index_Master_Inventaris');
    }

    public function update($id)
    {
        $q1 = "SELECT inventaris.NO_ID as ID,
                inventaris.NO_BUKTI AS NO_BUKTI,
                inventaris.NA_BAGIAN AS NA_BAGIAN,
                inventaris.NAMA AS NAMA,
                inventaris.TGL AS TGL,
                inventaris.TOTAL_QTY AS TOTAL_QTY,

                inventarisd.NO_ID AS NO_ID,
                inventarisd.REC AS REC,
                inventarisd.JENIS AS JENIS,
                inventarisd.MERK AS MERK,
                inventarisd.QTY AS QTY,
                inventarisd.SATUAN AS SATUAN,
                inventarisd.KET AS KET,
                inventarisd.TGL_MA AS TGL_MA,
                inventarisd.TGL_KE AS TGL_KE,
                inventarisd.TGL_MU AS TGL_MU
            FROM inventaris,inventarisd 
            WHERE inventaris.NO_ID=$id 
            AND inventaris.NO_ID=inventarisd.ID 
            ORDER BY inventarisd.REC";
        $data['inventaris'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Inventaris/Master_Inventaris_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
            'NAMA' => $this->input->post('NAMA', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'INV',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'inventaris');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT inventaris.NO_ID as ID,
                inventaris.NO_BUKTI AS NO_BUKTI,
                inventaris.NA_BAGIAN AS NA_BAGIAN,
                inventaris.NAMA AS NAMA,
                inventaris.TGL AS TGL,
                inventaris.TOTAL_QTY AS TOTAL_QTY,

                inventarisd.NO_ID AS NO_ID,
                inventarisd.REC AS REC,
                inventarisd.JENIS AS JENIS,
                inventarisd.MERK AS MERK,
                inventarisd.QTY AS QTY,
                inventarisd.SATUAN AS SATUAN,
                inventarisd.KET AS KET,
                inventarisd.TGL_MA AS TGL_MA,
                inventarisd.TGL_KE AS TGL_KE,
                inventarisd.TGL_MU AS TGL_MU
            FROM inventaris,inventarisd 
            WHERE inventaris.NO_ID=$id 
            AND inventaris.NO_ID=inventarisd.ID 
            ORDER BY inventarisd.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $JENIS = $this->input->post('JENIS');
        $MERK = $this->input->post('MERK');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET = $this->input->post('KET');
        $TGL_MA = $this->input->post('TGL_MA');
        $TGL_KE = $this->input->post('TGL_KE');
        $TGL_MU = $this->input->post('TGL_MU');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
                    'NAMA' => $this->input->post('NAMA', TRUE),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'JENIS' => $JENIS[$URUT],
                    'MERK' => $MERK[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET' => $KET[$URUT],
                    'TGL_MA' => date("Y-m-d", strtotime($TGL_MA[$URUT])),
                    'TGL_KE' => date("Y-m-d", strtotime($TGL_KE[$URUT])),
                    'TGL_MU' => date("Y-m-d", strtotime($TGL_MU[$URUT])),
                    'FLAG' => 'INV',
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'DR' => $this->session->userdata['dr'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'inventarisd');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'inventarisd');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
                    'NAMA' => $this->input->post('NAMA', TRUE),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'JENIS' => $JENIS[$i],
                    'MERK' => $MERK[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET' => $KET[$i],
                    'TGL_MA' => date("Y-m-d", strtotime($TGL_MA[$i])),
                    'TGL_KE' => date("Y-m-d", strtotime($TGL_KE[$i])),
                    'TGL_MU' => date("Y-m-d", strtotime($TGL_MU[$i])),
                    'FLAG' => 'INV',
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'DR' => $this->session->userdata['dr'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('inventarisd', $datad);
            }
            $i++;
        }
        // $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM inventaris WHERE NO_BUKTI='$bukti'")->result();
        // $no_bukti = $xx[0]->BUKTIX;
        // $this->db->query("CALL inventarisins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Inventaris/index_Master_Inventaris');
    }

    public function delete($id)
    {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'inventaris');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'inventarisd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Inventaris/index_Master_Inventaris');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('inventaris', 'inventarisd');
        redirect('admin/Master_Inventaris/index_Master_Inventaris');
    }

    public function getDataAjax_na_bagian()
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
        $results = $this->db->query("SELECT NO_ID, NA_BAGIAN, NAMA
            FROM bagian
            WHERE DR = '$dr' AND (NA_BAGIAN LIKE '%$search%' OR NAMA LIKE '%$search%')
            ORDER BY NA_BAGIAN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['NA_BAGIAN'],
                'text' => $row['NA_BAGIAN'],
                'NA_BAGIAN' => $row['NA_BAGIAN'] . " - " . $row['NAMA'],
                'NAMA' => $row['NAMA'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function getDataAjax_jenis()
    {
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT NO_ID, JENIS
            FROM sp_jenis_inv
            WHERE JENIS LIKE '%$search%'
            ORDER BY JENIS LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['JENIS'],
                'text' => $row['JENIS'],
                'JENIS' => $row['JENIS'],
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Master_Inventaris.jrxml");
        $no_id = $id;
        $query = "CALL spp_prntinv_1('$id')";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "NA_BAGIAN" => $row1["NA_BAGIAN"],
                "NAMA" => $row1["NAMA"],
                "TGL" => $row1["TGL"],
                "REC" => $row1["REC"],
                "JENIS" => $row1["JENIS"],
                "MERK" => $row1["MERK"],
                "QTY" => $row1["QTY"],
                "SATUAN" => $row1["SATUAN"]
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }

    function JASPER2($id)
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Master_Inventaris2.jrxml");
        $no_id = $id;
        $query = "CALL spp_prntinv_2('$id')";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "NA_BAGIAN" => $row1["NA_BAGIAN"],
                "NAMA" => $row1["NAMA"],
                "TGL" => $row1["TGL"],
                "REC" => $row1["REC"],
                "JENIS" => $row1["JENIS"],
                "MERK" => $row1["MERK"],
                "QTY" => $row1["QTY"],
                "SATUAN" => $row1["SATUAN"],
                "KET" => $row1["KET"]
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
