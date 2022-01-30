<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\Factories\AbstractWPTaxonomyPostTypeArgumentSetFactory;
use martinsluters\WPRegistrars\Exceptions\WPRegistrarsException;
use martinsluters\WPRegistrars\PluralizerInterface;
use Mockery;

/**
 * Abstract base class of Post Type and Taxonomy argument set factory tests
 */
abstract class AbstractTaxonomyPostTypeRegistrationArgumentSetFactoryTestCase extends FactoryTestCase {

	/**
	 * Test double (dummy) of Pluralizer
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $pluralizer_mock;

	/**
	 * Test double (dummy) of Pluralizer
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $my_awesome_custom_pluralizer_mock;

	/**
	 * AbstractWPTaxonomyPostTypeArgumentSetFactory factory
	 *
	 * @var AbstractWPTaxonomyPostTypeArgumentSetFactory
	 */
	protected AbstractWPTaxonomyPostTypeArgumentSetFactory $factory;

	/**
	 * AbstractWPTaxonomyPostTypeArgumentSetFactory factory
	 * Arguments are applied already for easier testing.
	 *
	 * @var AbstractWPTaxonomyPostTypeArgumentSetFactory
	 */
	protected AbstractWPTaxonomyPostTypeArgumentSetFactory $factory_with_arguments;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->pluralizer_mock = Mockery::mock( PluralizerInterface::class );
		$this->my_awesome_custom_pluralizer_mock = Mockery::mock( 'MyAwesomeCustomPluralizer', PluralizerInterface::class );
	}

	/**
	 * Test that an exception is thrown if attempting to create without arguments provided before
	 *
	 * @return void
	 */
	public function testBuildOperationAllowed(): void {
		$this->expectException( WPRegistrarsException::class );
		$this->expectExceptionMessage( 'Argument set factory arguments not provided' );
		$this->factory->create();
	}

	/**
	 * Builder method withArguments must return self (instance)
	 *
	 * @return void
	 */
	public function testWithArgumentsReturnsSelf(): void {
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory_with_arguments );
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
	 * Builder method shouldAutoCapitalize must return self (instance)
	 *
	 * @return void
	 */
	public function testShouldAutoCapitalizeReturnsReturnsSelf(): void {
		$this->assertInstanceOf( $this->getFactoryClassName(), $this->factory->shouldAutoCapitalize( true ) );
	}
}
