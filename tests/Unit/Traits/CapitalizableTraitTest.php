<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit;

use martinsluters\WPRegistrars\Tests\Unit\TestCase;

/**
 * Tests of Capitalizable interface implementation trait.
 */
class CapitalizableTraitTest extends TestCase {

	use \martinsluters\WPRegistrars\CapitalizableTrait;

	/**
	 * Auto capitalization should be "on" by default.
	 *
	 * @return void
	 */
	public function testAutoCapitalizeFlagIsTrueByDefault(): void {
		$this->assertTrue( $this->getDoAutoCapitalize() );
	}

	/**
	 * One can toggle auto capitalization "on/off"
	 *
	 * @return void
	 */
	public function testAutoCapitalizeFlagCanBeChanged() {
		$this->setDoAutoCapitalize( false );
		$this->assertFalse( $this->getDoAutoCapitalize() );
		$this->setDoAutoCapitalize( true );
		$this->assertTrue( $this->getDoAutoCapitalize() );
	}

}
