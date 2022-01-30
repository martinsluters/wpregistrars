<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit;

use martinsluters\WPRegistrars\PluralizerNull;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;

/**
 * Test of Null Pluralizer.
 */
class PluralizerNullTest extends TestCase {

	/**
	 * Passthrough
	 *
	 * @return void
	 */
	public function testReturnsSameString() {
		$this->assertSame( 'human', ( new PluralizerNull() )->pluralize( 'human' ) );
	}
}
