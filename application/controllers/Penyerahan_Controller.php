<?php
class Penyerahan_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="KPG")
			{
				redirect(site_url('Login_Controller'));
			}
		}
	}

	public function index()
	{
		$bmr['pesanan']     = $this->Penyerahan_Model->GetPesanan();
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penyerahan';
		$bmr['id']		    = 'tambahpenyerahan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penyerahan Bahan';
		$bmr['sub_navigasi']= 'Tambah Penyerahan Bahan';
		$bmr['content']     = 'Penyerahan/PenyerahanBahan_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function GetProduk()
	{
		$pesanan = $this->input->get('no_pesanan', TRUE);
		$produk  = $this->Penyerahan_Model->GetProduk($pesanan);
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

	public function PilihPesanan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'pesanan',
						  'label'=>'Nomor Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						),
					array('field'=>'produk',
						  'label'=>'Produk Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$pesanan['no_pesanan'] = $this->input->post('pesanan', TRUE);
			$pesanan['produk']  = $this->input->post('produk', TRUE);
			$this->session->set_userdata($pesanan); 	
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function PilihPenyerahanBahan()
	{
		if($this->session->userdata('no_pesanan') === NULL || $this->session->userdata('produk') === NULL)
		{
			$this->index();
		}else{
			$bmr['bahan']       = $this->Penyerahan_Model->GetBahanPesanan();
			$bmr['stok']        = $this->Penyerahan_Model->CekStok();
			$bmr['tgl_hari']    = hari_ini(date('w'));
			$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
			$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
			$bmr['class']       = 'penyerahan';
			$bmr['id']		    = 'tambahpenyerahan';
			$bmr['sub_id']      = '';
			$bmr['navigasi']    = 'Penyerahan Bahan';
			$bmr['sub_navigasi']= 'Tambah Penyerahan Bahan';
			$bmr['content']     = 'Penyerahan/PenyerahanPilih_View';
			$bmr['menu']        = 'Menu/Menu_View';
			$bmr['navbar']      = 'Navbar/Navbar_View';
			$this->load->view('Template',$bmr);
		}
	}

	public function KonfirmasiPenyerahan()
	{
		$konfirmasi = $this->Penyerahan_Model->KonfirmasiPenyerahan();
		if($konfirmasi)
		{
		   $this->session->set_flashdata('notifikasi_penyerahan', 'Penyerahan Bahan Berhasil Dimasukkan');
		   $this->session->unset_userdata($pesanan);
		   redirect('Penyerahan_Controller');
		}
		else{
		   $this->session->set_flashdata('notifikasi_penyerahan', 'Terjadi Kesalahan');
		   $this->session->unset_userdata($pesanan);
		   redirect('Penyerahan_Controller');	
		}
	}

	public function LihatPenyerahan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penyerahan';
		$bmr['id']		    = 'lihatpenyerahan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penyerahan Bahan';
		$bmr['sub_navigasi']= 'Lihat Penyerahan Bahan';
		$bmr['content']     = 'Penyerahan/PenyerahanLihat_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadPenyerahan()
	{
		$penyerahan = $this->Penyerahan_Model->getDataTablePenyerahan();
		$data       = array();
		foreach($penyerahan as $dataPenyerahan)
		{
			$row = array();
			$row[] = $dataPenyerahan->id_transaksi;
			$row[] = date('d-m-Y', strtotime($dataPenyerahan->tanggal_transaksi));
			$row[] = format_rp($dataPenyerahan->total);
			$row[] = '<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/Penyerahan_Controller/DetailPenyerahan').'/'.$dataPenyerahan->id_transaksi.'" title="Lihat Detail Penyerahan"><i class="material-icons">visibility</i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Penyerahan_Model->HitungSemua(),
			"recordsFiltered"=>$this->Penyerahan_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailPenyerahan()
	{
		$id_transaksi       = $this->uri->segment(3);
		$this->db->where('id_transaksi', $id_transaksi);
		$cek = $this->db->get('penyerahan_bahan');
		if($cek->num_rows() > 0){
		$bmr['id_transaksi'] = $this->uri->segment(3);
		$bmr['no_pesanan']   = $this->Penyerahan_Model->GetNomorPesanan($id_transaksi);
		$bmr['detail']      = $this->Penyerahan_Model->DetailPenyerahan($id_transaksi);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'penyerahan';
		$bmr['id']		    = 'lihatpenyerahan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Penyerahan Bahan';
		$bmr['sub_navigasi']= 'Lihat Penyerahan Bahan';
		$bmr['content']     = 'Penyerahan/PenyerahanDetail_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}else{
			$this->LihatPenjualan();
		}
	}
}
?>