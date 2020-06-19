<?php
//Hilangkan Selain Angka//
	function remove_string($data)
	{
		$hasil = preg_replace('/[^0-9]/','',$data);
		return $hasil;
	}
?>