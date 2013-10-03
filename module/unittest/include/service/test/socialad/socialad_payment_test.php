<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Payment_Test extends PHPUnit_Framework_TestCase {

	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

	public function testGetPaypalPaymentUrl() {

		$aVals = array( 
			"amount" => 100,
			"currency" => "USD",
			"return_url" => "http://google.com",
			"transaction_id" => 1,
			"method_id" => Phpfox::getService("socialad.helper")->getConst("transaction.method.paypal")
		);
		$sUrl = Phpfox::getService('socialad.payment')->getPaymentUrl($aVals);
		$this->assertTrue($sUrl ? true : false);


	}	

	public function testGet2CheckoutPaymentUrl() {
		$aVals = array( 
			"amount" => 100,
			"currency" => "USD",
			"return_url" => "http://google.com",
			"transaction_id" => 1,
			"method_id" => Phpfox::getService("socialad.helper")->getConst("transaction.method.2checkout")
		);
		$sUrl = Phpfox::getService('socialad.payment')->getPaymentUrl($aVals);
		$this->assertTrue($sUrl ? true : false);

	}	

	public function testTransactionInitialized() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_click' => 100,
			'package_impression' => 100,
			'package_day' => 100,
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

		$aTransaction = Phpfox::getService('socialad.payment')->getTransactionById($aResult['transaction_id']);

		$this->assertEquals($aTransaction['transaction_status_id'], Phpfox::getService('socialad.helper')->getConst("transaction.status.initialized"));

	}	

	public function testApiCallbackPaypal() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_click' => 100,
			'package_impression' => 100,
			'package_day' => 100,
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

		$aVals = array(
			'gateway' => 'paypal',
			'status' => 'completed',
			'total_paid' => 100,
			'custom' => $iTransactionId
		);

		Phpfox::callback('socialad.paymentApiCallback', $aVals);
		$aTransaction = Phpfox::getService('socialad.payment')->getTransactionById($iTransactionId);
		$this->assertEquals($aTransaction['transaction_status_id'], Phpfox::getService('socialad.helper')->getConst("transaction.status.completed"));

	}
	public function testApiCallback2Checkout() {
		$aPackageVals = array(
			'package_name' => 'Test',
			'package_description' => 'Test des',
			'package_price' => 100,
			'package_click' => 100,
			'package_impression' => 100,
			'package_day' => 100,
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

		$aVals = array(
			'gateway' => 'paypal',
			'status' => 'pending',
			'total_paid' => 100,
			'custom' => $iTransactionId
		);

		Phpfox::callback('socialad.paymentApiCallback', $aVals);
		$aTransaction = Phpfox::getService('socialad.payment')->getTransactionById($iTransactionId);
		$this->assertEquals($aTransaction['transaction_status_id'], Phpfox::getService('socialad.helper')->getConst("transaction.status.pending"));

	}
	public function tearDown()
	{
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}

}

?>
