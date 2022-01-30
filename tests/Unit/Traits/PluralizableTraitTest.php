<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit;

use martinsluters\WPRegistrars\Tests\Unit\TestCase;
use martinsluters\WPRegistrars\PluralizerInterface;
use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;
use Mockery;

/**
 * Tests of Pluralizable interface implementation trait.
 */
class PluralizableTraitTest extends TestCase {

	use \martinsluters\WPRegistrars\PluralizableTrait;

	/**
	 * Test double (dummy) of Pluralizer
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $pluralizer_mock;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->pluralizer_mock = Mockery::mock( PluralizerInterface::class );
	}

	/**
	 * Test getPluralizer throws an exception
	 *
	 * @return void
	 */
	public function testShouldFailIfPluralizerNotSet(): void {
		$this->expectException( WPRegistrarsException::class );
		$this->expectExceptionMessage( 'Pluralizer not available' );
		$this->getPluralizer();
	}

	/**
	 * Pluralizer can be available only after set
	 *
	 * @return void
	 */
	public function testPluralaizerAvailability(): void {
		$this->assertFalse( $this->isPluralizerAvailable() );
		$this->setPluralizer( $this->pluralizer_mock );
		$this->assertTrue( $this->isPluralizerAvailable() );
	}

	/**
	 * Pluralizer implementation must be implementing PluralizerInterface.
	 *
	 * @return void
	 */
	public function testPluralizerIsInstanceOfPluralizerInterfaceOnceSet(): void {
		$this->setPluralizer( $this->pluralizer_mock );
		$this->assertInstanceOf( PluralizerInterface::class, $this->getPluralizer() );
	}
}
