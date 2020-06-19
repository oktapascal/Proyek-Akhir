<?php
class Pesanan_Model extends CI_Model
{
	var $tabel_produk   = "produk";
	var $select_produk  = array("produk.id_produk", "nama_produk", "ukuran", "IFNULL(harga_jual,0) AS harga_jual", "bom.status as status_bom", "detail_beban.status as status_beban");
	var $search_produk  = array("produk.id_produk", "nama_produk");
	var $order_produk   = array('produk.id_produk'=>'asc');
	var $tabel_pesanan  = "pesanan";
	var $select_pesanan = array("no_pesanan", "nama_pemesan", "no_hp");
	var $search_pesanan = array("no_pesanan", "nama_pemesan");
	var $order_pesanan  = array('no_pesanan'=>'desc');
	var $tabel_tktl     = "pegawai";
	var $select_tktl    = array("pegawai.id_pegawai", "nama_pegawai", "nama_posisi");//Field yang tersedia di tabel $tabel//
	var $search_tktl    = array("pegawai.id_pegawai", "nama_pegawai");//Filed yang diizikan untuk dicari//
	var $order_tktl     = array('pegawai.id_pegawai'=>'asc');//urutan data//

	private function GetProduk()
	{
		$this->db->distinct();
		$this->db->select($this->select_produk);
		$this->db->from($this->tabel_produk);
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk', 'left');
		$this->db->join('bom', 'produk.id_produk=bom.id_produk', 'left');
		$this->db->join('detail_beban', 'produk.id_produk=detail_beban.id_produk', 'left');		

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

	public function getDataTableProduk()
	{
		$this->GetProduk();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->GetProduk();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel_produk);
		$this->db->join('bom', 'produk.id_produk=bom.id_produk');
		$this->db->join('detail_beban', 'produk.id_produk=detail_beban.id_produk');
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->where('detail_beban.status', "Dikonfirmasi");
		return $this->db->count_all_results();
	}

	public function AmbilProduk($id)
	{
		$this->db->select($this->select_produk);
		$this->db->from($this->tabel_produk);
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk', 'left');
		$this->db->join('bom', 'produk.id_produk=bom.id_produk');
		$this->db->join('detail_beban', 'produk.id_produk=detail_beban.id_produk');
		$this->db->where('bom.status', "Dikonfirmasi");
		$this->db->where('detail_beban.status', "Dikonfirmasi");	
		$this->db->where('produk.id_produk', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function GetHarga($id_produk)
	{
		$this->db->select('IFNULL(harga_jual,0) as harga_jual');
		$this->db->from('produk');
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk', 'left');
		$this->db->where('produk.id_produk', $id_produk);
		$this->db->order_by('detail_penjualan.no_pesanan', 'desc');
		$query = $this->db->get();
		return $query->row_array();
	}

	private function BuatKodePesanan()
	{
			$this->db->select('RIGHT(pesanan.no_pesanan,6) as kode', FALSE);
			$this->db->from('pesanan');
    		$this->db->order_by('pesanan.no_pesanan','DESC');    
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
    		$kodejadi = "PSN-".$kodemax;     
    	return $kodejadi;  
	}

	public function TambahPesanan($id_produk, $jumlah_baru)
	{
		$harga        = $this->GetHarga($id_produk);
		$subtotal     = $jumlah_baru * $harga['harga_jual'];
		$this->db->where('no_pesanan', NULL);
		$this->db->where('id_produk', $id_produk);
		$this->db->where('ip_address', $this->input->ip_address());
		$cek = $this->db->get('detail_pesanan');
		if($cek->num_rows() == 0)
		{
		$data = array('id_produk'=>$id_produk,
					  'jumlah'=>$jumlah_baru,
					  'status'=>'Belum Dikonfirmasi',
					  'tanggal_pesan'=>date('Y-m-d'),
					  'subtotal'=>$subtotal,
					  'ip_address'=>$this->input->ip_address()
					 );
		$this->db->insert('detail_pesanan', $data);
		}
		else{
			$this->db->where('no_pesanan', NULL);
			$this->db->where('id_produk', $id_produk);
			$this->db->where('ip_address', $this->input->ip_address());
			$jml = $this->db->get('detail_pesanan');
			$jumlah_lama = $jml->row();
			$this->db->where('no_pesanan', NULL);
			$this->db->where('ip_address', $this->input->ip_address());
			$this->db->where('id_produk', $id_produk);
			$this->db->update('detail_pesanan', array('jumlah'=>$jumlah_lama->jumlah+$jumlah_baru, 'subtotal'=>$subtotal));
		}
	}

	public function GetDetail()
	{
		$this->db->select('produk.id_produk, nama_produk, ukuran, jumlah, subtotal');
		$this->db->from('produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('ip_address', $this->input->ip_address());
		$this->db->where('status', "Belum Dikonfirmasi");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function HapusPesanan($produk)
	{
		$this->db->where('id_produk', $produk);
		$this->db->where('no_pesanan', NULL);
		$this->db->where('ip_address', $this->input->ip_address());
		$this->db->delete('detail_pesanan');
	}

	public function GetDetailPesanan()
	{
		$this->db->select('nama_produk, ukuran, IFNULL(`harga_jual`,0) as harga_jual, detail_pesanan.jumlah, detail_pesanan.subtotal');
		$this->db->from('detail_pesanan');
		$this->db->join('produk', 'detail_pesanan.id_produk=produk.id_produk');
		$this->db->join('detail_penjualan', 'produk.id_produk=detail_penjualan.id_produk', 'left');
		$this->db->where('detail_pesanan.no_pesanan', NULL);
		$this->db->where('detail_pesanan.ip_address', $this->input->ip_address());
		$this->db->where('detail_pesanan.status', "Belum Dikonfirmasi");
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function SimpanPesanan($nama, $no_telp, $tanggal_selesai)
	{
		$pesanan = "INSERT INTO `pesanan`(`no_pesanan`, `nama_pemesan`, `no_hp`, `barcode`) VALUES (?,?,?,?)";
		$detail  = "UPDATE `detail_pesanan` SET `no_pesanan`=?,`tanggal_selesai`=?,`status`=? WHERE `ip_address`=? AND `status`=?";
		$kode    = $this->BuatKodePesanan();
		$ip      = $this->input->ip_address();
		$this->zend->load('zend/barcode');
		$imageResource = Zend_Barcode::factory('code39', 'image', array('text'=>$kode), array())->draw();
  		$imageName = $kode.'.png';
  		$imagePath = 'assets/barcode/'; // penyimpanan file barcode
   		imagepng($imageResource, $imagePath.$imageName); 
	  	$pathBarcode = $imagePath.$imageName; //Menyimpan path image bardcode kedatabase
	  	//Notif//
	  	$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Pesanan ".$kode." Dimasukkan";
		$icon       = "rate_review";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		//EndNotif//
		$this->db->trans_begin();
		$this->db->query($pesanan, array($kode, $nama, $no_telp, $pathBarcode));
		$this->db->query($detail, array($kode, $tanggal_selesai, "Belum Diproses", $ip, "Belum Dikonfirmasi"));
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

	private function GetPesanan()
	{
		$this->db->select($this->select_pesanan);
		$this->db->from($this->tabel_pesanan);
		
		$pesanan = 0;

		foreach($this->search_pesanan as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($pesanan==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_pesanan) - 1 == $pesanan)
				{
					$this->db->group_end();
				}	
			}
			$pesanan++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_pesanan[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_pesanan))
		{
			$order_pesanan = $this->order_pesanan;
			$this->db->order_by(key($order_pesanan), $order_pesanan[key($order_pesanan)]);
		}
	}

	public function getDataTablePesanan()
	{
		$this->GetPesanan();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterPesanan()
	{
		$this->GetPesanan();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaPesanan()
	{
		$this->db->select($this->select_pesanan);
		$this->db->from($this->tabel_pesanan);
		return $this->db->count_all_results();
	}

	public function GetNomor($nomor)
	{
		$this->db->select('nama_produk, tanggal_pesan, tanggal_selesai, status, subtotal, jumlah, ukuran');
		$this->db->from('pesanan');
		$this->db->join('detail_pesanan', 'pesanan.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->join('produk', 'detail_pesanan.id_produk=produk.id_produk');
		$this->db->where('pesanan.no_pesanan', $nomor);
		$detail = $this->db->get();
		return $detail->result_array();
	}

	public function GetBarcode($nomor)
	{
		$this->db->where('no_pesanan', $nomor);
		$barcode = $this->db->get('pesanan');
		return $barcode->row_array();
	}

	public function GetProdukPesanan($kode)
	{
		$this->db->select('produk.id_produk, nama_produk, ukuran');
		$this->db->from('produk');
		$this->db->join('detail_pesanan', 'produk.id_produk=detail_pesanan.id_produk');
		$this->db->where('no_pesanan', $kode);
		$this->db->where('status', "Dalam Proses");
		$produk = $this->db->get();
		return $produk->result();
	}

	private function getPegawaiKontrak()
	{
		$this->db->from($this->tabel_tktl);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('tarif_posisi.status', "Kontrak");
		$this->db->where('detail_tarif_posisi.status', "Aktif");

		$pegawaikontrak = 0;

		foreach($this->search_tktl as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($pegawaikontrak==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_tktl) - 1 == $pegawaitetap)
				{
					$this->db->group_end();
				}	
			}
			$pegawaikontrak++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_tktl[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_tktl))
		{
			$order_tktl = $this->order_tktl;
			$this->db->order_by(key($order_tktl), $order_tktl[key($order_tktl)]);
		}
	}

	public function getDataTablePegawaiKontrak()
	{
		$this->getPegawaiKontrak();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}


	public function HitungFilterPegawaiKontrak()
	{
		$this->getPegawaiKontrak();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaPegawaiKontrak()
	{
		$this->db->from($this->tabel_tktl);
		$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
		$this->db->join('tarif_posisi', 'tarif_posisi.id_posisi=detail_tarif_posisi.id_posisi');
		$this->db->where('tarif_posisi.status', "Kontrak");
		return $this->db->count_all_results();
	}

	public function GetPesananProses()
	{
		$this->db->distinct();
		$this->db->select('pesanan.no_pesanan, nama_pemesan');
		$this->db->from('pesanan');
		$this->db->join('detail_pesanan', 'pesanan.no_pesanan=detail_pesanan.no_pesanan');
		$this->db->where('status', "Dalam Proses");
		$query = $this->db->get();
		return $query->result_array();
	}

}
?>