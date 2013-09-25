<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		//echo '-------------------------'	;
		//Phpfox::getService('unittest.module.musicsharing.process')->forceSettingUpTestingData();
	}

	public function test1() {
		$this->assertEquals(1, 1);
	}	

	public function tearDown()
	{
//		Phpfox::getService('unittest.module.musicsharing.process')->forceSettingUpTestingData();
	}

}

?>
