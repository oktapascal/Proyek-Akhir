<?php
class Bom_Model extends CI_Model
{
	public function GetProdukNoBomBahan()
	{
		$this->db->select('produk.id_produk, nama_produk, ukuran');
		$this->db->from('produk');
		$this->db->join('bom', 'produk.id_produk=bom.id_produk', 'left');
		$this->db->where('status', NULL);
		$this->db->or_where('status', "Belum Konfirmasi");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function GetSatuan($bahan)
	{
		$this->db->select('satuan');
		$this->db->from('bahan');
		$this->db->where('id_bahan', $bahan);
		$hasil = $this->db->get();
		return $hasil->row_array();
	}

	public function GetAllBahan()
	{
		$this->db->select('id_bahan, nama_bahan');
		$this->db->from('bahan');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function TambahBomBahan($bahan, $produk, $jumlah)
	{
		$data = array('id_bahan'=>$bahan, 'id_produk'=>$produk, 'jumlah'=>$jumlah, 'status'=>"Belum Konfirmasi");
		$this->db->insert('bom', $data);
	}

	public function hapusbahan($beban, $produk)
	{
		$this->db->where('id_bahan', $beban);
		$this->db->where('id_produk', $produk);
		$this->db->delete('bom');
	}

	public function GetBomBahan()
	{
		$this->db->select('bahan.id_bahan, nama_bahan, jumlah, satuan');
		$this->db->from('bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->where('id_produk', $this->session->userdata('produk'));
		$this->db->where('status', "Belum Konfirmasi");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function SimpanBomBahan()
	{
		$bom   = "UPDATE `bom` SET `status`=? WHERE `id_produk`=?";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Bill Of Material ".$this->session->userdata('produk')." Telah Dikonfirmasi";
		$icon       = "extension";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();	
	    $this->db->query($bom, array("Dikonfirmasi", $this->session->userdata('produk')));
		
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

	public function GetProdukNoBomBeban()
	{
		$this->db->select('produk.id_produk, nama_produk, ukuran');
		$this->db->from('produk');
		$this->db->join('detail_beban', 'produk.id_produk=detail_beban.id_produk', 'left');
		$this->db->where('status', NULL);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function GetAllBeban()
	{
		$this->db->select('id_beban, nama_beban');
		$this->db->from('beban');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function GetNamaBeban($beban)
	{
		$this->db->select('nama_beban');
		$this->db->from('beban');
		$this->db->where('id_beban', $beban);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function TambahBomBeban($beban, $produk)
	{
		$data = array('id_produk'=>$produk, 'id_beban'=>$beban, 'status'=>"Belum Konfirmasi");
		$this->db->insert('detail_beban', $data);
	}

	public function GetBomBeban()
	{
		$this->db->select('beban.id_beban, nama_beban');
		$this->db->from('beban');
		$this->db->join('detail_beban', 'beban.id_beban=detail_beban.id_beban');
		$this->db->where('id_produk', $this->session->userdata('produk'));
		$this->db->where('status', "Belum Konfirmasi");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function hapusbeban($beban, $produk)
	{
		$this->db->where('id_beban', $beban);
		$this->db->where('id_produk', $produk);
		$this->db->delete('detail_beban');
	}

	public function SimpanBomBeban()
	{
		$bom   = "UPDATE `detail_beban` SET `status`=? WHERE `id_produk`=?";
		$notif = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Bill Of Material Beban ".$this->session->userdata('produk')." Telah Dikonfirmasi";
		$icon       = "extension";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();	
		$this->db->query($bom, array("Dikonfirmasi", $this->session->userdata('produk')));
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

	public function GetProdukBomSemua()
	{
		$this->db->distinct();
		$this->db->select('produk.id_produk, nama_produk, ukuran');
		$this->db->from('produk');
		$this->db->join('bom', 'produk.id_produk=bom.id_produk');
		$this->db->join('detail_beban', 'produk.id_produk=detail_beban.id_produk');
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->where('detail_beban.status', "Dikonfirmasi");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function GetProdukBomBahan($produk)
	{
		$this->db->select('bahan.id_bahan, nama_bahan, satuan, jumlah');
		$this->db->from('bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->where("status", "Dikonfirmasi");
		$this->db->where('bom.id_produk', $produk);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function GetProdukBomBeban($produk)
	{
		$this->db->select('beban.id_beban, nama_beban');
		$this->db->from('beban');
		$this->db->join('detail_beban', 'beban.id_beban=detail_beban.id_beban');
		$this->db->where('status', "Dikonfirmasi");
		$this->db->where('detail_beban.id_produk', $produk);
		$query = $this->db->get();
		return $query->result_array();
	}

}
?>