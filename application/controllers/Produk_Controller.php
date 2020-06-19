<?php
class Produk_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="PMK")
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
		$bmr['class']       = 'produk';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Produk';
		$bmr['sub_navigasi']= 'Lihat Produk';
		$bmr['content']     = 'Produk/Produk_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadProduk()
	{
		$produk = $this->Produk_Model->getDataTableProduk();
		$data = array();
		foreach($produk as $dataProduk)
		{
			$row = array();
			$row[] = $dataProduk->id_produk;
			$row[] = $dataProduk->nama_produk;
			$row[] = $dataProduk->ukuran;
			$row[] = $dataProduk->stok;
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Produk_Controller/GetProduk').'/'.$dataProduk->id_produk.'" title="Edit Bahan Utama"><i class="material-icons">edit</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Produk_Model->HitungSemua(),
			"recordsFiltered"=>$this->Produk_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function TambahProduk()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'produk';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Produk';
		$bmr['sub_navigasi'] = 'Lihat Produk';
		$bmr['sub_navigasi2']= 'Tambah Produk';
		$bmr['content']     = 'Produk/ProdukTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanProduk()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_produk',
						  'label'=>'Nama Produk',
						  'rules'=>'trim|required|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'ukuran',
						  'label'=>'Ukuran Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$nama 			 = $this->input->post('nama_produk', TRUE);
			$ukuran 		 = $this->input->post('ukuran', TRUE);
			$this->Produk_Model->InsertProduk($nama, $ukuran);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}
		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function GetProduk()
	{
		$kode 				= $this->uri->segment(3);
		$bmr['data']        = $this->Produk_Model->GetKode($kode);
		$bmr['ukuran']      = $this->Produk_Model->GetUkuran($kode);
		$bmr['all_ukuran']  = $this->Produk_Model->GetSemuaUkuran($kode);
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['class']       = 'produk';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Produk';
		$bmr['sub_navigasi'] = 'Lihat Produk';
		$bmr['sub_navigasi2']= 'Update Produk';
		$bmr['content']     = 'Produk/ProdukUpdate_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdateProduk()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_produk',
						  'label'=>'Nama Produk',
						  'rules'=>'trim|required|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'ukuran',
						  'label'=>'Ukuran Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$kode            = $this->input->post('kode_produk', TRUE);
			$nama 			 = $this->input->post('nama_produk', TRUE);
			$ukuran 		 = $this->input->post('ukuran', TRUE);
			$this->Produk_Model->UpdateProduk($kode, $nama, $ukuran);
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