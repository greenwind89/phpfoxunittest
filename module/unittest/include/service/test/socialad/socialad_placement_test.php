<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Placement_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	// to remove ads after testing
	private $_aAdId = array (
		1989, 2000
	);

    private function _removeAllTestAds() {
		foreach($this->_aAdId as $iId) {
			Phpfox::getService('unittest.test.socialad')->removeAd($iId);
		}
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->removeTestUser();

		// TRUNCATE related tables 
		$aTable = array(
			Phpfox::getT('socialad_ad'),
			Phpfox::getT('socialad_image'),
			Phpfox::getT('socialad_ad_audience_user_group'),
			Phpfox::getT('socialad_ad_audience_location'),
			Phpfox::getT('socialad_ad_audience_language')
		);

		foreach($aTable as $sTable) {
			Phpfox::getService('unittest.db')->truncateTable($sTable);
		}


	}

	public function testGetAdsToDisplayOnBlockByBlock() { 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, 
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // male

		);
		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3

		);
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 5;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);

	}

	public function testGetAdsToDisplayOnBlockByBlockNotIn() { 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, 
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // male

		);
		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 5

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			$aAdVals['ad_id']
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 5;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);

	}

	public function testGetAdsToDisplayOnBlockByModule() { 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, 
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // male

		);
		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'module' => array(
				'photo', 'fundraising'
			)

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$sModuleId = 'contest';
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $sModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);

	}
	public function tearDown()
	{
		Phpfox::getService('unittest.test.socialad')->removeTestUser();

		foreach($this->_aAdId as $iId) {
			Phpfox::getService('unittest.test.socialad')->removeAd($iId);
		}

		Phpfox::getService('socialad.user')->resetUser();
	}

}

?>
