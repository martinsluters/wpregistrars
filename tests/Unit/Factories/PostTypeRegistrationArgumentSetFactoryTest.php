<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\ArgumentSets\PostTypeRegistrationArgumentSet;
use martinsluters\WPRegistrars\Factories\PostTypeRegistrationArgumentSetFactory;

/**
 * Tests of Post Type Registration Argument Set Factory.
 */
class PostTypeRegistrationArgumentSetFactoryTest extends AbstractTaxonomyPostTypeRegistrationArgumentSetFactoryTestCase {

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->factory_with_arguments = ( new PostTypeRegistrationArgumentSetFactory() )->withArguments( 'human', [] );
		$this->factory = new PostTypeRegistrationArgumentSetFactory();
	}

	/**
	 * Builder method create must create an Argument Set
	 *
	 * @return void
	 */
	public function testCreateReturnsInstanceOfPostTypeRegistrationArgumentSet(): void {
		$this->assertInstanceOf( PostTypeRegistrationArgumentSet::class, $this->factory_with_arguments->create() );
	}

}
