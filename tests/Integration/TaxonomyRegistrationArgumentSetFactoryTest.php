<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\Factories\TaxonomyRegistrationArgumentSetFactory;
use martinsluters\WPRegistrars\PluralizerDoctrineInflectorAdapter;
use martinsluters\WPRegistrars\PluralizerInterface;
use martinsluters\WPRegistrars\PluralizerNull;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery;

/**
 * Tests of Taxonomy Registration Argument Set Factory.
 */
class TaxonomyRegistrationArgumentSetFactoryTest extends MockeryTestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * Test double (dummy) of Pluralizer
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $my_awesome_custom_pluralizer_mock;

	/**
	 * TaxonomyRegistrationArgumentSetFactory factory
	 *
	 * @var TaxonomyRegistrationArgumentSetFactory
	 */
	protected TaxonomyRegistrationArgumentSetFactory $factory;

	/**
	 * TaxonomyRegistrationArgumentSetFactory factory
	 * Arguments are applied already for easier testing.
	 *
	 * @var TaxonomyRegistrationArgumentSetFactory
	 */
	protected TaxonomyRegistrationArgumentSetFactory $factory_with_arguments;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->my_awesome_custom_pluralizer_mock = Mockery::mock( 'MyAwesomeCustomPluralizer', PluralizerInterface::class );
		$this->factory_with_arguments = ( new TaxonomyRegistrationArgumentSetFactory() )->withArguments( 'category', [ 'foo' ], [] );
		$this->factory = new TaxonomyRegistrationArgumentSetFactory();
	}

	/**
	 * Test if Doctrine Inflector's Adapter for Pluralizer is returned
	 *
	 * @return void
	 */
	public function testCreateReturnsWithPluralizerDoctrineInflectorAdapterByDefault(): void {
		$this->assertInstanceOf( PluralizerDoctrineInflectorAdapter::class, $this->factory_with_arguments->create()->getPluralizer() );
	}

	/**
	 * Make sure that factory does pass a custom pluralizer instance to argument set if provided
	 * and the default is not used
	 *
	 * @return void
	 */
	public function testWithCustomPluralizerSetsTheCustomPluralizerInArgumentSet(): void {
		$this->assertInstanceOf(
			'MyAwesomeCustomPluralizer',
			$this->factory_with_arguments
			->withPluralizer( $this->my_awesome_custom_pluralizer_mock )
			->create()
			->getPluralizer()
		);
	}

	/**
	 * Make sure that a Null Pluralizer gets passed to argument set auto pluralization is turned off
	 *
	 * @return void
	 */
	public function testWithShouldAutoPluralizeFalseReturnsWithPluralizerNull(): void {
		$this->assertInstanceOf(
			PluralizerNull::class,
			$this->factory_with_arguments
				->shouldAutoPluralize( false )
				->create()
			->getPluralizer()
		);
	}

	/**
	 * Toggling auto pluralization switch does not affect the fact
	 * that Doctrine Inflector's Adapter gets passed to argument set
	 *
	 * @return void
	 */
	public function testTogglingShouldAutoPluralizeShouldNotChangeInitialPluralizer(): void {
		$this->assertInstanceOf(
			PluralizerDoctrineInflectorAdapter::class,
			$this->factory_with_arguments
				->shouldAutoPluralize( false )
				->shouldAutoPluralize( true )
				->create()
			->getPluralizer()
		);
	}

	/**
	 * Toggling auto pluralization switch does not affect the fact
	 * that a custom pluralizer gets passed to argument set
	 *
	 * @return void
	 */
	public function testTogglingShouldAutoPluralizeDoesKeepCustomPluralizer(): void {
		$this->assertInstanceOf(
			'MyAwesomeCustomPluralizer',
			$this->factory_with_arguments
				->withPluralizer( $this->my_awesome_custom_pluralizer_mock )
				->shouldAutoPluralize( false )
				->shouldAutoPluralize( true )
				->create()
				->getPluralizer()
		);
	}

	/**
	 * Calling build method withArguments later than withPluralizer
	 * does not affect the fact that a custom pluralizer gets returned
	 *
	 * @return void
	 */
	public function testDelayedCallOfWithArgumentsDoesNotChangeCustomPluralizer(): void {
		$this->assertInstanceOf(
			'MyAwesomeCustomPluralizer',
			$this->factory
			->withPluralizer( $this->my_awesome_custom_pluralizer_mock )
			->withArguments( 'human', [ 'foo' ], [] )
			->create()
			->getPluralizer()
		);
	}

	/**
	 * Calling build method withArguments later than shouldAutoPluralize
	 * does not affect the fact that a null pluralizer gets returned
	 *
	 * @return void
	 */
	public function testDelayedCallOfWithArgumentsDoesNotChangeShouldAutoPluralize(): void {
		$this->assertInstanceOf(
			PluralizerNull::class,
			$this->factory
				->shouldAutoPluralize( false )
				->withArguments( 'human', [ 'foo' ], [] )
				->create()
			->getPluralizer()
		);
	}

	/**
	 * Calling build method withArguments later than shouldAutoPluralize toggle
	 * does not affect the fact that Doctrine Inflector's Adapter gets passed to argument set
	 *
	 * @return void
	 */
	public function testDelayedCallOfWithArgumentsShouldReturnDefaultPluralizer(): void {
		$this->assertInstanceOf(
			PluralizerDoctrineInflectorAdapter::class,
			$this->factory
				->shouldAutoPluralize( false )
				->shouldAutoPluralize( true )
				->withArguments( 'human', [ 'foo' ], [] )
				->create()
			->getPluralizer()
		);
	}
}

