<?php
class PegawaiKontrak_Model extends CI_Model
{
	var $tabel  = "pegawai";//nama Table di DB//
	var $select = array("pegawai.id_pegawai", "nama_pegawai", "tanggal_masuk", "nama_posisi");//Field yang tersedia di tabel $tabel//
	var $search = array("pegawai.id_pegawai", "nama_pegawai");//Filed yang diizikan untuk dicari//
	var $order  = array('pegawai.id_pegawai'=>'asc');//urutan data//

	private function getPegawaiKontrak()
	{
		$this->db->from($this->tabel);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('tarif_posisi.status', "Kontrak");
		$this->db->where('detail_tarif_posisi.status', "Aktif");

		$pegawaikontrak = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($pegawaikontrak==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $pegawaikontrak)
				{
					$this->db->group_end();
				}	
			}
			$pegawaikontrak++;
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

	public function getDataTablePegawaiKontrak()
	{
		$this->getPegawaiKontrak();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getPegawaiKontrak();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('tarif_posisi.status', "Kontrak");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		return $this->db->count_all_results();
	}

	public function GetDetail($kode)
	{
		$this->db->select('alamat, no_hp, tanggal_awal, tanggal_akhir, nik_pegawai, status_pernikahan, tanggal_lahir');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->where('pegawai.id_pegawai', $kode);
		$query = $this->db->get();
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

	private function KodePegawai()
	{
		$this->db->select('RIGHT(pegawai.id_pegawai,6) as kode', FALSE);
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
    	$this->db->where('tarif_posisi.status', "Kontrak");
    	$this->db->order_by('pegawai.id_pegawai','DESC');    
    	$this->db->limit(1);     
    	$query = $this->db->get(); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    	$kodejadi = "PGK-".$kodemax;     
    	return $kodejadi;  
	}

	public function GetPosisi()
	{
		$this->db->where('status', "Kontrak");
		$this->db->where('status_keaktifan', "Aktif");
		$query = $this->db->get('tarif_posisi');
		return $query->result_array();	
	}

	public function InsertPegawaiKontrak($nama, $nomor, $alamat, $posisi, $nik, $pernikahan, $tanggal_lahir)
	{
		$kode          = $this->KodePegawai();
		$username	   = "root";
		$password      = "No Password";
		$tanggal_kerja = date('Y-m-d');
		$tanggal       = DateTime::createFromFormat('j-M-Y', '15-Dec-2030');
		$tanggal_akhir = $tanggal->format('Y-m-d');
		$notifikasi = "Pegawai Kontrak ".$kode." Dimasukkan";
		$icon       = "supervisor_account";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$pegawai    = "INSERT INTO `pegawai`(`id_pegawai`, `nama_pegawai`, `alamat`, `no_hp`, `tanggal_masuk`, `username`, `password`, `foto`, `nik_pegawai`, `status_pernikahan`, `tanggal_lahir`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
		$detail  	= "INSERT INTO `detail_tarif_posisi`(`id_posisi`, `id_pegawai`, `tanggal_awal`, `tanggal_akhir`,`status`) VALUES (?,?,?,?,?)";
		$notif   	= "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$this->db->trans_begin();
		$this->db->query($pegawai, array($kode, $nama, $alamat, $nomor, $tanggal_kerja, $username, $password, "index.png", $nik, $pernikahan, $tanggal_lahir));
		$this->db->query($detail, array($posisi, $kode, $tanggal_kerja, $tanggal_akhir, "Aktif"));
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
		$this->db->select('pegawai.id_pegawai, nama_pegawai, alamat, no_hp, nik_pegawai, status_pernikahan, tanggal_lahir');
		$this->db->from('pegawai');
		$this->db->where('pegawai.id_pegawai', $kode);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else{
			NULL;
		}
	}

	public function UpdatePegawaiKontrak($kode, $nama, $nomor, $alamat, $nik, $pernikahan, $tanggal_lahir)
	{
		$pegawai = "UPDATE `pegawai` SET `nama_pegawai`=?,`alamat`=?,`no_hp`=?, `nik_pegawai`=?, `status_pernikahan`=?, `tanggal_lahir`=? WHERE `id_pegawai`=?";
		$notif   = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Pegawai Kontrak ".$kode." Diubah";
		$icon       = "supervisor_account";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($pegawai, array($nama, $alamat, $nomor, $nik, $pernikahan, $tanggal_lahir, $kode));
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

}
?>