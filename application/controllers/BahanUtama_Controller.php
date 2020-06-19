<?php
class BahanUtama_Controller extends CI_Controller
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
		$bmr['id']		    = 'bahanutama';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan Utama';
		$bmr['sub_navigasi']= 'Lihat Bahan Utama';
		$bmr['content']     = 'BahanUtama/BahanUtama_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadBahanUtama()
	{
		$bahanutama = $this->BahanUtama_Model->getDataTableBahanUtama();
		$data = array();
		foreach($bahanutama as $dataBahanUtama)
		{
			$row = array();
			$row[] = $dataBahanUtama->id_bahan;
			$row[] = $dataBahanUtama->nama_bahan;
			$row[] = $dataBahanUtama->stok_digudang;
			$row[] = $dataBahanUtama->stok_diproduksi;
			$row[] = $dataBahanUtama->satuan;
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/BahanUtama_Controller/GetBahanUtama').'/'.$dataBahanUtama->id_bahan.'" title="Edit Bahan Utama"><i class="material-icons">edit</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->BahanUtama_Model->HitungSemua(),
			"recordsFiltered"=>$this->BahanUtama_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function TambahBahanUtama()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'bahan';
		$bmr['id']		    = 'bahanutama';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan Utama';
		$bmr['sub_navigasi'] = 'Lihat Bahan Utama';
		$bmr['sub_navigasi2']= 'Tambah Bahan Utama';
		$bmr['content']     = 'BahanUtama/BahanUtamaTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanBahanUtama()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_bahan',
						  'label'=>'Nama Bahan Utama',
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
			$this->BahanUtama_Model->InsertBahanUtama($nama, $satuan);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}
		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);

	}

	public function GetBahanUtama()
	{
		$kode 				= $this->uri->segment(3);
		$bmr['data']        = $this->BahanUtama_Model->GetKode($kode);
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['class']       = 'bahan';
		$bmr['id']		    = 'bahanutama';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bahan Utama';
		$bmr['sub_navigasi'] = 'Lihat Bahan Utama';
		$bmr['sub_navigasi2']= 'Update Bahan Utama';
		$bmr['content']     = 'BahanUtama/BahanUtamaUpdate_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdateBahanUtama()
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
			$this->BahanUtama_Model->UpdateBahanUtama($kode, $nama, $satuan);
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