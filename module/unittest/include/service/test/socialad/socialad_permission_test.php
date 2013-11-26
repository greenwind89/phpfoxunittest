<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Permission_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
		Phpfox::getService('socialad.ad')->setNeverCache(true);
	}


	public function testCanConfirmPayLaterRequestWithInitiliazedPaypalRequest() {
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

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => $iPackageId

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$iMethodId = Phpfox::getService("socialad.helper")->getConst("transaction.method.paypal", "id");
		$aResult = Phpfox::getService('socialad.payment')->startPayment($iAdId, $iMethodId);

		$iTransactionId = $aResult['transaction_id'];

		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(true);
		$actual = Phpfox::getService('socialad.permission')->canConfirmPayLaterTransaction($iTransactionId);
		$this->assertEquals($expect, $actual);


	}

	public function testCanConfirmPayLaterRequestWithInitiliazedPaylaterRequest() {
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

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => $iPackageId

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$iMethodId = Phpfox::getService("socialad.helper")->getConst("transaction.method.paylater", "id");
		$aResult = Phpfox::getService('socialad.payment')->startPayment($iAdId, $iMethodId);

		$iTransactionId = $aResult['transaction_id'];

		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(true);
		$actual = Phpfox::getService('socialad.permission')->canConfirmPayLaterTransaction($iTransactionId);
		$this->assertEquals($expect, $actual);

	}


	public function testCanCancelPayLaterRequest() {
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

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => $iPackageId,
			'ad_user_id' => 1111

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$iMethodId = Phpfox::getService("socialad.helper")->getConst("transaction.method.paylater", "id");
		$aResult = Phpfox::getService('socialad.payment')->startPayment($iAdId, $iMethodId);

		$iTransactionId = $aResult['transaction_id'];

		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		$actual = Phpfox::getService('socialad.permission')->canCancelPayLaterRequest($iTransactionId);
		$this->assertEquals($actual, $expect);

		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canCancelPayLaterRequest($iTransactionId);
		$this->assertEquals($actual, $expect);


	}


	public function testCanEditAd() {
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

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => $iPackageId,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.running')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		$actual = Phpfox::getService('socialad.permission')->canEditAd($iAdId);
		$this->assertEquals($expect, $actual);


		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canEditAd($iAdId);
		$this->assertEquals($expect, $actual);
	}

	public function testCanDeleteAd() {
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

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => $iPackageId,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.running')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is running, not owner, not admin -> false
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(false);
		$actual = Phpfox::getService('socialad.permission')->canDeleteAd($iAdId);
		$this->assertEquals($expect, $actual);


		// ad is running, not owner, admin -> true
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(true);
		$actual = Phpfox::getService('socialad.permission')->canDeleteAd($iAdId);
		$this->assertEquals($expect, $actual);

		// ad is running, owner, not admin -> true
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(true);
		$actual = Phpfox::getService('socialad.permission')->canDeleteAd($iAdId);
		$this->assertEquals($expect, $actual);


	}

	public function testCanDeleteAdInDeletedStatus() {

		$aAdVals = array(
			'ad_id' => 1909,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.deleted')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is running, not owner, not admin -> false
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(true);
		$actual = Phpfox::getService('socialad.permission')->canDeleteAd($iAdId);
		$this->assertEquals($expect, $actual);

	}

	public function testCanPlaceOrderRunningAd() {

		$aAdVals = array(
			'ad_id' => 1989,
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.running')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is running, owner -> false
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canPlaceOrderAd($iAdId);
		$this->assertEquals($expect, $actual);

	}

	public function testCanPlaceOrderUnpaidAd() {

		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.unpaid')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is unpaid, owner -> true
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canPlaceOrderAd($iAdId);
		$this->assertEquals($expect, $actual);

		// ad is unpaid, not owner -> true
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		$actual = Phpfox::getService('socialad.permission')->canPlaceOrderAd($iAdId);
		$this->assertEquals($expect, $actual);

	}

	public function testCanPauseAd() {
		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.running')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is running, owner -> true
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canPauseAd($iAdId);
		$this->assertEquals($expect, $actual);

		// ad is running, not owner -> true
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		$actual = Phpfox::getService('socialad.permission')->canPauseAd($iAdId);
		$this->assertEquals($expect, $actual);

		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.unpaid')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is unpaid, owner -> false
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canPauseAd($iAdId);
		$this->assertEquals($expect, $actual);
	}

	public function testCanResumeAd() {
		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.paused')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is paused, owner -> true
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canResumeAd($iAdId);
		$this->assertEquals($expect, $actual);

		// ad is paused, not owner -> false
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		$actual = Phpfox::getService('socialad.permission')->canResumeAd($iAdId);
		$this->assertEquals($expect, $actual);

		//ad is unpaid
		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.unpaid')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		// ad is unpaid, owner -> false
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId($aAdVals['ad_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canResumeAd($iAdId);
		$this->assertEquals($expect, $actual);
	}

	public function testCanDenyApproveAd() {
		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.pending')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setCanDenyApproveAd(true);
		$actual = Phpfox::getService('socialad.permission')->canDenyApproveAd($iAdId);
		$this->assertEquals($expect, $actual);


		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setCanDenyApproveAd(false);
		$actual = Phpfox::getService('socialad.permission')->canDenyApproveAd($iAdId);
		$this->assertEquals($expect, $actual);

		//ad is unpaid
		$aAdVals = array(
			'audience_gender' => 1,
			'placement_block_id' => 3,
			'ad_package_id' => 1,
			'ad_user_id' => 1111,
			'ad_status' => Phpfox::getService('socialad.helper')->getConst('ad.status.unpaid')

		);
		$iAdId = Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);

		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setCanDenyApproveAd(true);
		$actual = Phpfox::getService('socialad.permission')->canDenyApproveAd($iAdId);
		$this->assertEquals($expect, $actual);
	}

	public function testCanEditCampaign() {

		$aCampaignVal = array(
			'campaign_user_id' => 1111,
			'campaign_status' => Phpfox::getService('socialad.helper')->getConst('campaign.status.active')
		);
		$iCampaignId = Phpfox::getService('unittest.test.socialad')->insertTestCampaign($aCampaignVal);

		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		$actual = Phpfox::getService('socialad.permission')->canEditCampaign($iCampaignId);
		$this->assertEquals($expect, $actual);


		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aCampaignVal['campaign_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canEditCampaign($iCampaignId);
		$this->assertEquals($expect, $actual);

		// dleted campaign
		$aCampaignVal = array(
			'campaign_user_id' => 1111,
			'campaign_status' => Phpfox::getService('socialad.helper')->getConst('campaign.status.deleted')
		);
		$iCampaignId = Phpfox::getService('unittest.test.socialad')->insertTestCampaign($aCampaignVal);

		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId($aCampaignVal['campaign_user_id']);
		$actual = Phpfox::getService('socialad.permission')->canEditCampaign($iCampaignId);
		$this->assertEquals($expect, $actual);
	}

	public function testCanDeleteCampaign() {

		$aCampaignVal = array(
			'campaign_user_id' => 1111,
			'campaign_status' => Phpfox::getService('socialad.helper')->getConst('campaign.status.active')
		);
		$iCampaignId = Phpfox::getService('unittest.test.socialad')->insertTestCampaign($aCampaignVal);

		// not admin, not owner -> cannot delete
		$expect = false;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(false);
		$actual = Phpfox::getService('socialad.permission')->canDeleteCampaign($iCampaignId);
		$this->assertEquals($expect, $actual);


		// admin, not owner -> can delete
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId(1);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(true);
		$actual = Phpfox::getService('socialad.permission')->canDeleteCampaign($iCampaignId);
		$this->assertEquals($expect, $actual);

		// owner, not admin -> can delete
		$expect = true;
		Phpfox::getService('unittest.test.socialad')->setUserId($aCampaignVal['campaign_user_id']);
		Phpfox::getService('unittest.test.socialad')->setIsAdmin(false);
		$actual = Phpfox::getService('socialad.permission')->canDeleteCampaign($iCampaignId);
		$this->assertEquals($expect, $actual);
	}

	public function tearDown() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

}

?>
