<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\Factories\AbstractWPTaxonomyPostTypeBulkRegistrarFactory;
use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;
use martinsluters\WPRegistrars\PluralizerInterface;
use Mockery;

/**
 * Abstract base class of Post Type and Taxonomy bulk registrar factory tests
 */
abstract class AbstractBulkRegistrarTaxonomiesPostTypesFactoryTestCase extends FactoryTestCase {

	/**
	 * Test double (dummy) of Pluralizer
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $pluralizer_mock;

	/**
	 * AbstractWPTaxonomyPostTypeBulkRegistrarFactory factory
	 *
	 * @var AbstractWPTaxonomyPostTypeBulkRegistrarFactory
	 */
	protected AbstractWPTaxonomyPostTypeBulkRegistrarFactory $factory;

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
	 * Builder method withDefaultRegistrationArguments must return self (instance)
	 *
	 * @return void
	 */
	public function testWithDefaultRegistrationArgumentsReturnsSelf(): void {
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory->withDefaultRegistrationArguments( [ 'foo' => 'bar' ] ) );
	}

	/**
	 * Builder method withArguments must return self (instance)
	 *
	 * @return void
	 */
	public function testWithArgumentsReturnsSelf(): void {
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory->withArguments( [ 'human' ] ) );
	}

	/**
	 * Builder method withPluralizer must return self (instance)
	 *
	 * @return void
	 */
	public function testWithPluralizerReturnsSelf(): void {
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory->withPluralizer( $this->pluralizer_mock ) );
	}

	/**
	 * Builder method shouldAutoPluralize must return self (instance)
	 *
	 * @return void
	 */
	public function testShouldAutoPluralizeReturnsSelf(): void {
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory->shouldAutoPluralize( false ) );
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory->shouldAutoPluralize( true ) );
	}

	/**
	 * Test that exceptions are thrown if invalid format of arguments provided to factory
	 *
	 * @param array $arguments Invalid arguments.
	 * @return void
	 * @dataProvider invalidFormatArguments
	 */
	public function testShouldFailIfInvalidFormatOfArgumentsProvided( array $arguments ): void {
		$this->expectException( WPRegistrarsException::class );
		$this->expectExceptionMessage( 'invalid format of arguments provided' );
		$this->factory->withArguments( $arguments );
	}

	/**
	 * Test that an exception is thrown if attempting to create without arguments provided before
	 *
	 * @return void
	 */
	public function testShouldFailIfArgumentsNotProvided(): void {
		$this->expectException( WPRegistrarsException::class );
		$this->expectExceptionMessage( 'arguments not provided' );
		$this->factory->create();
	}

	/**
	 * Data provider with invalid format of arguments
	 *
	 * @return array
	 */
	public function invalidFormatArguments(): array {
		return [
			'Empty argument array' => [ [ [] ] ],
			'Non-assoc element with array value' => [
				[
					[ 'show_in_rest' => true ], // Invalid.
					'human' => [ 'show_in_rest' => true ], // Valid.
				],
			],
			'Assoc element with non-array value' => [
				[
					'human1' => 0,
					'human2' => null,
					'human3' => true,
					'human4' => 'string',
					'human5' => new \stdClass(),
				],
			],
			'Non-assoc element with non-array value' => [
				[
					0,
					null,
					true,
					new \stdClass(),
				],
			],
		];
	}
}
