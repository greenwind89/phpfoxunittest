<?php

trait WebDriverDevelop {

	protected function waitForUserInput() {
		if(trim(fgets(fopen("php://stdin","r"))) != chr(13)) return;
	}

	protected function assertElementNotFound($by) {
		$els = $this->driver->findElements($by);
		if (count($els)) {
			$this->fail("Unexpectedly element was found");
		}
		// increment assertion counter
		$this->assertTrue(true);        
	 }

	protected function assertElementFound($by) {
		$els = $this->driver->findElements($by);
		if (!count($els)) {
			$this->fail("Unexpectedly element was NOT found");
		}
		// increment assertion counter
		$this->assertTrue(true);        
	 }
}
