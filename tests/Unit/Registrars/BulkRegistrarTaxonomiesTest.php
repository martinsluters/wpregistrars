<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Registrars;

use martinsluters\WPRegistrars\ArgumentSetContainers\TaxonomyRegistrationArgumentSetContainer;
use martinsluters\WPRegistrars\ArgumentSets\TaxonomyRegistrationArgumentSet;
use martinsluters\WPRegistrars\Registrars\BulkRegistrarTaxonomies;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;
use WP_Mock;
use Mockery;

/**
 * Tests of Bulk Registrar of Taxonomies.
 */
class BulkRegistrarTaxonomiesTest extends TestCase {

	/**
	 * Test double (dummy) of \WP_Taxonomy
	 *
	 * @var \WP_Taxonomy
	 */
	protected $wp_taxonomy_instance_mock;

	/**
	 * Test double (mock) of TaxonomyRegistrationArgumentSet
	 *
	 * @var TaxonomyRegistrationArgumentSet
	 */
	protected TaxonomyRegistrationArgumentSet $argument_set_1_mock;

	/**
	 * Test double (mock) of TaxonomyRegistrationArgumentSet
	 *
	 * @var TaxonomyRegistrationArgumentSet
	 */
	protected TaxonomyRegistrationArgumentSet $argument_set_2_mock;

	/**
	 * Test double (mock) of PostTypeRegistrationArgumentSet
	 *
	 * @var TaxonomyRegistrationArgumentSetContainer
	 */
	protected TaxonomyRegistrationArgumentSetContainer $argument_set_container_mock;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->argument_set_1_mock = Mockery::mock( TaxonomyRegistrationArgumentSet::class );
		$this->argument_set_2_mock = Mockery::mock( TaxonomyRegistrationArgumentSet::class );
		$this->argument_set_container_mock = Mockery::mock( TaxonomyRegistrationArgumentSetContainer::class );
		$this->wp_taxonomy_instance_mock = Mockery::mock( '\WP_Taxonomy' );
		parent::setUp();
	}

	/**
	 * Calling register method will return array
	 *
	 * @return void
	 */
	public function testReturnsArray(): void {
		$this->expectRegisterTaxonomyFunctionCalled( 2, [ $this->wp_taxonomy_instance_mock, $this->wp_taxonomy_instance_mock ] );

		$this->argument_set_1_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [] );
		$this->argument_set_1_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'category_b' );
		$this->argument_set_1_mock
			->shouldReceive( 'getObjectType' )
			->andReturn( [ 'foo', 'bar' ] );

		$this->argument_set_2_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [ 'show_in_rest' => true ] );
		$this->argument_set_2_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'category_c' );
		$this->argument_set_2_mock
			->shouldReceive( 'getObjectType' )
			->andReturn( [ 'foo', 'bar' ] );

		$this->argument_set_container_mock
			->shouldReceive( 'getIterator' )
			->andReturn(
				new \ArrayIterator(
					[
						$this->argument_set_1_mock,
						$this->argument_set_2_mock,
					]
				)
			);

		$this->argument_set_container_mock
			->shouldReceive( 'isValidContainerContent' )
			->andReturn( true );

		$bulk_registrar_taxonomies = new BulkRegistrarTaxonomies( $this->argument_set_container_mock );
		$result = $bulk_registrar_taxonomies->register();
		$this->assertIsArray( $result );
	}

	/**
	 * Calling register method x times will return array with x elements
	 *
	 * @return void
	 */
	public function testReturnsTwoElementsInReturnArrayOnAttemptToRegisterTwoPostTypes(): void {
		$this->expectRegisterTaxonomyFunctionCalled( 2, [ $this->wp_taxonomy_instance_mock, $this->wp_taxonomy_instance_mock ] );

		$this->argument_set_1_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [] );
		$this->argument_set_1_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'foo' );
		$this->argument_set_1_mock
			->shouldReceive( 'getObjectType' )
			->andReturn( [ 'foo', 'bar' ] );

		$this->argument_set_2_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [ 'show_in_rest' => true ] );
		$this->argument_set_2_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'bar' );
		$this->argument_set_2_mock
			->shouldReceive( 'getObjectType' )
			->andReturn( [ 'foo', 'bar' ] );

		$this->argument_set_container_mock
			->shouldReceive( 'getIterator' )
			->andReturn(
				new \ArrayIterator(
					[
						$this->argument_set_1_mock,
						$this->argument_set_2_mock,
					]
				)
			);

		$this->argument_set_container_mock
			->shouldReceive( 'isValidContainerContent' )
			->andReturn( true );

		$bulk_registrar_taxonomies = new BulkRegistrarTaxonomies(
			$this->argument_set_container_mock
		);

		$result = $bulk_registrar_taxonomies->register();
		$this->assertTrue( 2 === \count( $result ) );
	}

	/**
	 * Make sure that ke, object type and arguments goes through WordPress filters.
	 *
	 * @return void
	 */
	public function testTaxonomyRegistrationKeyAndArgumentsRunThroughWPFilter(): void {

		WP_Mock::expectFilter( 'wpregistrars/bulk_registrar/taxonomy/key', 'foo', [ 'foo', 'bar' ], [] );
		WP_Mock::expectFilter( 'wpregistrars/bulk_registrar/taxonomy/object_type', [ 'foo', 'bar' ], 'foo', [] );
		WP_Mock::expectFilter( 'wpregistrars/bulk_registrar/taxonomy/args', [], 'foo', [ 'foo', 'bar' ] );
		$this->expectRegisterTaxonomyFunctionCalled( 1, [ $this->wp_taxonomy_instance_mock ] );

		$this->argument_set_1_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [] );
		$this->argument_set_1_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'foo' );
		$this->argument_set_1_mock
			->shouldReceive( 'getObjectType' )
			->andReturn( [ 'foo', 'bar' ] );

		$this->argument_set_container_mock
			->shouldReceive( 'getIterator' )
			->andReturn(
				new \ArrayIterator(
					[
						$this->argument_set_1_mock,
					]
				)
			);

		$this->argument_set_container_mock
			->shouldReceive( 'isValidContainerContent' )
			->andReturn( true );

		$bulk_registrar_taxonomies = new BulkRegistrarTaxonomies(
			$this->argument_set_container_mock
		);

		$result = $bulk_registrar_taxonomies->register();
		$this->assertTrue( 1 === \count( $result ) );
	}

	/**
	 * If empty container of arguments provided will return empty array
	 *
	 * @return void
	 */
	public function testReturnsEmptyArrayIfArgumentSetContainerNotValid(): void {

		$this->argument_set_container_mock
			->shouldReceive( 'isValidContainerContent' )
			->andReturn( false );

		$bulk_registrar_taxonomy = new BulkRegistrarTaxonomies( $this->argument_set_container_mock );

		$result = $bulk_registrar_taxonomy->register();
		$this->assertIsArray( $result );
		$this->assertTrue( 0 === \count( $result ) );
	}

	/**
	 * A shortcut method of WP_Mock's expectation of 'register_taxonomy' function.
	 *
	 * @param int   $times_to_expect the function to be called.
	 * @param array $values_to_expect_as_return after calling the function in question.
	 * @return void
	 */
	protected function expectRegisterTaxonomyFunctionCalled( int $times_to_expect, array $values_to_expect_as_return ): void {
		WP_Mock::userFunction(
			'register_taxonomy',
			[
				'times' => $times_to_expect,
				'args' => [ WP_Mock\Functions::type( 'string' ), '*', WP_Mock\Functions::type( 'array' ) ],
				'return_in_order' => $values_to_expect_as_return,
			],
		);
	}
}
