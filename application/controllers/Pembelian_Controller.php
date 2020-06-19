<?php
class Pembelian_Controller extends CI_Controller
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
		$bmr['pesanan']     = $this->Pembelian_Model->GetPesanan();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pembelian';
		$bmr['id']		    = 'tambahpembelian';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembelian';
		$bmr['sub_navigasi']= 'Tambah Pembelian';
		$bmr['content']     = 'Pembelian/PembelianPesanan_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function PilihPesanan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'pesanan',
						  'label'=>'Nomor Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$pesanan['pesanan'] = $this->input->post('pesanan', TRUE);
			$this->session->set_userdata($pesanan); 	
		}
		else{
			$data['messages'] = form_error('pesanan');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function PilihPesananProduk()
	{
		$bmr['detail']      = $this->Pembelian_Model->DetailPembelian();
		$bmr['no_pesanan']  = $this->session->userdata('pesanan');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pembelian';
		$bmr['id']		    = 'tambahpembelian';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembelian';
		$bmr['sub_navigasi']= 'Tambah Pembelian';
		$bmr['content']     = 'Pembelian/PembelianPilih_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadBahanPesanan()
	{
		$bahan = $this->Pembelian_Model->getDataTableBahanPesanan();
		$data = array();
		foreach($bahan as $dataBahan)
		{
			$row = array();
			$row[] = $dataBahan->id_bahan;
			$row[] = $dataBahan->nama_bahan;
			$row[] = '<a role="button" class="btn btn-success waves-effect" onclick=pesanbahan("'.$dataBahan->id_bahan.'") rel="modal:open" data-toggle="modal" title="Pesan Produk">Beli!</a>';	

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Pembelian_Model->HitungSemua(),
			"recordsFiltered"=>$this->Pembelian_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function GetBahan()
	{
		$kode  = $this->uri->segment(3);
		$bahan = $this->Pembelian_Model->AmbilBahan($kode);
		$data['id_bahan']   = $bahan['id_bahan'];
		$data['nama_bahan'] = $bahan['nama_bahan'];
		$data['kebutuhan']  = $bahan['kebutuhan'];
		$data['total_kebutuhan'] = $bahan['total_kebutuhan'];
		echo json_encode($data);
	}

	public function BeliBahan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'id_bahan',
						  'label'=>'Kode Bahan',
						  'rules'=>'trim|required|callback_cek_bahan',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'cek_bahan'=>'%s Sudah Dimasukkan')
						),
					array('field'=>'kebutuhan',
						  'label'=>'Kebutuhan Bahan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'harga',
						  'label'=>'Harga Beli Bahan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success']  = true;
			$kode      = $this->input->post('id_bahan', TRUE);
			$kebutuhan = $this->input->post('kebutuhan', TRUE);
			$harga     = remove_string($this->input->post('harga', TRUE));
			$this->Pembelian_Model->BeliBahan($kode, $kebutuhan, $harga);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function HapusBahan()
	{
		$kode = $this->uri->segment(3);
		$this->Pembelian_Model->HapusBahan($kode);
		redirect('Pembelian_Controller/PilihPesananProduk');
	}

	public function SimpanPembelian()
	{
		$cek    = $this->Pembelian_Model->SimpanPembelian();
		if($cek)
		{
			$this->session->set_flashdata('notifikasi', 'Pembelian Berhasil Disimpan');
			redirect('Pembelian_Controller');
		}
	}

	public function cek_bahan($id)
	{
		$this->db->where('id_bahan', $id);
		$this->db->where('id_transaksi', NULL);
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->where('ip_address', $this->input->ip_address());
		$cek = $this->db->get('detail_pembelian');
		if($cek->num_rows() > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function LihatPembelian()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pembelian';
		$bmr['id']		    = 'lihatpembelian';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembelian';
		$bmr['sub_navigasi']= 'Lihat Pembelian';
		$bmr['content']     = 'Pembelian/PembelianLihat_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadPembelian()
	{
		$pembelian = $this->Pembelian_Model->getDataTablePembelian();
		$data = array();
		foreach($pembelian as $dataPembelian)
		{
			$row = array();
			$row[] = $dataPembelian->id_transaksi;
			$row[] = date('d-m-Y', strtotime($dataPembelian->tanggal_transaksi));
			$row[] = format_rp($dataPembelian->total);
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Pembelian_Controller/DetailPembelian').'/'.$dataPembelian->id_transaksi.'" title="Lihat Detail Pembelian"><i class="material-icons">visibility</i></a>';	

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Pembelian_Model->HitungSemuaPembelian(),
			"recordsFiltered"=>$this->Pembelian_Model->HitungFilterPembelian(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailPembelian()
	{
		$id_transaksi       = $this->uri->segment(3);
		$this->db->where('id_transaksi', $id_transaksi);
		$cek = $this->db->get('pembelian');
		if($cek->num_rows() > 0){
		$bmr['id_transaksi'] = $this->uri->segment(3);
		$bmr['detail']      = $this->Pembelian_Model->AmbilPembelian($id_transaksi);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pembelian';
		$bmr['id']		    = 'lihatpembelian';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembelian';
		$bmr['sub_navigasi']= 'Lihat Pembelian';
		$bmr['content']     = 'Pembelian/PembelianDetail_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}else{
			$this->LihatPembelian();
		}
	}
}
?>