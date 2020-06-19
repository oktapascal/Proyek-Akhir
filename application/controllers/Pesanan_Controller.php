<?php 
class Pesanan_Controller extends CI_Controller 
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
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['keranjang']   = $this->Pesanan_Model->GetDetail();
		$bmr['class']       = 'pesanan';
		$bmr['id']		    = 'tambahpesanan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pesanan';
		$bmr['sub_navigasi']= 'Tambah Pesanan';
		$bmr['content']     = 'Pesanan/PesananTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadProduk()
	{
		$produk = $this->Pesanan_Model->getDataTableProduk();
		$data = array();
		foreach($produk as $dataProduk)
		{
			$row = array();
			$row[] = $dataProduk->id_produk;
			$row[] = $dataProduk->nama_produk;
			$row[] = $dataProduk->ukuran;
			$row[] = format_rp($dataProduk->harga_jual);
			if($dataProduk->status_bom === NULL || $dataProduk->status_beban === NULL || $dataProduk->status_bom == "Belum Dikonfirmasi" || $dataProduk->status_beban == "Belum Dikonfirmasi")
			{
			$row[] = '<a role="button" class="btn btn-primary waves-effect" rel="modal:open" data-toggle="modal" title="Pesan Produk" disabled>Pesan!</a>';
			}
			else{
			$row[] = '<a role="button" class="btn btn-primary waves-effect" onclick=pesanproduk("'.$dataProduk->id_produk.'") rel="modal:open" data-toggle="modal" title="Pesan Produk">Pesan!</a>';	
			}

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Pesanan_Model->HitungSemua(),
			"recordsFiltered"=>$this->Pesanan_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function GetProduk()
	{
		$kode    = $this->uri->segment(3);
		$produk  = $this->Pesanan_Model->AmbilProduk($kode);
		$data['id_produk']   = $produk['id_produk'];
		$data['nama_produk'] = $produk['nama_produk'];
		$data['ukuran']      = $produk['ukuran'];
		$data['harga_jual']  = format_rp($produk['harga_jual']);
		
		echo json_encode($data);
	}

	public function PesanProduk()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'kode_produk',
						  'label'=>'Kode Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'nama_produk',
						  'label'=>'Nama Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'ukuran',
						  'label'=>'Ukuran',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'harga_jual',
						  'label'=>'Harga Jual',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'jumlah',
						  'label'=>'Jumlah Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success']  = true;
			$id_produk = $this->input->post('kode_produk', TRUE);
			$jumlah    = $this->input->post('jumlah', TRUE);
			$this->Pesanan_Model->TambahPesanan($id_produk, $jumlah);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function HapusPesanan()
	{
		$produk = $this->uri->segment(3);
		$this->Pesanan_Model->HapusPesanan($produk);
		redirect('Pesanan_Controller/index');
	}

	public function LanjutkanPesanan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['pesanan']     = $this->Pesanan_Model->GetDetailPesanan();
		$bmr['class']       = 'pesanan';
		$bmr['id']		    = 'tambahpesanan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pesanan';
		$bmr['sub_navigasi']= 'Lanjutkan Pesanan';
		$bmr['content']     = 'Pesanan/PesananLanjut_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanPesanan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nama_pemesan',
						  'label'=>'Nama Pemesan',
						  'rules'=>'trim|required|min_length[5]|max_length[50]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 50 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'no_telp',
						  'label'=>'Nomor Telepon',
						  'rules'=>'trim|required|min_length[12]|max_length[16]|callback_numeric_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 12 Karakter', 'max_length'=>'%s Maksimal 16 Karakter', 'numeric_only'=>'%s Tidak Valid')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$nama    = $this->input->post('nama_pemesan', TRUE);
			$no_telp = $this->input->post('no_telp', TRUE);
			$tanggal_selesai = "0000-00-00";
			$this->Pesanan_Model->SimpanPesanan($nama, $no_telp, $tanggal_selesai);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatPesanan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['pesanan']     = $this->Pesanan_Model->GetDetailPesanan();
		$bmr['class']       = 'pesanan';
		$bmr['id']		    = 'lihatpesanan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pesanan';
		$bmr['sub_navigasi']= 'Lihat Pesanan';
		$bmr['content']     = 'Pesanan/PesananLihat_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadPesanan()
	{
		$pesanan = $this->Pesanan_Model->getDataTablePesanan();
		$data = array();
		foreach($pesanan as $dataPesanan)
		{
			$row = array();
			$row[] = $dataPesanan->no_pesanan;
			$row[] = $dataPesanan->nama_pemesan;
			$row[] = $dataPesanan->no_hp;
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Pesanan_Controller/DetailPesanan').'/'.$dataPesanan->no_pesanan.'" title="Lihat Detail Pesanan"><i class="material-icons">visibility</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Pesanan_Model->HitungSemuaPesanan(),
			"recordsFiltered"=>$this->Pesanan_Model->HitungFilterPesanan(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailPesanan()
	{
		$nomor = $this->uri->segment(3);
		$this->db->where('no_pesanan', $nomor);
		$cek = $this->db->get('pesanan');
		if($cek->num_rows() > 0)
		{
			$bmr['nomor']    	= $this->uri->segment(3);
			$bmr['detail']  	= $this->Pesanan_Model->GetNomor($nomor);
			$bmr['barcode']  	= $this->Pesanan_Model->GetBarcode($nomor);
			$bmr['tgl_hari']    = hari_ini(date('w'));
			$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
			$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
			$bmr['pesanan']     = $this->Pesanan_Model->GetDetailPesanan();
			$bmr['class']       = 'pesanan';
			$bmr['id']		    = 'lihatpesanan';
			$bmr['sub_id']      = '';
			$bmr['navigasi']    = 'Pesanan';
			$bmr['sub_navigasi']= 'Detail Pesanan';
			$bmr['content']     = 'Pesanan/PesananDetail_View';
			$bmr['menu']        = 'Menu/Menu_View';
			$bmr['navbar']      = 'Navbar/Navbar_View';
			$this->load->view('Template',$bmr);
		}
		else
		{
			$this->LihatPesanan();
		}
	}

	public function KonfirmasiPilihPesanan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['pesanan']     = $this->Pesanan_Model->GetPesananProses();
		$bmr['class']       = 'konfirmasi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Konfirmasi Pesanan';
		$bmr['sub_navigasi']= 'Konfirmasi Pesanan Selesai';
		$bmr['content']     = 'Pesanan/KonfirmasiPilih_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function GetProdukPesanan()
	{
		$pesanan = $this->input->get('no_pesanan', TRUE);
		$produk  = $this->Pesanan_Model->GetProdukPesanan($pesanan);
		$data    = array();

		foreach($produk as $pr)
		{
			$select   = array();
			$select[] = $pr->id_produk;
			$select[] = $pr->nama_produk;
			$select[] = $pr->ukuran;
			$data[]   = $select;
		}
		echo json_encode($data);
	}

	public function KonfirmasiPesanan()
	{
		$this->db->from('btkl');
		$this->db->join('detail_btkl', 'btkl.id_btkl=detail_btkl.id_btkl');
		$this->db->where('no_pesanan', $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('id_produk', $this->session->userdata('produk_konfirmasi'));
		$this->db->where('detail_btkl.id_btkl');
		$cek = $this->db->get();
		if($cek->num_rows() == 0)
		{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['detail_btkl'] = $this->Produksi_Model->GetDetailBTKL();
		$bmr['class']       = 'konfirmasi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Konfirmasi Pesanan';
		$bmr['sub_navigasi']= 'Konfirmasi Pesanan Selesai';
		$bmr['content']     = 'Pesanan/Konfirmasi_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}
		else{
			redirect('Produksi_Controller/GetBiayaProduksi','refresh');
		}
	}

	public function PilihPesanan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nomor_pesanan',
						  'label'=>'Nomor Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						),
					array('field'=>'produk_pesanan',
						  'label'=>'Produk Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						),
					array('field'=>'nominal',
						  'label'=>'Taksiran BOP',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'jumlah',
						  'label'=>'Taksiran Jumlah Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$konfirmasi['no_pesanan_konfirmasi'] = $this->input->post('nomor_pesanan', TRUE);
			$konfirmasi['produk_konfirmasi']  = $this->input->post('produk_pesanan', TRUE);
			$konfirmasi['taksiran_bop']  = remove_string($this->input->post('nominal', TRUE));
			$konfirmasi['taksiran_jumlah']  = $this->input->post('jumlah', TRUE);
			$this->session->set_userdata($konfirmasi); 	
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LoadPegawaiKontrak()
	{
		$kontrak = $this->Pesanan_Model->getDataTablePegawaiKontrak();
		$data = array();
		foreach($kontrak as $dataPegawaiKontrak)
		{
			$row = array();
			$row[] = $dataPegawaiKontrak->id_pegawai;
			$row[] = $dataPegawaiKontrak->nama_pegawai;
			$row[] = $dataPegawaiKontrak->nama_posisi;
			$row[] = '<a role="button" class="btn btn-primary waves-effect" onclick=setbtkl("'.$dataPegawaiKontrak->id_pegawai.'") rel="modal:open" data-toggle="modal" title="Tentukan Jumlah Hasil Pekerjaan Pegawai">Hitung!</a>';	
			

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Pesanan_Model->HitungSemuaPegawaiKontrak(),
			"recordsFiltered"=>$this->Pesanan_Model->HitungFilterPegawaiKontrak(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function Cetak_Pesanan()
	{
		$pesanan  = $this->uri->segment(3);
		$view     = 'Print/PrintPesanan_View';
		$filename = "Pesanan ".$pesanan."";
		$orientation = 'landscape';
		$paper    = 'A4';
		$data     = array("nomor"=>$pesanan,
						  "detail"=>$this->Pesanan_Model->GetNomor($pesanan),
						  "barcode"=>$this->Pesanan_Model->GetBarcode($pesanan)
						);
		$this->mypdf->GeneratePDFPesanan($view, $data, $filename, $paper, $orientation);
		//$this->load->view($view, $data);
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

	public function numeric_only($string)
	{
		if ($string != '' && ! preg_match('/^[0-9()-]+$/', $string))
		{
        	return FALSE;
   	 	}else {
       		return TRUE;
    	}
	}

}

?>