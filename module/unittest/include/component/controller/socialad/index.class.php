<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
define('YOUNET_IN_UNITTEST', true); 

require(PHPFOX_DIR . "module/socialad/yninstall/versions/v3.01.php");
/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		MinhTA
 * @package  		Module_socialad
 */


class Unittest_Component_Controller_Socialad_Index extends Phpfox_Component 

{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aVals = $this->request()->getArray('val');

		$this->testInstall();

		if($aVals) {
			Phpfox::getService('unittest.test.socialad')->test($aVals['test_suite']);

			$sContent = ob_get_contents();
			ob_clean();
			echo(str_replace("\n", "</br>", $sContent));
			ob_end_flush();
			exit;
		}

		$this->template()->assign(array(
			'aTestSuites' => Phpfox::getService('unittest.test.socialad')->getTestSuites()
		));
	}

	public function testConvertDataToChartFormat() {
		$aData = array( 
			array(
				'year' => 1992,
				'month' => 1,
				'day' => 1,
				'total_click' => 11,
				'total_impression' => 15
			), 
			array(
				'year' => 1993,
				'month' => 2,
				'day' => 2,
				'total_click' => 12,
				'total_impression' => 16
			), 
			array(
				'year' => 1993,
				'month' => 1,
				'day' => 1,
				'total_click' => 11,
				'total_impression' => 16
			), 
			array(
				'year' => 1992,
				'month' => 1,
				'day' => 1,
				'total_click' => 11,
				'total_impression' => 15
			), 
			array(
				'year' => 1992,
				'month' => 1,
				'day' => 1,
				'total_click' => 12,
				'total_impression' => 16
			), 
		);

		$result = Phpfox::getService('socialad.helper')->convertStatisticDataIntoTableChartFormat($aData);

		var_dump(json_encode( $result ));

	}
	public function testUpdateImpressionAndClick() {
		Phpfox::getService('socialad.ad.process')->updateImpressionAndClick(1);
	}

	public function testGetTotalImpressionOfAd() {
		$result = Phpfox::getService('socialad.ad.statistic')->getTotalImpressionsOfAd(1);
		var_dump($result);
		$result = Phpfox::getService('socialad.ad.statistic')->getTotalClicksOfAd(1);
		var_dump($result);
	}


	public function testStatisticExist() {
		$iAdId = 1;
		$iMonth = 9;
		$iDay = 16;
		$iYear = 2013;
		$result = Phpfox::getService('socialad.ad.statistic')->checkStatisticExist($iAdId, $iMonth, $iDay, $iYear);
		var_dump($result);
	}

	public function testComputeStatistic() {

		Phpfox::getService('socialad.ad.statistic')->compute(1);
	}

	public function testCountImpression() {
		$TS15_09_2013_23_59_59 = 1379289599;
		$TS19_09_2013_14_27_59 = 1379600879;
		$count = Phpfox::getService('socialad.ad.track')->getImpressionCountIn(1, $TS15_09_2013_23_59_59, $TS19_09_2013_14_27_59);
		var_dump($count);
	}

	public function testDate() {
		$TS15_09_2013_23_59_59 = 1379289599;
		$TS18_09_2013_11_22_59 = 1379503379; 

		$TS18_09_2013_0_0_0 = 1379462400;
		$TS18_09_2013_23_59_59 = 1379548799;

		$start = Phpfox::getService('socialad.date')->getStartOfDay($TS18_09_2013_11_22_59);
		$end = Phpfox::getService('socialad.date')->getEndOfDay($TS18_09_2013_11_22_59);

		var_dump($start);
		var_dump($TS18_09_2013_0_0_0);

		var_dump($end);
		var_dump($TS18_09_2013_23_59_59);

	}

	public function testGetConst() {
		$a = Phpfox::getService('socialad.helper')->getConst('ad.status.pending');
		var_dump($a);
		$a = Phpfox::getService('socialad.helper')->getConst('track.type.view');
		var_dump($a);

	}

	public function testInstall() {
		ynsocialad301install();
	}
}

