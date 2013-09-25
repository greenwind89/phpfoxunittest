<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
define('YOUNET_IN_UNITTEST', true); 

/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		MinhTA
 * @package  		Module_socialad
 */


class Unittest_Component_Controller_Unittest_Index extends Phpfox_Component 

{
	private $_aResults = array();
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aVals = $this->request()->getArray('val');

		$aResults = $this->runTest();
		$this->template()->assign(array(
			'aResults' => $aResults
		));
	}

	public function runTest() {
		Phpfox::getService('unittest.test.unittest')->testDB();
		return array();
	}


}


