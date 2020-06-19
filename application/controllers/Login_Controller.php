<?php
class Login_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	
		if($this->session->userdata('logged_in') == "getLoginMalla")
		{
			redirect('Beranda_Controller');	
		}
	}

	public function SuksesLogin()
	{
		redirect('Beranda_Controller');
	}

	public function index()
	{
		$this->load->view('Login/Login_View');
	}

	public function SubmitLogin()
	{
		//Setting Form Validasi Backend//
		$this->getUsername();
		$data   = array('success'=> false, 'messages'=>array());
		$config = array(
					array('field'=>'username',
						  'label'=>'Username',
						  'rules'=>'trim|required|callback_cek_username',
						  'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'cek_username'=>'%s Tidak Ditemukan')
						),
					array(
						'field'=>'password',
						'label'=>'Password',
						'rules'=>'trim|required|callback_cek_password',
						'errors'=>array('required'=>'%s Tidak Boleh Kosong', 'cek_password'=>'%s yang Dimasukkan Salah')	
					)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="align-justify">', '</p>');
		if($this->form_validation->run())
		{
			$data['success'] = true;
			$username = $this->input->post('username', TRUE);
			$password = $this->input->post('password', TRUE);
			$this->Login_Model->CekLogin($username,$password);
			
		}
		else{
			foreach($_POST as $error=>$value){
				$data['messages'][$error] = form_error($error);	
			}
			
		}

		$data['token']  = $this->security->get_csrf_hash();
		echo json_encode($data);
				
	}

	public function cek_username($username)
	{
		$this->db->where('username', $username);
		$result = $this->db->get('pegawai');
		if($result->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function getUsername()
	{
		$name = $this->input->post('username', TRUE);
		$user = $this->db->get_where('pegawai', array('username'=>$name));
		if($user->num_rows() > 0)
		{
			return $user->row_array();
		}
		else{
			NULL;
		}
	}

	public function cek_password($password)
	{
		$put = $this->getUsername();
		if(password_verify($password, $put['password']))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>