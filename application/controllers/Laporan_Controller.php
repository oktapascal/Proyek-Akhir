<?php
class Laporan_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="PMK" || $this->session->userdata('id_posisi')!="STU" || $this->session->userdata('id_posisi')!="KPP" || $this->session->userdata('id_posisi')!="KPG" || $this->session->userdata('id_posisi')!="STP")
			{
				redirect('Beranda_Controller/logout');
			}
		}
	}

	public function Jurnal()
	{
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('tanggal_laba_rugi');
		$this->session->unset_userdata('tanggal_laporan_gaji');
		$this->session->unset_userdata('akun');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'jurnal';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Jurnal Umum';
		$bmr['content']     = 'Laporan/PilihJurnal_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function PilihJurnal()
	{
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('tanggal_laba_rugi');
		$this->session->unset_userdata('tanggal_laporan_gaji');
		$this->session->unset_userdata('akun');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_jurnal',
						  'label'=>'Periode Jurnal',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_jurnal  = date('Y-m-d',strtotime($this->input->post('tanggal_jurnal', TRUE)));
			$laporan['tanggal_jurnal'] = $periode_jurnal;
			$this->session->set_userdata($laporan); 	
		}
		else{
			$data['messages'] = form_error('tanggal_jurnal');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatJurnal()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['jurnal']      = $this->Laporan_Model->GetJurnal();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'jurnal';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Jurnal Umum';
		$bmr['content']     = 'Laporan/Jurnal_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function BukuBesar()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['akun']        = $this->Laporan_Model->GetAkun();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'bukubesar';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Buku Besar';
		$bmr['content']     = 'Laporan/PilihBukuBesar_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function PilihBukuBesar()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_buku_besar',
						  'label'=>'Periode Buku Besar',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						),
					array('field'=>'akun',
						  'label'=>'Akun',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_buku_besar  = date('Y-m-d',strtotime($this->input->post('tanggal_buku_besar', TRUE)));
			$laporan['tanggal_buku_besar'] = $periode_buku_besar;
			$laporan['akun']               = $this->input->post('akun', TRUE);
			$this->session->set_userdata($laporan); 	
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatBukuBesar()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['header']      = $this->Laporan_Model->GetHeaderAkun();
		$bmr['saldo']       = $this->Laporan_Model->GetSaldoAwal();
		$bmr['buku_besar']  = $this->Laporan_Model->GetBukuBesar();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'bukubesar';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Buku Besar';
		$bmr['content']     = 'Laporan/BukuBesar_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function HargaPokokProduksi()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('akun');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'hpproduksi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Harga Pokok Produksi';
		$bmr['content']     = 'Laporan/PilihHPProduksi_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function PilihHPProduksi()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('akun');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_hpproduksi',
						  'label'=>'Periode Harga Pokok Produksi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_hpproduksi  = date('Y-m-d',strtotime($this->input->post('tanggal_hpproduksi', TRUE)));
			$laporan['tanggal_hpproduksi'] = $periode_hpproduksi;
			$this->session->set_userdata($laporan); 	
		}
		else{
			$data['messages'] = form_error('tanggal_hpproduksi');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatHPProduksi()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['persediaan_dalam_proses'] = $this->Laporan_Model->GetPersediaanAwalDalamProses();
		//$bmr['bahan_baku_awal']         = $this->Laporan_Model->GetBahanBakuAwal();
		$bmr['pembelian']   = $this->Laporan_Model->GetPembelian();
		$bmr['bahan_baku_akhir'] = $this->Laporan_Model->GetBahanBakuAkhir();
		$bmr['btkl']        = $this->Laporan_Model->GetBiayaTenagaKerjaLangsung();
		$bmr['bop']         = $this->Laporan_Model->GetBiayaOverheadPabrik();
		$bmr['persediaan_akhir_dalam_proses'] = $this->Laporan_Model->GetPersediaanAkhirDalamProses();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'hpproduksi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Harga Pokok Produksi';
		$bmr['content']     = 'Laporan/HargaPokokProduksi_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function HargaPokokPenjualan()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('akun');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'hppenjualan';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Harga Pokok Penjualan';
		$bmr['content']     = 'Laporan/PilihHPPenjualan_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function PilihHPPenjualan()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('akun');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_hppenjualan',
						  'label'=>'Periode Harga Pokok Penjualan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_hppenjualan  = date('Y-m-d',strtotime($this->input->post('tanggal_hppenjualan', TRUE)));
			$laporan['tanggal_hppenjualan'] = $periode_hppenjualan;
			$this->session->set_userdata($laporan); 	
		}
		else{
			$data['messages'] = form_error('tanggal_hppenjualan');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatHPPenjualan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['persediaan_dalam_proses'] = $this->Laporan_Model->GetPersediaanAwalDalamProsesPenjualan();
		//$bmr['bahan_baku_awal']         = $this->Laporan_Model->GetBahanBakuAwal();
		$bmr['pembelian']   = $this->Laporan_Model->GetPembelianPenjualan();
		$bmr['bahan_baku_akhir'] = $this->Laporan_Model->GetBahanBakuAkhirPenjualan();
		$bmr['btkl']        = $this->Laporan_Model->GetBiayaTenagaKerjaLangsungPenjualan();
		$bmr['bop']         = $this->Laporan_Model->GetBiayaOverheadPabrikPenjualan();
		$bmr['persediaan_akhir_dalam_proses'] = $this->Laporan_Model->GetPersediaanAkhirDalamProsesPenjualan();
		$bmr['persediaan_barang_jadi_awal']   = $this->Laporan_Model->GetPersediaanBarangJadiAwalPenjualan();
		$bmr['persediaan_barang_jadi_akhir']  = $this->Laporan_Model->GetPersediaanBarangJadiAkhirPenjualan();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'hppenjualan';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Harga Pokok Penjualan';
		$bmr['content']     = 'Laporan/HargaPokokPenjualan_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LabaRugi()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('akun');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'labarugi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Laba Rugi';
		$bmr['content']     = 'Laporan/PilihLabaRugi_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function PilihLabaRugi()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('akun');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_laba_rugi',
						  'label'=>'Periode Laba Rugi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_laba_rugi  = date('Y-m-d',strtotime($this->input->post('tanggal_laba_rugi', TRUE)));
			$laporan['tanggal_laba_rugi'] = $periode_laba_rugi;
			$this->session->set_userdata($laporan); 	
		}
		else{
			$data['messages'] = form_error('tanggal_laba_rugi');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatLabaRugi()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['penjualan']   = $this->Laporan_Model->GetPenjualan();
		$bmr['persediaan_dalam_proses'] = $this->Laporan_Model->GetPersediaanAwalDalamProsesLabaRugi();
		//$bmr['bahan_baku_awal']        = $this->Laporan_Model->GetBahanBakuAwal();
		$bmr['pembelian']   = $this->Laporan_Model->GetPembelianLabaRugi();
		$bmr['bahan_baku_akhir'] = $this->Laporan_Model->GetBahanBakuAkhirLabaRugi();
		$bmr['btkl']        = $this->Laporan_Model->GetBiayaTenagaKerjaLangsungLabaRugi();
		$bmr['bop']         = $this->Laporan_Model->GetBiayaOverheadPabrikLabaRugi();
		$bmr['persediaan_akhir_dalam_proses'] = $this->Laporan_Model->GetPersediaanAkhirDalamProsesLabaRugi();
		$bmr['barang_jadi_awal'] = $this->Laporan_Model->GetPersediaanBarangJadiAwalLabaRugi();
		$bmr['barang_jadi_akhir'] = $this->Laporan_Model->GetPersediaanBarangJadiAkhirLabaRugi();
		$bmr['beban']       = $this->Laporan_Model->GetBebanLabaRugi();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'labarugi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Laba Rugi';
		$bmr['content']     = 'Laporan/LabaRugi_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LaporanGaji()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('tanggal_laba_rugi');
		$this->session->unset_userdata('akun');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'laporangaji';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Laporan Gaji';
		$bmr['content']     = 'Laporan/PilihLaporanGaji_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function PilihLaporanGaji()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('tanggal_laba_rugi');
		$this->session->unset_userdata('akun');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_laporan_gaji',
						  'label'=>'Periode Laporan Gaji',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_laporan_gaji  = date('Y-m-d',strtotime($this->input->post('tanggal_laporan_gaji', TRUE)));
			$laporan['tanggal_laporan_gaji'] = $periode_laporan_gaji;
			$this->session->set_userdata($laporan); 	
		}
		else{
			$data['messages'] = form_error('tanggal_laporan_gaji');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatLaporanGaji()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['penggajian']  = $this->Laporan_Model->GetLaporanGaji();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'laporangaji';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Laporan Gaji';
		$bmr['content']     = 'Laporan/LaporanGaji_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LaporanUpah()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('tanggal_laba_rugi');
		$this->session->unset_userdata('tanggal_laporan_gaji');
		$this->session->unset_userdata('akun');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'laporanupah';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Laporan Upah';
		$bmr['content']     = 'Laporan/PilihLaporanUpah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);	
	}

	public function PilihLaporanUpah()
	{
		$this->session->unset_userdata('tanggal_jurnal');
		$this->session->unset_userdata('tanggal_buku_besar');
		$this->session->unset_userdata('tanggal_hpproduksi');
		$this->session->unset_userdata('tanggal_hppenjualan');
		$this->session->unset_userdata('tanggal_laba_rugi');
		$this->session->unset_userdata('tanggal_laporan_gaji');
		$this->session->unset_userdata('akun');
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_laporan_upah',
						  'label'=>'Periode Laporan Upah',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$periode_laporan_upah  = date('Y-m-d',strtotime($this->input->post('tanggal_laporan_upah', TRUE)));
			$laporan['tanggal_laporan_upah'] = $periode_laporan_upah;
			$this->session->set_userdata($laporan); 	
		}
		else{
			$data['messages'] = form_error('tanggal_laporan_upah');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatLaporanUpah()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['pengupahan']  = $this->Laporan_Model->GetLaporanUpah();
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'laporanupah';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Laporan Keuangan';
		$bmr['sub_navigasi']= 'Lihat Laporan Upah';
		$bmr['content']     = 'Laporan/LaporanUpah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function Cetak_Jurnal()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_jurnal')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_jurnal')));
		$view     = 'Print/PrintJurnal_View';
		$filename = "Jurnal Umum Periode ".$bulan[$no_bulan]." Tahun ".$tahun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data = array("jurnal"=>$this->Laporan_Model->GetJurnal());
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_BukuBesar()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_buku_besar')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_buku_besar')));
		$no_akun  = $this->session->userdata('akun');
		$view     = 'Print/PrintBukuBesar_View';
		$filename = "Buku Besar Periode ".$bulan[$no_bulan]." Tahun ".$tahun." Akun ".$no_akun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("header"=>$this->Laporan_Model->GetHeaderAkun(),
						  "saldo"=>$this->Laporan_Model->GetSaldoAwal(),
						  "buku_besar"=>$this->Laporan_Model->GetBukuBesar()
						);
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_HargaPokokProduksi()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_hpproduksi')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_hpproduksi')));
		$view     = 'Print/PrintHargaPokokProduksi_View';
		$filename = "Harga Pokok Produksi Periode ".$bulan[$no_bulan]." Tahun ".$tahun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("persediaan_dalam_proses"=>$this->Laporan_Model->GetPersediaanAwalDalamProses(),
						  "pembelian"=>$this->Laporan_Model->GetPembelian(),
						  "bahan_baku_akhir"=>$this->Laporan_Model->GetBahanBakuAkhir(),
						  "btkl"=>$this->Laporan_Model->GetBiayaTenagaKerjaLangsung(),
						  "bop"=>$this->Laporan_Model->GetBiayaOverheadPabrik(),
						  "persediaan_akhir_dalam_proses"=>$this->Laporan_Model->GetPersediaanAkhirDalamProses()
						);
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_HargaPokokPenjualan()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_hppenjualan')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_hppenjualan')));
		$view     = 'Print/PrintHargaPokokPenjualan_View';
		$filename = "Harga Pokok Penjualan Periode ".$bulan[$no_bulan]." Tahun ".$tahun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("persediaan_dalam_proses"=>$this->Laporan_Model->GetPersediaanAwalDalamProsesPenjualan(),
						  "pembelian"=>$this->Laporan_Model->GetPembelianPenjualan(),
						  "bahan_baku_akhir"=>$this->Laporan_Model->GetBahanBakuAkhirPenjualan(),
						  "btkl"=>$this->Laporan_Model->GetBiayaTenagaKerjaLangsungPenjualan(),
						  "bop"=>$this->Laporan_Model->GetBiayaOverheadPabrikPenjualan(),
						  "persediaan_akhir_dalam_proses"=>$this->Laporan_Model->GetPersediaanAkhirDalamProsesPenjualan(),
						  "persediaan_barang_jadi_awal"=>$this->Laporan_Model->GetPersediaanBarangJadiAwalPenjualan(),
						  "persediaan_barang_jadi_akhir"=>$this->Laporan_Model->GetPersediaanBarangJadiAkhirPenjualan()
						);
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_LabaRugi()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laba_rugi')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laba_rugi')));
		$view     = 'Print/PrintLabaRugi_View';
		$filename = "Laba Rugi Periode ".$bulan[$no_bulan]." Tahun ".$tahun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("persediaan_dalam_proses"=>$this->Laporan_Model->GetPersediaanAwalDalamProsesLabaRugi(),
						  "pembelian"=>$this->Laporan_Model->GetPembelianLabaRugi(),
						  "bahan_baku_akhir"=>$this->Laporan_Model->GetBahanBakuAkhirLabaRugi(),
						  "btkl"=>$this->Laporan_Model->GetBiayaTenagaKerjaLangsungLabaRugi(),
						  "bop"=>$this->Laporan_Model->GetBiayaOverheadPabrikLabaRugi(),
						  "persediaan_akhir_dalam_proses"=>$this->Laporan_Model->GetPersediaanAkhirDalamProsesLabaRugi(),
						  "barang_jadi_awal"=>$this->Laporan_Model->GetPersediaanBarangJadiAwalLabaRugi(),
						  "barang_jadi_akhir"=>$this->Laporan_Model->GetPersediaanBarangJadiAkhirLabaRugi(),
						  "beban"=>$this->Laporan_Model->GetBebanLabaRugi(),
						  "penjualan"=>$this->Laporan_Model->GetPenjualan()
						);
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_Penggajian()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laporan_gaji')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laporan_gaji')));
		$view     = 'Print/PrintPenggajian_View';
		$filename = "Laporan Gaji Periode ".$bulan[$no_bulan]." Tahun ".$tahun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("penggajian"=>$this->Laporan_Model->GetLaporanGaji());
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_Pengupahan()
	{
		$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laporan_upah')));
		$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laporan_upah')));
		$view     = 'Print/PrintPengupahan_View';
		$filename = "Laporan Upah Periode ".$bulan[$no_bulan]." Tahun ".$tahun."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("pengupahan"=>$this->Laporan_Model->GetLaporanUpah());
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_SlipGaji()
	{
		$id_gaji 			= $this->uri->segment(3);
		$id_pegawai         = $this->uri->segment(4);
		$view     = 'Print/PrintSlipGaji_View';
		$filename = "Slip Gaji ".$id_gaji." Pegawai ".$id_pegawai."";
		$orientation = 'portrait';
		$paper    = 'A4';
		$data     = array("id_gaji"=>$id_gaji,
						  "id_pegawai"=>$id_pegawai,
						  "jumlah"=>$this->GajiUpah_Model->GetJumlahPresensi(),
						  "data"=>$this->GajiUpah_Model->DetailGajiPegawai($id_gaji, $id_pegawai)
						);
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
	}

	public function Cetak_SlipUpah()
	{
		$id_upah 			= $this->uri->segment(3);
		$id_pegawai         = $this->uri->segment(4);
		$view     = 'Print/PrintSlipUpah_View';
		$filename = "Slip Upah ".$id_upah." Pegawai ".$id_pegawai."";
		$orientation = 'portrait';
		$paper    = 'A4';
		$data     = array("id_upah"=>$id_upah,
						  "id_pegawai"=>$id_pegawai,
						  "jumlah"=>$this->GajiUpah_Model->GetJumlahProduk(),
						  "data"=>$this->GajiUpah_Model->DetailUpahPegawai($id_upah, $id_pegawai)
						);
		$this->mypdf->GeneratePDF($view, $data, $filename, $paper, $orientation);
		//$this->load->view($view, $data);
	}
}
?>