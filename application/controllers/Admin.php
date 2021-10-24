<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Jakarta");
        parent::__construct();
        $this->load->library('form_validation');
        if ($this->session->userdata('role_id') == null) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            this session has expired, please login again!
              </div>');
            redirect("auth");
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $this->db->select_sum('nominal');
        $data['total_donasi'] = $this->db->get('tbl_transaksi')->row_array();

        $data['total_donatur'] = $this->db->query('select * from tbl_donatur')->num_rows();

        $data['kas_masuk'] = $this->db->query("SELECT sum(nominal) as nominal FROM kas where tipe_kas = 'masuk'")->row_array();
        $data['kas_keluar'] = $this->db->query("SELECT sum(nominal) as nominal FROM kas where tipe_kas = 'keluar'")->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('template/footer');
    }

    public function addDonatur()
    {
        $data['title'] = 'Data Donatur';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['donatur'] = $this->db->get('tbl_donatur')->result_array();

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/donasi', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
            ];
            $this->db->insert('tbl_donatur', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            New Donatur added!
          </div>');
            redirect('admin/donasi');
        }
    }

    public function donatur()
    {
        $data['title'] = 'Data Donatur';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['donatur'] = $this->db->get('tbl_donatur')->result_array();

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/donatur', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
            ];
            $this->db->insert('tbl_donatur', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            New Donatur added!
          </div>');
            redirect('admin/donatur');
        }
    }

    public function donasi()
    {
        $data['title'] = 'Data Donasi';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['donatur'] = $this->db->get('tbl_donatur')->result_array();

        $this->db->order_by("date_trx", "asc");
        $data['donasi'] = $this->db->get('tbl_transaksi')->result_array();

        $this->db->select_sum('nominal');
        $data['total_donasi'] = $this->db->get('tbl_transaksi')->row_array();


        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('nominal', 'Nominal', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/donasi', $data);
            $this->load->view('template/footer');
        } else {

            $idtransaksi = date("dmY") . '-' . rand(0000, 9999);
            $kas =  $this->db->get_where('kas', ['id_transaksi' => $idtransaksi])->row_array();
            if ($kas) {
                $idtransaksi = date("dmY") . '-' . rand(0000, 9999);
            }

            $anggota =  $this->db->get_where('tbl_donatur', ['id' => $this->input->post('nama')])->row_array();

            $data = [
                'id_transaksi' => $idtransaksi,
                'nama_transaksi' => 'Donasi A/n ' . $anggota['nama'],
                'nominal' => preg_replace('/[^0-9]/', '', $this->input->post('nominal')),
                'date_trx' =>  $this->input->post('tanggal'),
                'id_anggota' =>  $this->input->post('nama'),
                'jenis' => 'kas masuk'

            ];

            $data_kas = [
                'id_transaksi' => $idtransaksi,
                'tipe_kas' => 'masuk',
                'tgl_transaksi' => $this->input->post('tanggal'),
                'keterangan' => 'Donasi A/n ' . $anggota['nama'],
                'nominal' => preg_replace('/[^0-9]/', '', $this->input->post('nominal'))
            ];

            $this->db->insert('kas', $data_kas);
            $this->db->insert('tbl_transaksi', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           Tambah Donasi A/n ' . $anggota['nama'] . '  Berhasil!
          </div>');
            redirect('admin/donasi');
        }
    }

    public function cetak()
    {
        $this->load->view('cetak/sertifikat');
    }

    public function deleteDonatur()
    {
        $id = $this->input->get('id');
        $this->db->delete('tbl_donatur', array('id' => $id));
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Hapus Berhasil!
          </div>');
        redirect('admin/donatur');
    }

    public function deleteDonasi()
    {
        $id = $this->input->get('id');

        $donasi =  $this->db->get_where('tbl_transaksi', ['id_transaksi' => $id])->row_array();
        $kas =  $this->db->get_where('kas', ['id_transaksi' => $donasi['id_transaksi']])->row_array();
        $jurnal =  $this->db->get_where('jurnal', ['id_transaksi' => $kas['id_transaksi']])->row_array();

        $this->db->delete('tbl_transaksi', array('id_transaksi' => $id));
        $this->db->delete('kas', array('id_transaksi' => $donasi['id_transaksi']));
        $this->db->delete('jurnal', array('id_transaksi' => $kas['id_transaksi']));
        $this->db->delete('jurnal_detail', array('id_jurnal' => $jurnal['id']));


        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Hapus Donasi Berhasil!
          </div>');
        redirect('admin/donasi');
    }

    public function updatedonatur()
    {
        $data['title'] = 'Data Donatur';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['donatur'] = $this->db->get('tbl_donatur')->result_array();

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        $id = $this->input->post('id');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/donatur', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
            ];

            $this->db->where('id', $id);
            $this->db->update('tbl_donatur', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           Update donatur ' . $this->input->post('nama') . ' berhasil!
          </div>');
            redirect('admin/donatur');
        }
    }
}
