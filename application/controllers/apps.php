<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps extends CI_Controller {
    function __construct() {
		parent::__construct();
    }
    
    public function index() {
		if($this->session->userdata('validated') == FALSE){
            redirect('apps/login');
        }
		
		$a['page']	= "d_amain";
		
		$this->load->view('admin/aaa', $a);
    }
    
    //login
	public function login() {
		$this->load->view('admin/login');
    }
    
    public function do_login() {
		$u = $this->security->xss_clean($this->input->post('u'));
        $p = $this->security->xss_clean($this->input->post('p'));
         
		$q_cek	= $this->db->query("SELECT * FROM admin WHERE u = '".$u."' AND p = '".$p."'");
		$j_cek	= $q_cek->num_rows();
		$d_cek	= $q_cek->row();
		
		
        if($j_cek == 1) {
            $data = array(
                    'user' => $d_cek->u,
                    'pass' => $d_cek->p,
					'validated' => true
                    );
            $this->session->set_userdata($data);
            redirect('apps');
        } else {	
			$this->session->set_flashdata("k", "<div class=\"alert alert-error\">username or password is not valid</div>");
			redirect('apps/login');
		}
	}

}