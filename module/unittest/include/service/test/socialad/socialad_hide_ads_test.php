<?php


/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Socialad_Hide_Ads_Test extends PHPUnit_Framework_TestCase {

	use WebDriverDevelop;
	public function __constructor($name) {
		parent::__constructor($name);
	}

	protected function setUp() {
		Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
		$host = 'http://localhost:4444/wd/hub'; // this is the default
		$capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'firefox');
		$this->driver = RemoteWebDriver::create($host, $capabilities);
	}

	protected $url = "http://phpfox.local.com/phpFox360b6";
	protected $driver;

	public function test1() {

		$aAdVals = array(
			'ad_id' => 1989,
			'location' => array( 'VN', 'US'),
			'ad_type' => Phpfox::getService('socialad.helper')->getConst('ad.type.html')

		);

		Phpfox::getService('unittest.test.socialad')->insertTestAd($aAdVals);
		$this->driver->get($this->url);
		$search = $this->driver->findElement(WebDriverBy::cssSelector('.header_menu_login_left .header_menu_login_input'));
		$search->click();
		$this->driver->getKeyboard()->sendKeys('minhta@younetco.com');

		// need refactoring
		$search = $this->driver->findElement(WebDriverBy::cssSelector('.header_menu_login_right .header_menu_login_input'));
		$search->click();
		$this->driver->getKeyboard()->sendKeys('123456');

		$search = $this->driver->findElement(WebDriverBy::cssSelector('.header_menu_login_button input'));
		$search->click();
		        // checking that page title contains word 'GitHub'

		$by= WebDriverBy::cssSelector('#ynsaAdDisplay_' . $aAdVals['ad_id']);
		$this->assertElementFound($by);

		$search = $this->driver->findElement(WebDriverBy::cssSelector('#ynsaAdDisplay_' . $aAdVals['ad_id'] ));
		$this->driver->executeScript('$(".ynsaDisplayAdHideButton").show();');
		// $search->click();
		// $this->driver->getMouse()->mouseMove($search->getCoordinates());
		$search = $this->driver->findElement(WebDriverBy::cssSelector('#ynsaAdDisplay_' . $aAdVals['ad_id'] . ' .ynsaDisplayAdHideButton' ));

		$search->click();

		$search = $this->driver->findElement(WebDriverBy::cssSelector('#ynsaAdDisplay_' . $aAdVals['ad_id'] . ' .ynsaDisplayAdBlock' ));
		$this->assertFalse($search->isDisplayed());
		// need a function convert xPath to css selector
		$search = $this->driver->findElement(WebDriverBy::cssSelector('#header_menu_holder ul:nth-child(1) > li:nth-child(3) > a:nth-child(1)'));

		$search->click();

		$search = $this->driver->findElement(WebDriverBy::cssSelector('#header_menu_holder ul:nth-child(1) > li:nth-child(3) > ul > li:nth-child(6) a'));

		$search->click();
	}	

	public function tearDown()
	{
		// Phpfox::getService('unittest.test.socialad')->truncateAllRelatedTables();
	}


}

?>
