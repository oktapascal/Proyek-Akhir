<?php
class Setoran_Model extends CI_Model
{
	var $tabel  = "setoran_modal";//nama Table di DB//
	var $select = array("id_transaksi", "tanggal_transaksi", "total");//Field yang tersedia di tabel $tabel//
	var $search = array("id_transaksi", "tanggal_transaksi");//Filed yang diizikan untuk dicari//
	var $order  = array('id_transaksi'=>'desc');//urutan data//

	private function getSetoran()
	{
		$this->db->from($this->tabel);

		$setoran = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($setoran==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $setoran)
				{
					$this->db->group_end();
				}	
			}
			$setoran++;
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

	public function getDataTableSetoran()
	{
		$this->getSetoran();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getSetoran();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->from($this->tabel);
		return $this->db->count_all_results();
	}

	public function KodeSetoran()
    {    
    		$this->db->select('RIGHT(setoran_modal.id_transaksi,6) as kode', FALSE);
    		$this->db->order_by('id_transaksi','DESC');    
    		$this->db->limit(1);     
    		$query = $this->db->get('setoran_modal'); //<-----cek dulu apakah ada sudah ada kode di tabel.    
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
    		$kodejadi = "STR-".$kodemax;     
    	return $kodejadi;  
    }

	public function InsertSetoran($nominal)
	{
		$transaksi = "INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$setoran   = "INSERT INTO `setoran_modal`(`id_transaksi`, `tanggal_transaksi`, `total`) VALUES (?,?,?)";
		$jurnal    = "INSERT INTO `jurnal`(`no_akun`, `id_transaksi`, `tanggal`, `posisi_db_cr`, `nominal`) VALUES (?,?,?,?,?)";
		$kode      = $this->KodeSetoran();

		$this->db->trans_begin();
		$this->db->query($transaksi, array($kode, date('Y-m-d'), $nominal));
		$this->db->query($setoran, array($kode, date('Y-m-d'), $nominal));
		$this->db->query($jurnal, array('111', $kode, date('Y-m-d'), 'Debet', $nominal));
		$this->db->query($jurnal, array('311', $kode, date('Y-m-d'), 'Kredit', $nominal));
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