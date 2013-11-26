<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Helper_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

	public function testGetTransactionStatusId() {
		$expect = 1;
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status.initialized', 'id');
		$this->assertEquals($expect, $actual);

		$expect = 2;
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status.expired', 'id');
		$this->assertEquals($expect, $actual);

		$expect = 5;
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status.canceled', 'id');
		$this->assertEquals($expect, $actual);
	}	

	public function testGetTransactionStatusPhrase() {
		$expect = 'Initialized';
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status.initialized', 'phrase');
		$this->assertEquals($expect, $actual);

		$expect = 'Expired';
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status.expired', 'phrase');
		$this->assertEquals($expect, $actual);

		$expect = 'Canceled';
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status.canceled', 'phrase');
		$this->assertEquals($expect, $actual);
	}	

	public function testGetTransactionStatus() {
		$expect = Phpfox::getService("socialad.payment")->getAllTransactionStatus();
		$actual = Phpfox::getService('socialad.helper')->getConst('transaction.status', 'id');
		$this->assertEquals($expect, $actual);
	}	

	public function testGetBenefitTypeId() {
		$expect = 1;
		$actual = Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id');
		$this->assertEquals($expect, $actual);

		$expect = 2;
		$actual = Phpfox::getService('socialad.helper')->getConst('package.benefit.impression', 'id');
		$this->assertEquals($expect, $actual);

		$expect = 3;
		$actual = Phpfox::getService('socialad.helper')->getConst('package.benefit.day', 'id');
		$this->assertEquals($expect, $actual);
	}	
	public function tearDown()
	{
	}

}

?>
