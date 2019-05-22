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
  
  public function logout(){
    $this->session->sess_destroy();
  redirect('apps/login');
  }

  public function anggota() {
        if(! $this->session->userdata('validated')){
            redirect('apps/login');
        }
        
        /* pagination */    
        $this->load->library('pagination');
        $total_rows     = $this->db->query("SELECT * FROM t_anggota")->num_rows();
        
        
        $config['base_url']     = base_URL().'apps/anggota/p/';
        $config['total_rows']   = $total_rows;
        $config['uri_segment']  = 4;
        $config['per_page']     = 10; 
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close']= '</li>';
        $config['prev_link']    = '&lt;';
        $config['prev_tag_open']='<li>';
        $config['prev_tag_close']='</li>';
        $config['next_link']    = '&gt;';
        $config['next_tag_open']='<li>';
        $config['next_tag_close']='</li>';
        $config['cur_tag_open']='<li class="active disabled"><a href="#"  style="background: #e3e3e3">';
        $config['cur_tag_close']='</a></li>';
        $config['first_tag_open']='<li>';
        $config['first_tag_close']='</li>';
        $config['last_tag_open']='<li>';
        $config['last_tag_close']='</li>';
        
        $this->pagination->initialize($config); 
        
        
        $awal   = $this->uri->segment(4); 
        if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
        $akhir  = $config['per_page'];
        
        $a['pagi']  = $this->pagination->create_links();
        
        
        //ambil variabel URL
        $mau_ke                 = $this->uri->segment(3);
        $idu                    = $this->uri->segment(4);

        //ambil variabel Postingan
        $idp                    = addslashes($this->input->post('idp'));
        $nama                   = addslashes($this->input->post('nama'));
        $alamat                 = addslashes($this->input->post('alamat'));
        $jk                     = addslashes($this->input->post('jk'));
        $agama                  = addslashes($this->input->post('agama'));
        $tmp_lahir              = addslashes($this->input->post('tmp_lahir'));
        
        $tgl_lahir              = $this->input->post('th')."-".str_pad($this->input->post('bl'), 2, '0', STR_PAD_LEFT)."-".str_pad($this->input->post('tg'), 2, '0', STR_PAD_LEFT);
        
        $jenis                  = addslashes($this->input->post('jenis'));
        $status                 = addslashes($this->input->post('status'));
        
        $cari                   = addslashes($this->input->post('q'));
        //view tampilan website\
        $a['data']      = $this->db->query("SELECT * FROM t_anggota  LIMIT $awal, $akhir ")->result();
        $a['page']      = "d_anggota";

        if ($mau_ke == "del") {
            $this->db->query("DELETE FROM t_anggota WHERE id = '$idu'");
            
            $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been deleted </div>");
            redirect('apps/anggota');
        } else if ($mau_ke == "cari") {
            $a['data']      = $this->db->query("SELECT * FROM t_anggota WHERE nama LIKE '%$cari%' OR alamat LIKE '%$cari%' ORDER BY id DESC")->result();
            $a['page']  = "d_anggota";
        } else if ($mau_ke == "add") {
            $a['page']  = "f_anggota";
        } else if ($mau_ke == "edt") {
            $a['datpil']        = $this->db->query("SELECT * FROM t_anggota WHERE id = '$idu'")->row(); 
            $a['page']          = "f_anggota";
        }else if ($mau_ke == "act_add") {
            $this->db->query("INSERT INTO t_anggota VALUES ('', '$nama', '$alamat', '$jk', '$agama', '$tmp_lahir', '$tgl_lahir', '',  NOW(), '$jenis', '1')");
            
            $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been added</div>");
            redirect('apps/anggota');
        } else if ($mau_ke == "act_edt") {
            $this->db->query("UPDATE t_anggota SET nama = '$nama', alamat = '$alamat', jk = '$jk', agama = '$agama', tmp_lahir = '$tmp_lahir', tgl_lahir = '$tgl_lahir', jenis = '$jenis', stat = '$status' WHERE id = '$idp'");

            $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been updated</div>");           
            redirect('apps/anggota');
        } else if ($mau_ke == "list_pinjam") {
            $a['id_anggota']= $idu;
            $a['data']      = $this->db->query("SELECT t_trans.*, t_buku.judul 
                                                FROM t_trans 
                                                LEFT JOIN t_buku ON t_trans.id_buku = t_buku.id
                                                WHERE t_trans.id_anggota = '$idu' ORDER BY t_trans.id DESC")->result();
            $a['page']      = "d_lis_pinjam";
        } else {
            $a['page']  = "d_anggota";
        }
        
        $this->load->view('admin/aaa', $a);
  }

  public function buku() {
    if(! $this->session->userdata('validated')){
        redirect('apps/login');
    }
    
    /* pagination */	
    $this->load->library('pagination');
    $total_rows		= $this->db->query("SELECT * FROM t_buku")->num_rows();
    
    
    $config['base_url'] 	= base_URL().'apps/buku/p/';
    $config['total_rows'] 	= $total_rows;
    $config['uri_segment'] 	= 4;
    $config['per_page'] 	= 10; 
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close']= '</li>';
    $config['prev_link'] 	= '&lt;';
    $config['prev_tag_open']='<li>';
    $config['prev_tag_close']='</li>';
    $config['next_link'] 	= '&gt;';
    $config['next_tag_open']='<li>';
    $config['next_tag_close']='</li>';
    $config['cur_tag_open']='<li class="active disabled"><a href="#"  style="background: #e3e3e3">';
    $config['cur_tag_close']='</a></li>';
    $config['first_tag_open']='<li>';
    $config['first_tag_close']='</li>';
    $config['last_tag_open']='<li>';
    $config['last_tag_close']='</li>';
    
    $this->pagination->initialize($config); 
    
    
    $awal	= $this->uri->segment(4); 
    if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
    $akhir	= $config['per_page'];
    
    $a['pagi']	= $this->pagination->create_links();
    
    
    
    
    //ambil variabel URL
    $mau_ke					= $this->uri->segment(3);
    $idu					= $this->uri->segment(4);

    //ambil variabel Postingan
    $idp					= addslashes($this->input->post('idp'));
    $id_kelas				= addslashes($this->input->post('id_kelas'));
    $id_jenis				= addslashes($this->input->post('id_jenis'));
    $judul					= addslashes($this->input->post('judul'));
    $pengarang				= addslashes($this->input->post('pengarang'));
    $penerbit				= addslashes($this->input->post('penerbit'));
    $th_terbit				= addslashes($this->input->post('th_terbit'));
    $isbn					= addslashes($this->input->post('isbn'));
    $jml_hal				= addslashes($this->input->post('jml_hal'));
    $asal_perolehan			= addslashes($this->input->post('asal_perolehan'));
    $harga					= addslashes($this->input->post('harga'));
    $id_lokasi				= addslashes($this->input->post('id_lokasi'));
    $stat					= addslashes($this->input->post('stat'));
    $deskripsi				= addslashes($this->input->post('deskripsi'));
    
    $cari					= addslashes($this->input->post('q'));
    //view tampilan website\
    $a['data']				= $this->db->query("SELECT * FROM t_buku LIMIT $awal, $akhir ")->result();
    $a['page']				= "d_buku";

    if ($mau_ke == "del") {
        $this->db->query("DELETE FROM t_buku WHERE id = '$idu'");
        
        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been deleted </div>");
        redirect('apps/buku');
    } else if ($mau_ke == "cari") {
        $a['data']		= $this->db->query("SELECT * FROM t_buku WHERE judul LIKE '%$cari%' OR penerbit LIKE '%$cari%' OR pengarang LIKE '%$cari%' OR deskripsi LIKE '%$cari%' ORDER BY id DESC")->result();
        $a['page']	= "d_buku";
    } else if ($mau_ke == "add") {
        $a['page']	= "f_buku";
    } else if ($mau_ke == "edt") {
        $a['datpil']		= $this->db->query("SELECT * FROM t_buku WHERE id = '$idu'")->row();	
        $a['page']			= "f_buku";
    }else if ($mau_ke == "act_add") {
        $this->db->query("INSERT INTO t_buku VALUES ('', '$id_kelas', '$id_jenis', '$judul', '$pengarang', '$penerbit', '$th_terbit', '$isbn', '$jml_hal', '$asal_perolehan', '$harga', '$id_lokasi', '$stat', 'R', '$deskripsi', NOW())");
        
        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been added</div>");
        redirect('apps/buku');
    } else if ($mau_ke == "act_edt") {
        $this->db->query("UPDATE t_buku SET id_kelas = '$id_kelas', id_jenis = '$id_jenis', judul = '$judul', pengarang = '$pengarang', penerbit = '$penerbit', th_terbit = '$th_terbit', isbn = '$isbn', jml_hal = '$jml_hal', asal_perolehan = '$asal_perolehan', harga = '$harga', id_lokasi = '$id_lokasi', stat = '$stat', deskripsi = '$deskripsi'  WHERE id = '$idp'");

        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been updated</div>");			
        redirect('apps/buku');
    } else {
        $a['page']	= "d_buku";
    }
    
    $this->load->view('admin/aaa', $a);
}

}