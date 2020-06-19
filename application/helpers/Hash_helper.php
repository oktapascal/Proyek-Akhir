<?php
	function sandi_hash($password)
	{
		$pass_hash = password_hash($password, PASSWORD_DEFAULT);
		return $pass_hash;
	}

	function sandi_verif($string, $sandi_hash)
	{
		$pass_verify = password_verify($string, $sandi_hash);
		return $pass_verify;
	}
?>