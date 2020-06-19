<?php
class Beban_Model extends CI_Model
{
	var $tabel  = "beban";//nama Table di DB//
	var $select = array("id_beban", "nama_beban");//Field yang tersedia di tabel $tabel//
	var $search = array("id_beban", "nama_beban");//Filed yang diizikan untuk dicari//
	var $order  = array('id_beban'=>'asc');//urutan data//

	private function getBeban()
	{
		$this->db->from($this->tabel);

		$beban = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($beban==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $beban)
				{
					$this->db->group_end();
				}	
			}
			$beban++;
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

	public function getDataTableBeban()
	{
		$this->getBeban();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getBeban();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		return $this->db->count_all_results();
	}

	public function KodeBeban()
    {    
    		$this->db->select('RIGHT(beban.id_beban,6) as kode', FALSE);
    		$this->db->order_by('id_beban','DESC');    
    		$this->db->limit(1);     
    		$query = $this->db->get('beban'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
    		if($query->num_rows() <> 0){       
   			//jika kode ternyata sudah ada.      
    		$data = $query->row();      
    		$kode = intval($data->kode) + 1;     
    	}
    	else{       
    	//jika kode belum ada      
    		$kode = 1;     
    		}
    		$kodemax = str_pad($kode, 6, "0", STR_PAD_LEFT);    
    		$kodejadi = "BBN-".$kodemax;     
    	return $kodejadi;  
    }

	public function UpdateBeban($kode, $nama)
	{
		$beban = "UPDATE `beban` SET `nama_beban`=? WHERE `id_beban`=?";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Beban ".$kode." Diubah";
		$icon       = "bug_report";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($beban, array($nama, $kode));
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

	public function InsertBeban($nama, $akun)
	{
		$kode  = $this->KodeBeban();
		$beban = "INSERT INTO `beban`(`id_beban`, `nama_beban`, `no_akun`) VALUES (?,?,?)";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Beban ".$kode." Dimasukkan";
		$icon       = "bug_report";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($beban, array($kode, $nama, $akun));
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

	public function GetAkun()
	{
		$this->db->like('nama_akun', 'Beban');
		$this->db->not_like('nama_akun', 'Gaji');
		$this->db->not_like('nama_akun', 'Upah');
		return $this->db->get('akun');
	}

	public function GetKode($kode)
	{
		$query = $this->db->get_where('beban', array('id_beban'=>$kode));
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