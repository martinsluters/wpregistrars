<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\Tests\Unit\TestCase;

/**
 * Abstract base class of factory tests
 */
abstract class FactoryTestCase extends TestCase {

	/**
	 * Get class name of a factory
	 *
	 * @return string
	 */
	protected function getFactoryClassName(): string {
		return \get_class( $this->factory );
	}
}
