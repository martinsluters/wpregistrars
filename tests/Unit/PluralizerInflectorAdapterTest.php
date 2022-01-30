<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit;

use martinsluters\WPRegistrars\PluralizerDoctrineInflectorAdapter;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;
use Mockery;

/**
 * Test of Pluralizer adapter of Doctrine Inflector.
 */
class PluralizerDoctrineInflectorAdapterTest extends TestCase {

	/**
	 * Test double (mock) of Doctrine\Inflector\Inflector
	 *
	 * @var \Doctrine\Inflector\Inflector
	 */
	protected \Doctrine\Inflector\Inflector $inflector_instance_mock;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->inflector_instance_mock = Mockery::mock( 'Doctrine\Inflector\Inflector' );
	}

	/**
	 * Adapter should pluralize
	 *
	 * @return void
	 */
	public function testReturnsPlural() {
		$this->inflector_instance_mock
			->shouldReceive( 'pluralize' )
			->once()
			->with( 'human' )
			->andReturn( 'humans' );

		$this->assertSame(
			'humans',
			( new PluralizerDoctrineInflectorAdapter( $this->inflector_instance_mock ) )->pluralize( 'human' )
		);
	}
}
