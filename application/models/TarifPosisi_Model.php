<?php
class TarifPosisi_Model extends CI_Model
{
	var $tabel  = "tarif_posisi";//nama Table di DB//
	var $select = array("id_posisi", "nama_posisi", "status");//Field yang tersedia di tabel $tabel//
	var $search = array("id_posisi", "nama_posisi");//Filed yang diizikan untuk dicari//
	var $order  = array('id_posisi'=>'asc');//urutan data//

	private function getTarifPosisi()
	{
		$this->db->from($this->tabel);
		$this->db->where('status_keaktifan', "Aktif");

		$tarifposisi = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($tarifposisi==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $tarifposisi)
				{
					$this->db->group_end();
				}	
			}
			$tarifposisi++;
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

	public function getDataTableTarifPosisi()
	{
		$this->getTarifPosisi();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getTarifPosisi();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		$this->db->where('status_keaktifan', "Aktif");
		return $this->db->count_all_results();
	}

	public function GetKode($kode)
	{
		$query = $this->db->get_where('tarif_posisi', array('id_posisi'=>$kode));
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

	public function InsertTarifPosisi($kode, $nama, $tunjangan_makan, $tunjangan_kesehatan, $tarif_produk, $tarif_harian, $statusposisi)
	{
		$tarif = "INSERT INTO `tarif_posisi`(`id_posisi`, `nama_posisi`, `status`, `tunjangan_kesehatan`, `tunjangan_makan`, `tarif_per_produk`, `tarif_per_hari`, `status_keaktifan`) VALUES (?,?,?,?,?,?,?,?)";
		$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Posisi ".$kode." Dimasukkan";
		$icon       = "school";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($tarif, array($kode, $nama, $statusposisi, $tunjangan_kesehatan, $tunjangan_makan, $tarif_produk, $tarif_harian, "Aktif"));
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

	private function KodePosisi($kodeposisi)
	{
		$this->db->select('RIGHT(tarif_posisi.id_posisi,6) as kode', FALSE);
    	$this->db->like('id_posisi', "".$kodeposisi."-");
    	$this->db->where('status_keaktifan', "Tidak Aktif");
    	$this->db->order_by('id_posisi','DESC');    
    	$this->db->limit(1);     
    	$query = $this->db->get('tarif_posisi'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    		$kodejadi = $kodeposisi."-".$kodemax;     
    	return $kodejadi;  
	}

	public function UpdateTarifPosisi($kode, $nama, $tunjangan_makan, $tunjangan_kesehatan, $tarif_produk, $tarif_harian, $makan, $kesehatan, $produk, $hari, $status)
	{
		$update = "UPDATE `tarif_posisi` SET `nama_posisi`=?,`tunjangan_kesehatan`=?,`tunjangan_makan`=?,`tarif_per_produk`=?,`tarif_per_hari`=? WHERE `id_posisi`=?";
		$insert = "INSERT INTO `tarif_posisi`(`id_posisi`, `nama_posisi`, `status`, `tunjangan_kesehatan`, `tunjangan_makan`, `tarif_per_produk`, `tarif_per_hari`, `status_keaktifan`) VALUES (?,?,?,?,?,?,?,?)";
		$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Posisi ".$kode." Diubah";
		$icon       = "assignment_ind";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$kodeBaru   = $this->KodePosisi($kode);
		$this->db->trans_begin();
		$this->db->query($update, array($nama, $tunjangan_kesehatan, $tunjangan_makan, $tarif_produk, $tarif_harian, $kode));
		$this->db->query($insert, array($kodeBaru, $nama, $status, $kesehatan, $makan, $produk, $hari, "Tidak Aktif"));
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

	public function GetPosisi()
	{
		$this->db->where('status', "Tetap");
		$this->db->where('status_keaktifan', "Aktif");
		$this->db->where('id_posisi !=', $this->session->userdata('id_posisi'));
		$query = $this->db->get('tarif_posisi');
		return $query->result_array();
	}
}
?>