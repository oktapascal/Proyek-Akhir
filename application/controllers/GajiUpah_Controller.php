<?php
class GajiUpah_Controller extends CI_Controller
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
		$bmr['keranjang']   = $this->Pesanan_Model->GetDetail();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'tambahgajiupah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Bayar Gaji & Upah';
		$bmr['content']     = 'GajiUpah/GajiUpahTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadGajiPegawaiTetap()
	{
		$this->db->select('SUM(penjualan.id_transaksi) as jumlah');
		$this->db->from('penjualan');
		$this->db->join('detail_penjualan', 'penjualan.id_transaksi=detail_penjualan.id_transaksi');
		$this->db->where('MONTH(penjualan.tanggal_transaksi)', date('m'));
		$jml = $this->db->get();
		$bonus = $jml->row();

		$gaji = $this->GajiUpah_Model->getDataTableGaji();
		$data = array();
		foreach($gaji as $dataGaji)
		{
			$row = array();
			$row[] = $dataGaji->id_pegawai;
			$row[] = $dataGaji->nama_pegawai;
			$row[] = format_rp($dataGaji->tunjangan_kesehatan);
			$row[] = format_rp($dataGaji->tunjangan_makan);
			$row[] = format_rp($dataGaji->total_harian);
			$row[] = format_rp($dataGaji->tarif_per_produk*$bonus->jumlah);
			$row[] = format_rp($dataGaji->tunjangan_kesehatan + $dataGaji->tunjangan_makan + $dataGaji->total_harian + ($dataGaji->tarif_per_produk*$bonus->jumlah));

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->GajiUpah_Model->HitungSemuaGaji(),
			"recordsFiltered"=>$this->GajiUpah_Model->HitungFilterGaji(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function LoadUpahPegawaiKontrak()
	{
		$upah = $this->GajiUpah_Model->getDataTableUpah();
		$data = array();
		foreach($upah as $dataUpah)
		{
			$row = array();
			$row[] = $dataUpah->id_pegawai;
			$row[] = $dataUpah->nama_pegawai;
			$row[] = format_rp($dataUpah->tunjangan_makan);
			$row[] = format_rp($dataUpah->total_produk);
			$row[] = format_rp($dataUpah->tunjangan_makan + $dataUpah->total_produk);

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->GajiUpah_Model->HitungSemuaUpah(),
			"recordsFiltered"=>$this->GajiUpah_Model->HitungFilterUpah(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function SimpanGajiUpah()
	{
		$this->db->where('MONTH(tanggal_gaji)', date('m'));
		$gaji = $this->db->get('penggajian');
		$this->db->where('MONTH(tanggal_upah)', date('m'));
		$upah = $this->db->get('pengupahan');
		if($gaji->num_rows() == 0 || $upah->num_rows() == 0)
		{
		$gaji_upah = $this->GajiUpah_Model->SimpanGajiUpah();
		if($gaji_upah == TRUE)
		{
			$this->session->set_flashdata('notifikasi_pembayaran_gaji_upah', 'Pembayaran Gaji dan Upah Berhasil');
			redirect('Beranda_Controller/index');
		}else{
			$this->session->set_flashdata('notifikasi_pembayaran_gaji_upah', 'Pembayaran Gaji dan Upah Gagal');
			redirect('Beranda_Controller/index');
		}
		}
		else{
			$this->session->set_flashdata('notifikasi_pembayaran_gaji_upah', 'Pembayaran Gaji dan Upah Bulan Ini Sudah Dibayar');
			redirect('Beranda_Controller/index');	
		}
	}

	public function LihatGaji()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['keranjang']   = $this->Pesanan_Model->GetDetail();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'lihatgaji';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Lihat Gaji Pegawai Tetap';
		$bmr['content']     = 'GajiUpah/Gaji_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadDataGaji()
	{
		$penggajian = $this->GajiUpah_Model->getDataTableLihatGaji();
		$data = array();
		foreach($penggajian as $dataPenggajian)
		{
			$row = array();
			$row[] = $dataPenggajian->id_gaji;
			$row[] = date('d-m-Y', strtotime($dataPenggajian->tanggal_gaji));
			$row[] = format_rp($dataPenggajian->total_gaji);
			$row[] = $dataPenggajian->status;
			$row[] = '<a role="button" class="btn btn-info waves-effect waves-float" href="'.site_url('/GajiUpah_Controller/DetailGaji').'/'.$dataPenggajian->id_gaji.'" title="Lihat Detail Gaji">Detail</a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->GajiUpah_Model->HitungSemuaLihatGaji(),
			"recordsFiltered"=>$this->GajiUpah_Model->HitungFilterLihatGaji(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailGaji()
	{
		$id_gaji 			= $this->uri->segment(3);
		$bmr['id_gaji']     = $id_gaji;
		$bmr['data']        = $this->GajiUpah_Model->DetailGaji($id_gaji);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'lihatgaji';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Detail Gaji Pegawai Tetap';
		$bmr['content']     = 'GajiUpah/GajiDetail_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function DetailGajiPegawai()
	{
		$id_gaji 			= $this->uri->segment(3);
		$id_pegawai         = $this->uri->segment(4);
		$bmr['id_gaji']     = $id_gaji;
		$bmr['id_pegawai']  = $id_pegawai;
		$bmr['jumlah']      = $this->GajiUpah_Model->GetJumlahPresensi();
		$bmr['data']        = $this->GajiUpah_Model->DetailGajiPegawai($id_gaji, $id_pegawai);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'lihatgaji';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Slip Gaji Pegawai';
		$bmr['content']     = 'GajiUpah/GajiDetailPegawai_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LihatUpah()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['keranjang']   = $this->Pesanan_Model->GetDetail();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'lihatupah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Lihat Upah Pegawai Kontrak';
		$bmr['content']     = 'GajiUpah/Upah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadDataUpah()
	{
		$pengupahan = $this->GajiUpah_Model->getDataTableLihatUpah();
		$data = array();
		foreach($pengupahan as $dataPengupahan)
		{
			$row = array();
			$row[] = $dataPengupahan->id_upah;
			$row[] = date('d-m-Y', strtotime($dataPengupahan->tanggal_upah));
			$row[] = format_rp($dataPengupahan->total_upah);
			$row[] = $dataPengupahan->status;
			$row[] = '<a role="button" class="btn btn-info waves-effect waves-float" href="'.site_url('/GajiUpah_Controller/DetailUpah').'/'.$dataPengupahan->id_upah.'" title="Lihat Detail Upah">Detail</a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->GajiUpah_Model->HitungSemuaLihatUpah(),
			"recordsFiltered"=>$this->GajiUpah_Model->HitungFilterLihatUpah(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailUpah()
	{
		$id_upah 			= $this->uri->segment(3);
		$bmr['id_upah']     = $id_upah;
		$bmr['data']        = $this->GajiUpah_Model->DetailUpah($id_upah);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'lihatupah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Detail Gaji Pegawai Tetap';
		$bmr['content']     = 'GajiUpah/UpahDetail_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function DetailUpahPegawai()
	{
		$id_upah 			= $this->uri->segment(3);
		$id_pegawai         = $this->uri->segment(4);
		$bmr['id_upah']     = $id_upah;
		$bmr['id_pegawai']  = $id_pegawai;
		$bmr['jumlah']      = $this->GajiUpah_Model->GetJumlahProduk();
		$bmr['data']        = $this->GajiUpah_Model->DetailUpahPegawai($id_upah, $id_pegawai);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'gaji&upah';
		$bmr['id']		    = 'lihatupah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penggajian & Pengupahan';
		$bmr['sub_navigasi']= 'Slip Gaji Pegawai';
		$bmr['content']     = 'GajiUpah/UpahDetailPegawai_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}
}
?>