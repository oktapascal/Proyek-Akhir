<?php
class Penjualan_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="STP")
			{
				redirect(site_url('Login_Controller'));
			}
		}
	}

	public function index()
	{
		if(!empty($this->session->userdata('pesanan_selesai')))
		{
			$this->session->unset_userdata('pesanan_selesai');
		}
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penjualan';
		$bmr['id']		    = 'tambahpenjualan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penjualan';
		$bmr['sub_navigasi']= 'Tambah Penjualan Produk';
		$bmr['content']     = 'Penjualan/PenjualanPilih_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SubmitPesanan()
	{
		if(isset($_POST['no_pesanan']))
		{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'no_pesanan',
						  'label'=>'Nomor Pesanan',
						  'rules'=>'trim|required|callback_cek_pesanan|callback_cek_nomor',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong','cek_pesanan'=>'%s ' .$_POST['no_pesanan'].' Tidak Ditemukan', 'cek_nomor'=>'%s '.$_POST['no_pesanan'].' Belum Selesai Produksi')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
		  $data['success'] = true;
		  $no_pesanan      = $this->input->post('no_pesanan', TRUE);
		  $this->session->set_userdata('pesanan_selesai', $no_pesanan);

		}else{
			$data['messages'] = form_error('no_pesanan');	
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
		}
	}

	public function LoadPesananSelesai()
	{
		$produk = $this->Penjualan_Model->getDataTablePesananSelesai();
		$data   = array();
		foreach($produk as $dataProdukSelesai)
		{
			$row = array();
			$row[] = $dataProdukSelesai->id_produk;
			$row[] = $dataProdukSelesai->nama_produk;
			$row[] = $dataProdukSelesai->jumlah;
			$row[] = '<a role="button" class="btn btn-success waves-effect" onclick=jualproduk("'.$dataProdukSelesai->id_produk.'") title="Pesan Produk">Jual!</a>';	

			$data[] = $row; 
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Penjualan_Model->HitungSemuaPesananSelesai(),
			"recordsFiltered"=>$this->Penjualan_Model->HitungFilterPesananSelesai(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function LihatPesananSelesai()
	{
		if(!empty($this->session->userdata('pesanan_selesai'))){
		$bmr['penjualan']   = $this->Penjualan_Model->GetPenjualanPesanan();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penjualan';
		$bmr['id']		    = 'tambahpenjualan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penjualan';
		$bmr['sub_navigasi']= 'Tambah Penjualan Produk';
		$bmr['content']     = 'Penjualan/PenjualanPesanan_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}
		else{
			$this->index();
		}
	}

	public function TambahPenjualan()
	{
		$data    = array('success'=>false);
		$produk  = $this->uri->segment(3);
		$pesanan = $this->session->userdata('pesanan_selesai');
		$penjualan = $this->Penjualan_Model->TambahPenjualan($produk, $pesanan);
		if($penjualan == TRUE)
		{
			$data['success'] = true;
		}
		else{
			$data['success'] = false;
		}
		echo json_encode($data);
	}

	public function HapusProdukPesanan()
	{
		$kode = $this->uri->segment(3);
		$this->Penjualan_Model->HapusProdukPesanan($kode);
		redirect('Penjualan_Controller/LihatPesananSelesai');
	}

	public function SimpanPenjualan()
	{
		$cek    = $this->Penjualan_Model->SimpanPenjualan();
		if($cek == TRUE)
		{
			$this->session->unset_userdata('pesanan_selesai');
			$this->session->set_flashdata('notifikasi_penjualan', 'Penjualan Berhasil Disimpan');
			redirect('Penjualan_Controller');
		}
	}

	public function LihatPenjualan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penjualan';
		$bmr['id']		    = 'lihatpenjualan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penjualan';
		$bmr['sub_navigasi']= 'Lihat Penjualan Produk';
		$bmr['content']     = 'Penjualan/PenjualanLihat_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadPenjualan()
	{
		$penjualan = $this->Penjualan_Model->getDataTablePenjualan();
		$data = array();
		foreach($penjualan as $dataPenjualan)
		{
			$row = array();
			$row[] = $dataPenjualan->id_transaksi;
			$row[] = $dataPenjualan->tanggal_transaksi;
			$row[] = format_rp($dataPenjualan->total);
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Penjualan_Controller/DetailPenjualan').'/'.$dataPenjualan->id_transaksi.'" title="Lihat Detail Penjualan"><i class="material-icons">visibility</i></a>';	

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Penjualan_Model->HitungSemuaPenjualan(),
			"recordsFiltered"=>$this->Penjualan_Model->HitungFilterPenjualan(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailPenjualan()
	{
		$id_transaksi       = $this->uri->segment(3);
		$this->db->where('id_transaksi', $id_transaksi);
		$cek = $this->db->get('penjualan');
		if($cek->num_rows() > 0){
		$bmr['id_transaksi'] = $this->uri->segment(3);
		$bmr['detail']      = $this->Penjualan_Model->AmbilPenjualan($id_transaksi);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penjualan';
		$bmr['id']		    = 'lihatpenjualan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penjualan';
		$bmr['sub_navigasi']= 'Lihat Penjualan';
		$bmr['content']     = 'Penjualan/PenjualanDetail_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}else{
			$this->LihatPenjualan();
		}
	}

	public function cek_pesanan($no_pesanan)
	{
		$this->db->where('no_pesanan', $no_pesanan);
		$pesanan = $this->db->get('pesanan');
		if($pesanan->num_rows() > 0)
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function cek_nomor($no_pesanan)
	{
		$this->db->where('no_pesanan', $no_pesanan);
		$this->db->where('status', "Selesai Produksi");
		$pesanan = $this->db->get('detail_pesanan');
		if($pesanan->num_rows() > 0)
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>