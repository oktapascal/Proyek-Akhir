<?php
class Presensi_Controller extends CI_Controller
{
	public function index()
	{
		$cek 	 = $this->session->userdata('logged_in');
		$posisi  = $this->session->userdata('id_posisi'); 
		if(!empty($cek && $cek == "getLoginMalla" && $posisi == "PMK" || $posisi == "SKU"))
		{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'absensi';
		$bmr['id']		    = 'lihatkehadiran';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Kehadiran';
		$bmr['sub_navigasi']= 'Lihat Data Kehadiran';
		$bmr['content']     = 'Kehadiran/Kehadiran_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}
		else{
			redirect('Login_Controller');
		}
	}

	public function LoadKehadiran()
	{
		$kehadiran = $this->Presensi_Model->getDataTablePresensi();
		$data = array();
		foreach($kehadiran as $dataKehadiran)
		{
			$row = array();
			$row[] = $dataKehadiran->nama_pegawai;
			$row[] = $dataKehadiran->status;
			
			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Presensi_Model->HitungSemua(),
			"recordsFiltered"=>$this->Presensi_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function LihatPresensi()
	{
		$cek 	 = $this->session->userdata('logged_in');
		$posisi  = $this->session->userdata('id_posisi'); 
		if(!empty($cek && $cek == "getLoginMalla" && $posisi == "PMK" || $posisi == "SKU"))
		{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'absensi';
		$bmr['id']		    = 'lihatkehadiran';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Kehadiran';
		$bmr['sub_navigasi']= 'Lihat Data Kehadiran';
		$bmr['content']     = 'Kehadiran/KehadiranLihat_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}
		else{
			redirect('Login_Controller');
		}
	}

	public function PilihPresensi()
	{

		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_presensi',
						  'label'=>'Tanggal Presensi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$presensi['tanggal_presensi'] = $this->input->post('tanggal_presensi', TRUE);
			$this->session->set_userdata($presensi); 	
		}
		else{
			$data['messages'] = form_error('tanggal_presensi');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	
	}

	public function PilihJumlahPresensi()
	{

		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'tanggal_jumlah_presensi',
						  'label'=>'Tanggal Presensi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Harus Dipilih')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$tanggal = date('Y-m-d', strtotime($this->input->post('tanggal_jumlah_presensi', TRUE)));
			$jumlah['tanggal_jumlah_presensi'] = $tanggal;
			$jumlah['bulan_jumlah_presensi']   = date('m', strtotime($tanggal));
			$jumlah['tahun_jumlah_presensi']   = date('Y', strtotime($tanggal));
			$this->session->set_userdata($jumlah); 	
		}
		else{
			$data['messages'] = form_error('tanggal_jumlah_presensi');
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	
	}

	public function LihatJumlahPresensi()
	{
		$cek 	 = $this->session->userdata('logged_in');
		$posisi  = $this->session->userdata('id_posisi'); 
		if(!empty($cek && $cek == "getLoginMalla" && $posisi == "PMK" || $posisi == "SKU"))
		{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'absensi';
		$bmr['id']		    = 'lihatjumlah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Kehadiran';
		$bmr['sub_navigasi']= 'Lihat Jumlah Kehadiran';
		$bmr['content']     = 'Kehadiran/KehadiranJumlah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}
		else{
			redirect('Login_Controller');
		}
	}

	public function LoadJumlahKehadiran()
	{
		$jumlah = $this->Presensi_Model->getDataTableJumlahPresensi();
		$data = array();
		foreach($jumlah as $dataJumlahKehadiran)
		{
			$row = array();
			$row[] = $dataJumlahKehadiran->id_pegawai;
			$row[] = $dataJumlahKehadiran->nama_pegawai;
			$row[] = $dataJumlahKehadiran->jumlah_kehadiran;
			
			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Presensi_Model->HitungSemuaJumlahPresensi(),
			"recordsFiltered"=>$this->Presensi_Model->HitungFilterJumlahPresensi(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function LihatPresensiJumlah()
	{
		$cek 	 = $this->session->userdata('logged_in');
		$posisi  = $this->session->userdata('id_posisi'); 
		if(!empty($cek && $cek == "getLoginMalla" && $posisi == "PMK" || $posisi == "SKU"))
		{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'absensi';
		$bmr['id']		    = 'lihatjumlah';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Kehadiran';
		$bmr['sub_navigasi']= 'Lihat Jumlah Kehadiran';
		$bmr['content']     = 'Kehadiran/KehadiranLihatJumlah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
		}
		else{
			redirect('Login_Controller');
		}
	}

	public function PresensiInput()
	{
		$this->load->view('Kehadiran/KehadiranTambah_View');
	}

	public function SubmitPresensi()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'id_pegawai',
						  'label'=>'Kode Pegawai',
						  'rules'=>'trim|required|callback_cek_kode|callback_cek_presensi',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong','cek_kode'=>'%s ' .$_POST['id_pegawai'].' Tidak Ditemukan', 'cek_presensi'=>'%s '.$_POST['id_pegawai'].' Sudah Melakukan Presensi')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
		  $data['success'] = true;
		  $id = $this->input->post('id_pegawai', TRUE);
		  $this->Presensi_Model->SubmitPresensi($id);
		}
		else{
				$data['messages'] = form_error('id_pegawai');	
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function cek_kode($id_pegawai)
	{
		$this->db->where('id_pegawai', $id_pegawai);
		$cek = $this->db->get('pegawai');
		if($cek->num_rows() == 0)
		{
			return FALSE;
		}
		else{
			return TRUE;
		}
	}

	public function cek_presensi($id_pegawai)
	{
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('tanggal', date('Y-m-d'));
		$this->db->where('status', "Hadir");
		$cek = $this->db->get('presensi');
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