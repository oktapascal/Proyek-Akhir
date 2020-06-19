<?php
class Produksi_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			if($this->session->userdata('id_posisi')!="KPP")
			{
				redirect('Beranda_Controller/logout');
			}
		}
	}

	public function GetBtkl()
	{
		$id      = $this->uri->segment(3);
		$posisi  = $this->Produksi_Model->GetPosisi($id);
		$id_posisi = $posisi['id_posisi'];
		$pesanan = $this->Produksi_Model->GetDetailPesanan();
		$sisa    = $this->Produksi_Model->GetSisaProduksi($id_posisi);
		$data['id_pegawai']     = $id;
		$data['jumlah_pesanan'] = $pesanan['jumlah'] - $sisa['jumlah'];
		echo json_encode($data);

	}

	public function SimpanBTKL()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'pegawai',
						  'label'=>'Pegawai',
						  'rules'=>'trim|required|callback_cek_pegawai',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'cek_bahan'=>'%s Sudah Dimasukkan')
						),
					array('field'=>'jumlah_pesanan',
						  'label'=>'Jumlah Pesanan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'jumlah_kerja',
						  'label'=>'Jumlah yang Dikerjakan',
						  'rules'=>'trim|required|less_than_equal_to['.$_POST['jumlah_pesanan'].']',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong','less_than_equal_to'=>'%s Tidak Melebihi Jumlah Pesanan')
						)
			);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success']  = true;
			$no_pesanan = $this->session->userdata('no_pesanan_konfirmasi');
			$id_produk  = $this->session->userdata('produk_konfirmasi');
			$jumlah     = $this->input->post('jumlah_kerja', TRUE);
			$id_pegawai = $this->input->post('pegawai', TRUE);
			$this->Produksi_Model->SimpanBTKL($no_pesanan, $id_produk, $jumlah, $id_pegawai);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function HapusBTKL()
	{
		$id_pegawai = $this->uri->segment(3);
		$no_pesanan = $this->session->userdata('no_pesanan_konfirmasi');
		$id_produk  = $this->session->userdata('produk_konfirmasi');
		$this->Produksi_Model->HapusBTKL($id_pegawai, $no_pesanan, $id_produk);
		redirect('Pesanan_Controller/KonfirmasiPesanan');
	}

	public function KonfirmasiBTKL()
	{
		$konfirmasi = $this->Produksi_Model->KonfirmasiBTKL();
		if($konfirmasi)
		{
			redirect('Produksi_Controller/GetBiayaProduksi');
		}else{
			$this->session->set_flashdata('notifikasi_produksi', 'Terjadi Kesalahan!');
		}
	}

	public function GetBiayaProduksi()
	{
		$no_pesanan  = $this->session->userdata('no_pesanan_konfirmasi');
		$produk   = $this->session->userdata('produk_konfirmasi');
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['bbb']         = $this->Produksi_Model->GetBiayaBahanBaku();
		$bmr['btkl']        = $this->Produksi_Model->GetBiayaTenagaKerjaLangsung();
		$bmr['bpp']         = $this->Produksi_Model->GetBahanPenolong();
		$bmr['bbn']         = $this->Produksi_Model->GetBOPLain();
		$bmr['jumlah']      = $this->Produksi_Model->GetJumlah($no_pesanan, $produk);
		$bmr['class']       = 'konfirmasi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Konfirmasi Pesanan';
		$bmr['sub_navigasi']= 'Konfirmasi Pesanan Selesai';
		$bmr['content']     = 'Produksi/ProduksiBiaya_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function KonfirmasiProduksi()
	{
		$konfirmasi  = array('no_pesanan_konfirmasi', 'produk_konfirmasi');
		$no_pesanan  = $this->session->userdata('no_pesanan_konfirmasi');
		$produk   = $this->session->userdata('produk_konfirmasi');
		$bbb      = $this->input->post('bbb', TRUE);
		$btkl     = $this->input->post('btkl', TRUE);
		$bop      = $this->input->post('bop', TRUE);
		$total    = $this->input->post('total', TRUE);
		$produksi = $this->Produksi_Model->KonfirmasiProduksi($no_pesanan, $produk, $bbb, $btkl, $bop, $total);
		if($produksi)
		{
			$data['success']  = true;
			$this->session->unset_userdata($konfirmasi);
		}
		else{
			$data['success']  = false;
			$this->session->unset_userdata($konfirmasi);
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function cek_pegawai($id)
	{
		$this->db->where('id_pegawai', $id);
		$this->db->where('id_btkl', NULL);
		$cek = $this->db->get('detail_btkl');
		if($cek->num_rows() > 0)
		{
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
}
?>