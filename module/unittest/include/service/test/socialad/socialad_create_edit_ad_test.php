<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Create_Edit_Ad_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}


	public function testCreateNewAdFromPaidPackageAndBenfitClick() {
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

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			'ad_package_id' => $iPackageId,
			'ad_number_of_package' => 3
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals);
		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		// status
		$this->assertEquals($aAd['ad_status'], Phpfox::getService('socialad.helper')->getConst("ad.status.draft"));

		//total price 
		$this->assertEquals($aAd['ad_total_price'], $aPackageVals['package_price'] * $aAdVals['ad_number_of_package']);
		$this->assertEquals($expected = $aPackageVals['package_benefit_number'] * $aAdVals['ad_number_of_package'], $actual = $aAd['ad_remain_number']);
	}

	public function testPackageFreeAndApproveOnSoPlaceOrderToMakeAdChangeToPending() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 0,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_is_free' => 1,
		);


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			// later
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(true);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);


		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.pending"), $actual = $aAd['ad_status']  );
	}

	public function testApproveIsOnButPackageIsNotFreeSoPlaceOrderMakesAdChangeToUnpaid() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_is_free' => 0,
		);


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			// later
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(true);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.unpaid"), $actual = $aAd['ad_status']  );


	}

	public function testApproveIsOffPackageIsFreeSoSubmittingMakesAdChangeToRunning() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_is_free' => 1,
		);


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			// later
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(false);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.running"), $actual = $aAd['ad_status']  );


	}

	public function testApproveIsOnAdIsUnpaidSoAfterFinishPaymentAdChangeToPending() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_is_free' => 0,
		);


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			// later
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(true);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// ad is unpaid

		// pay by paypal
		$iMethodId = Phpfox::getService("socialad.helper")->getConst("transaction.method.paypal", "id");
		$aResult = Phpfox::getService('socialad.payment')->startPayment($iAdId, $iMethodId);

		$iTransactionId = $aResult['transaction_id'];

		$aVals = array(
			'gateway' => 'paypal',
			'status' => 'completed',
			'total_paid' => 100,
			'custom' => $iTransactionId
		);

		// simulate paypal IPN return till callback 
		Phpfox::callback('socialad.paymentApiCallback', $aVals);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.pending"), $actual = $aAd['ad_status']  );


	}

	public function testApproveIsOffAdIsUnpaidSoAfterFinishPaymentAdChangeToRunning() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_benefit_number' => 100,
			'package_benefit_type_id' => Phpfox::getService('socialad.helper')->getConst('package.benefit.click', 'id'),
			'package_currency' => 'USD',
			'package_last_edited_time' => PHPFOX_TIME, 
			'package_is_active' => 1,
			'package_is_free' => 0,
		);


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			// later
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(false);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// ad is unpaid

		// pay by paypal
		$iMethodId = Phpfox::getService("socialad.helper")->getConst("transaction.method.paypal", "id");
		$aResult = Phpfox::getService('socialad.payment')->startPayment($iAdId, $iMethodId);

		$iTransactionId = $aResult['transaction_id'];

		$aVals = array(
			'gateway' => 'paypal',
			'status' => 'completed',
			'total_paid' => 100,
			'custom' => $iTransactionId
		);

		// simulate paypal IPN return till callback 
		Phpfox::callback('socialad.paymentApiCallback', $aVals);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.running"), $actual = $aAd['ad_status']  );


	}
	public function testAdFromPendingToDenied() {
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


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			// later
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(true);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// -> pending

		Phpfox::getService('socialad.ad.process')->denyAd($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.denied"), $actual = $aAd['ad_status']  );

	}

	public function testAdFromPendingToApprovedAndStartTimeHasNotCome() {
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


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			 "ad_expect_start_time_year" => "2014" ,
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(true);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// -> pending

		Phpfox::getService('socialad.ad.process')->approveAd($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.approved"), $actual = $aAd['ad_status']  );


	}

	public function testAdFromPendingToApprovedAndNoStartTime() {
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


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		// turn on pending approval for current user
		Phpfox::getService('unittest.test.socialad')->setNeedApprove(true);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// -> pending

		Phpfox::getService('socialad.ad.process')->approveAd($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.running"), $actual = $aAd['ad_status']  );


	}

	public function testPauseAd() {
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


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		Phpfox::getService('unittest.test.socialad')->setNeedApprove(false);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// -> running

		Phpfox::getService('socialad.ad.process')->pauseAd($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.paused"), $actual = $aAd['ad_status']  );


	}

	public function testResumeAd() {
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


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		Phpfox::getService('unittest.test.socialad')->setNeedApprove(false);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// -> running

		Phpfox::getService('socialad.ad.process')->pauseAd($iAdId);
		Phpfox::getService('socialad.ad.process')->resumeAd($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.running"), $actual = $aAd['ad_status']  );



	}

	public function testDeleteAd() {
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


		$iPackageId = Phpfox::getService('socialad.package.process')->handleSubmitForm($aPackageVals);

		$aAdVals = array_merge( Phpfox::getService('unittest.test.socialad')->getAdFormData(), array (
			)
		);
		$iAdId = Phpfox::getService('socialad.ad.process')->handleSubmitForm($aAdVals); // ad is at draft

		Phpfox::getService('unittest.test.socialad')->setNeedApprove(false);

		$iAdNextStatusId = Phpfox::getService('socialad.ad.process')->placeOrder($iAdId);
		// -> running

		Phpfox::getService('socialad.ad.process')->deleteAd($iAdId);

		$aAd = Phpfox::getService('socialad.ad')->getAdById($iAdId);

		$this->assertEquals($expect = Phpfox::getService('socialad.helper')->getConst("ad.status.deleted"), $actual = $aAd['ad_status']  );


	}



	public function tearDown()
	{
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

}

?>
