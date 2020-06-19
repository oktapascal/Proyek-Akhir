<?php
class Penyerahan_Model extends CI_Model
{
	var $tabel  = "penyerahan_bahan";
	var $select = array("id_transaksi", "tanggal_transaksi", "total");
	var $search = array("id_transaksi");
	var $order  = array('id_transaksi'=>'desc');

	public function GetPesanan()
	{
		$this->db->distinct();
		$this->db->select('pesanan.no_pesanan, nama_pemesan');
		$this->db->from('pesanan');
		$this->db->join('detail_pesanan', 'pesanan.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('status', "Belum Diproses");
		$pesanan = $this->db->get();
		return $pesanan->result_array();
	}

	public function GetProduk($kode)
	{
		$this->db->select('produk.id_produk, nama_produk, ukuran');
		$this->db->from('produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('no_pesanan', $kode);
		$this->db->where('status', "Belum Diproses");
		$produk = $this->db->get();
		return $produk->result();
	}

	public function GetBahanPesanan()
	{
		$this->db->distinct();
		$this->db->select('bahan.id_bahan, nama_bahan, harga, stok_digudang, (bom.jumlah*detail_pesanan.jumlah) as total,CONCAT(stok_digudang, " ", satuan) as stok, CONCAT((bom.jumlah*detail_pesanan.jumlah), " ", satuan) as kebutuhan');
		$this->db->from('bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('detail_pesanan', 'detail_pembelian.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('bom.id_produk', $this->session->userdata('produk'));
		$this->db->where('detail_pembelian.no_pesanan', $this->session->userdata('no_pesanan'));
		$this->db->where('detail_pesanan.id_produk', $this->session->userdata('produk'));
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->group_by('detail_pembelian.id_bahan');
		$hasil = $this->db->get();
		return $hasil->result_array();		
	}

	public function CekStok()
	{
		$this->db->select('stok_digudang, (bom.jumlah*detail_pesanan.jumlah) as total');
		$this->db->from('bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('produk', 'bom.id_produk=produk.id_produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('produk.id_produk', $this->session->userdata('produk'));
		$this->db->where('detail_pesanan.no_pesanan', $this->session->userdata('no_pesanan'));
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->where('stok_digudang < (bom.jumlah*detail_pesanan.jumlah)');
		$cek = $this->db->get();
		return $cek;
	}

	public function BuatKodePenyerahan()
	{
		$this->db->select('RIGHT(penyerahan_bahan.id_transaksi,6) as kode', FALSE);
    	$this->db->order_by('id_transaksi','DESC');    
    	$this->db->limit(1);     
    	$query = $this->db->get('penyerahan_bahan'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    		$kodejadi = "PYB-".$kodemax;     
    	return $kodejadi;  
	}

	private function GetTotalPenyerahan()
	{
		$this->db->select('SUM(((bom.jumlah*detail_pesanan.jumlah)*harga)) as total');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('produk', 'bom.id_produk=produk.id_produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('bom.id_produk', $this->session->userdata('produk'));
		$this->db->where('detail_pembelian.no_pesanan', $this->session->userdata('no_pesanan'));
		$this->db->where('detail_pesanan.id_produk', $this->session->userdata('produk'));
		$this->db->where('bom.status', "Dikonfirmasi");
		$total = $this->db->get();
		return $total->row_array();
	}

	private function GetPenyerahanBahan()
	{
		$this->db->distinct();
		$this->db->select('bahan.id_bahan, jenis_bahan, stok_digudang, stok_diproduksi, (bom.jumlah*detail_pesanan.jumlah) as jumlah, ((bom.jumlah*detail_pesanan.jumlah)*harga) as subtotal');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('produk', 'bom.id_produk=produk.id_produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('produk.id_produk', $this->session->userdata('produk'));
		$this->db->where('detail_pesanan.no_pesanan', $this->session->userdata('no_pesanan'));
		$this->db->where('detail_pesanan.id_produk', $this->session->userdata('produk'));
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->group_by('detail_pembelian.id_bahan');
		$hasil = $this->db->get();
		return $hasil->result_array();
	}

	public function KonfirmasiPenyerahan()
	{
		$kode         = $this->BuatKodePenyerahan();
		$transaksi    = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$penyerahan   = "INSERT INTO `penyerahan_bahan`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$detail       = "INSERT INTO `detail_penyerahan_bahan`(`id_transaksi`, `no_pesanan`, `id_bahan`, `id_produk`,`jumlah`, `subtotal`, `status`, `ip_address`) VALUES (?,?,?,?,?,?,?,?)";
		$jurnal_debet = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$jurnal_kredit = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$bahan        = "UPDATE `bahan` SET `stok_digudang`=?-?, `stok_diproduksi`=?+? WHERE `id_bahan`=?";
		$pesanan      = "UPDATE `detail_pesanan` SET `status`=? WHERE `no_Pesanan`=? AND `id_produk`=?";
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Penyerahan ".$kode." Dimasukkan";
		$icon       = "local_shipping";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		
		$total              = $this->GetTotalPenyerahan();
		$bahan_pesanan      = $this->GetPenyerahanBahan();
		$this->db->trans_begin();
		$this->db->query($transaksi, array($kode, date('Y-m-d'), $total));
		$this->db->query($penyerahan, array($kode, date('Y-m-d'), $total));
		$bahan_utama = 0;
		$bahan_penolong = 0;
		$nomor_pesanan = $this->session->userdata('no_pesanan');
		$kode_produk   = $this->session->userdata('produk');
		$ip_address    = $this->input->ip_address();
		foreach($bahan_pesanan as $bhn)
		{
			if($bhn['jenis_bahan'] == "Bahan Utama")
			{
				$bahan_utama = $bahan_utama + $bhn['subtotal'];
			}
			elseif($bhn['jenis_bahan'] == "Bahan Penolong")
			{
				$bahan_penolong = $bahan_penolong + $bhn['subtotal'];
			}

		$this->db->query($detail, array($kode, $nomor_pesanan, $bhn['id_bahan'], $kode_produk, $bhn['jumlah'], $bhn['subtotal'], "Dikonfirmasi", $ip_address));
		$this->db->query($bahan, array($bhn['stok_digudang'], $bhn['jumlah'], $bhn['stok_diproduksi'], $bhn['jumlah'], $bhn['id_bahan']));
		}
		$this->db->query($pesanan, array("Dalam Proses", $nomor_pesanan, $kode_produk));
		$this->db->query($jurnal_debet, array("522", $kode, date('Y-m-d'), "Debet", $bahan_utama));
		$this->db->query($jurnal_debet, array("112", $kode, date('Y-m-d'), "Kredit", $bahan_utama));
		$this->db->query($jurnal_debet, array("525", $kode, date('Y-m-d'), "Debet", $bahan_penolong));
		$this->db->query($jurnal_debet, array("115", $kode, date('Y-m-d'), "Kredit", $bahan_penolong));

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

	private function GetPenyerahan()
	{
		$this->db->from($this->tabel);		

		$penyerahan = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($penyerahan==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $penyerahan)
				{
					$this->db->group_end();
				}	
			}
			$penyerahan++;
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


	public function getDataTablePenyerahan()
	{
		$this->GetPenyerahan();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->GetPenyerahan();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		return $this->db->count_all_results();
	}

	public function DetailPenyerahan($kode)
	{
		$this->db->select('bahan.id_bahan, nama_bahan, CONCAT(jumlah," ", satuan) as jumlah, subtotal');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('bahan', 'detail_penyerahan_bahan.id_bahan=bahan.id_bahan');
		$this->db->where('status', "Dikonfirmasi");
		$this->db->where('id_transaksi', $kode);
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function GetNomorPesanan($kode)
	{
		$this->db->where('id_transaksi', $kode);
		$nomor = $this->db->get('detail_penyerahan_bahan');
		return $nomor->row_array();
	}
}
?>