<?php 
class Pegawai_Model extends CI_Model
{
	public function GetDataPegawai($id_pegawai)
	{
		$this->db->select('pegawai.id_pegawai, nama_pegawai, tarif_posisi.id_posisi, nama_posisi, tarif_posisi.status');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->where('pegawai.id_pegawai', $id_pegawai);
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$get = $this->db->get();
		return $get->row_array();
	}

	public function GetPosisi()
	{
		$this->db->where('id_posisi != "'.$this->session->userdata('posisi_id').'"');
		$this->db->where('status', $this->session->userdata('status'));
		$this->db->where('status_keaktifan', "Aktif");
		$posisi = $this->db->get('tarif_posisi');
		return $posisi->result_array();
	}

	public function GetStatusPosisi($posisi)
	{
		$this->db->select('status');
		$this->db->from('tarif_posisi');
		$this->db->where('id_posisi', $posisi);
		$posisi = $this->db->get();
		return $posisi->row_array();
	}

	public function KonfirmasiPindahJabatan($posisi_baru)
	{
		$detail_insert = "INSERT INTO `detail_tarif_posisi`(`id_posisi`, `id_pegawai`, `tanggal_awal`, `tanggal_akhir`, `status`) VALUES (?,?,?,?,?)";
		$detail_update = "UPDATE `detail_tarif_posisi` SET `tanggal_akhir`=?,`status`=? WHERE `id_posisi`=? AND `id_pegawai`=?";
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Pegawai Berhasil Dikonfirmasi";
		$icon       = "supervisor_account";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$kode_pegawai = $this->session->userdata('pegawai_id');
		$posisi_lama  = $this->session->userdata('posisi_id');
		$tanggal       = DateTime::createFromFormat('j-M-Y', '15-Dec-2030');
		$tanggal_akhir = $tanggal->format('Y-m-d');
		$this->db->trans_begin();
		$this->db->query($detail_update, array(date('Y-m-d'), "Tidak Aktif", $posisi_lama, $kode_pegawai));
		$this->db->query($detail_insert, array($posisi_baru, $kode_pegawai, date('Y-m-d'), $tanggal_akhir, "Aktif"));
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
			$pindah = array('pegawai_id, pegawai_nama, posisi_id, posisi_nama, status');
			$this->session->unset_userdata($pindah);
			return true;
		}

	}

	public function KonfirmasiPegawaiKeluar()
	{
		$detail_update = "UPDATE `detail_tarif_posisi` SET `tanggal_akhir`=?,`status`=? WHERE `id_posisi`=? AND `id_pegawai`=?";
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Pegawai Berhasil Dikeluarkan";
		$icon       = "supervisor_account";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$kode_pegawai = $this->session->userdata('pegawai_kode');
		$posisi       = $this->session->userdata('posisi_kode');
		$this->db->trans_begin();
		$this->db->query($detail_update, array(date('Y-m-d'), "Tidak Aktif", $posisi, $kode_pegawai));
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