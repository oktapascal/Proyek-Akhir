<?php
class Laporan_Model extends CI_Model
{
	public function GetJurnal()
	{
		$periode_jurnal = $this->session->userdata('tanggal_jurnal');
		$bulan_jurnal   = date('m', strtotime($periode_jurnal));
		$tahun_jurnal   = date('Y', strtotime($periode_jurnal));
		$this->db->select('no_akun, id_transaksi, tanggal, posisi_db_cr, nominal');
		$this->db->from('jurnal');
		$this->db->where('MONTH(tanggal)', $bulan_jurnal);
		$this->db->where('YEAR(tanggal)', $tahun_jurnal);
		$jurnal = $this->db->get();
		return $jurnal->result_array();
	}

	public function GetAkun()
	{
		return $this->db->get('akun')->result_array();
	}

	public function GetNamaAkun($kode)
	{
		$this->db->where('no_akun', $kode);
		$akun = $this->db->get('akun');
		return $akun->row_array();
	}

	public function GetHeaderAkun()
	{
		$akun = $this->session->userdata('akun');
		$this->db->where('no_akun', $akun);
		$header = $this->db->get('akun');
		return $header->row_array();
	}

	public function GetSaldoAwal()
	{
		$periode_buku_besar = $this->session->userdata('tanggal_buku_besar');
		$bulan_buku_besar   = date('m', strtotime($periode_buku_besar));
		$tahun_buku_besar   = date('Y', strtotime($periode_buku_besar));
		$akun               = $this->session->userdata('akun');
		if($tahun_buku_besar == date('Y')){
		//Get Saldo Debit//
		$this->db->distinct();
		$this->db->select_sum('nominal');
		$this->db->from('jurnal');
		$this->db->where('posisi_db_cr', "Debet");
		$this->db->where('no_akun', $akun);
		$this->db->where('MONTH(tanggal) != "'.$bulan_buku_besar.'"');
		$this->db->where('MONTH(tanggal) < "'.$bulan_buku_besar.'"');
		$this->db->where('YEAR(tanggal) <= "'.$tahun_buku_besar.'"');
		$saldo_debit = $this->db->get()->row_array();
		if(!isset($saldo_debit))
		{
			$saldo_debit = 0;
		}
		//Get Saldo Kredit//
		$this->db->distinct();
		$this->db->select_sum('nominal');
		$this->db->from('jurnal');
		$this->db->where('posisi_db_cr', "Kredit");
		$this->db->where('no_akun', $akun);
		$this->db->where('MONTH(tanggal) != "'.$bulan_buku_besar.'"');
		$this->db->where('MONTH(tanggal) < "'.$bulan_buku_besar.'"');
		$this->db->where('YEAR(tanggal) <= "'.$tahun_buku_besar.'"');
		$saldo_kredit = $this->db->get()->row_array();
		if(!isset($saldo_kredit))
		{
			$saldo_debit = 0;
		}
		$selisih = $saldo_debit['nominal'] - $saldo_kredit['nominal'];
		return $selisih;
		}else{
		//Get Saldo Debit//
		$this->db->distinct();
		$this->db->select_sum('nominal');
		$this->db->from('jurnal');
		$this->db->where('posisi_db_cr', "Debet");
		$this->db->where('no_akun', $akun);
		$this->db->where('YEAR(tanggal) < "'.$tahun_buku_besar.'"');
		$saldo_debit = $this->db->get()->row_array();
		if(!isset($saldo_debit))
		{
			$saldo_debit = 0;
		}
		//Get Saldo Kredit//
		$this->db->distinct();
		$this->db->select_sum('nominal');
		$this->db->from('jurnal');
		$this->db->where('posisi_db_cr', "Kredit");
		$this->db->where('no_akun', $akun);
		$this->db->where('YEAR(tanggal) < "'.$tahun_buku_besar.'"');
		$saldo_kredit = $this->db->get()->row_array();
		if(!isset($saldo_kredit))
		{
			$saldo_debit = 0;
		}
		$selisih = $saldo_debit['nominal'] - $saldo_kredit['nominal'];
		return $selisih;
		}
	}

	public function GetBukuBesar()
	{
		$periode_buku_besar = $this->session->userdata('tanggal_buku_besar');
		$bulan_buku_besar   = date('m', strtotime($periode_buku_besar));
		$tahun_buku_besar   = date('Y', strtotime($periode_buku_besar));
		$akun               = $this->session->userdata('akun');
		$this->db->select('header_akun, nama_akun, id_transaksi, tanggal, posisi_db_cr, nominal');
		$this->db->from('jurnal');
		$this->db->join('akun', 'jurnal.no_akun=akun.no_akun');
		$this->db->where('MONTH(tanggal)', $bulan_buku_besar);
		$this->db->where('YEAR(tanggal)', $tahun_buku_besar);
		$this->db->where('akun.no_akun', $akun);
		$buku_besar = $this->db->get();
		return $buku_besar->result_array();
	}

	public function GetPersediaanAwalDalamProses()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$bulan_hpproduksi   = date('m', strtotime($periode_hpproduksi));
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));

		$this->db->select('SUM(DISTINCT(detail_penyerahan_bahan.subtotal)) as total, IFNULL(total, 0) as total_dalam_proses');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.id_produk=detail_pesanan.id_produk');
		$this->db->join('penyerahan_bahan', 'detail_penyerahan_bahan.id_transaksi=penyerahan_bahan.id_transaksi');
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('MONTH(penyerahan_bahan.tanggal_transaksi) < "'.$bulan_hpproduksi.'"');
		$this->db->where('YEAR(penyerahan_bahan.tanggal_transaksi) < "'.$tahun_hpproduksi.'"');
		$persediaan_dalam_proses = $this->db->get();
		return $persediaan_dalam_proses->row_array();
	}

	/*public function GetBahanBakuAwal()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select('harga, stok_digudang');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi) = MONTH(NOW()-INTERVAL 1 MONTH)');
		$this->db->where('YEAR(pembelian.tanggal_transaksi)', $tahun_hpproduksi);
		$this->db->order_by('pembelian.id_transaksi', 'desc');
		$saldo_bahan = $this->db->get()->result_array();
		$total_bahan = 0;
		foreach($saldo_bahan as $saldo)
		{
			$total_bahan = $total_bahan + ($saldo['harga']*$saldo['stok_digudang']);
		}

		return $total_bahan;

	}*/

	public function GetPembelian()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$bulan_hpproduksi   = date('m', strtotime($periode_hpproduksi));
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select_sum('subtotal');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi)', $bulan_hpproduksi);
		$this->db->where('YEAR(pembelian.tanggal_transaksi)', $tahun_hpproduksi);
		$saldo_pembelian = $this->db->get()->row_array();
		
		return $saldo_pembelian;
	}

	public function GetBahanBakuAkhir()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$bulan_hpproduksi   = date('m', strtotime($periode_hpproduksi));
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select('harga, stok_digudang, stok_diproduksi');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi) = "'.$bulan_hpproduksi.'"');
		$this->db->where('YEAR(pembelian.tanggal_transaksi)  = "'.$tahun_hpproduksi.'"');
		$saldo_bahan_akhir = $this->db->get()->result_array();
		$total_bahan_akhir = 0;
		foreach($saldo_bahan_akhir as $saldo)
		{
			$total_bahan_akhir = $total_bahan_akhir + ($saldo['harga']*$saldo['stok_digudang']);
		}

		return $total_bahan_akhir;
	}

	public function GetBiayaTenagaKerjaLangsung()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$bulan_hpproduksi   = date('m', strtotime($periode_hpproduksi));
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select_sum('total');
		$this->db->from('btkl');
		$this->db->where('MONTH(tanggal)', $bulan_hpproduksi);
		$this->db->where('YEAR(tanggal)', $tahun_hpproduksi);
		$saldo_btkl = $this->db->get();
		return $saldo_btkl->row_array();
	}

	public function GetBiayaOverheadPabrik()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$bulan_hpproduksi   = date('m', strtotime($periode_hpproduksi));
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select_sum('nominal');
		$this->db->from('akun');
		$this->db->join('jurnal', 'akun.no_akun=jurnal.no_akun');
		$this->db->like('id_transaksi', 'PRS');
		$this->db->where('akun.no_akun', "525");
		$this->db->where('MONTH(tanggal)', $bulan_hpproduksi);
		$this->db->where('YEAR(tanggal)', $tahun_hpproduksi);
		$saldo_bop = $this->db->get();
		return $saldo_bop->row_array();
	}

	public function GetPersediaanAkhirDalamProses()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$bulan_hpproduksi   = date('m', strtotime($periode_hpproduksi));
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));

		$this->db->select('SUM(DISTINCT(detail_penyerahan_bahan.subtotal)) as total, IFNULL(total, 0) as total_akhir_dalam_proses');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.id_produk=detail_pesanan.id_produk');
		$this->db->join('penyerahan_bahan', 'detail_penyerahan_bahan.id_transaksi=penyerahan_bahan.id_transaksi');
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('MONTH(penyerahan_bahan.tanggal_transaksi) = "'.$bulan_hpproduksi.'"');
		$this->db->where('YEAR(penyerahan_bahan.tanggal_transaksi)  = "'.$tahun_hpproduksi.'"');
		$persediaan_dalam_proses = $this->db->get();
		return $persediaan_dalam_proses->row_array();
	}

	public function GetPersediaanAwalDalamProsesPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));

		$this->db->select('SUM(DISTINCT(detail_penyerahan_bahan.subtotal)) as total, IFNULL(total, 0) as total_dalam_proses');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.id_produk=detail_pesanan.id_produk');
		$this->db->join('penyerahan_bahan', 'detail_penyerahan_bahan.id_transaksi=penyerahan_bahan.id_transaksi');
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('MONTH(penyerahan_bahan.tanggal_transaksi) < "'.$bulan_hppenjualan.'"');
		$this->db->where('YEAR(penyerahan_bahan.tanggal_transaksi) < "'.$tahun_hppenjualan.'"');
		$persediaan_dalam_proses = $this->db->get();
		return $persediaan_dalam_proses->row_array();
	}

	/*public function GetBahanBakuAwal()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select('harga, stok_digudang');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi) = MONTH(NOW()-INTERVAL 1 MONTH)');
		$this->db->where('YEAR(pembelian.tanggal_transaksi)', $tahun_hpproduksi);
		$this->db->order_by('pembelian.id_transaksi', 'desc');
		$saldo_bahan = $this->db->get()->result_array();
		$total_bahan = 0;
		foreach($saldo_bahan as $saldo)
		{
			$total_bahan = $total_bahan + ($saldo['harga']*$saldo['stok_digudang']);
		}

		return $total_bahan;

	}*/

	public function GetPembelianPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));
		$this->db->select_sum('subtotal');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi)', $bulan_hppenjualan);
		$this->db->where('YEAR(pembelian.tanggal_transaksi)', $tahun_hppenjualan);
		$saldo_pembelian = $this->db->get()->row_array();
		
		return $saldo_pembelian;
	}

	public function GetBahanBakuAkhirPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));
		$this->db->select('harga, stok_digudang, stok_diproduksi');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi) = "'.$bulan_hppenjualan.'"');
		$this->db->where('YEAR(pembelian.tanggal_transaksi)  = "'.$tahun_hppenjualan.'"');
		$saldo_bahan_akhir = $this->db->get()->result_array();
		$total_bahan_akhir = 0;
		foreach($saldo_bahan_akhir as $saldo)
		{
			$total_bahan_akhir = $total_bahan_akhir + ($saldo['harga']*$saldo['stok_digudang']);
		}

		return $total_bahan_akhir;
	}

	public function GetBiayaTenagaKerjaLangsungPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));
		$this->db->select_sum('total');
		$this->db->from('btkl');
		$this->db->where('MONTH(tanggal)', $bulan_hppenjualan);
		$this->db->where('YEAR(tanggal)', $tahun_hppenjualan);
		$saldo_btkl = $this->db->get();
		return $saldo_btkl->row_array();
	}

	public function GetBiayaOverheadPabrikPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));
		$this->db->select_sum('nominal');
		$this->db->from('akun');
		$this->db->join('jurnal', 'akun.no_akun=jurnal.no_akun');
		$this->db->like('id_transaksi', 'PRS');
		$this->db->where('akun.no_akun', "525");
		$this->db->where('MONTH(tanggal)', $bulan_hppenjualan);
		$this->db->where('YEAR(tanggal)', $tahun_hppenjualan);
		$saldo_bop = $this->db->get();
		return $saldo_bop->row_array();
	}

	public function GetPersediaanAkhirDalamProsesPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));

		$this->db->select('SUM(DISTINCT(detail_penyerahan_bahan.subtotal)) as total, IFNULL(total, 0) as total_akhir_dalam_proses');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.id_produk=detail_pesanan.id_produk');
		$this->db->join('penyerahan_bahan', 'detail_penyerahan_bahan.id_transaksi=penyerahan_bahan.id_transaksi');
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('MONTH(penyerahan_bahan.tanggal_transaksi) = "'.$bulan_hppenjualan.'"');
		$this->db->where('YEAR(penyerahan_bahan.tanggal_transaksi)  = "'.$tahun_hppenjualan.'"');
		$persediaan_dalam_proses = $this->db->get();
		return $persediaan_dalam_proses->row_array();
	}

	public function GetPersediaanBarangJadiAwalPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));

		$this->db->select('SUM(total) as total, IFNULL(total, 0) as saldo_persediaan_barang_jadi_awal');
		$this->db->from('produksi');
		$this->db->join('detail_pesanan', 'produksi.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('MONTH(tanggal_transaksi) < "'.$bulan_hppenjualan.'"');
		$this->db->where('YEAR(tanggal_transaksi) < "'.$tahun_hppenjualan.'"');
		$this->db->where('status', "Selesai Produksi");
		$saldo_barang_jadi_awal = $this->db->get()->row_array();
		return $saldo_barang_jadi_awal;
	}

	public function GetPersediaanBarangJadiAkhirPenjualan()
	{
		$periode_hppenjualan = $this->session->userdata('tanggal_hppenjualan');
		$bulan_hppenjualan   = date('m', strtotime($periode_hppenjualan));
		$tahun_hppenjualan   = date('Y', strtotime($periode_hppenjualan));

		$this->db->select('SUM(total) as total, IFNULL(total, 0) as saldo_persediaan_barang_jadi_akhir');
		$this->db->from('produksi');
		$this->db->join('detail_pesanan', 'produksi.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('MONTH(tanggal_transaksi)', $bulan_hppenjualan);
		$this->db->where('YEAR(tanggal_transaksi)', $tahun_hppenjualan);
		$this->db->where('status', "Selesai Produksi");
		$saldo_barang_jadi_awal = $this->db->get()->row_array();
		return $saldo_barang_jadi_awal;
	}

	public function GetPenjualan()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));

		$this->db->select('SUM(total) as total, IFNULL(total, 0) as total_penjualan');
		$this->db->from('penjualan');
		$this->db->where('MONTH(tanggal_transaksi)', $bulan_laba_rugi);
		$this->db->where('YEAR(tanggal_transaksi)', $tahun_laba_rugi);
		$saldo_penjualan = $this->db->get()->row_array();
		return $saldo_penjualan;
	}

	public function GetPersediaanAwalDalamProsesLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));

		$this->db->select('SUM(DISTINCT(detail_penyerahan_bahan.subtotal)) as total, IFNULL(total, 0) as total_dalam_proses');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.id_produk=detail_pesanan.id_produk');
		$this->db->join('penyerahan_bahan', 'detail_penyerahan_bahan.id_transaksi=penyerahan_bahan.id_transaksi');
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('MONTH(penyerahan_bahan.tanggal_transaksi) < "'.$bulan_laba_rugi.'"');
		$this->db->where('YEAR(penyerahan_bahan.tanggal_transaksi) < "'.$tahun_laba_rugi.'"');
		$persediaan_dalam_proses = $this->db->get();
		return $persediaan_dalam_proses->row_array();
	}

	/*public function GetBahanBakuAwal()
	{
		$periode_hpproduksi = $this->session->userdata('tanggal_hpproduksi');
		$tahun_hpproduksi   = date('Y', strtotime($periode_hpproduksi));
		$this->db->select('harga, stok_digudang');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi) = MONTH(NOW()-INTERVAL 1 MONTH)');
		$this->db->where('YEAR(pembelian.tanggal_transaksi)', $tahun_hpproduksi);
		$this->db->order_by('pembelian.id_transaksi', 'desc');
		$saldo_bahan = $this->db->get()->result_array();
		$total_bahan = 0;
		foreach($saldo_bahan as $saldo)
		{
			$total_bahan = $total_bahan + ($saldo['harga']*$saldo['stok_digudang']);
		}

		return $total_bahan;

	}*/

	public function GetPembelianLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));
		$this->db->select_sum('subtotal');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi)', $bulan_laba_rugi);
		$this->db->where('YEAR(pembelian.tanggal_transaksi)', $tahun_laba_rugi);
		$saldo_pembelian = $this->db->get()->row_array();
		
		return $saldo_pembelian;
	}

	public function GetBahanBakuAkhirLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));
		$this->db->select('harga, stok_digudang, stok_diproduksi');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->join('pembelian', 'detail_pembelian.id_transaksi=pembelian.id_transaksi');
		$this->db->like('jenis_bahan', 'Bahan Utama');
		$this->db->where('MONTH(pembelian.tanggal_transaksi) = "'.$bulan_laba_rugi.'"');
		$this->db->where('YEAR(pembelian.tanggal_transaksi)  = "'.$tahun_laba_rugi.'"');
		$saldo_bahan_akhir = $this->db->get()->result_array();
		$total_bahan_akhir = 0;
		foreach($saldo_bahan_akhir as $saldo)
		{
			$total_bahan_akhir = $total_bahan_akhir + ($saldo['harga']*$saldo['stok_digudang']);
		}

		return $total_bahan_akhir;
	}

	public function GetBiayaTenagaKerjaLangsungLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));
		$this->db->select_sum('total');
		$this->db->from('btkl');
		$this->db->where('MONTH(tanggal)', $bulan_laba_rugi);
		$this->db->where('YEAR(tanggal)', $tahun_laba_rugi);
		$saldo_btkl = $this->db->get();
		return $saldo_btkl->row_array();
	}

	public function GetBiayaOverheadPabrikLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));
		$this->db->select_sum('nominal');
		$this->db->from('akun');
		$this->db->join('jurnal', 'akun.no_akun=jurnal.no_akun');
		$this->db->like('id_transaksi', 'PRS');
		$this->db->where('akun.no_akun', "525");
		$this->db->where('MONTH(tanggal)', $bulan_laba_rugi);
		$this->db->where('YEAR(tanggal)', $tahun_laba_rugi);
		$saldo_bop = $this->db->get();
		return $saldo_bop->row_array();
	}

	public function GetPersediaanAkhirDalamProsesLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));

		$this->db->select('SUM(DISTINCT(detail_penyerahan_bahan.subtotal)) as total, IFNULL(total, 0) as total_akhir_dalam_proses');
		$this->db->from('detail_penyerahan_bahan');
		$this->db->join('detail_pesanan', 'detail_penyerahan_bahan.id_produk=detail_pesanan.id_produk');
		$this->db->join('penyerahan_bahan', 'detail_penyerahan_bahan.id_transaksi=penyerahan_bahan.id_transaksi');
		$this->db->where('detail_pesanan.status', "Dalam Proses");
		$this->db->where('MONTH(penyerahan_bahan.tanggal_transaksi)', $bulan_laba_rugi);
		$this->db->where('YEAR(penyerahan_bahan.tanggal_transaksi)', $tahun_laba_rugi);
		$persediaan_dalam_proses = $this->db->get();
		return $persediaan_dalam_proses->row_array();
	}

	public function GetPersediaanBarangJadiAwalLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));

		$this->db->select('SUM(total) as total, IFNULL(total, 0) as saldo_persediaan_barang_jadi_awal');
		$this->db->from('produksi');
		$this->db->join('detail_pesanan', 'produksi.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('MONTH(tanggal_transaksi) < "'.$bulan_laba_rugi.'"');
		$this->db->where('YEAR(tanggal_transaksi) < "'.$tahun_laba_rugi.'"');
		$this->db->where('status', "Selesai Produksi");
		$saldo_barang_jadi_awal = $this->db->get()->row_array();
		return $saldo_barang_jadi_awal;
	}

	public function GetPersediaanBarangJadiAkhirLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));

		$this->db->select('SUM(total) as total, IFNULL(total, 0) as saldo_persediaan_barang_jadi_akhir');
		$this->db->from('produksi');
		$this->db->join('detail_pesanan', 'produksi.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('MONTH(tanggal_transaksi)', $bulan_laba_rugi);
		$this->db->where('YEAR(tanggal_transaksi)', $tahun_laba_rugi);
		$this->db->where('status', "Selesai Produksi");
		$saldo_barang_jadi_awal = $this->db->get()->row_array();
		return $saldo_barang_jadi_awal;
	}

	public function GetBebanLabaRugi()
	{
		$periode_laba_rugi = $this->session->userdata('tanggal_laba_rugi');
		$bulan_laba_rugi   = date('m', strtotime($periode_laba_rugi));
		$tahun_laba_rugi   = date('Y', strtotime($periode_laba_rugi));

		$this->db->select('nama_beban, subtotal');
		$this->db->from('beban');
		$this->db->join('detail_pembayaran_beban', 'beban.id_beban=detail_pembayaran_beban.id_beban');
		$this->db->join('pembayaran_beban', 'detail_pembayaran_beban.id_transaksi=pembayaran_beban.id_transaksi');
		$this->db->not_like('nama_beban', 'Pabrik');
		$this->db->where('MONTH(tanggal_transaksi)', $bulan_laba_rugi);
		$this->db->where('YEAR(tanggal_transaksi)', $tahun_laba_rugi);
		$beban = $this->db->get();
		return $beban->result_array();
	}

	public function GetLaporanGaji()
	{
		$periode_laporan_gaji = $this->session->userdata('tanggal_laporan_gaji');
		$bulan_laporan_gaji   = date('m', strtotime($periode_laporan_gaji));
		$tahun_laporan_gaji   = date('Y', strtotime($periode_laporan_gaji));

		$this->db->select('pegawai.id_pegawai, nama_pegawai, tarif_posisi.id_posisi, nama_posisi, tarif_posisi.status, subtotal_perhari, COUNT(presensi.id_pegawai) as jumlah_kehadiran, tunjangan_makan, tunjangan_kesehatan, detail_penggajian.bonus, detail_penggajian.lembur, subtotal_gaji');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('detail_penggajian', 'pegawai.id_pegawai=detail_penggajian.id_pegawai');
		$this->db->join('penggajian', 'penggajian.id_gaji=detail_penggajian.id_gaji');
		$this->db->join('presensi', 'pegawai.id_pegawai=presensi.id_pegawai');
		$this->db->where('MONTH(tanggal_gaji)', $bulan_laporan_gaji);
		$this->db->where('YEAR(tanggal_gaji)', $tahun_laporan_gaji);
		$this->db->where('presensi.status', 'Hadir');
		$this->db->group_by('pegawai.id_pegawai');
		$penggajian = $this->db->get();
		return $penggajian->result_array();
	}

	public function GetLaporanUpah()
	{
		$periode_laporan_upah = $this->session->userdata('tanggal_laporan_upah');
		$bulan_laporan_upah   = date('m', strtotime($periode_laporan_upah));
		$tahun_laporan_upah   = date('Y', strtotime($periode_laporan_upah));

		$this->db->select('pegawai.id_pegawai, nama_pegawai, tarif_posisi.id_posisi, nama_posisi, tarif_posisi.status, subtotal_perproduk, SUM(detail_btkl.jumlah) as jumlah_produksi, tunjangan_makan, subtotal_upah');
		$this->db->from('pegawai');
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
		$this->db->join('detail_btkl', 'pegawai.id_pegawai=detail_btkl.id_pegawai');
		$this->db->join('detail_pengupahan', 'pegawai.id_pegawai=detail_pengupahan.id_pegawai');
		$this->db->join('pengupahan', 'pengupahan.id_upah=detail_pengupahan.id_upah');
		$this->db->where('MONTH(tanggal_upah)', $bulan_laporan_upah);
		$this->db->where('YEAR(tanggal_upah)', $tahun_laporan_upah);
		$this->db->group_by('pegawai.id_pegawai');
		$pengupahan = $this->db->get();
		return $pengupahan->result_array();
	}
}
?>