<?php
class PembayaranBeban_Controller extends CI_Controller
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
		$bmr['beban']       = $this->PembayaranBeban_Model->GetBeban();
		$bmr['detail']      = $this->PembayaranBeban_Model->GetDetail();
		$bmr['class']       = 'pembayaranbeban';
		$bmr['id']		    = 'tambahpembayaranbeban';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembayaran Beban';
		$bmr['sub_navigasi']= 'Tambah Pembayaran Beban';
		$bmr['content']     = 'PembayaranBeban/PembayaranBebanTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function GetTipeBeban()
	{
		$beban = $this->input->get('beban', TRUE);
		$cek   = $this->PembayaranBeban_Model->GetTipeBeban($beban);
		$cek_beban = strrpos($cek['nama_beban'], '(Pabrik)');
		if($cek_beban)
		{
			$check['check'] = true;
		}
		else{
			$check['check'] = false;
		}
		echo json_encode($check);
	}

	public function PilihBeban()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'beban',
						  'label'=>'Beban Perusahaan',
						  'rules'=>'trim|required|callback_cek_beban',
						  'errors'=>array('required'=>'%s Harus Dipilih', 'cek_beban'=>'%s Sudah Dimasukkan')
						),
					array('field'=>'nominal',
						  'label'=>'Biaya Beban',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'jumlah',
						  'label'=>'Jumlah Produksi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$beban           = $this->input->post('beban', TRUE);
			$nominal         = remove_string($this->input->post('nominal', TRUE));
			$jumlah          = $this->input->post('jumlah', TRUE);
			$this->PembayaranBeban_Model->TambahBeban($beban, $nominal, $jumlah);  	
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function HapusBeban()
	{
		$id_beban = $this->uri->segment(3);
		$hapus    = $this->PembayaranBeban_Model->HapusBeban($id_beban);
		if($hapus)
		{
			$this->session->set_flashdata('notifikasi_hapus', 'Beban Berhasil Dihapus');
			redirect('PembayaranBeban_Controller/index');
		}
		else{
			$this->session->set_flashdata('notifikasi_hapus', 'Beban Gagal Dihapus');
			redirect('PembayaranBeban_Controller/index');
		}
	}

	public function SimpanPembayaranBeban()
	{
		$simpan = $this->PembayaranBeban_Model->SimpanPembayaranBeban();
		if($simpan)
		{
			$this->session->set_flashdata('notifikasi_simpan', 'Pembayaran Beban Berhasil Dikonfirmasi');
			redirect('PembayaranBeban_Controller/index');
		}else{
			$this->session->set_flashdata('notifikasi_simpan', 'Pembayaran Beban Gagal Dikonfirmasi');
			redirect('PembayaranBeban_Controller/index');
		}
	}

	public function LoadPembayaranBeban()
	{
		$beban = $this->PembayaranBeban_Model->getDataTablePembayaranBeban();
		$data = array();
		foreach($beban as $dataPembayaranBeban)
		{
			$row = array();
			$row[] = $dataPembayaranBeban->id_transaksi;
			$row[] = date('d-m-Y', strtotime($dataPembayaranBeban->tanggal_transaksi));
			$row[] = format_rp($dataPembayaranBeban->total);
			$row[] = '<a role="button" class="btn btn-info waves-effect waves-float" href="'.site_url('/PembayaranBeban_Controller/GetDetail').'/'.$dataPembayaranBeban->id_transaksi.'" title="Edit Akun">Detail</a>';

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->PembayaranBeban_Model->HitungSemua(),
			"recordsFiltered"=>$this->PembayaranBeban_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function LihatPembayaranBeban()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pembayaranbeban';
		$bmr['id']		    = 'lihatpembayaranbeban';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembayaran Beban';
		$bmr['sub_navigasi']= 'Lihat Pembayaran Beban';
		$bmr['content']     = 'PembayaranBeban/PembayaranBeban_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function GetDetail()
	{
		$id_transaksi       = $this->uri->segment(3);
		$bmr['id_transaksi'] = $id_transaksi;
		$bmr['data']        = $this->PembayaranBeban_Model->GetDetailPembayaranBeban($id_transaksi);
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pembayaranbeban';
		$bmr['id']		    = 'lihatpembayaranbeban';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pembayaran Beban';
		$bmr['sub_navigasi']= 'Lihat Pembayaran Beban';
		$bmr['content']     = 'PembayaranBeban/PembayaranBebanDetail_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function cek_beban($id_beban)
	{
		$this->db->where('id_transaksi');
		$this->db->where('id_beban', $id_beban);
		$cek = $this->db->get('detail_pembayaran_beban');
		if($cek->num_rows() == 0)
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>