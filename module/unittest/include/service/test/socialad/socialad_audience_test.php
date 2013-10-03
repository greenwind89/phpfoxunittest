<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Audience_Test extends PHPUnit_Framework_TestCase {

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

	public function testGetAdsToDisplayOnBlockByGender() { 
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

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			$aAdVals['ad_id']
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);

	}

	public function testGetAdsToDisplayOnBlockByGenderAny() {
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

		// test with any gender

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 0,

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			$aAdVals['ad_id']
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByGenderNoneOfUser() {
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, 
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 0 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			1989
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByDifferentGender() {
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, 
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 2,

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByAge() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, 
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 0 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'audience_age_min' => 15,
			'audience_age_max' => 20,

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			$aAdVals['ad_id']
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByAgeOverAdLimit() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1990, // 23
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 2,
			'audience_age_min' => 15,
			'audience_age_max' => 20,

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByLocation() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, // 23
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'location' => array( 'VN')

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			1989
			
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByLocationMultiple() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, // 23
			'country_iso' => 'VN',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'location' => array( 'VN', 'US')

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			1989
			
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByLocationMultipleNotIn() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, // 23
			'country_iso' => 'FR',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'location' => array( 'VN', 'US')

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array();

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByLanguage() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => NORMAL_USER_ID,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, // 23
			'country_iso' => 'FR',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'language' => array( 'en', 'vn')

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			$aAdVals['ad_id']
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByUsergroup() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => 3,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, // 23
			'country_iso' => 'FR',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'language' => array( 'en', 'vn'),
			'user_group' => array(
				3,4
			)

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
			$aAdVals['ad_id']
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
		);
		$actual = Phpfox::getService('socialad.ad')->getToDisplayOnBlock($aQuery);
		$this->assertEquals($expected, $actual);
	}

	public function testGetAdsToDisplayOnBlockByNotInUsergroup() {
		// now is 2003 
		$aVals = array( 
			'user_group_id' => 5,
			'birth_day' => 12,
			'birth_month' => 2,
			'birth_year' => 1995, // 23
			'country_iso' => 'FR',
			'language_id' => 'en', 
			'gender' => 1 // no gender 
		);

		Phpfox::getService('unittest.test.socialad')->insertTestUser($aVals);
		// test with user gender equal 0
		$aAdVals = array(
			'ad_id' => 1989,
			'language' => array( 'en', 'vn'),
			'user_group' => array(
				3,4
			)

		);
		//to remove ads at tear off
		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expected = array(
		);

		$iUserId = Phpfox::getService('unittest.test.socialad')->getTestUserId();
		$iBlockId = 3;
		$iModuleId = null;
		$aQuery = array(
			'user_id' => $iUserId,
			'block_id' => $iBlockId,
			'module_id' => $iModuleId
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
