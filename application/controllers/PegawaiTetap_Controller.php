<?php 
class PegawaiTetap_Controller extends CI_Controller
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
		$bmr['class']       = 'pegawai';
		$bmr['id']		    = 'pegawaitetap';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pegawai';
		$bmr['sub_navigasi']= 'Lihat Pegawai Tetap';
		$bmr['content']     = 'Pegawai/PegawaiTetap/PegawaiTetap_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadPegawaiTetap()
	{
		$pegawaitetap = $this->PegawaiTetap_Model->getDataTablePegawaiTetap();
		$data = array();
		foreach($pegawaitetap as $dataPegawaiTetap)
		{
			$row = array();
			$tanggal = date("d-m-Y", strtotime($dataPegawaiTetap->tanggal_masuk));
			$row[] = $dataPegawaiTetap->id_pegawai;
			$row[] = $dataPegawaiTetap->nama_pegawai;
			$row[] = $dataPegawaiTetap->nama_posisi;
			$row[] = $tanggal;
			$render = array('<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick=lihatdata("'.$dataPegawaiTetap->id_pegawai.'") rel="modal:open" data-toggle="modal" title="Lihat Pegawai"><i class="material-icons">visibility</i></a>
			   <a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/PegawaiTetap_Controller/GetPegawaiTetap').'/'.$dataPegawaiTetap->id_pegawai.'" title="Edit Pegawai Tetap"><i class="material-icons">edit</i></a>');
			$row[] = $render;

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->PegawaiTetap_Model->HitungSemua(),
			"recordsFiltered"=>$this->PegawaiTetap_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailPegawaiTetap()
	{
		$kode = $this->uri->segment(3);
		$this->db->where('id_pegawai', $kode);
		$cek = $this->db->get('pegawai');
		if($cek->num_rows() > 0)
			{
				$pegawaitetap              = $this->PegawaiTetap_Model->GetDetail($kode);
				$pt['kode_pegawai']        = $kode;
				$pt['no_telp']             = $pegawaitetap['no_hp'];
				$pt['tanggal_awal']        = date("d-m-Y", strtotime($pegawaitetap['tanggal_awal']));
				$pt['tanggal_akhir']       = date("d-m-Y", strtotime($pegawaitetap['tanggal_akhir']));
				$pt['alamat']              = $pegawaitetap['alamat'];
				$pt['nik_pegawai']         = $pegawaitetap['nik_pegawai'];
				$pt['status_pernikahan']   = $pegawaitetap['status_pernikahan'];
				$pt['tanggal_lahir']       = $pegawaitetap['tanggal_lahir'];
			}
		else
			{
				$pt['kode_pegawai'] 	   = "";
				$pt['no_telp']      	   = "";
				$pt['tanggal_awal'] 	   = "";
				$pt['tanggal_akhir']	   = "";
				$pt['alamat']       	   = "";
				$pt['nik_pegawai']         = "";
				$pt['status_pernikahan']   = "";
				$pt['tanggal_lahir']       = "";
			}
		echo json_encode($pt);
	}

	public function TambahPegawaiTetap()
	{
		$bmr['posisi']              = $this->PegawaiTetap_Model->GetPosisi();
		$bmr['tgl_hari']   			= hari_ini(date('w'));
		$bmr['tgl_indo']    		= tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']          = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       		= 'pegawai';
		$bmr['id']		    		= 'pegawaitetap';
		$bmr['sub_id']      		= '';
		$bmr['navigasi']    		= 'Pegawai';
		$bmr['sub_navigasi']		= 'Lihat Pegawai Tetap';
		$bmr['sub_navigasi2']       = 'Tambah Pegawai Tetap';
		$bmr['content']     		= 'Pegawai/PegawaiTetap/PegawaiTambah_View';
		$bmr['menu']        		= 'Menu/Menu_View';
		$bmr['navbar']      		= 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanPegawaiTetap()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array(
						'field'=>'kode_pegawai',
						'label'=>'Kode Pegawai',
						'rules'=>'trim|required|is_unique[pegawai.id_pegawai]|callback_numeric_only|min_length[10]|max_length[10]',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 10 Karakter', 'max_length'=>'%s Maksimal 10 Karakter', 'numeric_only'=>'%s Tidak Valid', 'is_unique'=>'%s Sudah Ada')
					),
					array(
						'field'=>'nama_pegawai',
						'label'=>'Nama Pegawai',
						'rules'=>'trim|required|callback_alpha_only|min_length[4]|max_length[30]',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 4 Karakter', 'max_length'=>'%s Maksimal 30 Karakter')
					),
					array(
						'field'=>'no_telpon',
						 'label'=>'Nomor Telepon',
						 'rules'=>'trim|required|callback_numeric_only|min_length[11]|max_length[12]',
						 'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'numeric_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 11 Karakter', 'max_length'=>'%s Maksimal 12 Karakter')
					),
					array(
						'field'=>'alamat',
						 'label'=>'Alamat',
						 'rules'=>'trim|required|max_length[100]',
						 'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'max_length'=>'%s Maksimal 100 Karakter')
					),
					array(
						'field'=>'username',
						'label'=>'Username Pegawai',
						'rules'=>'trim|required|min_length[5]|max_length[10]|is_unique[pegawai.username]',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 10 Karakter', 'is_unique'=>'%s Sudah Ada')
					),
					array(
						'field'=>'password',
						'label'=>'Password',
						'rules'=>'trim|required',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong')
					),
					array(
						'field'=>'confirm',
						'label'=>'Konfirmasi Password',
						'rules'=>'trim|required|matches[password]',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'matches'=>'%s Tidak Sama Dengan Password')
					),
					array(
						'field'=>'posisi',
						'label'=>'Posisi Pegawai',
						'rules'=>'trim|required',
						'errors'=>array('required'=>'%s Harus Dipilih')
					),
					array(
						'field'=>'status_pernikahan',
						'label'=>'Status Pernikahan Pegawai',
						'rules'=>'trim|required',
						'errors'=>array('required'=>'%s Harus Dipilih')
					),
					array(
						'field'=>'nik_pegawai',
						'label'=>'NIK Pegawai',
						'rules'=>'trim|required|is_unique[pegawai.nik_pegawai]|callback_numeric_only',
						'errors'=>array('required'=>'%s Harus Dipilih', 'numeric_only'=>'%s Tidak Valid', 'is_unique'=>'%s Sudah Ada')
					),
					array(
						'field'=>'tanggal_lahir',
						'label'=>'Tanggal Lahir Pegawai',
						'rules'=>'trim|required',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong')
					)
				);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
			if($this->form_validation->run())
			{
				$data['success'] = true;
				$kode 			 = $this->input->post('kode_pegawai', TRUE);
				$nik             = $this->input->post('nik_pegawai', TRUE);
				$nama 			 = $this->input->post('nama_pegawai', TRUE);
				$nomor           = $this->input->post('no_telpon', TRUE);
				$alamat          = $this->input->post('alamat', TRUE);
				$username        = $this->input->post('username', TRUE);
				$tanggal_lahir   = date('Y-m-d', strtotime($this->input->post('tanggal_lahir', TRUE)));
				$password        = sandi_hash($this->input->post('password', TRUE));
				$posisi          = $this->input->post('posisi', TRUE);
				$pernikahan      = $this->input->post('status_pernikahan', TRUE);
				$this->PegawaiTetap_Model->InsertPegawaiTetap($kode, $nik, $nama, $nomor, $alamat, $username, $password, $posisi, $pernikahan, $tanggal_lahir);              
			}
			else{
				foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
				}
			}

			$data['token']  = $this->security->get_csrf_hash();
			echo json_encode($data);
	}

	public function GetPegawaiTetap()
	{
		$kode                       = $this->uri->segment(3);
		$bmr['data']				= $this->PegawaiTetap_Model->GetKode($kode);
		$bmr['tgl_hari']   			= hari_ini(date('w'));
		$bmr['tgl_indo']    		= tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']          = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       		= 'pegawai';
		$bmr['id']		    		= 'pegawaitetap';
		$bmr['sub_id']      		= '';
		$bmr['navigasi']    		= 'Pegawai';
		$bmr['sub_navigasi']		= 'Lihat Pegawai Tetap';
		$bmr['sub_navigasi2']       = 'Update Pegawai Tetap';
		$bmr['content']     		= 'Pegawai/PegawaiTetap/PegawaiUpdate_View';
		$bmr['menu']        		= 'Menu/Menu_View';
		$bmr['navbar']      		= 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdatePegawaiTetap()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array(
						'field'=>'kode_pegawai',
						'label'=>'Kode Pegawai',
						'rules'=>'trim|required|callback_numeric_only|min_length[10]|max_length[10]',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 10 Karakter', 'max_length'=>'%s Maksimal 10 Karakter', 'numeric_only'=>'%s Tidak Valid')
					),
					array(
						'field'=>'nik_pegawai',
						'label'=>'NIK Pegawai',
						'rules'=>'trim|required|callback_numeric_only',
						'errors'=>array('required'=>'%s Harus Dipilih', 'numeric_only'=>'%s Tidak Valid')
					),
					array(
						'field'=>'nama_pegawai',
						'label'=>'Nama Pegawai',
						'rules'=>'trim|required|callback_alpha_only|min_length[4]|max_length[30]',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 4 Karakter', 'max_length'=>'%s Maksimal 30 Karakter')
					),
					array(
						'field'=>'no_telpon',
						 'label'=>'Nomor Telepon',
						 'rules'=>'trim|required|callback_numeric_only|min_length[11]|max_length[12]',
						 'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'numeric_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 11 Karakter', 'max_length'=>'%s Maksimal 12 Karakter')
					),
					array(
						'field'=>'alamat',
						 'label'=>'Alamat',
						 'rules'=>'trim|required|max_length[100]',
						 'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'max_length'=>'%s Maksimal 100 Karakter')
					),
					array(
						'field'=>'status_pernikahan',
						'label'=>'Status Pernikahan Pegawai',
						'rules'=>'trim|required',
						'errors'=>array('required'=>'%s Harus Dipilih')
					),
					array(
						'field'=>'tanggal_lahir',
						'label'=>'Tanggal Lahir Pegawai',
						'rules'=>'trim|required',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong')
					)
				);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
			if($this->form_validation->run())
			{
				$data['success'] = true;
				$nik             = $this->input->post('nik_pegawai', TRUE);
				$kode 			 = $this->input->post('kode_pegawai', TRUE);
				$nama 			 = $this->input->post('nama_pegawai', TRUE);
				$nomor           = $this->input->post('no_telpon', TRUE);
				$alamat          = $this->input->post('alamat', TRUE);
				$pernikahan      = $this->input->post('status_pernikahan', TRUE);
				$tanggal_lahir   = date('Y-m-d', strtotime($this->input->post('tanggal_lahir', TRUE)));
				$this->PegawaiTetap_Model->UpdatePegawaiTetap($kode, $nama, $nomor, $alamat, $nik, $pernikahan, $tanggal_lahir);

			}
			else{
				foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
				}
			}

			$data['token']  = $this->security->get_csrf_hash();
			echo json_encode($data);
	}

	public function alpha_only($string)
	{
		if ($string != '' && ! preg_match('/^[a-zA-Z\s]+$/', $string))
		{
        	return FALSE;
   	 	}else {
       		return TRUE;
    	}
	}

	public function numeric_only($string)
	{
		if ($string != '' && ! preg_match('/^[0-9]+$/', $string))
		{
        	return FALSE;
   	 	}else {
       		return TRUE;
    	}
	}
}
?>