<?php

class Buku extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model');
    }

    public function index()
    {
        $data['judul'] = 'Data Buku';
        $data['buku'] = $this->Buku_model->tampilSemuaBrg();
        $this->load->view('templates/header', $data);
        $this->load->view('barang/index');
        $this->load->view('templates/footer');
    }
}