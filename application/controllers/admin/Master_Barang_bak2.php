<?php


class Master_Barang extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'bhn') {
            $this->session->set_userdata('menu_sparepart', 'bhn');
            $this->session->set_userdata('kode_menu', 'M0001');
            $this->session->set_userdata('keyword_bhn', '');
            $this->session->set_userdata('order_bhn', 'NO_ID');
        }
    }
    var $column_order = array(null, null, 'KD_BHN', 'NA_BHN', 'SATUAN', 'RAK_DR1', 'RAK_DR2', 'RAK_DR3');
    var $column_search = array('KD_BHN', 'NA_BHN', 'SATUAN', 'RAK_DR1', 'RAK_DR2', 'RAK_DR3');
    // var $order = array('bhn.NO_ID' => 'asc');
    var $order = array('NO_ID' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            // 'bhnd.DR' => $dr,
            // 'bhnd.FLAG' => 'SP',
            // 'bhnd.FLAG2' => 'SP',
            // 'bhnd.SUB' => $sub,
            // 'bhnd.RAK <>' => '',

            'FLAG' => 'SP',
            'FLAG2' => 'SP',
            'SUB' => $sub,
        );
        $this->db->select('*');
        $this->db->from('bhn');
        // $this->db->join('bhnd', 'bhn.KD_BHN = bhnd.KD_BHN');
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
        $sub = $this->session->userdata['sub'];
        $where = array(
            // 'bhnd.DR' => $dr,
            // 'bhnd.FLAG' => 'SP',
            // 'bhnd.FLAG2' => 'SP',
            // 'bhnd.SUB' => $sub,
            // 'bhnd.RAK <>' => '',

            'FLAG' => 'SP',
            'FLAG2' => 'SP',
            'SUB' => $sub,
        );
        $this->db->select('*');
        $this->db->from('bhn');
        // $this->db->join('bhnd', 'bhn.KD_BHN = bhnd.KD_BHN');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_sp_barang()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        $dr = $this->session->userdata['dr'];
        foreach ($list as $bhn) {
            $no++;
            $row = array();
            // $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $bhn->NO_ID . "'>";
            if ($dr === 'SUPER_ADMIN') {
                $row[] = '<div class="dropdown">
                            <a style="background-color: ##00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars icon" style="font-size: 5px;"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="' . site_url('admin/Master_Barang/update/' . $bhn->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="' . site_url('admin/Master_Barang/delete/' . $bhn->NO_ID) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>';
            } else {
                $row[] = '<div class="dropdown">
                        <a style="background-color: ##00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 5px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Master_Barang/update/' . $bhn->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Master_Barang/delete/' . $bhn->NO_ID) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>';
            }
            $row[] = $no . ".";
            $row[] = $bhn->KD_BHN;
            $row[] = $bhn->NA_BHN;
            $row[] = $bhn->SATUAN;
            $row[] = $bhn->RAK_DR1;
            $row[] = $bhn->RAK_DR2;
            $row[] = $bhn->RAK_DR3;
            // $dr1 = 'I';
            // $dr2 = 'II';
            // $dr3 = 'III';
            // $dragon = $bhn->DR;
            // $row[] = $dragon;
            // $rak = $bhn->RAK;
            // if($rak <> '' && $dragon <> $dr3){
            //     $rak_dr1 = $rak;
            // }elseif($rak <> '' && $dragon <> $dr1){
            //     $rak_dr1 = '';
            // }
            // $row[] = $rak_dr1;

            // if($rak <> '' && $dragon == $dr2){
            //     $rak_dr = $rak;
            // }elseif($rak <> '' && $dragon <> $dr2){
            //     $rak_dr = '';
            // }
            // $row[] = $rak_dr;

            // if($rak <> '' && $dragon == $dr3){
            //     $rak_dr = $rak;
            // }elseif($rak <> '' && $dragon <> $dr3){
            //     $rak_dr = '';
            // }
            // $row[] = $rak_dr;

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

    public function index_Master_Barang()
    {
       // $data['bhn'] = $this->master_model->tampil_data('bhn', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
      //  $this->load->view('admin/Master_Barang/Master_Barang', $data);
        $this->load->view('admin/Master_Barang/Master_Barang');
        $this->load->view('templates_admin/footer');
    }

    public function getOrder()
    {
        $data['orderBy'] = $this->input->get('order');
        $this->session->set_userdata('order_bhn', $data['orderBy']);
    }

    public function input()
    {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Barang/Master_Barang_form');
        $this->load->view('templates_admin/footer');
    }

    public function update($id)
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $q1 = "SELECT bhn.NO_ID as NO_ID,
                bhn.KD_BHN,
                bhn.NA_BHN,
                bhn.SATUAN,
                bhn.RAK_DR1,
                bhn.RAK_DR2,
                bhn.RAK_DR3,

                if(bhnd.DR='I',bhnd.RAK,'') AS RAK1,
                if(bhnd.DR='II',bhnd.RAK,'') AS RAK2,
                if(bhnd.DR='III',bhnd.RAK,'') AS RAK3,
                '$dr' AS DR
            FROM bhn,bhnd 
            WHERE bhn.NO_ID=$id 
            AND bhnd.KD_BHN=bhn.KD_BHN 
            ORDER BY bhnd.KD_BHN";
        $data['bahan'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Barang/Master_Barang_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $NO_ID = $this->input->post('NO_ID');
        $data = array(
            // 'KD_BHN' => $this->input->post('KD_BHN', TRUE),
            // 'NA_BHN' => $this->input->post('NA_BHN', TRUE),
            'SATUAN' => $this->input->post('SATUAN', TRUE),
            'AKTIF' => $this->input->post('AKTIF', TRUE),
            'RAK_DR1' => $this->input->post('RAK1', TRUE),
            'RAK_DR2' => $this->input->post('RAK2', TRUE),
            'RAK_DR3' => $this->input->post('RAK3', TRUE),
            // 'DR' => $this->session->userdata['dr'],
            // 'SUB' => $this->session->userdata['sub'],
            // 'FLAG' => 'SP',
            // 'FLAG2' => 'SP',
        );
        $where = array(
            'NO_ID' => $NO_ID,
        );
        $this->master_model->update_data($where, $data, 'bhn');

        $datadr = $this->transaksi_model->edit_data("SELECT * from dr")->result();
        $jumdr = count($datadr);
        $i = 1;
        while ($i <= $jumdr) {
            $datad = array(
                'RAK' => $this->input->post('RAK' . $i, TRUE),
            );
            $whered = array(
                'KD_BHN' => $this->input->post('KD_BHN', TRUE),
                'DR' => $this->getRomawi($i),
            );
            $this->master_model->update_data($whered, $datad, 'bhnd');
            $i++;
        }

        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Barang/index_Master_Barang');
    }

    public function delete($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $this->master_model->hapus_data($where, 'bhn');
        $whered = array('NO_ID' => $NO_ID);
        $this->master_model->hapus_data($whered, 'bhnd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Barang/index_Master_Barang');
    }

    function delete_multiple()
    {
        $this->master_model->remove_checked('bhn');
        redirect('admin/Master_Barang/index_Master_Barang');
    }

    function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}
