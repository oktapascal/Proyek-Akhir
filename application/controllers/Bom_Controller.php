<?php
class Bom_Controller extends CI_Controller
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
		$bmr['produk']      = $this->Bom_Model->GetProdukNoBomBahan();
		$bmr['class']       = 'bom';
		$bmr['id']		    = 'bombahantambah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bill Of Material';
		$bmr['sub_navigasi']= 'Bill Of Material Produk (Bahan)';
		$bmr['sub_navigasi2']= 'Tentukan Bill Of Material Produk (Bahan)';
		$bmr['content']     = 'Bom/BomBahan/BomBahanTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function PilihProduk()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'produk',
						  'label'=>'Produk',
						  'rules'=>'trim|required|callback_select_validate',
						  'errors'=>array('select_validate'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$produk['produk'] = $this->input->post('produk', TRUE);
			$this->session->set_userdata($produk); 	
		}
		else{
			$data['messages'] = form_error('produk');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function PilihBomBahan()
	{
		
		$bmr['id_produk']   = $this->session->userdata('produk');
		$bmr['bahan']       = $this->Bom_Model->GetAllBahan();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['bombahan']    = $this->Bom_Model->GetBomBahan();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'bom';
		$bmr['id']		    = 'bombahantambah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bill Of Material';
		$bmr['sub_navigasi']= 'Bill Of Material Produk (Bahan)';
		$bmr['sub_navigasi2']= 'Tentukan Bill Of Material Produk (Bahan)';
		$bmr['content']     = 'Bom/BomBahan/BomBahanPilih_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function GetSatuan()
	{
		$bahan = $this->input->get('bahan', TRUE);
		$data   = $this->Bom_Model->GetSatuan($bahan);
		echo json_encode($data);
	}

	public function TambahBom()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'bahan',
						  'label'=>'Bahan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'jumlah',
						  'label'=>'Jumlah yang Dibutuhkan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'satuan',
						  'label'=>'Satuan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success']  = true;
			$bahan            = $this->input->post('bahan', TRUE);
			$produk           = $this->session->userdata('produk');
			$jumlah           = $this->input->post('jumlah', TRUE);
			$this->Bom_Model->TambahBomBahan($bahan, $produk, $jumlah);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function hapus()
	{
		$bahan  = $this->uri->segment(3);
		$produk = $this->session->userdata('produk');
		$this->Bom_Model->hapusbahan($bahan, $produk);
		redirect('Bom_Controller/PilihBomBahan');
	}

	public function SimpanBomBahan()
	{
		$simpan = $this->Bom_Model->SimpanBomBahan();
		if($simpan == TRUE)
		{
			$this->cart->destroy();
			$this->session->unset_userdata('produk');
			$this->session->set_flashdata('notifikasi_bom', 'Bill Of Material (Bahan) Berhasil Disimpan');
			redirect('Bom_Controller');
		}
	}

	public function BomProduk()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['produk']      = $this->Bom_Model->GetProdukNoBomBeban();
		$bmr['class']       = 'bom';
		$bmr['id']		    = 'bombebantambah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bill Of Material';
		$bmr['sub_navigasi']= 'Bill Of Material Produk (Beban)';
		$bmr['sub_navigasi2']= 'Tentukan Bill Of Material Produk (Beban)';
		$bmr['content']     = 'Bom/BomBeban/BomBebanTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function PilihBomBeban()
	{
		$bmr['id_produk']   = $this->session->userdata('produk');
		$bmr['beban']       = $this->Bom_Model->GetAllBeban();
		$bmr['bombeban']    = $this->Bom_Model->GetBomBeban();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'bom';
		$bmr['id']		    = 'bombebantambah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bill Of Material';
		$bmr['sub_navigasi']= 'Bill Of Material Produk (Beban)';
		$bmr['sub_navigasi2']= 'Tentukan Bill Of Material Produk (Beban)';
		$bmr['content']     = 'Bom/BomBeban/BomBebanPilih_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function TambahBomBeban()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'beban',
						  'label'=>'Beban',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success']  = true;
			$beban            = $this->input->post('beban', TRUE);
			$produk           = $this->session->userdata('produk');
			$this->Bom_Model->TambahBomBeban($beban, $produk);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function hapusbeban()
	{
		$beban  = $this->uri->segment(3);
		$produk = $this->session->userdata('produk');
		$this->Bom_Model->hapusbeban($beban, $produk);
		redirect('Bom_Controller/PilihBomBeban');
	}

	public function SimpanBomBeban()
	{
		$simpan = $this->Bom_Model->SimpanBomBeban();
		if($simpan == TRUE)
		{
			$this->session->unset_userdata('produk');
			$this->session->set_flashdata('notifikasi_beban', 'Bill Of Material (Beban) Berhasil Disimpan');
			redirect('Bom_Controller/BomProduk');
		}
	}

	public function LihatBom()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['produk']      = $this->Bom_Model->GetProdukBomSemua();
		$bmr['class']       = 'bom';
		$bmr['id']		    = 'bomlihat';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bill Of Material';
		$bmr['sub_navigasi']= 'Bill Of Material Produk (Beban)';
		$bmr['sub_navigasi2']= 'Melihat Bill Of Material Produk';
		$bmr['content']     = 'Bom/BomSemua/BomSemuaPilih_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function LihatBomSemua()
	{
		$produk             = $this->session->userdata('produk');
		$bmr['id_produk']   = $this->session->userdata('produk');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['bahan']       = $this->Bom_Model->GetProdukBomBahan($produk);
		$bmr['beban']       = $this->Bom_Model->GetProdukBomBeban($produk);
		$bmr['class']       = 'bom';
		$bmr['id']		    = 'bomlihat';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Bill Of Material';
		$bmr['sub_navigasi']= 'Bill Of Material Produk (Beban)';
		$bmr['sub_navigasi2']= 'Melihat Bill Of Material Produk';
		$bmr['content']     = 'Bom/BomSemua/BomSemuaLihat_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function select_validate($select)
	{
		if($select == "none")
		{
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
}
?>