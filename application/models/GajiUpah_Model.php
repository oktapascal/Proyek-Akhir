<?php
class GajiUpah_Model extends CI_Model
{
	var $select_tetap = array("pegawai.id_pegawai", "nama_pegawai", "tunjangan_kesehatan", "tunjangan_makan", "(tarif_per_hari*COUNT(presensi.id_pegawai)) as total_harian", "tarif_per_produk");
	var $tabel_tetap  = "pegawai";
	var $search_tetap = array("pegawai.id_pegawai", "nama_pegawai");
	var $order_tetap  = array('pegawai.id_pegawai'=>'asc');
	var $select_kontrak = array("pegawai.id_pegawai", "nama_pegawai", "tunjangan_makan", "SUM((tarif_per_produk*detail_btkl.jumlah)) as total_produk");
	var $tabel_kontrak  = "pegawai";
	var $search_kontrak = array("pegawai.id_pegawai", "nama_pegawai");
	var $order_kontrak  = array('pegawai.id_pegawai'=>'asc');
	var $select_gaji  = array("id_gaji", "tanggal_gaji", "total_gaji", "status");
	var $tabel_gaji   = "penggajian";
	var $search_gaji  = array("id_gaji");
	var $order_gaji   = array('id_gaji'=>'desc');
	var $select_upah  = array("id_gaji", "tanggal_upah", "total_upah", "status");
	var $tabel_upah   = "pengupahan";
	var $search_upah  = array("id_upah");
	var $order_upah   = array('id_upah'=>'desc');

	private function getGaji()
	{
		$this->db->select($this->select_tetap);
		$this->db->from($this->tabel_tetap);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('presensi', 'pegawai.id_pegawai=presensi.id_pegawai');
		//$this->db->where('presensi.tanggal BETWEEN "'.date('Y-m-01').'"AND"'.date('Y-m-d').'"');
		$this->db->where('MONTH(presensi.tanggal)', date('m'));
		$this->db->where('YEAR(presensi.tanggal)', date('Y'));
		$this->db->where('presensi.status', "Hadir");
		$this->db->where('tarif_posisi.status', "Tetap");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->group_by('pegawai.id_pegawai');

		$gaji = 0;

		foreach($this->search_tetap as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($gaji==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_tetap) - 1 == $gaji)
				{
					$this->db->group_end();
				}	
			}
			$gaji++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_tetap[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_tetap))
		{
			$order_tetap = $this->order_tetap;
			$this->db->order_by(key($order_tetap), $order_tetap[key($order_tetap)]);
		}
	}

	public function getDataTableGaji()
	{
		$this->getGaji();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterGaji()
	{
		$this->getGaji();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaGaji()
	{
		$this->db->select($this->select_tetap);
		$this->db->from($this->tabel_tetap);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('presensi', 'pegawai.id_pegawai=presensi.id_pegawai');
		$this->db->where('presensi.tanggal BETWEEN "'.date('Y-m-01').'"AND"'.date('Y-m-d').'"');
		$this->db->where('presensi.status', "Hadir");
		$this->db->where('tarif_posisi.status', "Tetap");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->group_by('pegawai.id_pegawai');
		return $this->db->count_all_results();
	}

	private function getUpah()
	{
		$this->db->select($this->select_kontrak);
		$this->db->from($this->tabel_kontrak);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('detail_btkl', 'pegawai.id_pegawai=detail_btkl.id_pegawai');
		$this->db->join('btkl', 'btkl.id_btkl=detail_btkl.id_btkl');
		$this->db->where('MONTH(btkl.tanggal)', date('m'));
		$this->db->where('btkl.status', "Belum Dibayar");
		$this->db->where('tarif_posisi.status', "Kontrak");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->group_by('pegawai.id_pegawai');

		$upah = 0;

		foreach($this->search_kontrak as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($upah==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_kontrak) - 1 == $upah)
				{
					$this->db->group_end();
				}	
			}
			$upah++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_kontrak[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_upah))
		{
			$order_kontrak = $this->order_kontrak;
			$this->db->order_by(key($order_kontrak), $order_kontrak[key($order_kontrak)]);
		}
	}

	public function getDataTableUpah()
	{
		$this->getUpah();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterUpah()
	{
		$this->getUpah();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaUpah()
	{
		$this->db->select($this->select_kontrak);
		$this->db->from($this->tabel_kontrak);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('detail_btkl', 'pegawai.id_pegawai=detail_btkl.id_pegawai');
		$this->db->join('btkl', 'btkl.id_btkl=detail_btkl.id_btkl');
		$this->db->where('MONTH(btkl.tanggal)', date('m'));
		$this->db->where('btkl.status', "Belum Dibayar");
		$this->db->where('tarif_posisi.status', "Kontrak");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->group_by('pegawai.id_pegawai');
		return $this->db->count_all_results();
	}

	private function BuatKodePenggajian()
	{
		$this->db->select('RIGHT(penggajian.id_gaji,6) as kode', FALSE);
		$this->db->from('penggajian');
		$this->db->where('status', "Dibayar");
    	$this->db->order_by('penggajian.id_gaji','DESC');    
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
    	$kodejadi = "PGJ-".$kodemax;     
    	return $kodejadi;  
	}

	private function BuatKodePengupahan()
	{
		$this->db->select('RIGHT(pengupahan.id_upah,6) as kode', FALSE);
		$this->db->from('pengupahan');
		$this->db->where('status', "Dibayar");
    	$this->db->order_by('pengupahan.id_upah','DESC');    
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
    	$kodejadi = "PUG-".$kodemax;     
    	return $kodejadi;  
	}

	private function BuatKodePembayaranGaji()
	{
		$this->db->select('RIGHT(pembayaran_gaji.id_transaksi,6) as kode', FALSE);
		$this->db->from('pembayaran_gaji');
    	$this->db->order_by('pembayaran_gaji.id_transaksi','DESC');    
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
    	$kodejadi = "PMG-".$kodemax;     
    	return $kodejadi;  
	}

	private function BuatKodePembayaranUpah()
	{
		$this->db->select('RIGHT(pembayaran_upah.id_transaksi,6) as kode', FALSE);
		$this->db->from('pembayaran_upah');
    	$this->db->order_by('pembayaran_upah.id_transaksi','DESC');    
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
    	$kodejadi = "PMU-".$kodemax;     
    	return $kodejadi;  
	}

	private function GetGajiPegawaiTetap()
	{
		$this->db->select('pegawai.id_pegawai, tunjangan_kesehatan, tunjangan_makan, (tarif_per_hari*COUNT(presensi.id_pegawai)) as total_harian, tarif_per_produk');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('presensi', 'pegawai.id_pegawai=presensi.id_pegawai');
		$this->db->where('presensi.tanggal BETWEEN "'.date('Y-m-01').'"AND"'.date('Y-m-d').'"');
		$this->db->where('presensi.status', "Hadir");
		$this->db->where('tarif_posisi.status', "Tetap");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->group_by('pegawai.id_pegawai');
		$gaji = $this->db->get();
		return $gaji->result_array();
	}

	private function GetUpahPegawaiKontrak()
	{
		$this->db->select('pegawai.id_pegawai, tunjangan_makan, SUM((tarif_per_produk*detail_btkl.jumlah)) as total_produk');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('detail_btkl', 'pegawai.id_pegawai=detail_btkl.id_pegawai');
		$this->db->join('btkl', 'detail_btkl.id_btkl=btkl.id_btkl');
		$this->db->where('MONTH(btkl.tanggal)', date('m'));
		$this->db->where('btkl.status', "Belum Dibayar");
		$this->db->where('tarif_posisi.status', "Kontrak");
		$this->db->where('tarif_posisi.status_keaktifan', "Aktif");
		$this->db->where('detail_tarif_posisi.status', "Aktif");
		$this->db->group_by('pegawai.id_pegawai');
		$upah = $this->db->get();
		return $upah->result_array();
	}

	public function SimpanGajiUpah()
	{
		$transaksi  = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$penggajian = "INSERT INTO `penggajian`(`id_gaji`, `tanggal_gaji`, `total_gaji`, `status`) VALUES (?,?,?,?)";
		$pengupahan = "INSERT INTO `pengupahan`(`id_upah`, `tanggal_upah`, `total_upah`, `status`) VALUES (?,?,?,?)";
		$pembayaran_gaji   = "INSERT INTO `pembayaran_gaji`(`id_transaksi`, `tanggal_transaksi`, `total`, `id_gaji`) VALUES (?,?,?,?)";
		$pembayaran_upah   = "INSERT INTO `pembayaran_upah`(`id_transaksi`, `tanggal_transaksi`, `total`, `id_upah`) VALUES (?,?,?,?)";
		$detail_penggajian = "INSERT INTO `detail_penggajian`(`id_gaji`, `id_pegawai`, `subtotal_perhari`, `subtotal_tunjangan`, `bonus`, `lembur`, `subtotal_gaji`) VALUES (?,?,?,?,?,?,?)";
		$detail_pengupahan = "INSERT INTO `detail_pengupahan`(`id_upah`, `id_pegawai`, `subtotal_perproduk`, `subtotal_tunjangan`, `subtotal_upah`) VALUES (?,?,?,?,?)";
		$btkl = "UPDATE `btkl` SET `status`=? WHERE MONTH(tanggal)=?";
		$jurnal_debit  = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$jurnal_kredit = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$kode_gaji     = $this->BuatKodePenggajian();
		$kode_upah     = $this->BuatKodePengupahan();
		$kode_pembayaran_gaji = $this->BuatKodePembayaranGaji();
		$kode_pembayaran_upah = $this->BuatKodePembayaranUpah();
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Penggajian dan Pengupahan Berhasil Dimasukkan";
		$icon       = "contact_mail";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$this->db->trans_begin();
		$gaji = $this->GetGajiPegawaiTetap();
		$upah = $this->GetUpahPegawaiKontrak();
		$total_gaji = 0;
		$total_upah = 0;
		//Penggajian//
		$this->db->select('SUM(jumlah) as jumlah');
		$this->db->from('detail_penjualan');
		$this->db->join('penjualan', 'detail_penjualan.id_transaksi=penjualan.id_transaksi');
		$this->db->where('MONTH(tanggal_transaksi)', date('m'));
		$jml = $this->db->get();
		$jumlah = $jml->row_array();
		foreach($gaji as $gj)
		{
			$subtotal_tunjangan_tetap   = $gj['tunjangan_makan'] + $gj['tunjangan_kesehatan'];
			$total_gaji                 = $total_gaji + $subtotal_tunjangan_tetap + $gj['total_harian'] + ($gj['tarif_per_produk']*$jumlah['jumlah']);
			
		}
		$this->db->query($penggajian, array($kode_gaji, date('Y-m-d'), $total_gaji, "Dibayar"));
		foreach($gaji as $ji)
		{
			$subtotal_tunjangan_tetap   = $ji['tunjangan_makan'] + $ji['tunjangan_kesehatan'];
			$bonus                      = $ji['tarif_per_produk']*$jumlah['jumlah'];
			$lembur                     = 0;
			$subtotal_gaji              = $subtotal_tunjangan_tetap + $bonus + $lembur + $ji['total_harian'];
			$this->db->query($detail_penggajian, array($kode_gaji, $ji['id_pegawai'], $ji['total_harian'], $subtotal_tunjangan_tetap, $bonus, $lembur, $subtotal_gaji)); 
		}
		$this->db->query($transaksi, array($kode_pembayaran_gaji, date('Y-m-d'), $total_gaji));
		$this->db->query($pembayaran_gaji, array($kode_pembayaran_gaji, date('Y-m-d'), $total_gaji, $kode_gaji));
		$this->db->query($jurnal_debit, array("513", $kode_pembayaran_gaji, date('Y-m-d'), "Debet", $total_gaji));
		$this->db->query($jurnal_kredit, array("111", $kode_pembayaran_gaji, date('Y-m-d'), "Kredit", $total_gaji));

		//Pengupahan//
		foreach($upah as $up)
		{
			$total_upah = $total_upah + $up['total_produk'] + $up['tunjangan_makan'];
		}
		$this->db->query($pengupahan, array($kode_upah, date('Y-m-d'), $total_upah, "Dibayar"));
		foreach($upah as $pah)
		{
			$subtotal_tunjangan_kontrak = $pah['tunjangan_makan'];
			$subtotal_upah				= $subtotal_tunjangan_kontrak + $pah['total_produk'];
			$this->db->query($detail_pengupahan, array($kode_upah, $pah['id_pegawai'], $pah['total_produk'], $subtotal_tunjangan_kontrak, $subtotal_upah));
		}
		$this->db->query($btkl, array("Dibayar", date('m')));
		$this->db->query($transaksi, array($kode_pembayaran_upah, date('Y-m-d'), $total_upah));
		$this->db->query($pembayaran_upah, array($kode_pembayaran_upah, date('Y-m-d'), $total_upah, $kode_upah));
		$this->db->query($jurnal_debit, array("512", $kode_pembayaran_gaji, date('Y-m-d'), "Debet", $total_upah));
		$this->db->query($jurnal_kredit, array("111", $kode_pembayaran_gaji, date('Y-m-d'), "Kredit", $total_upah));

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

	private function LihatGaji()
	{
		$this->db->from($this->tabel_gaji);

		$penggajian = 0;

		foreach($this->search_gaji as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($penggajian==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_gaji) - 1 == $penggajian)
				{
					$this->db->group_end();
				}	
			}
			$penggajian++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_gaji[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_gaji))
		{
			$order_gaji = $this->order_gaji;
			$this->db->order_by(key($order_gaji), $order_gaji[key($order_gaji)]);
		}
	}

	public function getDataTableLihatGaji()
	{
		$this->LihatGaji();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterLihatGaji()
	{
		$this->LihatGaji();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaLihatGaji()
	{
		$this->db->from($this->tabel_gaji);
		return $this->db->count_all_results();
	}

	public function DetailGaji($id_gaji)
	{
		$this->db->select('tanggal_gaji');
		$this->db->from('penggajian');
		$this->db->where('id_gaji', $id_gaji);
		$tanggal = $this->db->get()->row_array();
		$bulan   = date('m', strtotime($tanggal['tanggal_gaji']));
		$this->db->select('id_gaji, pegawai.id_pegawai, nama_pegawai, nama_posisi, subtotal_perhari, subtotal_tunjangan, bonus, lembur, subtotal_gaji, COUNT(presensi.id_pegawai) as jumlah_kehadiran, tarif_per_hari');
		$this->db->from('pegawai');
		$this->db->join('detail_penggajian', 'pegawai.id_pegawai=detail_penggajian.id_pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('presensi', 'pegawai.id_pegawai=presensi.id_pegawai');
		$this->db->where('id_gaji', $id_gaji);
		$this->db->where('MONTH(presensi.tanggal)', $bulan);
		$this->db->group_by('pegawai.id_pegawai');
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function GetBulanGaji()
	{
		$id_gaji    = $this->uri->segment(3);
		$this->db->where('id_gaji', $id_gaji);
		$penggajian = $this->db->get('penggajian');
		return $penggajian->row_array();
	}

	public function GetJumlahPresensi()
	{
		$gaji       = $this->GetBulanGaji();
		$bulan      = date('m', strtotime($gaji['tanggal_gaji']));
		$id_pegawai = $this->uri->segment(4);
		$this->db->select('COUNT(id_pegawai) as jumlah_kehadiran');
		$this->db->from('presensi');
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('status', "Hadir");
		$this->db->where('MONTH(tanggal)', $bulan);
		$jumlah = $this->db->get();
		return $jumlah->row_array();
	}

	public function DetailGajiPegawai($id_gaji, $id_pegawai)
	{
		$this->db->select('id_transaksi, tanggal_gaji, pegawai.id_pegawai, nama_pegawai, subtotal_perhari, subtotal_tunjangan, bonus, lembur, subtotal_gaji, nama_posisi, tarif_posisi.status, tunjangan_kesehatan, tunjangan_makan, tarif_per_hari');
		$this->db->from('pegawai');
		$this->db->join('detail_penggajian', 'detail_penggajian.id_pegawai=pegawai.id_pegawai');
		$this->db->join('penggajian', 'detail_penggajian.id_gaji=penggajian.id_gaji');
		$this->db->join('pembayaran_gaji', 'penggajian.id_gaji=pembayaran_gaji.id_gaji');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->where('penggajian.id_gaji', $id_gaji);
		$this->db->where('pegawai.id_pegawai', $id_pegawai);
		$slip = $this->db->get();
		return $slip->row_array();
	}

	private function LihatUpah()
	{
		$this->db->from($this->tabel_upah);

		$pengupahan = 0;

		foreach($this->search_upah as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($pengupahan==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_upah) - 1 == $pengupahan)
				{
					$this->db->group_end();
				}	
			}
			$pengupahan++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_upah[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_upah))
		{
			$order_upah = $this->order_upah;
			$this->db->order_by(key($order_upah), $order_upah[key($order_upah)]);
		}
	}

	public function getDataTableLihatUpah()
	{
		$this->LihatUpah();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterLihatUpah()
	{
		$this->LihatUpah();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaLihatUpah()
	{
		$this->db->from($this->tabel_upah);
		return $this->db->count_all_results();
	}

	public function DetailUpah($id_upah)
	{
		$this->db->select('id_upah, pegawai.id_pegawai, nama_pegawai, nama_posisi, subtotal_perproduk, subtotal_tunjangan, subtotal_upah, SUM(detail_btkl.jumlah) as jumlah_produk, tarif_per_produk');
		$this->db->from('pegawai');
		$this->db->join('detail_pengupahan', 'pegawai.id_pegawai=detail_pengupahan.id_pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('detail_btkl','pegawai.id_pegawai=detail_btkl.id_pegawai');
		$this->db->where('id_upah', $id_upah);
		$this->db->group_by('pegawai.id_pegawai');
		$upah = $this->db->get();
		return $upah->result_array();
	}

	public function GetBulanUpah()
	{
		$id_upah    = $this->uri->segment(3);
		$this->db->where('id_upah', $id_upah);
		$pengupahan = $this->db->get('pengupahan');
		return $pengupahan->row_array();
	}

	public function GetJumlahProduk()
	{
		$upah       = $this->GetBulanUpah();
		$bulan      = date('m', strtotime($upah['tanggal_upah']));
		$id_pegawai = $this->uri->segment(4);
		$this->db->select('SUM(jumlah) as jumlah_produk');
		$this->db->from('detail_btkl');
		$this->db->join('btkl', 'detail_btkl.id_btkl=btkl.id_btkl');
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('status', "Dibayar");
		$this->db->where('MONTH(tanggal)', $bulan);
		$jumlah = $this->db->get();
		return $jumlah->row_array();
	}

	public function DetailUpahPegawai($id_upah, $id_pegawai)
	{
		$this->db->select('id_transaksi, tanggal_upah, pegawai.id_pegawai, nama_pegawai, subtotal_perproduk, subtotal_tunjangan, subtotal_upah, nama_posisi, tarif_posisi.status,  tunjangan_makan, tarif_per_produk');
		$this->db->from('pegawai');
		$this->db->join('detail_pengupahan', 'detail_pengupahan.id_pegawai=pegawai.id_pegawai');
		$this->db->join('pengupahan', 'detail_pengupahan.id_upah=pengupahan.id_upah');
		$this->db->join('pembayaran_upah', 'pengupahan.id_upah=pembayaran_upah.id_upah');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->where('pengupahan.id_upah', $id_upah);
		$this->db->where('pegawai.id_pegawai', $id_pegawai);
		$slip = $this->db->get();
		return $slip->row_array();
	}
}
?>