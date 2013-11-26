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
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

	public function test1() {
		$this->assertEquals(1, 1);
	}	

	public function tearDown()
	{
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

}

?>
