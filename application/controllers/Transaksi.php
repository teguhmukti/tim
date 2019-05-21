<?php

class Transaksi extends CI_Controller {

    public function __construct()
    {
        // parent::__construct();
        // $this->load->model('Buku_model');
    }

    public function index()
    {
        $data['judul'] = 'Transaksi';
        //$data['buku'] = $this->Buku_model->tampilSemuaBrg();
        $this->load->view('templates/header', $data);
        $this->load->view('barang/index');
        $this->load->view('templates/footer');
    }
}