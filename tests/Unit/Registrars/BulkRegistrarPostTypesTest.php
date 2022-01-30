<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Registrars;

use martinsluters\WPRegistrars\ArgumentSetContainers\PostTypeRegistrationArgumentSetContainer;
use martinsluters\WPRegistrars\ArgumentSets\PostTypeRegistrationArgumentSet;
use martinsluters\WPRegistrars\Registrars\BulkRegistrarPostTypes;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;
use WP_Mock;
use Mockery;

/**
 * Tests of Bulk Registrar of Post Types.
 */
class BulkRegistrarPostTypesTest extends TestCase {

	/**
	 * Test double (dummy) of \WP_Post_Type
	 *
	 * @var \WP_Post_Type
	 */
	protected \WP_Post_Type $wp_post_type_instance_mock;

	/**
	 * Test double (mock) of PostTypeRegistrationArgumentSet
	 *
	 * @var PostTypeRegistrationArgumentSet
	 */
	protected PostTypeRegistrationArgumentSet $argument_set_1_mock;

	/**
	 * Test double (mock) of PostTypeRegistrationArgumentSet
	 *
	 * @var PostTypeRegistrationArgumentSet
	 */
	protected PostTypeRegistrationArgumentSet $argument_set_2_mock;

	/**
	 * Test double (mock) of PostTypeRegistrationArgumentSet
	 *
	 * @var PostTypeRegistrationArgumentSetContainer
	 */
	protected PostTypeRegistrationArgumentSetContainer $argument_set_container_mock;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->argument_set_1_mock = Mockery::mock( PostTypeRegistrationArgumentSet::class );
		$this->argument_set_2_mock = Mockery::mock( PostTypeRegistrationArgumentSet::class );
		$this->argument_set_container_mock = Mockery::mock( PostTypeRegistrationArgumentSetContainer::class );
		$this->wp_post_type_instance_mock = Mockery::mock( '\WP_Post_Type' );
	}

	/**
	 * Calling register method will return array
	 *
	 * @return void
	 */
	public function testRegistrationReturnsArray(): void {
		$this->expectRegisterPostTypeMethodCalled( 2, [ $this->wp_post_type_instance_mock, $this->wp_post_type_instance_mock ] );

		$this->argument_set_1_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [] );
		$this->argument_set_1_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'foo' );

		$this->argument_set_2_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [ 'show_in_rest' => true ] );
		$this->argument_set_2_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'bar' );

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

		$bulk_registrar_cpt = new BulkRegistrarPostTypes(
			$this->argument_set_container_mock
		);
		$this->assertIsArray( $bulk_registrar_cpt->register() );
	}

	/**
	 * Calling register method x times will return array with x elements
	 *
	 * @return void
	 */
	public function testReturnsTwoElementsInReturnArrayOnAttemptToRegisterTwoPostTypes(): void {
		$this->expectRegisterPostTypeMethodCalled( 2, [ $this->wp_post_type_instance_mock, $this->wp_post_type_instance_mock ] );

		$this->argument_set_1_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [] );
		$this->argument_set_1_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'foo' );

		$this->argument_set_2_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [ 'show_in_rest' => true ] );
		$this->argument_set_2_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'bar' );

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

		$bulk_registrar_cpt = new BulkRegistrarPostTypes(
			$this->argument_set_container_mock
		);

		$result = $bulk_registrar_cpt->register();
		$this->assertTrue( 2 === \count( $result ) );
	}

	/**
	 * Make sure that key and arguments goes through WordPress filters.
	 *
	 * @return void
	 */
	public function testPostTypeRegistrationKeyAndArgumentsRunThroughWPFilter(): void {

		WP_Mock::expectFilter( 'wpregistrars/bulk_registrar/post_type/key', 'foo', [] );
		WP_Mock::expectFilter( 'wpregistrars/bulk_registrar/post_type/args', [], 'foo' );
		$this->expectRegisterPostTypeMethodCalled( 1, [ $this->wp_post_type_instance_mock ] );

		$this->argument_set_1_mock
			->shouldReceive( 'getArguments' )
			->andReturn( [] );
		$this->argument_set_1_mock
			->shouldReceive( 'getKey' )
			->andReturn( 'foo' );

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

		$bulk_registrar_cpt = new BulkRegistrarPostTypes(
			$this->argument_set_container_mock
		);

		$result = $bulk_registrar_cpt->register();
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

		$bulk_registrar_cpt = new BulkRegistrarPostTypes(
			$this->argument_set_container_mock
		);

		$result = $bulk_registrar_cpt->register();
		$this->assertIsArray( $result );
		$this->assertTrue( 0 === \count( $result ) );
	}

	/**
	 * A shortcut method of WP_Mock's expectation of 'register_post_type' function.
	 *
	 * @param int   $times_to_expect the function to be called.
	 * @param array $values_to_expect_as_return after calling the function in question.
	 * @return void
	 */
	protected function expectRegisterPostTypeMethodCalled( int $times_to_expect, array $values_to_expect_as_return ): void {
		WP_Mock::userFunction(
			'register_post_type',
			[
				'times' => $times_to_expect,
				'args' => [ WP_Mock\Functions::type( 'string' ), WP_Mock\Functions::type( 'array' ) ],
				'return_in_order' => $values_to_expect_as_return,
			]
		);
	}
}
