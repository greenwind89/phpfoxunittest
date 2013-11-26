<?php

require_once 'PHPUnit/Autoload.php';                                                                                                    
require_once 'PHPUnit/Framework/TestCase.php';                                                                                          
require_once "PHPUnit/TextUI/TestRunner.php";                                                                                           
require_once "PHPUnit/Framework/TestSuite.php";                                                                                         
require_once "socialad_testcase.php";
require_once "socialad_audience_test.php";
require_once "socialad_placement_test.php";
require_once "socialad_package_test.php";
require_once "socialad_create_edit_ad_test.php";
require_once "socialad_payment_test.php";
require_once "socialad_helper_test.php";
require_once "socialad_permission_test.php";

class Unittest_Service_Test_SocialAd_SocialAd extends Phpfox_Service {

	private $_aTestSuites;

	public function __construct() {

		$this->_aTestSuites = array(
			 array( 
				 'id' => 'socialad_test',
				 'description' => 'Test Social Ad Module'
			 ), 
			 array( 
				 'id' => 'socialad_audience_test',
				 'description' => 'Test Audience Of Ads'
			 ), 
			 array( 
				 'id' => 'socialad_placement_test',
				 'description' => 'Test Placement Of Ads'
			 ), 
			 array( 
				 'id' => 'socialad_package_test',
				 'description' => 'Test Creating Packages'
			 ), 
			 array( 
				 'id' => 'socialad_create_edit_ad_test',
				 'description' => 'Test create edit ad '
			 ), 
			 array( 
				 'id' => 'socialad_payment_test',
				 'description' => 'Test payment gateway and services'
			 ), 
			 array( 
				 'id' => 'socialad_helper_test',
				 'description' => 'Test Helper functions'
			 ), 

			 array( 
				 'id' => 'socialad_permission_test',
				 'description' => 'Test Permission Functions'
			 ), 

		 );
	}

	public function generateTestData() {

	}

	public function truncateAllRelatedTables () {
		// TRUNCATE related tables 
		$aTable = array(
			Phpfox::getT('socialad_ad'),
			Phpfox::getT('socialad_ad_track'),
			Phpfox::getT('socialad_image'),
			Phpfox::getT('socialad_ad_audience_user_group'),
			Phpfox::getT('socialad_ad_audience_location'),
			Phpfox::getT('socialad_ad_audience_language'),
			Phpfox::getT('socialad_transaction'),
			Phpfox::getT('socialad_package'),
			Phpfox::getT('socialad_ad_statistic'),
			Phpfox::getT('socialad_ad_track'),
			Phpfox::getT('socialad_campaign'),

		);

		foreach($aTable as $sTable) {
			Phpfox::getService('unittest.db')->truncateTable($sTable);
		}

	}

	public function getTestSuites() {
		return $this->_aTestSuites;
	}

	public function test($aTestSuite) {
		$test_suites = new PHPUnit_Framework_TestSuite();

		foreach($aTestSuite as $sTestSuite) {
			$test_suites->addTestSuite($sTestSuite);
		}

		$arguments = array();
		
		//$arguments['testdoxHTMLFile'] = PHPFOX_DIR . 'module/unittest/include/service/test/socialad/results.html';
		PHPUnit_TextUI_TestRunner::run($test_suites, $arguments);
	}


	public function createDataForAdTable() {
		$iTs = 1379473602;

		for( $i = 10000; $i < 11000; $i++) {
			$aInsert = array(
				'ad_id' => $i,
				'ad_title' => 'Title ' . $i,
				'ad_text' => 'Text minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta minhta ', 
				'ad_item_id' => rand(1,5),
				'ad_item_type' => rand(1,4),
				'ad_last_edited_time' => $iTs + $i * 10,
				'ad_type' => rand(1,3),
				'ad_campaign_id' => 1 
			);
			$this->database()->insert(Phpfox::getT('socialad_ad'), $aInsert);
		}
	}

	public function createDataForAudience() {
		

	}

	public function runTestCase() {

	}

	private $_iTestUserId = 2507;

	public function getTestUserId() {
		return $this->_iTestUserId;
	}

	public function removeTestUser() {
		$this->database()->delete(Phpfox::getT('user'), 'user_id = ' . $this->_iTestUserId);

	}

	public function updateAdStatus($iAdId, $iStatus) {

		$this->database()->update(Phpfox::getT('socialad_ad'), array('ad_status' => $iStatus), 'ad_id = '. $iAdId);
	}

	public function insertTestCampaign($aVals) {
		$aInsert = array_merge(array(
			'campaign_name' => 'test campaign',
			'campaign_status' => 1,
			'campaign_user_id' => 1,
			'campaign_timestamp' => PHPFOX_TIME
		), $aVals);

		$iId = $this->database()->insert(Phpfox::getT('socialad_campaign'), $aInsert);
		return $iId;
	}

	public function insertTestUser($aVals) {


		$iUserGroupId = $aVals['user_group_id'];
		$iBirthDay = $aVals['birth_day'];
		$iBirthMonth = $aVals['birth_month'];
		$iBirthYear = $aVals['birth_year'];
		$sCountryIso = $aVals['country_iso']; // VN, US
		$iLanguageId = $aVals['language_id']; // fr, en
		$iGender = $aVals['gender']; // 1 for male, 2 for female

		$sSalt = Phpfox::getService('unittest.helper')->getSalt();
		$iId = $this->_iTestUserId;
		$aInsert = array(
			'user_id' => $iId,
			'user_group_id' => $iUserGroupId,
			'full_name' => 'minhta test' . $iId,
			'password' => Phpfox::getLib('hash')->setHash('123456', $sSalt),
			'password_salt' => $sSalt,
			'email' => 'minhta' . $iId . 'dump@younetco.com',
			'joined' => PHPFOX_TIME,
			'gender' => $iGender, // this field is used to target audience by gender
			'birthday' => Phpfox::getService('user')->buildAge($iBirthDay, $iBirthMonth, $iBirthYear) , // this field used to infer age, ex: 12021995
			'birthday_search' =>  Phpfox::getLib('date')->mktime(0, 0, 0, $iBirthMonth, $iBirthDay, $iBirthYear), // this field used to sort by birth day, or search, saved as time stamp
			'country_iso' => $sCountryIso,
			'language_id' => $iLanguageId,
			'time_zone' => NULL,
		);

		$this->database()->insert(Phpfox::getT('user'), $aInsert);

	}

	public function insertTestAd($aVals) {

		if(isset($aVals['location'])) {
			foreach($aVals['location'] as $sLocation) {
				$aLocationInsert = array( 
					'ad_id' => $aVals['ad_id'],
					'location_id' => $sLocation
				);

				$this->database()->insert(Phpfox::getT('socialad_ad_audience_location'), $aLocationInsert);
			}
			unset($aVals['location']);
		}

		if(isset($aVals['language'])) {
			foreach($aVals['language'] as $sLang) {
				$aLanguageInsert = array( 
					'ad_id' => $aVals['ad_id'],
					'language_id' => $sLang
				);

				$this->database()->insert(Phpfox::getT('socialad_ad_audience_language'), $aLanguageInsert);
			}
			unset($aVals['language']);
		}

		if(isset($aVals['user_group'])) {
			foreach($aVals['user_group'] as $iUserGroupId) {
				$aUsergroupInsert = array( 
					'ad_id' => $aVals['ad_id'],
					'user_group_id' => $iUserGroupId
				);

				$this->database()->insert(Phpfox::getT('socialad_ad_audience_user_group'), $aUsergroupInsert);
			}
			unset($aVals['user_group']);
		}

		if(isset($aVals['module'])) {
			foreach($aVals['module'] as $sModuleId) {
				$aModuleInsert = array( 
					'ad_id' => $aVals['ad_id'],
					'module_id' => $sModuleId
				);

				$this->database()->insert(Phpfox::getT('socialad_ad_placement_module'), $aModuleInsert);
			}
			unset($aVals['module']);
		}
		$aInsert = array_merge(array(
			'ad_title' => 'Test ad title ' ,
			'ad_text' => ' test text',
			'audience_age_min' => 0,
			'audience_gender' => 0,
			'audience_age_max' => 10000,
			'placement_block_id' => 3,
			'ad_status' => 5,
			'ad_user_id' => 1
		), $aVals);

		$iAdId = $this->database()->insert(Phpfox::getT('socialad_ad'), $aInsert);

		return $iAdId;
	}

	public function removeAd($iAdId) {
		$this->database()->delete(Phpfox::getT('socialad_ad'), 'ad_id = ' . $iAdId);
	}

	public function insertTestPackage($aVals) {

		$aInsert = array_merge(array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_click' => 100,
			'package_impression' => 100,
			'package_day' => 100,
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_benefit_type_id' => 1,
		), $aVals);

		$iAdId = $this->database()->insert(Phpfox::getT('socialad_package'), $aInsert);

		return $iPackageId;
	}

	public function getAdFormData() {
		$aData = array( 
			 "image_path" => "2013/10/39fa9be47fbd8cc8f9e6d7e7e1a540d3.jpg" ,
			 "ad_package_id" => "1" ,
			 "ad_id" => "" ,
			 "ad_item_type" => "4" ,
			 "ad_item_id" => "3" ,
			 "ad_external_url" => "" ,
			 "ad_type" => "2" ,
			 "ad_title" => "test 2" ,
			 "ad_text" => "dfgsgd" ,
			 "campaign_id" => "2" ,
			 "campaign_name" => "" ,
			 "is_continuous" => "0" ,
			 "ad_expect_start_time_month" => "10" ,
			 "ad_expect_start_time_day" => "24" ,
			 "ad_expect_start_time_year" => "2013" ,
			 "ad_expect_start_time_hour" => "04" ,
			 "ad_expect_start_time_minute" => "24" ,
			 "ad_expect_end_time_month" => "10" ,
			 "ad_expect_end_time_day" => "31" ,
			 "ad_expect_end_time_year" => "2013" ,
			 "ad_expect_end_time_hour" => "04" ,
			 "ad_expect_end_time_minute" => "24" ,
			 "placement_module_id" => array (
				 0 => "admincp" ) ,
			 "placement_block_id" => "1" ,
			 "audience_location" => array( 
			 0 => "AE" ) ,
			 "audience_gender" => "1" ,
			 "audience_age_min" => "17" ,
			 "audience_age_max" => "18",
			 "ad_number_of_package" => 1
		);

		return $aData;
	}

	private $_bNeedApprove = false;

	public function getNeedApprove() {
		return $this->_bNeedApprove;
	}
	public function setNeedApprove($value) {
		$this->_bNeedApprove = $value;
	}

	private $_bIsAdmin = false;

	public function getIsAdmin() {
		return $this->_bIsAdmin;
	}

	public function setIsAdmin($value) {
		$this->_bIsAdmin = $value;
	}

	private $_iUserId = false;

	public function getUserId() {
		return $this->_iUserId;
	}

	public function setUserId($value) {
		$this->_iUserId = $value;
	}

	private $_bCanDenyApprove = false;

	public function getCanDenyApproveAd() {
		return $this->_bCanDenyApprove;
	}

	public function setCanDenyApproveAd($value) {
		$this->_bCanDenyApprove = $value;
	}
}
