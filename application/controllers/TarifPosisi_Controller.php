<?php
class TarifPosisi_Controller extends CI_Controller
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
		$bmr['class']       = 'tarifposisi';
		$bmr['id']		    = '';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Tarif Posisi';
		$bmr['sub_navigasi']= 'Lihat Data Tarif Posisi';
		$bmr['content']     = 'TarifPosisi/TarifPosisi_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadTarifPosisi()
	{
		$tarifposisi = $this->TarifPosisi_Model->getDataTableTarifPosisi();
		$data = array();
		foreach($tarifposisi as $dataTarifPosisi)
		{
			$row = array();
			$row[]  = $dataTarifPosisi->id_posisi;
			$row[]  = $dataTarifPosisi->nama_posisi;
			$row[]  = $dataTarifPosisi->status;
			$render = array('<a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick=lihatdata("'.$dataTarifPosisi->id_posisi.'") rel="modal:open" data-toggle="modal" title="Lihat Posisi"><i class="material-icons">visibility</i></a>
			    <a role="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float" href="'.site_url('/TarifPosisi_Controller/GetTarifPosisi').'/'.$dataTarifPosisi->id_posisi.'" title="Edit Posisi"><i class="material-icons">edit</i></a>');
			$row[]  = $render;

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->TarifPosisi_Model->HitungSemua(),
			"recordsFiltered"=>$this->TarifPosisi_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}

	public function DetailTarifPosisi()
	{
		$kode = $this->uri->segment(3);
		$this->db->where('id_posisi', $kode);
		$cek = $this->db->get('tarif_posisi');
		if($cek->num_rows() > 0)
			{
				$tarifposisi               = $this->TarifPosisi_Model->GetKode($kode);
				$tp['kode_posisi']         = $kode;
				$tp['tunjangan_makan']     = $tarifposisi['tunjangan_makan'];
				$tp['tunjangan_kesehatan'] = $tarifposisi['tunjangan_kesehatan'];
				$tp['tarif_per_produk']    = $tarifposisi['tarif_per_produk'];
				$tp['tarif_per_hari']      = $tarifposisi['tarif_per_hari'];
			}
		else
			{
				$tp['kode_posisi']         = "";
				$tp['tunjangan_makan']     = "";
				$tp['tunjangan_kesehatan'] = "";
				$tp['tarif_per_produk']    = "";
				$tp['tarif_per_hari']      = "";
			}
		echo json_encode($tp);
	}

	public function TambahTarifPosisi()
	{
		$bmr['tgl_hari']   			= hari_ini(date('w'));
		$bmr['tgl_indo']    		= tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']          = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       		= 'tarifposisi';
		$bmr['id']		    		= '';
		$bmr['sub_id']      		= '';
		$bmr['navigasi']    		= 'Tarif Posisi';
		$bmr['sub_navigasi']		= 'Lihat Data Tarif dan Posisi';
		$bmr['sub_navigasi2']       = 'Tambah Data Tarif dan Posisi';
		$bmr['content']     		= 'TarifPosisi/TarifPosisiTambah_View';
		$bmr['menu']        		= 'Menu/Menu_View';
		$bmr['navbar']      		= 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanTarifPosisi()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'kode_posisi',
						  'label'=>'Kode Posisi',
						  'rules'=>'trim|required|is_unique[tarif_posisi.id_posisi]|min_length[3]|max_length[3]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'is_unique'=>"".$_POST['kode_posisi']." Sudah Tersimpan", 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 3 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'nama_posisi',
						  'label'=>'Nama Posisi',
						  'rules'=>'trim|required|is_unique[tarif_posisi.nama_posisi]|min_length[5]|max_length[30]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 30 Karakter', 'is_unique'=>"".$_POST['nama_posisi']." Sudah Tersimpan")
						),
					array('field'=>'makan_tunjangan',
						  'label'=>'Tunjangan Makan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'kesehatan_tunjangan',
						  'label'=>'Tunjangan Kesehatan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'produk_tarif',
						  'label'=>'Tarif per Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'hari_tarif',
						  'label'=>'Tarif per Hari',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'status',
						  'label'=>'Status Posisi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
				);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
			if($this->form_validation->run())
			{
				$data['success']     = true;
				$kode                = $this->input->post('kode_posisi', TRUE);
				$nama                = $this->input->post('nama_posisi', TRUE);
				$status              = $this->input->post('status', TRUE);
				$tunjangan_makan     = remove_string($this->input->post('makan_tunjangan', TRUE));
				$tunjangan_kesehatan = remove_string($this->input->post('kesehatan_tunjangan', TRUE));
				$tarif_produk 		 = remove_string($this->input->post('produk_tarif', TRUE));
				$tarif_harian		 = remove_string($this->input->post('hari_tarif', TRUE));
				$this->TarifPosisi_Model->InsertTarifPosisi($kode, $nama, $tunjangan_makan, $tunjangan_kesehatan, $tarif_produk, $tarif_harian, $status);
			}
			else{
				foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
				}
			}

			$data['token']  = $this->security->get_csrf_hash();
			echo json_encode($data);
	}

	public function GetTarifPosisi()
	{
		$kode                       = $this->uri->segment(3);
		$bmr['data']				= $this->TarifPosisi_Model->GetKode($kode);
		$bmr['tgl_hari']   			= hari_ini(date('w'));
		$bmr['tgl_indo']    		= tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']          = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       		= 'tarifposisi';
		$bmr['id']		    		= '';
		$bmr['sub_id']      		= '';
		$bmr['navigasi']    		= 'Tarif Posisi';
		$bmr['sub_navigasi']		= 'Lihat Data Tarif dan Posisi';
		$bmr['sub_navigasi2']       = 'Update Data Tarif dan Posisi';
		$bmr['content']     		= 'TarifPosisi/TarifPosisiUpdate_View';
		$bmr['menu']        		= 'Menu/Menu_View';
		$bmr['navbar']      		= 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function UpdateTarifPosisi()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'kode_posisi',
						  'label'=>'Kode Posisi',
						  'rules'=>'trim|required|min_length[3]|max_length[3]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'min_length'=>'%s Minimal 3 Karakter', 'max_length'=>'%s Maksimal 3 Karakter', 'alpha_only'=>'%s Tidak Valid')
						),
					array('field'=>'nama_posisi',
						  'label'=>'Nama Posisi',
						  'rules'=>'trim|required|min_length[5]|max_length[30]|callback_alpha_only',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'alpha_only'=>'%s Tidak Valid', 'min_length'=>'%s Minimal 5 Karakter', 'max_length'=>'%s Maksimal 30 Karakter')
						),
					array('field'=>'makan_tunjangan',
						  'label'=>'Tunjangan Makan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'kesehatan_tunjangan',
						  'label'=>'Tunjangan Kesehatan',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'produk_tarif',
						  'label'=>'Tarif per Produk',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'hari_tarif',
						  'label'=>'Tarif per Hari',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						),
					array('field'=>'status',
						  'label'=>'Status Posisi',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
				);
			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
			if($this->form_validation->run())
			{
				$data['success']     = true;
				$kode   = $this->input->post('kode_posisi', TRUE);
				$nama   = $this->input->post('nama_posisi', TRUE);
				$status = $this->input->post('status', TRUE);
				$tunjangan_makan     = remove_string($this->input->post('makan_tunjangan', TRUE));
				$tunjangan_kesehatan = remove_string($this->input->post('kesehatan_tunjangan', TRUE));
				$tarif_produk 		 = remove_string($this->input->post('produk_tarif', TRUE));
				$tarif_harian		 = remove_string($this->input->post('hari_tarif', TRUE));
				$makan 				 = $this->input->post('makan', TRUE);
				$kesehatan   		 = $this->input->post('kesehatan', TRUE);
				$produk 			 = $this->input->post('produk', TRUE);
				$hari 			     = $this->input->post('hari', TRUE);
				$this->TarifPosisi_Model->UpdateTarifPosisi($kode, $nama, $tunjangan_makan, $tunjangan_kesehatan, $tarif_produk, $tarif_harian, $makan, $kesehatan, $produk, $hari, $status);

			}
			else{
				foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
				}
			}

			$data['token']  = $this->security->get_csrf_hash();
			echo json_encode($data);
	}

	public function alpha_only($string)
	{
		if ($string != '' && ! preg_match('/^[a-zA-Z\s]+$/', $string))
		{
        	return FALSE;
   	 	}else {
       		return TRUE;
    	}
	}

}
?>