<?php
class Penjualan_Model extends CI_Model
{
	var $select_produk = array("produk.id_produk", "nama_produk", "jumlah");
	var $table_produk   = "produk";
	var $search_produk  = array("produk.id_produk", "nama_produk");
	var $order_produk   = array('produk.id_produk'=>'asc');
	var $select_penjualan = array("id_transaksi", "tanggal_transaksi", "total");
	var $table_penjualan  = "penjualan";
	var $search_penjualan = array("id_transaksi");
	var $order_penjualan  = array('id_transaksi'=>'desc');

	public function LoadPesananSelesai()
	{
		$this->db->select($this->select_produk);
		$this->db->from($this->table_produk);
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('no_pesanan', $this->session->userdata('pesanan_selesai'));
		$this->db->where('status', "Selesai Produksi");

		$produk = 0;
		foreach($this->search_produk as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($produk==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_produk) - 1 == $produk)
				{
					$this->db->group_end();
				}	
			}
			$produk++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_produk[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_produk))
		{
			$order_produk = $this->order_produk;
			$this->db->order_by(key($order_produk), $order_produk[key($order_produk)]);
		}
	}

	public function getDataTablePesananSelesai()
	{
		$this->LoadPesananSelesai();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterPesananSelesai()
	{
		$this->LoadPesananSelesai();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaPesananSelesai()
	{
		$this->db->select($this->select_produk);
		$this->db->from($this->table_produk);
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('no_pesanan', $this->session->userdata('pesanan_selesai'));
		$this->db->where('status', "Selesai Produksi");
		return $this->db->count_all_results();
	}

	public function GetPenjualanPesanan()
	{
		$this->db->select('produk.id_produk, nama_produk, jumlah, harga_jual, subtotal');
		$this->db->from('produk');
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk');
		$this->db->where('no_pesanan', $this->session->userdata('pesanan_selesai'));
		$this->db->where('status', "Belum Konfirmasi");
		$penjualan = $this->db->get();
		return $penjualan->result_array();
	}

	private function GetProdukPesanan($produk, $pesanan)
	{
		$this->db->select('jumlah, total');
		$this->db->from('produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->join('produksi', 'produk.id_produk=produksi.id_produk');
		$this->db->where('produksi.no_pesanan', $pesanan);
		$this->db->where('produksi.id_produk', $produk);
		$this->db->where('status', "Selesai Produksi");
		$produk = $this->db->get();
		return $produk->row_array();
	}

	public function TambahPenjualan($produk, $pesanan)
	{
		$produk_pesanan = $this->GetProdukPesanan($produk, $pesanan);
		$harga_jual     = ceil(($produk_pesanan['total']/$produk_pesanan['jumlah'])+(($produk_pesanan['total']/$produk_pesanan['jumlah'])*0.5));
		$subtotal       = ceil($produk_pesanan['jumlah']*$harga_jual);
		$ip             = $this->input->ip_address();
		$this->db->where('no_pesanan', $pesanan);
		$this->db->where('id_produk', $produk);
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->where('id_transaksi');
		$this->db->where('ip_address', $this->input->ip_address());
		$penjualan = $this->db->get('detail_penjualan');
		if($penjualan->num_rows() == 0)
		{
			$data = array('no_pesanan'=>$pesanan,
						  'id_produk'=>$produk,
						  'harga_jual'=>$harga_jual,
						  'jumlah'=>$produk_pesanan['jumlah'],
						  'subtotal'=>$subtotal,
						  'status'=>"Belum Konfirmasi",
						  'ip_address'=>$ip
						);
			$this->db->insert('detail_penjualan', $data);
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function HapusProdukPesanan($kode)
	{
		$this->db->where('no_pesanan', $this->session->userdata('pesanan_selesai'));
		$this->db->where('id_produk', $kode);
		$this->db->where('ip_address', $this->input->ip_address());	
		$this->db->where('id_transaksi');
		$this->db->where('status', "Belum Konfirmasi");
		$this->db->delete('detail_penjualan');
	}

	public function BuatKodePenjualan()
	{
		$this->db->select('RIGHT(penjualan.id_transaksi,6) as kode', FALSE);
    	$this->db->order_by('id_transaksi','DESC');    
    	$this->db->limit(1);     
    	$query = $this->db->get('penjualan'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    	$kodejadi = "PJL-".$kodemax;     
    	return $kodejadi;  
	}

	public function SimpanPenjualan()
	{
		$pesanan        = $this->session->userdata('pesanan_selesai');
		$kode_penjualan = $this->BuatKodePenjualan();
		$transaksi = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$update_produk = "UPDATE `produk` SET `stok`=?-? WHERE `id_produk`=?";
		$penjualan = "INSERT INTO `penjualan`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$detail    = "UPDATE `detail_penjualan` SET `id_transaksi`=?,`status`=? WHERE `no_pesanan`=? AND `id_produk`=? AND `status`=?";
		$detail_pesanan = "UPDATE `detail_pesanan` SET `status`=? WHERE `no_pesanan`=? AND `id_produk`=? AND `status`=?";
		$jurnal_debit = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$jurnal_kredit = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Penjualan ".$kode_penjualan." Dimasukkan";
		$icon       = "shopping_basket";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$total = 0;
		$hpp   = 0;
		$this->db->trans_begin();
		$this->db->select('produk.id_produk, subtotal, total');
		$this->db->from('produk');
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk');
		$this->db->join('produksi', 'produk.id_produk=produksi.id_produk');
		$this->db->where('detail_penjualan.id_transaksi');
		$this->db->where('detail_penjualan.no_pesanan', $pesanan);
		$this->db->where('status', "Belum Konfirmasi");
		$produk = $this->db->get();
		foreach($produk->result_array() as $prd)
		{
			$total = $total + $prd['subtotal'];
			$hpp   = $hpp   + $prd['total'];
		}
		$this->db->query($transaksi, array($kode_penjualan, date('Y-m-d'), $total));
		$this->db->query($penjualan, array($kode_penjualan, date('Y-m-d'), $total));
		foreach($produk->result_array() as $prd)
		{
		 $this->db->query($detail, array($kode_penjualan, "Konfirmasi", $pesanan, $prd['id_produk'], "Belum Konfirmasi"));
		 $this->db->query($detail_pesanan, array("Terjual", $pesanan, $prd['id_produk'], "Selesai Produksi"));
		}
		$this->db->query($jurnal_debit, array("111", $kode_penjualan, date('Y-m-d'), "Debet", $total));
		$this->db->query($jurnal_kredit, array("411", $kode_penjualan, date('Y-m-d'), "Kredit", $total));
		$this->db->query($jurnal_debit, array("511", $kode_penjualan, date('Y-m-d'), "Debet", $hpp));
		$this->db->query($jurnal_kredit, array("113", $kode_penjualan, date('Y-m-d'), "Kredit", $hpp));
		//Get Stok dan Jumlah Produk//
		$this->db->select('produk.id_produk, stok, jumlah');
		$this->db->from('produk');
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk');
		$this->db->where('detail_penjualan.id_transaksi', $kode_penjualan);
		$this->db->where('detail_penjualan.no_pesanan', $pesanan);
		$stok = $this->db->get();
		//Get Stok dan Jumlah Produk//
		foreach($stok->result_array() as $stk)
		{
			$this->db->query($update_produk, array($stk['stok'], $stk['jumlah'], $stk['id_produk']));
		}
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

	public function LoadPenjualan()
	{
		$this->db->from($this->table_penjualan);

		$penjualan = 0;
		foreach($this->search_penjualan as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($penjualan==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_penjualan) - 1 == $penjualan)
				{
					$this->db->group_end();
				}	
			}
			$penjualan++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_penjualan[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_penjualan))
		{
			$order_penjualan = $this->order_penjualan;
			$this->db->order_by(key($order_penjualan), $order_penjualan[key($order_penjualan)]);
		}
	}

	public function getDataTablePenjualan()
	{
		$this->LoadPenjualan();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterPenjualan()
	{
		$this->LoadPenjualan();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaPenjualan()
	{
		$this->db->select($this->select_penjualan);
		$this->db->from($this->table_penjualan);
		return $this->db->count_all_results();
	}

	public function AmbilPenjualan($kode)
	{
		$this->db->select('produk.id_produk, nama_produk, CONCAT(jumlah," ","pcs") as jumlah, harga_jual, subtotal');
		$this->db->from('detail_penjualan');
		$this->db->join('produk', 'detail_penjualan.id_produk=produk.id_produk');
		$this->db->where('status', "Konfirmasi");
		$this->db->where('id_transaksi', $kode);
		$detail = $this->db->get();
		return $detail->result_array();
	}
}
?>