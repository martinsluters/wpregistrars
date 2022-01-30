<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\ArgumentSets;

use martinsluters\WPRegistrars\PluralizerInterface;
use martinsluters\WPRegistrars\Tests\Unit\TestCase;
use Mockery;

/**
 * Abstract base class of Post Type and Taxonomy registration argument set tests
 */
abstract class AbstractWPTaxonomyPostTypeArgumentSetTestCase extends TestCase {

	/**
	 * Mock of Pluralizer
	 *
	 * @var PluralizerInterface
	 */
	protected PluralizerInterface $pluralizer_mock;

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
	 * Asserts that the contents of two arrays are equal, without accounting for the order of elements.
	 *
	 * @param array $expected Expected array.
	 * @param array $actual   Array to check.
	 * @return void
	 */
	public function assertEqualSetsWithIndex( array $expected, array $actual ): void {
		ksort( $expected );
		ksort( $actual );
		$this->assertEquals( $expected, $actual );
	}
}
