<?php
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once "PHPUnit/TextUI/TestRunner.php";
require_once "PHPUnit/Framework/TestSuite.php";
require_once "db_testcase";
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Unittest_Service_Test_Unittest extends Phpfox_Service {
	public function testDB()
	{
		$test_suites = new PHPUnit_Framework_TestSuite();
		$test_suites->addTestSuite('Socialad_DB_Test');

		$arguments = array();
		PHPUnit_TextUI_TestRunner::run($test_suites, $arguments);

	}

}

?>
