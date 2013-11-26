<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Package_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

	public function testCreateAdPackage() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_is_free' => 1,
		);

		$expect = 0; // expect greater than 0 

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$this->assertGreaterThan($expect, $iPackageId);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertFalse(!$aPackage);
		$this->assertEquals(1, $aPackage['package_is_active']);
		$this->assertEquals(Phpfox::getService('socialad.helper')->getConst('package.benefit.click'), $aPackage['package_benefit_type_id']);
		$this->assertEquals(1, $aPackage['package_is_free']);

	}

	public function testCreateAdPackageWithItemType() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_allow_item_type' => array( 
				1,2,3,4	
			)
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertEquals($expect = $aPackageVals['package_allow_item_type'], $actual = $aPackage['package_allow_item_type']);

	}

	public function testCreateAdPackageWithItemTypeAndModuleAndAdTypeAndBlockNull() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertNull($actual = $aPackage['package_allow_item_type']);
		$this->assertNull($actual = $aPackage['package_allow_ad_type']);
		$this->assertNull($actual = $aPackage['package_allow_block']);
		$this->assertNull($actual = $aPackage['package_allow_module']);

	}

	public function testEditAdPackageWithItemTypeAndModuleAndAdTypeAndBlockNull() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_benefit_number' => 100,
			'package_price' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_allow_item_type' => array( 
				1,2,3
			),
			'package_allow_block' => array( 
				1,2,3
			),
			'package_allow_ad_type' => array( 
				1,2,3
			),
			'package_allow_module' => array( 
				'photo', 'blog'
			),
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackageVals = array(
			'package_id' => $iPackageId, // add package_id to make it become edit
			'package_name' => 'Test',
			'package_price' => 100,
			'package_description' => 'Test des',
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertNull($actual = $aPackage['package_allow_item_type']);
		$this->assertNull($actual = $aPackage['package_allow_ad_type']);
		$this->assertNull($actual = $aPackage['package_allow_block']);
		$this->assertNull($actual = $aPackage['package_allow_module']);

	}
	public function testCreateAdPackageWithBlock() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_allow_block' => array( 
				1,2,3,4	
			)
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertEquals($expect = $aPackageVals['package_allow_block'], $actual = $aPackage['package_allow_block']);

	}

	public function testCreateAdPackageWithModules() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_allow_module' => array( 
				'contest', 'photo', 'music'
			)
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertEquals($expect = $aPackageVals['package_allow_module'], $actual = $aPackage['package_allow_module']);

	}

	public function testCreateAdPackageWithAdTypes() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_allow_ad_type' => array( 
				'contest', 'photo', 'music'
			)
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);

		$this->assertEquals($expect = $aPackageVals['package_allow_ad_type'], $actual = $aPackage['package_allow_ad_type']);

	}

	public function testGetAdTypeFromPackage() {
		// Create a package

		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_allow_ad_type' => array( 
				1,2,3,4
			),
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdTypes = Phpfox::getService('socialad.package')->getAdTypesOfPackage($iPackageId);

		$this->assertEquals(1, $aAdTypes[0]['id']);
	}	

	public function testCreateUnlimitedPackage() {
		// Create a package

		$aPackageVals = array(
			'package_is_unlimited' => 1, // This is bad, inferred from layout form
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
		);

		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aPackage = Phpfox::getService('socialad.package')->getPackageById($iPackageId);
		$this->assertEquals(1, $aPackage['package_is_unlimited']);
	}	

	public function tearDown()
	{
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

}

?>
