<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Integration;

use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * Class to test that tests are running with the right tools at the right time
 */
class Boot extends TestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * WordPress has to be loaded before running integration tests
	 *
	 * @return void
	 */
	public function testWordpressLoaded(): void {
		$this->assertTrue( \function_exists( 'do_action' ) );
	}

	/**
	 * PHP Composer has to be the "composer" of WordPress for integration tests
	 *
	 * @return void
	 */
	public function testWPPphpunitIsLoadedViaComposer(): void {
		$this->assertStringStartsWith(
			\dirname( \dirname( __DIR__ ) ) . '/vendor/',
			getenv( 'WP_PHPUNIT__DIR' )
		);

		$this->assertStringStartsWith(
			\dirname( \dirname( __DIR__ ) ) . '/vendor/',
			( new \ReflectionClass( 'WP_UnitTestCase' ) )->getFileName()
		);
	}

	/**
	 * WordPress action 'init' has to be run at least once before integration tests
	 *
	 * @return void
	 */
	public function testInitActionDidRun(): void {
		$this->assertTrue( 0 < did_action( 'init' ) );
	}
}
