<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
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
        $data['title'] = 'Data Transaksi';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('template/footer');
    }

    public function kaskeluar()
    {
        $data['title'] = 'Kas Keluar';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('nominal', 'Nominal', 'required');



        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('transaksi/kaskeluar', $data);
            $this->load->view('template/footer');
        } else {
            $idtransaksi = date("dmY") . '-' . rand(0000, 9999);
            $kas =  $this->db->get_where('kas', ['id_transaksi' => $idtransaksi])->row_array();
            if ($kas) {
                $idtransaksi = date("dmY") . '-' . rand(0000, 9999);
            }
            $data = [
                'id_transaksi' => $idtransaksi,
                'tipe_kas' => 'keluar',
                'keterangan' => $this->input->post('keterangan'),
                'tgl_transaksi' => $this->input->post('tanggal'),
                'nominal' => preg_replace('/[^0-9]/', '', $this->input->post('nominal'))
            ];

            $this->db->insert('kas', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Kas Keluar berhasil ditambah!
          </div>');
            redirect('transaksi/kaskeluar');
        }
    }

    public function kasmasuk()
    {
        $data['title'] = 'Kas Masuk';
        $data['user'] =  $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('nominal', 'Nominal', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('transaksi/kasmasuk', $data);
            $this->load->view('template/footer');
        } else {
            $idtransaksi = date("dmY") . '-' . rand(0000, 9999);
            $kas =  $this->db->get_where('kas', ['id_transaksi' => $idtransaksi])->row_array();
            if ($kas) {
                $idtransaksi = date("dmY") . '-' . rand(0000, 9999);
            }
            $data = [
                'id_transaksi' => $idtransaksi,
                'tipe_kas' => 'masuk',
                'keterangan' => $this->input->post('keterangan'),
                'tgl_transaksi' => $this->input->post('tanggal'),
                'nominal' => preg_replace('/[^0-9]/', '', $this->input->post('nominal'))
            ];
            $this->db->insert('kas', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Kas Masuk berhasil ditambah!
          </div>');
            redirect('transaksi/kasmasuk');
        }
    }
}
