<?php
class Akun_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="SKU")
			{
				redirect('Beranda_Controller/logout');
			}
		}
	}

	public function index()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'akun';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Akun';
		$bmr['sub_navigasi']= 'Lihat Data Akun';
		$bmr['content']     = 'Akun/Akun_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadAkun()
	{
		$akun = $this->Akun_Model->getDataTableAkun();
		$data = array();
		foreach($akun as $dataAkun)
		{
			$row = array();
			$row[] = $dataAkun->no_akun;
			$row[] = $dataAkun->nama_akun;
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Akun_Controller/GetAkun').'/'.$dataAkun->no_akun.'" title="Edit Akun"><i class="material-icons">edit</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Akun_Model->HitungSemua(),
			"recordsFiltered"=>$this->Akun_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function TambahAkun()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'akun';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Akun';
		$bmr['sub_navigasi'] = 'Lihat Data Akun';
		$bmr['sub_navigasi2']= 'Tambah Data Akun';
		$bmr['content']     = 'Akun/AkunTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanAkun()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'kode_akun',
						  'label'=>'Kode Akun',
						  'rules'=>'trim|required|numeric|is_unique[akun.no_akun]|min_length[3]|max_length[3]|callback_cek_header',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'numeric'=>'%s Hanya Berupa Angka', 'is_unique'=>"".$_POST['kode_akun']." Sudah Tersedia", 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 3 Karakter', 'cek_header'=>'%s Tidak Valid')
						),
					array('field'=>'nama_akun',
						  'label'=>'Nama Akun',
						  'rules'=>'trim|required|is_unique[akun.nama_akun]|callback_alpha_only|min_length[3]|max_length[50]',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'is_unique'=>"".$_POST['nama_akun']." Sudah Tersimpan", 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 50 Karakter')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$kode   = $this->input->post('kode_akun', TRUE);
			$nama   = $this->input->post('nama_akun', TRUE);
			$header = substr($kode, 0, 1);
			$this->Akun_Model->InsertAkun($kode, $nama, $header);
			
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);

	}

	public function GetAkun()
	{
		$kode 				= $this->uri->segment(3);
		$bmr['data']        = $this->Akun_Model->GetKode($kode);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'akun';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Akun';
		$bmr['sub_navigasi'] = 'Lihat Data Akun';
		$bmr['sub_navigasi2']= 'Update Data Akun';
		$bmr['content']     = 'Akun/AkunUpdate_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdateAkun()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_akun',
						  'label'=>'Nama Akun',
						  'rules'=>'trim|required|is_unique[akun.nama_akun]|callback_alpha_only|min_length[3]|max_length[50]',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'is_unique'=>"".$_POST['nama_akun']." Sudah Tersimpan", 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 50 Karakter')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$kode   = $this->input->post('kode_akun', TRUE);
			$nama   = $this->input->post('nama_akun', TRUE);
			$this->Akun_Model->UpdateAkun($kode, $nama);
			
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function alpha_only($nama)
	{
		if ($nama != '' && ! preg_match('/^[a-zA-Z\s]+$/', $nama))
		{
        	return FALSE;
   	 	}else {
       		return TRUE;
    	}
	}

	public function cek_header($kode)
	{
		$header = substr($kode, 0, 1);
		if($header>5)
		{
			return FALSE;
		}else{
			return TRUE;
		}
	}
}
?>