<?php
class PegawaiTetap_Model extends CI_Model
{
	var $tabel  = "pegawai";//nama Table di DB//
	var $select = array("pegawai.id_pegawai", "nama_pegawai", "tanggal_masuk", "nama_posisi");//Field yang tersedia di tabel $tabel//
	var $search = array("pegawai.id_pegawai", "nama_pegawai");//Filed yang diizikan untuk dicari//
	var $order  = array('pegawai.id_pegawai'=>'asc');//urutan data//

	private function getPegawaiTetap()
	{
		$this->db->from($this->tabel);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('tarif_posisi.status', "Tetap");
		$this->db->where('detail_tarif_posisi.status', "Aktif");

		$pegawaitetap = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($pegawaitetap==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $pegawaitetap)
				{
					$this->db->group_end();
				}	
			}
			$pegawaitetap++;
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

	public function getDataTablePegawaiTetap()
	{
		$this->getPegawaiTetap();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getPegawaiTetap();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('tarif_posisi.status', "Tetap");
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

	public function GetPosisi()
	{
		$this->db->where('status', "Tetap");
		$this->db->where('status_keaktifan', "Aktif");
		$query = $this->db->get('tarif_posisi');
		return $query->result_array();	
	}

	public function InsertPegawaiTetap($kode, $nik, $nama, $nomor, $alamat, $username, $password, $posisi, $pernikahan, $tanggal_lahir)
	{
		$tanggal_kerja = date('Y-m-d');
		$tanggal       = DateTime::createFromFormat('j-M-Y', '15-Dec-2030');
		$tanggal_akhir = $tanggal->format('Y-m-d');
		$notifikasi = "Pegawai Tetap ".$kode." Dimasukkan";
		$icon       = "supervisor_account";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$pegawai 	= "INSERT INTO `pegawai`(`id_pegawai`, `nama_pegawai`, `alamat`, `no_hp`, `tanggal_masuk`, `username`, `password`, `foto`, `nik_pegawai`, `status_pernikahan`, `tanggal_lahir`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
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

	public function UpdatePegawaiTetap($kode, $nama, $nomor, $alamat, $nik, $pernikahan, $tanggal_lahir)
	{
		$pegawai = "UPDATE `pegawai` SET `nama_pegawai`=?,`alamat`=?,`no_hp`=?, `tanggal_lahir`=?, `status_pernikahan`=?, `nik_pegawai`=? WHERE `id_pegawai`=?";
		$notif   = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Pegawai Tetap ".$kode." Diubah";
		$icon       = "supervisor_account";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($pegawai, array($nama, $alamat, $nomor, $tanggal_lahir, $pernikahan, $nik, $kode));
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