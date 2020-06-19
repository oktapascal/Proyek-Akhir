<?php
class Produksi_Model extends CI_Model
{
	public function GetJumlah($no_pesanan, $produk)
	{
		$this->db->select('jumlah');
		$this->db->from('detail_pesanan');
		$this->db->where('no_pesanan', $no_pesanan);
		$this->db->where('id_produk', $produk);
		$jumlah = $this->db->get();
		return $jumlah->row_array();
	}

	public function GetSisaProduksi($id_posisi)
	{
		$this->db->select('IFNULL(SUM(jumlah), 0) as jumlah');
		$this->db->from('detail_btkl');
		$this->db->where('id_posisi', $id_posisi);
		$this->db->where('id_btkl');
		$this->db->where('no_pesanan', $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('id_produk', $this->session->userdata('produk_konfirmasi'));
		$sisa = $this->db->get();
		return $sisa->row_array();
	}

	public function GetDetailPesanan()
	{
		$this->db->select('no_pesanan, jumlah');
		$this->db->from('detail_pesanan');
		$this->db->where('status', "Dalam Proses");
		$this->db->where('id_produk', $this->session->userdata('produk_konfirmasi'));
		$pesanan = $this->db->get();
		return $pesanan->row_array();
	}

	private function GetTarif($id_pegawai)
	{
		$this->db->select('tarif_per_produk');
		$this->db->from('tarif_posisi');
		$this->db->join('detail_tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('id_pegawai', $id_pegawai);
		$tarif = $this->db->get();
		return $tarif->row_array();
	}

	public function GetPosisi($id_pegawai)
	{
		$this->db->select('id_posisi');
		$this->db->from('detail_tarif_posisi');
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('status', "Aktif");
		$posisi = $this->db->get();
		return $posisi->row_array();
	}

	public function SimpanBTKL($no_pesanan, $id_produk, $jumlah, $id_pegawai)
	{
		$posisi  = $this->GetPosisi($id_pegawai);
		$tarif    = $this->GetTarif($id_pegawai);
		$subtotal = $tarif['tarif_per_produk']*$jumlah;
		$ip       = $this->input->ip_address();
		$data     = array('no_pesanan'=>$no_pesanan,
						  'id_produk'=>$id_produk,
						  'jumlah'=>$jumlah,
						  'subtotal'=>$subtotal,
						  'id_pegawai'=>$id_pegawai,
						  'id_posisi'=>$posisi['id_posisi'],
						  'ip_address'=>$ip
						);
		$this->db->insert('detail_btkl', $data);
	}

	public function GetDetailBTKL()
	{
		$this->db->select('pegawai.id_pegawai, nama_pegawai, jumlah, subtotal');
		$this->db->from('pegawai');
		$this->db->join('detail_btkl', 'pegawai.id_pegawai=detail_btkl.id_pegawai');
		$this->db->where('id_btkl', NULL);
		$this->db->where('no_pesanan',  $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('ip_address', $this->input->ip_address());
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function HapusBTKL($id_pegawai, $no_pesanan, $id_produk)
	{
		$this->db->where('no_pesanan', $no_pesanan);
		$this->db->where('id_produk', $id_produk);
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('id_btkl', NULL);
		$this->db->delete('detail_btkl');
	}

	public function BuatKodeBTKL()
	{
		$this->db->select('RIGHT(btkl.id_btkl,6) as kode', FALSE);
    	$this->db->order_by('id_btkl','DESC');    
    	$this->db->limit(1);     
    	$query = $this->db->get('btkl'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    		$kodejadi = "BTK-".$kodemax;     
    	return $kodejadi;  
	}

	public function KonfirmasiBTKL()
	{
		$kode     = $this->BuatKodeBTKL();
		$pesanan  = $this->session->userdata('no_pesanan_konfirmasi');
		$produk   = $this->session->userdata('produk_konfirmasi');
		$ip       = $this->input->ip_address();
		$btkl   = "INSERT INTO `btkl`(`id_btkl`, `tanggal`, `total`, `status`) VALUES (?,?,?,?)";
		$detail = "UPDATE `detail_btkl` SET `id_btkl`=? WHERE `no_pesanan`=? AND `ip_address`=? AND `id_produk`=? AND `id_btkl` IS NULL";
		
		$this->db->trans_begin();
		$this->db->select_sum('subtotal');
		$this->db->from('detail_btkl');
		$this->db->where('no_pesanan', $pesanan);
		$this->db->where('id_produk', $produk);
		$this->db->where('id_btkl');
		$ttl = $this->db->get();
		$total = $ttl->row()->subtotal;
		$this->db->query($btkl, array($kode, date('Y-m-d'), $total, "Belum Dibayar"));
		$this->db->query($detail, array($kode, $pesanan, $ip, $produk));
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

	public function GetBiayaBahanBaku()
	{
		$this->db->select('bahan.id_bahan, nama_bahan, CONCAT(detail_penyerahan_bahan.jumlah," ", satuan) as jumlah, (detail_penyerahan_bahan.subtotal) as subtotal');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('bahan', 'detail_penyerahan_bahan.id_bahan=bahan.id_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('detail_pesanan.no_pesanan',  $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('detail_penyerahan_bahan.id_produk', $this->session->userdata('produk_konfirmasi'));
		$this->db->where('jenis_bahan', "Bahan Utama");
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('detail_penyerahan_bahan.status', "Dikonfirmasi");
		$this->db->group_by('bahan.id_bahan');
		$bbb = $this->db->get();
		return $bbb->result_array();

	}

	public function GetBiayaTenagaKerjaLangsung()
	{
		$this->db->select('tarif_posisi.id_posisi, nama_posisi, detail_btkl.subtotal as subtotal');
		$this->db->from('tarif_posisi');
		$this->db->join('detail_tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->join('detail_btkl', 'detail_tarif_posisi.id_pegawai=detail_btkl.id_pegawai');
		$this->db->where('detail_btkl.no_pesanan', $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('detail_btkl.id_produk', $this->session->userdata('produk_konfirmasi'));
		$this->db->group_by('tarif_posisi.id_posisi');
		$btkl = $this->db->get();
		return $btkl->result_array();
	}

	public function GetBahanPenolong()
	{
		$this->db->select('bahan.id_bahan, nama_bahan, CONCAT(detail_penyerahan_bahan.jumlah, " ", satuan) as jumlah, SUM(detail_penyerahan_bahan.subtotal) as subtotal');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('bahan', 'detail_penyerahan_bahan.id_bahan=bahan.id_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->join('produk', 'detail_pesanan.id_produk=produk.id_produk');
		$this->db->where('detail_pesanan.no_pesanan', $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('produk.id_produk', $this->session->userdata('produk_konfirmasi'));
		$this->db->where('jenis_bahan', "Bahan Penolong");
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('detail_penyerahan_bahan.status', "Dikonfirmasi");
		$this->db->group_by('bahan.id_bahan');
		$penolong = $this->db->get();
		return $penolong->result_array();
	}

	public function GetBOPLain()
	{
		$this->db->select('beban.id_beban, nama_beban, detail_pesanan.jumlah, SUM(detail_pembayaran_beban.subtotal) as total, (AVG(total)/AVG(jumlah_produksi)) as rata');
		$this->db->from('beban');
		$this->db->join('detail_beban', 'beban.id_beban=detail_beban.id_beban');
		$this->db->join('produk', 'detail_beban.id_produk=produk.id_produk');
		$this->db->join('detail_pembayaran_beban', 'beban.id_beban=detail_pembayaran_beban.id_beban');
		$this->db->join('pembayaran_beban', 'detail_pembayaran_beban.id_transaksi=pembayaran_beban.id_transaksi');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('detail_pesanan.no_pesanan', $this->session->userdata('no_pesanan_konfirmasi'));
		$this->db->where('detail_pesanan.id_produk', $this->session->userdata('produk_konfirmasi'));
		$this->db->where('detail_beban.status', "Dikonfirmasi");
		$this->db->where('pembayaran_beban.tanggal_transaksi >= NOW()-INTERVAL 2 MONTH');
		$this->db->group_by('beban.id_beban');	
		$bop = $this->db->get();
		return $bop->result_array();
	}

	private function BuatKodeProduksi()
	{
		$this->db->select('RIGHT(produksi.id_transaksi,6) as kode', FALSE);
    	$this->db->order_by('id_transaksi','DESC');    
    	$this->db->limit(1);     
    	$query = $this->db->get('produksi'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    	$kodejadi = "PRS-".$kodemax;     
    	return $kodejadi;  
	}

	public function KonfirmasiProduksi($no_pesanan, $produk, $bbb, $btkl, $bop, $total)
	{
		$kode_produksi = $this->BuatKodeProduksi();
		$transaksi = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)"; 
		$produksi  = "INSERT INTO `produksi`(`id_transaksi`, `tanggal_transaksi`, `no_pesanan`, `id_produk`, `bbb`, `btkl`, `bop`, `total`) VALUES (?,?,?,?,?,?,?,?)";
		$pesanan   = "UPDATE `detail_pesanan` SET `status`=?, `tanggal_selesai`=? WHERE `no_pesanan`=? AND`id_produk`=? AND `status`=?";
		$bahan     = "UPDATE `bahan` SET `stok_diproduksi`=?-? WHERE `id_bahan`=?";
		$update_produk = "UPDATE `produk` SET `stok`=?+? WHERE `id_produk`=?";
		$jurnal_debet  = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$jurnal_kredit = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Produksi ".$kode_produksi." Dimasukkan";
		$icon       = "assignment_turned_in";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$this->db->trans_begin();
		$this->db->query($transaksi, array($kode_produksi, date('Y-m-d'), $total));
		$this->db->query($produksi, array($kode_produksi, date('Y-m-d'), $no_pesanan, $produk, $bbb, $btkl, $bop, $total));
		$this->db->query($pesanan, array("Selesai Produksi", date('Y-m-d'), $no_pesanan, $produk, "Dalam Proses"));
		//Get Stok Lama//
		$this->db->select('stok');
		$this->db->from('produk');
		$this->db->where('id_produk', $produk);
		$stok_lama = $this->db->get()->row_array();
		//Get Jumlah Pesanan//
		$this->db->select('jumlah');
		$this->db->from('detail_pesanan');
		$this->db->where('no_pesanan', $no_pesanan);
		$this->db->where('id_produk', $produk);
		$jumlah_produk = $this->db->get()->row_array();
		//Update Stok//
		$this->db->query($update_produk, array($stok_lama['stok'], $jumlah_produk['jumlah'], $produk));
		//Get Bahan Berdasarkan BOM//
		$this->db->distinct();
		$this->db->select('bahan.id_bahan, stok_diproduksi, detail_penyerahan_bahan.jumlah');
		$this->db->from('bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('detail_penyerahan_bahan', 'bahan.id_bahan=detail_penyerahan_bahan.id_bahan');
		$this->db->where('no_pesanan', $no_pesanan);
		$this->db->where('bom.id_produk', $produk);
		$this->db->where('detail_penyerahan_bahan.id_produk', $produk);
		$update = $this->db->get();
		foreach($update->result_array() as $bhn)
		{
			//Update Bahan//
			$this->db->query($bahan, array($bhn['stok_diproduksi'], $bhn['jumlah'], $bhn['id_bahan']));		
		}
		$this->db->query($jurnal_debet, array("113", $kode_produksi, date('Y-m-d'), "Debet", $total));
		$this->db->query($jurnal_kredit, array("522", $kode_produksi, date('Y-m-d'), "Kredit", $bbb));
		$this->db->query($jurnal_kredit, array("524", $kode_produksi, date('Y-m-d'), "Kredit", $btkl));
		$this->db->query($jurnal_kredit, array("525", $kode_produksi, date('Y-m-d'), "Kredit", $bop));

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