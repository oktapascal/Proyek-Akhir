<?php 
class Produk_Model extends CI_Model
{
	var $tabel  = "produk";//nama Table di DB//
	var $select = array("id_produk", "nama_produk", "ukuran", "stok");//Field yang tersedia di tabel $tabel//
	var $search = array("id_produk", "nama_produk");//Filed yang diizikan untuk dicari//
	var $order  = array('id_produk'=>'asc');//urutan data//

	private function getProduk()
	{
		$this->db->from($this->tabel);

		$produk = 0;

		foreach($this->search as $data)
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
				if(count($this->search) - 1 == $produk)
				{
					$this->db->group_end();
				}	
			}
			$produk++;
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

	public function getDataTableProduk()
	{
		$this->getProduk();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getProduk();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		return $this->db->count_all_results();
	}

	public function KodeProduk()
    {    
    		$this->db->select('RIGHT(produk.id_produk,6) as kode', FALSE);
    		$this->db->order_by('id_produk','DESC');    
    		$this->db->limit(1);     
    		$query = $this->db->get('produk'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    		$kodejadi = "PRD-".$kodemax;     
    	return $kodejadi;  
    }

	public function UpdateProduk($kode, $nama, $ukuran)
	{
		$produk = "UPDATE `produk` SET `nama_produk`=?,`ukuran`=? WHERE `id_produk`=?";
		$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Produk ".$kode." Diubah";
		$icon       = "school";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($produk, array($nama, $ukuran, $kode));
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

	public function InsertProduk($nama, $ukuran)
	{
		$kode   = $this->KodeProduk();
		$produk = "INSERT INTO `produk`(`id_produk`, `nama_produk`, `ukuran`, `stok`) VALUES (?,?,?,?)";
		$notif  = "INSERT INTO `notifikasi`(`notifikasi`, `icon`, `waktu`, `status`, `id_posisi`) VALUES (?,?,?,?,?)";
		$notifikasi = "Kode Produk ".$kode." Dimasukkan";
		$icon       = "school";
		date_default_timezone_set("Asia/Jakarta");
		$waktu      = date("H:i:s");
		$status     = 0;
		$this->db->trans_begin();
		$this->db->query($produk, array($kode, $nama, $ukuran, 0));
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

	public function GetKode($kode)
	{
		$query = $this->db->get_where('produk', array('id_produk'=>$kode));
		$row   = $query->num_rows();
		if($row > 0)
		{
			return $query->row_array();
		}
		else
		{
			NULL;
		}
	}

	public function GetUkuran($kode)
	{
		$query = $this->db->get_where('produk', array('id_produk'=>$kode));
		$row   = $query->num_rows();
		if($row > 0)
		{
			return $query->row_array();
		}
		else
		{
			NULL;
		}
	}

	public function GetSemuaUkuran($kode)
	{
		$this->db->where('id_produk', $kode);
		$sql = $this->db->get('produk');
		$ukuran = $sql->row_array();
		//Get Ukuran Selain Produk yang dipilih//
		$this->db->distinct();
		$this->db->select('ukuran');
		$this->db->from('produk');
		$this->db->where('id_produk !=', $kode);
		$this->db->where('ukuran !=', $ukuran['ukuran']);
		$query = $this->db->get();
		if($query->num_rows() > 1)
		{
			return $query->result_array();
		}
		else{
			return $query->row_array();;
		}
	}
}
?>