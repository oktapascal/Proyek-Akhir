<?php
class Beranda_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') != "getLoginMalla")
		{
			
			redirect(site_url('Login_Controller'));
			
		}
	}

	public function index()
	{
		$bmr['tgl_hari']     =  hari_ini(date('w'));
		$bmr['tgl_indo']     = tgl_indo(date('Y-m-d'));
		$bmr['notifikasi']  = $this->Notifikasi_Model->GetNotifikasi();
		$bmr['class']        = 'beranda';
		$bmr['id']		     = '';
		$bmr['sub_id']       = '';
		$bmr['navigasi']     = 'Beranda';
		$brm['sub_navigas']  = '';
		$bmr['content']      = 'Beranda/Beranda_View';
		$bmr['menu']         = 'Menu/Menu_View';
		$bmr['navbar']       = 'Navbar/Navbar_View';
		$this->load->view('Template',$bmr);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('Login_Controller/index');
	}

	public function ComingSoon()
	{
		$this->load->view('ComingSoon');
	}

}
?>