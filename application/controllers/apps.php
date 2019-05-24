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

  public function trans() {
    if(! $this->session->userdata('validated')){
        redirect('apps/login');
    }
    
    
    //ambil variabel URL
    $mau_ke					= $this->uri->segment(3);
    $idu					= $this->uri->segment(4);
    $idu2					= $this->uri->segment(5);

    //ambil variabel Postingan
    $id_anggota				= addslashes($this->input->post('id_anggota'));
    $tgl_pinjam				= addslashes($this->input->post('tgl_pinjam'));
    $tgl_kembali			= addslashes($this->input->post('tgl_kembali'));
    $ket					= addslashes($this->input->post('ket'));
    $jml_buku				= addslashes($this->input->post('jml_buku'));
    
    $cari					= addslashes($this->input->post('q'));
    //view tampilan website\
    $a['data']		= $this->db->query("SELECT *, COUNT(id_anggota) AS jml_pinjam FROM t_trans WHERE stat = 'P' GROUP BY id_anggota DESC")->result();
    $a['page']		= "d_trans";

    if ($mau_ke == "pilih_anggota") {
        $a['page']		= "d_pilih_anggota";
    } else if ($mau_ke == "det") {
        $q_instansi	= $this->db->query("SELECT * FROM r_config LIMIT 1")->row();
        $a['denda']		= $q_instansi->denda;
        $a['nama_anggota']	= getNama($idu);
        $a['data']		= $this->db->query("SELECT *, DATEDIFF(NOW(), tgl_kembali) AS terlambat FROM t_trans WHERE id_anggota = '$idu' AND stat = 'P'")->result();
        $a['page']		= "d_detil_pinjam";
    } else if ($mau_ke == "cari") {
        $a['data']		= $this->db->query("SELECT *, COUNT(id_anggota) AS jml_pinjam FROM t_trans WHERE id_anggota = '$cari' AND stat = 'P' GROUP BY id_anggota DESC")->result();
        $a['page']		= "d_trans";
    } else if ($mau_ke == "add") {
        $id_anggota		= $this->input->post('id_anggota');
        $jumlah_buku	= $this->input->post('jml_buku');
        
        $cek_peminjam	= $this->db->query("SELECT *, COUNT(id_anggota) AS jml FROM t_trans WHERE stat = 'P' AND id_anggota = '$id_anggota' GROUP BY id_anggota")->num_rows();
        
        if ($cek_peminjam > 0) {
            $this->session->set_flashdata("k", "<div class=\"alert alert-error\">Peminjam tersebut masih mempunyai peminjaman yang belum dikembalikan. </div>");
            redirect('apps/trans/pilih_anggota');
        } else {
            $a['det_anggota']	= $this->db->query("SELECT * FROM t_anggota WHERE id = '$id_anggota'")->row();
            $a['data']		= $this->db->query("SELECT * FROM t_trans WHERE id_anggota = '$id_anggota' AND stat = 'P' ORDER BY id DESC")->result();
            $a['jml_buku']	= $jumlah_buku;
            $a['page']		= "f_trans";
        }
    } else if ($mau_ke == "kembali") {
        $id_anggota		= $this->uri->segment(4);
        $id_buku		= $this->uri->segment(5);
        $id_trans		= $this->uri->segment(6);
        $telat			= $this->uri->segment(7);
        $denda			= $this->uri->segment(8);
        
        $a['data']		= $this->db->query("UPDATE t_trans SET stat = 'K', telat = '$telat', denda = '$denda'  WHERE id = '$id_trans'");
        $a['data']		= $this->db->query("UPDATE t_buku SET stat_pinjam = 'R' WHERE id = '$id_buku'");
        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been changed </div>");
        redirect('apps/trans/det/'.$id_anggota);
    } else if ($mau_ke == "perpanjang") {
        $a['data']		= $this->db->query("UPDATE t_trans SET tgl_kembali = '".adddate(7)."' WHERE id = '$idu'");
        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been changed </div>");
        redirect('apps/trans/det/'.$idu2);
    } else if ($mau_ke == "act_add") {
        //$sama  = "";
        for ($i = 1; $i <= $jml_buku; $i++) {
            $this->db->query("INSERT INTO t_trans VALUES ('', '".$this->input->post('id_buku_'.$i)."', '$id_anggota', '$tgl_pinjam', '$tgl_kembali', 'P', '$ket', '0', '0')");
            $this->db->query("UPDATE t_buku SET stat_pinjam = 'P' WHERE id = '".$this->input->post('id_buku_'.$i)."'");
        }
        $this->session->set_flashdata("k", "<div class=\"alert alert-success\">Data has been added</div>");
        redirect('apps/trans');
    } else if ($mau_ke == "caribuku") {
        $id_data			=  empty($_POST['id_data']) ? $_GET['id_data'] : $_POST['id_data'];
        $kata_kunci			=  empty($_POST['kata_kunci']) ? $_GET['kata_kunci'] : $_POST['kata_kunci'];
    
        $q_data				=  $this->db->query("SELECT id, judul FROM t_buku WHERE judul LIKE '%$kata_kunci%' ORDER BY id ASC");
        $data				=  $q_data->result();
        $jumlah_hasil		=  $q_data->num_rows();
        
        if (strlen($kata_kunci) < 4) {
            echo '<div class="alert alert-error">Kata kunci minimal 3 huruf</a>';
        } else if (!empty($data)) {
            echo ' 	<div class="alert alert-info">Ditemukan <b>'.$jumlah_hasil.'</b> data</div>';
            echo '	<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="20%">ID</th>
                                <th width="70%">Judul</th>
                                <th width="10%">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($data as $d) {
                echo '	<tr>
                            <td>'.$d->id.'</td>
                            <td>'.$d->judul.'</td>
                            <td><a href="#" class="btn btn-success btn-sm" onclick="return isikan_kode('.$id_data.', '.$d->id.', \''.addslashes($d->judul).'\');">OK</a></td>
                        </tr>';
            }
            echo '	</tbody></table>';
        } else {
            echo '<div class="alert alert-error">Tidak ditemukan</a>';
        }
        exit;
    } else {
        $a['page']	= "d_trans";
    }
    
    $this->load->view('admin/aaa', $a);
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