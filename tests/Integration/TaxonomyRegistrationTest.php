<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Integration;

/**
 * Tests of Taxonomy registration.
 */
class TaxonomyRegistrationTest extends TestCase {

	/**
	 * Taxonomy keys should be found in taxonomy list meaning
	 * they are registered.
	 *
	 * @return void
	 */
	public function testTaxonomiesRegistered(): void {
		$taxonomies = get_taxonomies( [ '_builtin' => false ] );
		$this->assertArrayHasKey( 'region', $taxonomies );
		$this->assertArrayHasKey( 'loot', $taxonomies );
		$this->assertArrayHasKey( 'distinction', $taxonomies );
		$this->assertArrayHasKey( 'division', $taxonomies );
		$this->assertArrayHasKey( 'class', $taxonomies );
		$this->assertArrayHasKey( 'version', $taxonomies );
	}

	/**
	 * Test all the instances in registered taxonomy list are \WP_Taxonomy
	 * meaning they are registered.
	 *
	 * @return void
	 */
	public function testWPTaxonomyInstancesReturned(): void {
		$this->assertContainsOnlyInstancesOf(
			'WP_Taxonomy',
			$this->taxonomies
		);
	}

	/**
	 * Number of registered taxonomies should match the number
	 * of taxonomies attempted to register.
	 *
	 * @return void
	 */
	public function testRegisteredTaxonomiesCountMatchConfiguredPostTypeCount(): void {
		$taxonomies = get_taxonomies( [ '_builtin' => false ] );
		$this->assertTrue(
			\count( $this->taxonomies ) === \count( $taxonomies ),
			'Failed asserting that number ' . \count( $this->taxonomies ) . ' is equal to ' . \count( $taxonomies )
		);
	}

	/**
	 * Check is calling create method twice with the same instance of factory does not attempt to register
	 * first attempt's taxonomies.
	 *
	 * Good:
	 * 1st: [ 'color', 'system' ]
	 * 2nd: [ 'area', 'brightness' ]
	 *
	 * Bad:
	 * 1st: [ 'color', 'system' ]
	 * 2nd: [ 'area', 'brightness', 'color', 'system' ]
	 *
	 * @return void
	 */
	public function testRunningCreateSecondTimeDidNotAttemptToRegisterFirstTimesTaxonomies(): void {
		$results_1_attempt = $this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'color', 'system' ] )
			->create()
			->register();
		$results_1_attempt_taxonomy_names = array_map( fn( $taxonomy_instance ) => $taxonomy_instance->name, $results_1_attempt );
		$this->assertSame( [ 'color', 'system' ], $results_1_attempt_taxonomy_names );

		$results_2_attempt = $this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'area', 'brightness' ] )
			->create()
			->register();
		$results_2_attempt_taxonomy_names = array_map( fn( $taxonomy_instance ) => $taxonomy_instance->name, $results_2_attempt );
		$this->assertSame( [ 'area', 'brightness' ], $results_2_attempt_taxonomy_names );
	}

	/**
	 * Check that arguments are cleared after each factory creation
	 *
	 * @return void
	 */
	public function testRunningCreateSecondTimeDoesNotReturnFirstTimesReturnElements(): void {
		$this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'area', 'brightness' ] )
			->create();
		$this->assertTrue( 2 === \count( $this->bulk_registrar_taxonomy_factory->testGetArgumentSetContainer() ) );
		$this->assertSame(
			[ 'area', 'brightness' ],
			$this->getAllArgumentSetKeysInContainer( $this->bulk_registrar_taxonomy_factory->testGetArgumentSetContainer() )
		);

		$this->bulk_registrar_taxonomy_factory
			->withArguments( [ 'color', 'system' ] )
			->create();
		$this->assertTrue( 2 === \count( $this->bulk_registrar_taxonomy_factory->testGetArgumentSetContainer() ) );
		$this->assertSame(
			[ 'color', 'system' ],
			$this->getAllArgumentSetKeysInContainer( $this->bulk_registrar_taxonomy_factory->testGetArgumentSetContainer() )
		);
	}
}
