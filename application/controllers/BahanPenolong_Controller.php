<?php
class BahanPenolong_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="KPG")
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
		$bmr['class']       = 'bahan';
		$bmr['id']		    = 'bahanpenolong';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan Penolong';
		$bmr['sub_navigasi']= 'Lihat Bahan Penolong';
		$bmr['content']     = 'BahanPenolong/BahanPenolong_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadBahanPenolong()
	{
		$bahanpenolong = $this->BahanPenolong_Model->getDataTableBahanPenolong();
		$data = array();
		foreach($bahanpenolong as $dataBahanPenolong)
		{
			$row = array();
			$row[] = $dataBahanPenolong->id_bahan;
			$row[] = $dataBahanPenolong->nama_bahan;
			$row[] = $dataBahanPenolong->stok_digudang;
			$row[] = $dataBahanPenolong->stok_diproduksi;
			$row[] = $dataBahanPenolong->satuan;
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/BahanPenolong_Controller/GetBahanPenolong').'/'.$dataBahanPenolong->id_bahan.'" title="Edit Bahan Penolong"><i class="material-icons">edit</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->BahanPenolong_Model->HitungSemua(),
			"recordsFiltered"=>$this->BahanPenolong_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function TambahBahanPenolong()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'bahan';
		$bmr['id']		    = 'bahanpenolong';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan Penolong';
		$bmr['sub_navigasi'] = 'Lihat Bahan Penolong';
		$bmr['sub_navigasi2']= 'Tambah Bahan Penolong';
		$bmr['content']     = 'BahanPenolong/BahanPenolongTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanBahanPenolong()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_bahan',
						  'label'=>'Nama Bahan Penolong',
						  'rules'=>'trim|required|is_unique[bahan.nama_bahan]|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'is_unique'=>"".$_POST['nama_bahan']." Sudah Tersimpan", 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'satuan',
						  'label'=>'Satuan Bahan',
						  'rules'=>'trim|required|callback_alpha_only|min_length[3]|max_length[10]',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 10 Karakter')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$nama 			 = $this->input->post('nama_bahan', TRUE);
			$satuan 		 = $this->input->post('satuan', TRUE);
			$this->BahanPenolong_Model->InsertBahanPenolong($nama, $satuan);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function GetBahanPenolong()
	{
		$kode 				= $this->uri->segment(3);
		$bmr['data']        = $this->BahanPenolong_Model->GetKode($kode);
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['class']       = 'bahan';
		$bmr['id']		    = 'bahanpenolong';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan Penolong';
		$bmr['sub_navigasi'] = 'Lihat Bahan Penolong';
		$bmr['sub_navigasi2']= 'Update Bahan Penolong';
		$bmr['content']     = 'BahanPenolong/BahanPenolongUpdate_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdateBahanPenolong()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_bahan',
						  'label'=>'Nama Bahan Penolong',
						  'rules'=>'trim|required|is_unique[bahan.nama_bahan]|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'is_unique'=>"".$_POST['nama_bahan']." Sudah Tersimpan", 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'satuan',
						  'label'=>'Satuan Bahan',
						  'rules'=>'trim|required|callback_alpha_only|min_length[3]|max_length[10]',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 10 Karakter')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$kode 			 = $this->input->post('kode_bahan', TRUE);
			$nama 			 = $this->input->post('nama_bahan', TRUE);
			$satuan 		 = $this->input->post('satuan', TRUE);
			$this->BahanPenolong_Model->UpdateBahanPenolong($kode, $nama, $satuan);
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
}
?>