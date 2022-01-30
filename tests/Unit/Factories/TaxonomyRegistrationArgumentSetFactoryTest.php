<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\ArgumentSets\TaxonomyRegistrationArgumentSet;
use martinsluters\WPRegistrars\Factories\TaxonomyRegistrationArgumentSetFactory;

/**
 * Tests of Taxonomy Registration Argument Set Factory.
 */
class TaxonomyRegistrationArgumentSetFactoryTest extends AbstractTaxonomyPostTypeRegistrationArgumentSetFactoryTestCase {

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->factory_with_arguments = ( new TaxonomyRegistrationArgumentSetFactory() )->withArguments( 'category', [ 'foo' ], [] );
		$this->factory = new TaxonomyRegistrationArgumentSetFactory();
	}

	/**
	 * Builder method create must create an Argument Set
	 *
	 * @return void
	 */
	public function testCreateReturnsInstanceOfTaxonomyRegistrationArgumentSet(): void {
		$this->assertInstanceOf( TaxonomyRegistrationArgumentSet::class, $this->factory_with_arguments->create() );
	}
}
