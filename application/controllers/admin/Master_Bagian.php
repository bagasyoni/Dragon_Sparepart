<?php


class Master_Bagian extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'bagian') {
            $this->session->set_userdata('menu_sparepart', 'bagian');
            $this->session->set_userdata('kode_menu', 'M0002');
            $this->session->set_userdata('keyword_bagian', '');
            $this->session->set_userdata('order_bagian', 'NO_ID');
        }
    }
    var $column_order = array(null, null, null, 'NA_BAGIAN', 'NAMA', 'DR', 'USRNM');
    var $column_search = array('NA_BAGIAN', 'NAMA', 'DR', 'USRNM');
    var $order = array('NO_ID' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $where = array(
            'DR' => $dr,
        );
        $this->db->select('*');
        $this->db->from('bagian');
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
        $where = array(
            'DR' => $dr,
        );
        $this->db->from('bagian');
        return $this->db->count_all_results();
    }

    function get_ajax_bagian()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        $dr = $this->session->userdata['dr'];
        foreach ($list as $bagian) {
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $bagian->NO_ID . "'>";
            if ($dr === 'SUPER_ADMIN') {
                $row[] = '<div class="dropdown">
                            <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="' . site_url('admin/Master_Bagian/update/' . $bagian->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="' . site_url('admin/Master_Bagian/delete/' . $bagian->NO_ID) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>';
            } else {
                $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Master_Bagian/update/' . $bagian->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Master_Bagian/delete/' . $bagian->NO_ID) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>';
            }
            $row[] = $no . ".";
            $row[] = $bagian->NA_BAGIAN;
            $row[] = $bagian->NAMA;
            $row[] = $bagian->DR;
            $row[] = $bagian->USRNM;
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

    public function index_Master_Bagian()
    {
        // $data['bagian'] = $this->master_model->tampil_data('bagian', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        // $this->load->view('admin/Master_Bagian/Master_Bagian', $data);
        $this->load->view('admin/Master_Bagian/Master_Bagian');
        $this->load->view('templates_admin/footer');
    }

    public function getOrder()
    {
        $data['orderBy'] = $this->input->get('order');
        $this->session->set_userdata('order_bagian', $data['orderBy']);
    }

    public function input()
    {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Bagian/Master_Bagian_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $data = array(
            'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
            'NAMA' => $this->input->post('NAMA', TRUE),
            'DR' => $this->session->userdata['dr'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date('Y-m-d H:i:s'),
        );
        $this->master_model->input_data('bagian', $data);
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Bagian/index_Master_Bagian');
    }

    public function update($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'bagian');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NA_BAGIAN' => $r['NA_BAGIAN'],
            'NAMA' => $r['NAMA'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Bagian/Master_Bagian_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $NO_ID = $this->input->post('NO_ID');
        $data = array(
            'NA_BAGIAN' => $this->input->post('NA_BAGIAN', TRUE),
            'NAMA' => $this->input->post('NAMA', TRUE),
            'DR' => $this->session->userdata['dr'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date('Y-m-d H:i:s'),
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->master_model->update_data($where, $data, 'bagian');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Bagian/index_Master_Bagian');
    }

    public function delete($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $this->master_model->hapus_data($where, 'bagian');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Master_Bagian/index_Master_Bagian');
    }

    function delete_multiple()
    {
        $this->master_model->remove_checked('bagian');
        redirect('admin/Master_Bagian/index_Master_Bagian');
    }
}
