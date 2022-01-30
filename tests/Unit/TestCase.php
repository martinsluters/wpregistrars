<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit;

use WP_Mock\Tools\TestCase as WP_Mock_TestCase;
use WP_Mock;

/**
 * Abstract base class of unit tests.
 */
abstract class TestCase extends WP_Mock_TestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		WP_Mock::setUp();
	}

	/**
	 * Clean up
	 *
	 * @return void
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
	}
}
