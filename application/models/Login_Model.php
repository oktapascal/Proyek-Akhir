<?php
class Login_Model extends CI_Model
{
	public function CekLogin($username,$password)
  	{
  		$this->db->select('*');
  		$this->db->from('pegawai');
  		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
  		$this->db->where('username', $username);
      $this->db->where('detail_tarif_posisi.status', "Aktif");
  		$result = $this->db->get();
      $hasil = $result->row_array();

  		if($result->num_rows() > 0)
  		{
  				if(sandi_verif($password, $hasil['password']))
  				{
  					$session['logged_in']    = 'getLoginMalla';
  					$session['id_posisi']    = $hasil['id_posisi'];
  					$session['nama_pegawai'] = $hasil['nama_pegawai'];
            $session['foto']         = $hasil['foto'];
  					$this->session->set_userdata($session);
  				}
  			
  		}
  	}
}
?>

