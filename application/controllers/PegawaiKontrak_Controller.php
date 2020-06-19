<?php 
class PegawaiKontrak_Controller extends CI_Controller
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
		$bmr['id']		    = 'pegawaikontrak';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pegawai';
		$bmr['sub_navigasi']= 'Lihat Pegawai Kontrak';
		$bmr['content']     = 'Pegawai/PegawaiKontrak/PegawaiKontrak_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadPegawaiKontrak()
	{
		$pegawaikontrak = $this->PegawaiKontrak_Model->getDataTablePegawaiKontrak();
		$data = array();
		foreach($pegawaikontrak as $dataPegawaiKontrak)
		{
			$row = array();
			$tanggal = date("d-m-Y", strtotime($dataPegawaiKontrak->tanggal_masuk));
			$row[] = $dataPegawaiKontrak->id_pegawai;
			$row[] = $dataPegawaiKontrak->nama_pegawai;
			$row[] = $dataPegawaiKontrak->nama_posisi;
			$row[] = $tanggal;
			$render = array('<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick=lihatdata("'.$dataPegawaiKontrak->id_pegawai.'") rel="modal:open" data-toggle="modal" title="Lihat Pegawai"><i class="material-icons">visibility</i></a>
			   <a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/PegawaiKontrak_Controller/GetPegawaiKontrak').'/'.$dataPegawaiKontrak->id_pegawai.'" title="Edit Pegawai Kontrak"><i class="material-icons">edit</i></a>');
			$row[] = $render;

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->PegawaiKontrak_Model->HitungSemua(),
			"recordsFiltered"=>$this->PegawaiKontrak_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailPegawaiKontrak()
	{
		$kode = $this->uri->segment(3);
		$this->db->where('id_pegawai', $kode);
		$cek = $this->db->get('pegawai');
		if($cek->num_rows() > 0)
			{
				$pegawaikontrak            = $this->PegawaiKontrak_Model->GetDetail($kode);
				$pk['kode_pegawai']        = $kode;
				$pk['no_telp']             = $pegawaikontrak['no_hp'];
				$pk['tanggal_awal']        = date("d-m-Y", strtotime($pegawaikontrak['tanggal_awal']));
				$pk['tanggal_akhir']       = date("d-m-Y", strtotime($pegawaikontrak['tanggal_akhir']));
				$pk['alamat']              = $pegawaikontrak['alamat'];
				$pk['nik_pegawai']         = $pegawaikontrak['nik_pegawai'];
				$pk['status_pernikahan']   = $pegawaikontrak['status_pernikahan'];
				$pk['tanggal_lahir']       = date('d-m-Y', strtotime($pegawaikontrak['tanggal_lahir']));
			}
		else
			{
				$pk['kode_pegawai'] 	   = "";
				$pk['no_telp']      	   = "";
				$pk['tanggal_awal'] 	   = "";
				$pk['tanggal_akhir']	   = "";
				$pk['alamat']       	   = "";
				$pk['nik_pegawai']         = "";
				$pk['status_pernikahan']   = "";
				$pk['tanggal_lahir']       = "";
			}
		echo json_encode($pk);
	}

	public function TambahPegawaiKontrak()
	{
		$bmr['posisi']              = $this->PegawaiKontrak_Model->GetPosisi();
		$bmr['tgl_hari']   			= hari_ini(date('w'));
		$bmr['tgl_indo']    		= tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']          = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       		= 'pegawai';
		$bmr['id']		    		= 'pegawaikontrak';
		$bmr['sub_id']      		= '';
		$bmr['navigasi']    		= 'Pegawai';
		$bmr['sub_navigasi']		= 'Lihat Pegawai Kontrak';
		$bmr['sub_navigasi2']       = 'Tambah Pegawai Kontrak';
		$bmr['content']     		= 'Pegawai/PegawaiKontrak/PegawaiTambah_View';
		$bmr['menu']        		= 'Menu/Menu_View';
		$bmr['navbar']      		= 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanPegawaiKontrak()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
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
				$nama 			 = $this->input->post('nama_pegawai', TRUE);
				$nomor           = $this->input->post('no_telpon', TRUE);
				$alamat          = $this->input->post('alamat', TRUE);
				$posisi          = $this->input->post('posisi', TRUE);
				$nik             = $this->input->post('nik_pegawai', TRUE);
				$pernikahan      = $this->input->post('status_pernikahan', TRUE);
				$tanggal_lahir   = date('Y-m-d',strtotime($this->input->post('tanggal_lahir', TRUE)));
				$this->PegawaiKontrak_Model->InsertPegawaiKontrak($nama, $nomor, $alamat, $posisi, $nik, $pernikahan, $tanggal_lahir);              
			}
			else{
				foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
				}
			}

			$data['token']  = $this->security->get_csrf_hash();
			echo json_encode($data);
	}

	public function GetPegawaiKontrak()
	{
		$kode                       = $this->uri->segment(3);
		$bmr['data']				= $this->PegawaiKontrak_Model->GetKode($kode);
		$bmr['notifikasi']          = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['tgl_hari']   			= hari_ini(date('w'));
		$bmr['tgl_indo']    		= tgl_indo(date('Y-m-d'));
		$bmr['class']       		= 'pegawai';
		$bmr['id']		    		= 'pegawaikontrak';
		$bmr['sub_id']      		= '';
		$bmr['navigasi']    		= 'Pegawai';
		$bmr['sub_navigasi']		= 'Lihat Pegawai Kontrak';
		$bmr['sub_navigasi2']       = 'Update Pegawai Kontrak';
		$bmr['content']     		= 'Pegawai/PegawaiKontrak/PegawaiUpdate_View';
		$bmr['menu']        		= 'Menu/Menu_View';
		$bmr['navbar']      		= 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdatePegawaiKontrak()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array(
						'field'=>'kode_pegawai',
						'label'=>'Kode Pegawai',
						'rules'=>'trim|required|min_length[10]|max_length[10]',
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
				$kode 			 = $this->input->post('kode_pegawai', TRUE);
				$nama 			 = $this->input->post('nama_pegawai', TRUE);
				$nomor           = $this->input->post('no_telpon', TRUE);
				$alamat          = $this->input->post('alamat', TRUE);
				$nik             = $this->input->post('nik_pegawai', TRUE);
				$pernikahan      = $this->input->post('status_pernikahan', TRUE);
				$tanggal_lahir   = date('Y-m-d',strtotime($this->input->post('tanggal_lahir', TRUE)));
				$this->PegawaiKontrak_Model->UpdatePegawaiKontrak($kode, $nama, $nomor, $alamat, $nik, $pernikahan, $tanggal_lahir);

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