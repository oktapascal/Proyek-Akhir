<?php
class Presensi_Model extends CI_Model
{
	var $tabel  = "presensi";
	var $select = array("no_presensi, nama_pegawai, tanggal, status");
	var $search = array("nama_pegawai");
	var $order  = array('no_presensi'=>'asc');
	var $tabel_jumlah  = "presensi";
	var $select_jumlah = array("COUNT(status) as jumlah_kehadiran, pegawai.id_pegawai, nama_pegawai");
	var $search_jumlah = array("id_pegawai", "nama_pegawai");
	var $order_jumlah  = array('id_pegawai'=>'asc');

	private function GetPresensi()
	{
		$this->db->from($this->tabel);
		$this->db->join('pegawai', 'presensi.id_pegawai=pegawai.id_pegawai');
		$this->db->where('tanggal', date('Y-m-d', strtotime($this->session->userdata('tanggal_presensi'))));

		$presensi = 0;

		foreach($this->search as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($presensi==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search) - 1 == $jumlah)
				{
					$this->db->group_end();
				}	
			}
			$presensi++;
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

	public function getDataTablePresensi()
	{
		$this->getPresensi();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilter()
	{
		$this->getPresensi();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemua()
	{
		$this->db->select($this->select);
		$this->db->from($this->tabel);
		$this->db->join('pegawai', 'presensi.id_pegawai=pegawai.id_pegawai');
		$this->db->where('tanggal', date('Y-m-d', strtotime($this->session->userdata('tanggal_presensi'))));
		return $this->db->count_all_results();
	}

	private function GetJumlahPresensi()
	{
		$this->db->select($this->select_jumlah);
		$this->db->from($this->tabel_jumlah);
		$this->db->join('pegawai', 'presensi.id_pegawai=pegawai.id_pegawai');
		$this->db->where('MONTH(tanggal)', $this->session->userdata('bulan_jumlah_presensi'));
		$this->db->where('YEAR(tanggal)', $this->session->userdata('tahun_jumlah_presensi'));
		$this->db->where('status', "Hadir");
		$this->db->group_by('pegawai.id_pegawai');

		$jumlah = 0;

		foreach($this->search_jumlah as $data)
		{
			if($_GET['search']['value']) //Jika datatable mengirimkan pencarian dengan metode GET//
			{
				if($jumlah==0)
				{
					$this->db->group_start();
					$this->db->like($data, $_GET['search']['value']);
				}
				else
				{
					$this->db->or_like($data, $_GET['search']['value']);
				}
				if(count($this->search_jumlah) - 1 == $jumlah)
				{
					$this->db->group_end();
				}	
			}
			$jumlah++;
		}
		if(isset($_GET['order']))
		{
			$this->db->order_by($this->select_jumlah[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		}
		else if(isset($this->order_jumlah))
		{
			$order_jumlah = $this->order_jumlah;
			$this->db->order_by(key($order_jumlah), $order_jumlah[key($order_jumlah)]);
		}
	}

	public function getDataTableJumlahPresensi()
	{
		$this->GetJumlahPresensi();
		if($_GET['length'] != -1)
		{
			$this->db->limit($_GET['length'], $_GET['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function HitungFilterJumlahPresensi()
	{
		$this->GetJumlahPresensi();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function HitungSemuaJumlahPresensi()
	{
		$this->db->select($this->select_jumlah);
		$this->db->from($this->tabel_jumlah);
		$this->db->join('pegawai', 'presensi.id_pegawai=pegawai.id_pegawai');
		$this->db->where('MONTH(tanggal)', date('m', strtotime($this->session->userdata('tanggal_jumlah_presensi'))));
		$this->db->where('YEAR(tanggal)', date('Y',strtotime($this->session->userdata('tanggal_jumlah_presensi'))));
		$this->db->where('status', "Hadir");
		$this->db->group_by('pegawai.id_pegawai');
		return $this->db->count_all_results();
	}

	public function SubmitPresensi($id)
	{
		$data = array('id_pegawai'=>$id,
					  'tanggal'=>date('Y-m-d'),
					  'status'=>'Hadir'
					);
		$this->db->insert('presensi', $data);
	}
}
?>