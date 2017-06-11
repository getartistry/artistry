<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Driver\Selenium2Driver;

define('SCREENSHOT_PATH', '/tmp');
define('SCREENSHOT_URL', 'http://localhost');
define('HTML_DUMP_PATH', '/tmp');
define('HTML_DUMP_URL', 'http://localhost');

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
	/**
	 * Initializes context.
	 *
	 * Every scenario gets its own context instance.
	 * You can also pass arbitrary arguments to the
	 * context constructor through behat.yml.
	 */
	public function __construct()
	{
	}

	/**
	 * @When I click the element with CSS selector :selector
	 */
	public function iClickTheElementWithCssSelector( $selector ) {
		$element = $this->getSession()->getPage()->find( 'css', $selector );
		if ( empty( $element ) ) {
			throw new \Exception( sprintf( "The page '%s' does not contain the css selector '%s'", $this->getSession()->getCurrentUrl(), $selector ) );
		}
		$element->click();
	}

	/**
	 * Checks, that form element with CSS selector is visible on page.
	 *
	 * @Then /^(?:|I )should see the "(?P<selector>[^"]*)" element$/
	 */
	public function assertElementOnPage( $selector ) {
		$nodes = $this->getSession()->getPage()->findAll( 'css', $selector );
		foreach ( $nodes as $node ) {
			if ( $node->isVisible() ) {
				return;
			} else {
				throw new \Exception( "Element with selector \"$selector\" not visible." );
			}
		}
		throw new \Behat\Mink\Exception\ElementNotFoundException( $this->getSession(), '', $selector, '' );
	}

	/**
	 * Checks, that form element with CSS selector is not visible on page.
	 *
	 * @Then /^(?:|I )should not see the "(?P<selector>[^"]*)" element$/
	 */
	public function assertElementNotOnPage( $selector ) {
		$nodes = $this->getSession()->getPage()->findAll( 'css', $selector );
		if ( empty( $nodes ) ) {
			return;
		}
		foreach ( $nodes as $node ) {
			if ( ! $node->isVisible() ) {
				return;
			} else {
				throw new \Exception( "Element with selector \"$selector\" visible." );
			}
		}
		throw new \Behat\Mink\Exception\ElementNotFoundException( $this->getSession(), '', $selector, '' );
	}

	/**
	 * @AfterStep
	 */
	public function takeScreenshotAfterFailedStep(AfterStepScope $scope) {
		if ( 99 === $scope->getTestResult()->getResultCode() ) {
			$this->takeScreenshot();
		}
	}

	private function takeScreenshot() {
		$driver = $this->getSession()->getDriver();
		if ( ! $driver instanceof Selenium2Driver ) {
			return;
		}
		$fileName = date( 'Y-m-d' ) . '-' . uniqid() . '.png';
		$filePath = '/tmp/';

		$this->saveScreenshot( $fileName, $filePath );
		print 'Screenshot at: /tmp/' . $fileName;
	}

}
