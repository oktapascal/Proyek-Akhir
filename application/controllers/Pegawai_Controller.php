<?php
class Pegawai_Controller extends CI_Controller
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

	public function PindahJabatan()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pegawai';
		$bmr['id']		    = 'pindahjabatan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pegawai';
		$bmr['sub_navigasi']= 'Pindah Jabatan';
		$bmr['content']     = 'Pegawai/Pegawai/PindahJabatan_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LanjutPindahJabatan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'id_pegawai',
						  'label'=>'Kode Pegawai',
						  'rules'=>'trim|required|callback_cek_pegawai',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'cek_pegawai'=>'%s Tidak Ditemukan')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$id_pegawai = $this->input->post('id_pegawai', TRUE);
			$pegawai    = $this->Pegawai_Model->GetDataPegawai($id_pegawai);
			$pindah['pegawai_id']   = $pegawai['id_pegawai'];
			$pindah['pegawai_nama'] = $pegawai['nama_pegawai'];
			$pindah['posisi_id']    = $pegawai['id_posisi'];
			$pindah['posisi_nama']  = $pegawai['nama_posisi'];
			$pindah['status']       = $pegawai['status'];
			$this->session->set_userdata($pindah);
			
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function PindahJabatanLanjut()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['posisi']      = $this->Pegawai_Model->GetPosisi();
		$bmr['class']       = 'pegawai';
		$bmr['id']		    = 'pindahjabatan';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pegawai';
		$bmr['sub_navigasi']= 'Pindah Jabatan';
		$bmr['content']     = 'Pegawai/Pegawai/PindahJabatanLanjut_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function KonfirmasiPindahJabatan()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'posisi',
						  'label'=>'Posisi Pegawai',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong'	)
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$posisi_baru = $this->input->post('posisi', TRUE);
			$this->Pegawai_Model->KonfirmasiPindahJabatan($posisi_baru);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function PegawaiKeluar()
	{

		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pegawai';
		$bmr['id']		    = 'pegawaikeluar';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pegawai';
		$bmr['sub_navigasi']= 'Pegawai Keluar';
		$bmr['content']     = 'Pegawai/Pegawai/PegawaiKeluar_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LanjutPegawaiKeluar()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'id_pegawai',
						  'label'=>'Kode Pegawai',
						  'rules'=>'trim|required|callback_cek_pegawai',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'cek_pegawai'=>'%s Tidak Ditemukan')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$id_pegawai = $this->input->post('id_pegawai', TRUE);
			$pegawai    = $this->Pegawai_Model->GetDataPegawai($id_pegawai);
			$keluar['pegawai_kode']   = $pegawai['id_pegawai'];
			$keluar['pegawai_nm']     = $pegawai['nama_pegawai'];
			$keluar['posisi_kode']    = $pegawai['id_posisi'];
			$keluar['posisi_nm']      = $pegawai['nama_posisi'];
			$keluar['status']         = $pegawai['status'];
			$this->session->set_userdata($keluar);
			
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function PegawaiKeluarLanjut()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'pegawai';
		$bmr['id']		    = 'pegawaikeluar';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Pegawai';
		$bmr['sub_navigasi']= 'Pegawai Keluar';
		$bmr['content']     = 'Pegawai/Pegawai/PegawaiKeluarLanjut_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function KonfirmasiPegawaiKeluar()
	{
		$konfirmasi = $this->Pegawai_Model->KonfirmasiPegawaiKeluar();
		if($konfirmasi)
		{
			$keluar = array('pegawai_kode, pegawai_nm, posisi_kode, posisi_nm, status');
			$this->session->unset_userdata($keluar);
			$this->session->set_flashdata('notifikasi_pegawai_keluar', 'Pegawai Berhasil Dikeluarkan');
			redirect('Pegawai_Controller/PegawaiKeluar');
		}else{
			$keluar = array('pegawai_kode, pegawai_nm, posisi_kode, posisi_nm, status');
			$this->session->unset_userdata($keluar);
			$this->session->set_flashdata('notifikasi_pegawai_keluar', 'Pegawai Gagal Dikeluarkan Dikeluarkan');
			redirect('Pegawai_Controller/PegawaiKeluar');
		}	
	}

	public function cek_pegawai($id_pegawai)
	{
		$this->db->select('pegawai.id_pegawai');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->where('pegawai.id_pegawai', $id_pegawai);
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$cek = $this->db->get();
		if($cek->num_rows() == 0)
		{
			return FALSE;
		}else{
			return TRUE;
		}
	}
}
?>