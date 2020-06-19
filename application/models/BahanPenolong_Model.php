<?php 
class BahanPenolong_Model extends CI_Model
{
	var $tabel  = "bahan";//nama Table di DB//
	var $select = array("id_bahan", "nama_bahan", "stok_digudang", "stok_diproduksi", "satuan");//Field yang tersedia di tabel $tabel//
	var $search = array("id_bahan", "nama_bahan");//Filed yang diizikan untuk dicari//
	var $order  = array('id_bahan'=>'asc');//urutan data//

	private function getBahanPenolong()
	{
		$this->db->from($this->tabel);
		$this->db->where('jenis_bahan', "Bahan Penolong");

		$bahanpenolong = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($bahanpenolong==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $bahanpenolong)
				{
					$this->db->group_end();
				}	
			}
			$bahanpenolong++;
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

	public function getDataTableBahanPenolong()
	{
		$this->getBahanPenolong();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getBahanPenolong();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		$this->db->where('jenis_bahan', "Bahan Penolong");
		return $this->db->count_all_results();
	}

	public function KodeBahanPenolong()
    {    
    		$this->db->select('RIGHT(bahan.id_bahan,6) as kode', FALSE);
    		$this->db->where('jenis_bahan', "Bahan Penolong");
    		$this->db->order_by('id_bahan','DESC');    
    		$this->db->limit(1);     
    		$query = $this->db->get('bahan'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    		$kodejadi = "BHP-".$kodemax;     
    	return $kodejadi;  
    }

	public function UpdateBahanPenolong($kode, $nama, $satuan)
	{
		$bahan = "UPDATE `bahan` SET `nama_bahan`=?, `satuan`=? WHERE `id_bahan`=?";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Bahan Penolong ".$kode." Diubah";
		$icon       = "local_activity";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($bahan, array($nama, $satuan, $kode));
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

	public function InsertBahanPenolong($nama, $satuan)
	{
		$kode  = $this->KodeBahanPenolong();
		$bahan = "INSERT INTO `bahan`(`id_bahan`, `nama_bahan`, `stok_digudang`, `stok_diproduksi`, `satuan`, `jenis_bahan`) VALUES (?,?,?,?,?,?)";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Bahan Penolong ".$kode." Dimasukkan";
		$icon       = "local_activity";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($bahan, array($kode, $nama, 0, 0, $satuan, "Bahan Penolong"));
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
		$query = $this->db->get_where('bahan', array('id_bahan'=>$kode));
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