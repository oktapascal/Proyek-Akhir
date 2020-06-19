<?php
class Data_Model extends CI_Model
{
	public function jumlah_data($table)
		{
			//Menghtiung Jumlah Row Pada Tabel//
			$query  = $this->db->get($table);
			$jumlah = $query->num_rows(); //Menghtiung Jumlah Row//
			return $jumlah;
		}

	public function jumlah_data_kondisi($table, $where)
		{
			//Menghtiung Jumlah Row Pada Tabel//
			$query  = $this->db->where('status', $where);
			$query  = $this->db->get($table);
			$jumlah = $query->num_rows(); //Menghtiung Jumlah Row//
			return $jumlah;
		}

	public function total_transaksi($table)
		{
			$this->db->select('SUM(total) as total');
			$this->db->from($table);
			$query = $this->db->get();
			return $query->row()->total;
		}

	public function jumlah_pegawai($kondisi)
		{
			$this->db->select('pegawai.id_pegawai');
			$this->db->from('pegawai');
			$this->db->join('detail_tarif_posisi', 'pegawai.id_pegawai=detail_tarif_posisi.id_pegawai');
			$this->db->join('tarif_posisi', 'detail_tarif_posisi.id_posisi=tarif_posisi.id_posisi');
			$this->db->where('tarif_posisi.status', $kondisi);
			$query = $this->db->get();
			$jumlah = $query->num_rows();
			return $jumlah;
		}
}
?>