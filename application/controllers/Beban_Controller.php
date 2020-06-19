<?php
class Beban_Controller extends CI_Controller
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
		$bmr['class']       = 'beban';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Beban-Beban';
		$bmr['sub_navigasi']= 'Lihat Data Beban';
		$bmr['content']     = 'Beban/Beban_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadBeban()
	{
		$beban = $this->Beban_Model->getDataTableBeban();
		$data = array();
		foreach($beban as $dataBeban)
		{
			$row = array();
			$row[] = $dataBeban->id_beban;
			$row[] = $dataBeban->nama_beban;
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Beban_Controller/GetBeban').'/'.$dataBeban->id_beban.'" title="Edit Beban"><i class="material-icons">edit</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Beban_Model->HitungSemua(),
			"recordsFiltered"=>$this->Beban_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function TambahBeban()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['akun']        = $this->Beban_Model->GetAkun();
		$bmr['class']       = 'beban';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan-Beban';
		$bmr['sub_navigasi'] = 'Lihat Data Beban';
		$bmr['sub_navigasi2']= 'Tambah Data Beban';
		$bmr['content']     = 'Beban/BebanTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanBeban()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_beban',
						  'label'=>'Nama Beban',
						  'rules'=>'trim|required|is_unique[beban.nama_beban]|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'numeric'=>'%s Hanya Berupa Angka', 'is_unique'=>"".$_POST['nama_beban']." Sudah Tersimpan", 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'no_akun',
						  'label'=>'Akun Beban',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Wajib Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$nama = $this->input->post('nama_beban', TRUE);
			$akun = $this->input->post('no_akun', TRUE);
			$this->Beban_Model->InsertBeban($nama, $akun);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function GetBeban()
	{
		$kode 				= $this->uri->segment(3);
		$bmr['data']        = $this->Beban_Model->GetKode($kode);
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['class']       = 'beban';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan-Beban';
		$bmr['sub_navigasi'] = 'Lihat Data Beban';
		$bmr['sub_navigasi2']= 'Update Data Beban';
		$bmr['content']     = 'Beban/BebanUpdate_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdateBeban()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_beban',
						  'label'=>'Nama Beban',
						  'rules'=>'trim|required|is_unique[beban.nama_beban]|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'numeric'=>'%s Hanya Berupa Angka', 'is_unique'=>"".$_POST['nama_beban']." Sudah Tersimpan", 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$kode = $this->input->post('kode_beban', TRUE);
			$nama = $this->input->post('nama_beban', TRUE);
			$this->Beban_Model->UpdateBeban($kode, $nama);
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
		if ($nama != '' && ! preg_match('/^[a-zA-Z\s()]+$/', $nama))
		{
        	return FALSE;
   	 	}else {
       		return TRUE;
    	}
	}
}
?>