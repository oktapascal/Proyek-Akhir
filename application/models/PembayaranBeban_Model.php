<?php
class PembayaranBeban_Model extends CI_Model
{
	var $select = array("id_transaksi", "tanggal_transaksi", "total");
	var $tabel  = "pembayaran_beban";
	var $order  = array('id_transaksi'=>'desc');
	var $search = array("id_transaksi");

	public function GetBeban()
	{
		$beban = $this->db->get('beban');
		return $beban->result_array();
	}

	public function GetTipeBeban($beban)
	{
		$this->db->where('id_beban', $beban);
		$beban = $this->db->get('beban');
		return $beban->row_array();
	}

	public function TambahBeban($beban, $nominal, $jumlah)
	{
		$data = array('id_beban'=>$beban,
					  'jumlah_produksi'=>$jumlah,
					  'subtotal'=>$nominal
					);
		$this->db->insert('detail_pembayaran_beban', $data);
	}

	public function GetDetail()
	{
		$this->db->select('beban.id_beban, nama_beban, jumlah_produksi, subtotal');
		$this->db->from('beban');
		$this->db->join('detail_pembayaran_beban', 'beban.id_beban=detail_pembayaran_beban.id_beban');
		$this->db->where('id_transaksi');
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function HapusBeban($id_beban)
	{
		$this->db->where('id_beban', $id_beban);
		$this->db->where('id_transaksi');
		$hapus = $this->db->delete('detail_pembayaran_beban');
		if($hapus){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	private function BuatKodePembayaranBeban()
	{
		$this->db->select('RIGHT(pembayaran_beban.id_transaksi,6) as kode', FALSE);
		$this->db->from('pembayaran_beban');
    	$this->db->order_by('pembayaran_beban.id_transaksi','DESC');    
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
    	$kodejadi = "PBN-".$kodemax;     
    	return $kodejadi;  
	}

	public function SimpanPembayaranBeban()
	{
		$transaksi = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$pembayaran_beban = "INSERT INTO `pembayaran_beban`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$detail    = "UPDATE `detail_pembayaran_beban` SET `id_transaksi`=? WHERE `id_transaksi` IS NULL";
		$jurnal_debit  = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$jurnal_kredit = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$kode      = $this->BuatKodePembayaranBeban();
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Pembayaran Beban ".$kode." Dimasukkan";
		$icon       = "local_library";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$this->db->trans_begin();
		$total_pembayaran = 0;
		//Get Pembayaran Beban//
		$this->db->where('id_transaksi');
		$hasil = $this->db->get('detail_pembayaran_beban');
		$beban = $hasil->result_array();
		foreach($beban as $bbn)
		{
			$total_pembayaran = $total_pembayaran + $bbn['subtotal'];
		}
		$this->db->query($transaksi, array($kode, date('Y-m-d'), $total_pembayaran));
		$this->db->query($pembayaran_beban, array($kode, date('Y-m-d'), $total_pembayaran));
		$this->db->query($detail, array($kode));
		//Get Detail Beban//
		$this->db->select('no_akun, subtotal');
		$this->db->from('beban');
		$this->db->join('detail_pembayaran_beban', 'beban.id_beban=detail_pembayaran_beban.id_beban');
		$this->db->where('id_transaksi', $kode);
		$beban_produk = $this->db->get();
		foreach($beban_produk->result_array() as $bn)
		{
			$this->db->query($jurnal_debit, array($bn['no_akun'], $kode, date('Y-m-d'), "Debet", $bn['subtotal']));
			$this->db->query($jurnal_kredit, array("111", $kode, date('Y-m-d'), "Kredit", $bn['subtotal']));
		}

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

	private function getPembayaranBeban()
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
				if(count($this->search) - 1 == $akun)
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

	public function getDataTablePembayaranBeban()
	{
		$this->getPembayaranBeban();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getPembayaranBeban();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		return $this->db->count_all_results();
	}

	public function GetDetailPembayaranBeban($id_transaksi)
	{
		$this->db->select('beban.id_beban, nama_beban, subtotal, total');
		$this->db->from('beban');
		$this->db->join('detail_pembayaran_beban', 'beban.id_beban=detail_pembayaran_beban.id_beban');
		$this->db->join('pembayaran_beban', 'detail_pembayaran_beban.id_transaksi=pembayaran_beban.id_transaksi');
		$this->db->where('pembayaran_beban.id_transaksi', $id_transaksi);
		$detail = $this->db->get();
		return $detail->result_array();
	}
}
?>