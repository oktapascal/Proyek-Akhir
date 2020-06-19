<?php
class Notifikasi_Model extends CI_Model
{
	public function GetNotifikasi()
	{  
		$this->db->select('notifikasi, icon, MINUTE(timediff(CURRENT_TIME(), waktu)) AS selisih');
		$this->db->from('notifikasi');
		$this->db->where('id_posisi', $this->session->userdata('id_posisi'));
		$this->db->limit(7);
		$this->db->order_by('id_notifikasi', 'desc');
		$notif = $this->db->get();
		return $notif->result_array();
	}

	public function JumlahNotif()
	{
		$this->db->where('status', "0");
		$this->db->where('id_posisi', $this->session->userdata('id_posisi'));
		$query = $this->db->get('notifikasi');
		$jumlah = $query->num_rows();
		return $jumlah;
	}

	public function UpdateNotifikasi()
	{
		$data = array('status'=>1);
		$this->db->where('status', "0");
		$this->db->where('id_posisi', $this->session->userdata('id_posisi'));
		$this->db->update('notifikasi', $data);
	}
}
?>