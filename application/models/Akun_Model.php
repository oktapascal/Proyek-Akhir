<?php 
class Akun_Model extends CI_Model
{
	var $tabel  = "akun";//nama Table di DB//
	var $select = array("no_akun", "nama_akun", "header_akun");//Field yang tersedia di tabel $tabel//
	var $search = array("no_akun", "nama_akun");//Filed yang diizikan untuk dicari//
	var $order  = array('no_akun'=>'asc');//urutan data//

	private function getAkun()
	{
		$this->db->from($this->tabel);

		$akun = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($akun==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $akun)
				{
					$this->db->group_end();
				}	
			}
			$akun++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function getDataTableAkun()
	{
		$this->getAkun();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getAkun();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		return $this->db->count_all_results();
	}

	public function UpdateAkun($kode, $nama)
	{
		$akun  = "UPDATE `akun` SET `nama_akun`=? WHERE `no_akun`=?";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Akun ".$kode." Diubah";
		$icon       = "local_atm";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($akun, array($nama, $kode));
		//Insert Notifikasi//
		$posisi = $this->TarifPosisi_Model->GetPosisi();
		foreach($posisi as $ps)
		{
			$this->db->query($notif, array($notifikasi, $icon, $waktu, $status, $ps['id_posisi']));
		}
		if($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}

	public function InsertAkun($kode, $nama, $header)
	{
		$akun  = "INSERT INTO `akun`(`no_akun`, `nama_akun`, `header_akun`) VALUES (?,?,?)";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Akun ".$kode." Dimasukkan";
		$icon       = "local_atm";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0; 
		$this->db->trans_begin();
		$this->db->query($akun, array($kode, $nama, $header));
		//Insert Notifikasi//
		$posisi = $this->TarifPosisi_Model->GetPosisi();
		foreach($posisi as $ps)
		{
			$this->db->query($notif, array($notifikasi, $icon, $waktu, $status, $ps['id_posisi']));
		}
		if($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}	
	}

	public function GetKode($kode)
	{
		$query = $this->db->get_where('akun', array('no_akun'=>$kode));
		$row   = $query->num_rows();
		if($row > 0)
		{
			return $query->row_array();
		}
		else
		{
			NULL;
		}
	}
}
?>