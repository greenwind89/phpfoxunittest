<?php 

class Unittest_Service_Helper extends Phpfox_Service {
	public function getSalt($iTotal = 3) { 
		$sSalt = '';
		for ($i = 0; $i < $iTotal; $i++)
		{
			$sSalt .= chr(rand(33, 91));
		}

		return $sSalt;
	}
}
