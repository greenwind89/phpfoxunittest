<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		MinhTA
 * @package  		Module_socialad
 */

class Unittest_Service_Db extends Phpfox_Service
{
	public function checkTableExist($table) {
		$query = "SHOW TABLES LIKE '" . $sTable . "'";
		$result = $this->database()->query($query);
		var_dump($result);

		return true;
	}

	public function truncateTable($sTable) {
		$query = " TRUNCATE TABLE $sTable";
		$this->database()->query($query);

	}
}



