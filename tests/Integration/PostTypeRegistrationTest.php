<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Integration;

/**
 * Tests of Post Type registration.
 */
class PostTypeRegistrationTest extends TestCase {

	/**
	 * Post type keys should be found in post type list meaning
	 * they are registered.
	 *
	 * @return void
	 */
	public function testPostTypesRegistered(): void {
		$wp_post_types = get_post_types( [ '_builtin' => false ] );
		$this->assertArrayHasKey( 'dwarf', $wp_post_types );
		$this->assertArrayHasKey( 'dwarf_mountain', $wp_post_types );
		$this->assertArrayHasKey( 'hobbit', $wp_post_types );
		$this->assertArrayHasKey( 'elve', $wp_post_types );
		$this->assertArrayHasKey( 'human', $wp_post_types );
		$this->assertArrayHasKey( 'ork', $wp_post_types );
		$this->assertArrayHasKey( 'munition', $wp_post_types );
	}

	/**
	 * Test all the instances in registered post type list are \WP_Post_Type
	 * meaning they are registered.
	 *
	 * @return void
	 */
	public function testWPPostTypeInstancesReturned(): void {
		$this->assertContainsOnlyInstancesOf(
			'WP_Post_Type',
			$this->post_types
		);
	}

	/**
	 * Number of registered post types should match the number
	 * of post types attempted to register.
	 *
	 * @return void
	 */
	public function testRegisteredPostTypesCountMatchConfiguredPostTypeCount(): void {
		$wp_post_types = get_post_types( [ '_builtin' => false ] );

		$this->assertTrue(
			\count( $this->post_types ) === \count( $wp_post_types ),
			'Failed asserting that number ' . \count( $this->post_types ) . ' is equal to ' . \count( $wp_post_types )
		);
	}

	/**
	 * Check is calling create method twice with the same instance of factory does not attempt to register
	 * first attempt's post types.
	 *
	 * Good:
	 * 1st: [ 'man', 'ork' ]
	 * 2nd: [ 'hobbit', 'elve' ]
	 *
	 * Bad:
	 * 1st: [ 'man', 'ork' ]
	 * 2nd: [ 'hobbit', 'elve', 'man', 'ork' ]
	 *
	 * @return void
	 */
	public function testRunningCreateSecondTimeDidNotAttemptToRegisterFirstTimesPostTypes(): void {
		$results_1_attempt = $this->bulk_registrar_post_type_factory
			->withArguments( [ 'man', 'ork' ] )
			->create()
			->register();
		$results_1_attempt_post_type_names = array_map( fn( $post_type_instance ) => $post_type_instance->name, $results_1_attempt );
		$this->assertSame( [ 'man', 'ork' ], $results_1_attempt_post_type_names );

		$results_2_attempt = $this->bulk_registrar_post_type_factory
			->withArguments( [ 'hobbit', 'elve' ] )
			->create()
			->register();
		$results_2_attempt_post_type_names = array_map( fn( $post_type_instance ) => $post_type_instance->name, $results_2_attempt );
		$this->assertSame( [ 'hobbit', 'elve' ], $results_2_attempt_post_type_names );
	}

	/**
	 * Check that arguments are cleared after each factory creation
	 *
	 * @return void
	 */
	public function testRunningCreateSecondTimeDoesNotReturnFirstTimesReturnElements(): void {
		$this->bulk_registrar_post_type_factory
			->withArguments( [ 'man', 'ork' ] )
			->create();
		$this->assertTrue( 2 === \count( $this->bulk_registrar_post_type_factory->testGetArgumentSetContainer() ) );
		$this->assertSame(
			[ 'man', 'ork' ],
			$this->getAllArgumentSetKeysInContainer( $this->bulk_registrar_post_type_factory->testGetArgumentSetContainer() )
		);

		$this->bulk_registrar_post_type_factory
			->withArguments( [ 'hobbit', 'elve' ] )
			->create();
		$this->assertTrue( 2 === \count( $this->bulk_registrar_post_type_factory->testGetArgumentSetContainer() ) );
		$this->assertSame(
			[ 'hobbit', 'elve' ],
			$this->getAllArgumentSetKeysInContainer( $this->bulk_registrar_post_type_factory->testGetArgumentSetContainer() )
		);
	}
}
