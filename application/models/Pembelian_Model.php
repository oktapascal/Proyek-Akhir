<?php
class Pembelian_Model extends CI_Model
{
	var $select_bahan = array("bahan.id_bahan", "nama_bahan");
	var $table_bahan  = "bahan";
	var $search_bahan = array("bahan.id_bahan", "nama_bahan");
	var $order_bahan  = array('bahan.id_bahan'=>'asc');
	var $select_pembelian = array("id_transaksi", "tanggal_transaksi", "total");
	var $table_pembelian  = "pembelian";
	var $search_pembelian = array("id_transaksi");
	var $order_Pembelian  = array('id_transaksi'=>'desc');

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

	private function GetBahan()
	{
		$nomor = $this->session->userdata('pesanan');
		$this->db->distinct();
		$this->db->select($this->select_bahan);
		$this->db->from($this->table_bahan);
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('detail_pesanan', 'bom.id_produk=detail_pesanan.id_produk');
		$this->db->where('no_pesanan', $nomor);
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->where('detail_pesanan.status', "Belum Diproses");

		$bahan = 0;
		foreach($this->search_bahan as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($bahan==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_bahan) - 1 == $bahan)
				{
					$this->db->group_end();
				}	
			}
			$bahan++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_bahan[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_bahan))
		{
			$order_bahan = $this->order_bahan;
			$this->db->order_by(key($order_bahan), $order_bahan[key($order_bahan)]);
		}
	}

	public function getDataTableBahanPesanan()
	{
		$this->GetBahan();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->GetBahan();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$nomor = $this->session->userdata('pesanan');
		$this->db->distinct();
		$this->db->select($this->select_bahan);
		$this->db->from($this->table_bahan);
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('detail_pesanan', 'bom.id_produk=detail_pesanan.id_produk');
		$this->db->where('no_pesanan', $nomor);
		return $this->db->count_all_results();
	}

	public function AmbilBahan($kode)
	{
		$this->db->select('bahan.id_bahan, nama_bahan, SUM(detail_pesanan.jumlah*bom.jumlah) as kebutuhan, CONCAT(SUM(detail_pesanan.jumlah*bom.jumlah)," ", satuan) as total_kebutuhan');
		$this->db->from('bahan');
		$this->db->join('bom', 'bahan.id_bahan=bom.id_bahan');
		$this->db->join('detail_pesanan', 'bom.id_produk=detail_pesanan.id_produk');
		$this->db->where('bahan.id_bahan', $kode);
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->where('detail_pesanan.no_pesanan', $this->session->userdata('pesanan'));	
		$bahan = $this->db->get();
		return $bahan->row_array();
	}

	public function DetailPembelian()
	{
		$this->db->select('bahan.id_bahan, nama_bahan, CONCAT(jumlah," ", satuan) as jumlah_bahan, jumlah, harga, subtotal');
		$this->db->from('bahan');
		$this->db->join('detail_pembelian', 'bahan.id_bahan=detail_pembelian.id_bahan');
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->where('ip_address', $this->input->ip_address());
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function BeliBahan($kode, $kebutuhan, $harga)
	{
		$pesanan  = $this->session->userdata('pesanan');
		$subtotal = $kebutuhan*$harga;
		$this->db->where('id_bahan', $kode);
		$this->db->where('id_transaksi');
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->where('ip_address', $this->input->ip_address());
		$cek = $this->db->get('detail_pembelian');
		if($cek->num_rows() == 0)
		{
			$data = array('id_bahan'=>$kode,
						  'no_pesanan'=>$pesanan,
						  'jumlah'=>$kebutuhan,
						  'harga'=>$harga,
						  'subtotal'=>$subtotal,
						  'status'=>"Belum Konfirmasi",
						  'ip_address'=>$this->input->ip_address()
						 );
			$this->db->insert('detail_pembelian', $data);
		}
		else
		{
			return FALSE;
		}
	}

	public function HapusBahan($kode)
	{
		$this->db->WHERE('id_transaksi');
		$this->db->where('id_bahan', $kode);
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->where('ip_address', $this->input->ip_address());
		$this->db->where('id_transaksi');
		$this->db->delete('detail_pembelian');

	}

	private function BuatKodePembelian()
	{
		$this->db->select('RIGHT(pembelian.id_transaksi,6) as kode', FALSE);
		$this->db->from('pembelian');
    	$this->db->order_by('pembelian.id_transaksi','DESC');    
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
    	$kodejadi = "PBL-".$kodemax;     
    	return $kodejadi;  
	}

	private function GetDetailPembelian()
	{
		$this->db->distinct();
		$this->db->select('bahan.id_bahan, subtotal, jenis_bahan');
		$this->db->from('detail_pembelian');
		$this->db->join('bahan', 'detail_pembelian.id_bahan=bahan.id_bahan');
		$this->db->where('id_transaksi', NULL);
		$this->db->where('ip_address', $this->input->ip_address());
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->where('no_pesanan', $this->session->userdata('pesanan'));
		$bahan = $this->db->get();
		return $bahan->result_array();
	}

	public function SimpanPembelian()
	{
		$nomor_pesanan = $this->session->userdata('pesanan');
		$transaksi     = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$pembelian     = "INSERT INTO `pembelian`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$detail        = "UPDATE `detail_pembelian` SET `id_transaksi`=?, `status`=? WHERE `status`=? AND `ip_address`=? AND `no_pesanan`=?";
		$bahan         = "UPDATE `bahan` SET `stok_digudang`=?+? WHERE `id_bahan`=?";
		$jurnal        = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$pesanan       = $this->GetDetailPembelian();
		$kode          = $this->BuatKodePembelian();
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Pembelian ".$kode." Dimasukkan";
		$icon       = "redeem";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//

		$this->db->trans_begin();
		$total_bb      = 0;
		$total_bp      = 0;
		foreach($pesanan as $psn){
	    	$total    = $psn['subtotal'] + $total;
	    	$id_bahan = $psn['id_bahan'];
	    	if($psn['jenis_bahan'] == "Bahan Utama")
			{
				$total_bb = $total_bb + $psn['subtotal'];
			}
			elseif($psn['jenis_bahan'] == "Bahan Penolong")
			{
				$total_bp = $total_bp + $psn['subtotal'];
			}
	    	//GetStok Lama//
			$this->db->where('id_bahan', $id_bahan);
			$stok = $this->db->get('bahan');
			$stok_lama = $stok->row()->stok_digudang;

			//GetStok Baru//
			$this->db->where('id_bahan', $id_bahan);
			$this->db->where('status', "Belum Konfirmasi");
			$this->db->where('ip_address', $this->input->ip_address());
			$row = $this->db->get('detail_pembelian');
			$stok_baru = $row->row()->jumlah;

			$this->db->query($bahan, array($stok_lama, $stok_baru, $id_bahan));  
		}
		$this->db->query($transaksi, array($kode, date('Y-m-d'), $total));
		$this->db->query($pembelian, array($kode, date('Y-m-d'), $total));
		$this->db->query($jurnal, array("112", $kode, date('Y-m-d'), "Debet", $total_bb));
		$this->db->query($jurnal, array("111", $kode, date('Y-m-d'), "Kredit", $total_bb));
		$this->db->query($jurnal, array("115", $kode, date('Y-m-d'), "Debet", $total_bp));
		$this->db->query($jurnal, array("111", $kode, date('Y-m-d'), "Kredit", $total_bp));
		$this->db->query($detail, array($kode, "Konfirmasi", "Belum Konfirmasi", $this->input->ip_address(), $nomor_pesanan));

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

	public function GetPembelian()
	{
		$this->db->from($this->table_pembelian);
		
		$pembelian = 0;
		foreach($this->search_pembelian as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($pembelian==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_pembelian) - 1 == $pembelian)
				{
					$this->db->group_end();
				}	
			}
			$pembelian++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_pembelian[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_pembelian))
		{
			$order_pembelian = $this->order_pembelian;
			$this->db->order_by(key($order_pembelian), $order_pembelian[key($order_pembelian)]);
		}
	}

	public function getDataTablePembelian()
	{
		$this->GetPembelian();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterPembelian()
	{
		$this->GetPembelian();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaPembelian()
	{
		$this->db->from($this->table_pembelian);
		return $this->db->count_all_results();
	}

	public function AmbilPembelian($kode)
	{
		$this->db->select('bahan.id_bahan, nama_bahan, CONCAT(jumlah," ", satuan) as jumlah, harga, subtotal');
		$this->db->from('detail_pembelian');
		$this->db->join('bahan', 'detail_pembelian.id_bahan=bahan.id_bahan');
		$this->db->where('status', "Konfirmasi");
		$this->db->where('id_transaksi', $kode);
		$detail = $this->db->get();
		return $detail->result_array();
	}
}
?>