<?php
class Setoran_Controller extends CI_Controller
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
		$bmr['class']       = 'setoranmodal';
		$bmr['id']		    = 'tambahsetoran';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Setoran Modal';
		$bmr['sub_navigasi']= 'Tambah Setoran Modal';
		$bmr['content']     = 'Setoran/SetoranTambah_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function SimpanSetoran()
	{
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'nominal',
						  'label'=>'Jumlah Setoran Modal',
						  'rules'=>'trim|required',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong')
						)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$nominal         = remove_string($this->input->post('nominal', TRUE));
			$this->Setoran_Model->InsertSetoran($nominal);
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function LihatSetoran()
	{
		$bmr['tgl_hari']    = hari_ini(date('w'));
		$bmr['tgl_indo']    = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']       = 'setoranmodal';
		$bmr['id']		    = 'lihatsetoran';
		$bmr['sub_id']      = '';
		$bmr['navigasi']    = 'Setoran Modal';
		$bmr['sub_navigasi']= 'Lihat Setoran Modal';
		$bmr['content']     = 'Setoran/Setoran_View';
		$bmr['menu']        = 'Menu/Menu_View';
		$bmr['navbar']      = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function LoadSetoran()
	{
		$setoran = $this->Setoran_Model->getDataTableSetoran();
		$data = array();
		foreach($setoran as $dataSetoran)
		{
			$row = array();
			$row[] = $dataSetoran->id_transaksi;
			$row[] = date('d-m-Y', strtotime($dataSetoran->tanggal_transaksi));
			$row[] = format_rp($dataSetoran->total); 

			$data[] = $row;
		}

		$output = array(
			"draw"=>$_GET['draw'],
			"recordsTotal"=>$this->Setoran_Model->HitungSemua(),
			"recordsFiltered"=>$this->Setoran_Model->HitungFilter(),
			"data"=>$data,
			);
		echo json_encode($output);
	}
}
?>