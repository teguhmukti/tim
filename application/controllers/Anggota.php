<?php

class Anggota extends CI_Controller {

    public function __construct()
    {
        // parent::__construct();
        // $this->load->model('');
    }

    public function index()
    {
        $data['judul'] = 'Data Anggota';
       // $data['buku'] = $this->Buku_model->tampilSemuaBrg();
        $this->load->view('templates/header', $data);
        $this->load->view('barang/index');
        $this->load->view('templates/footer');
    }
}