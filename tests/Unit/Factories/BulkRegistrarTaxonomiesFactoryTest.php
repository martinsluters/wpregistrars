<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\Factories\BulkRegistrarTaxonomiesFactory;

/**
 * Tests of Bulk Registrar Taxonomies Factory.
 */
class BulkRegistrarTaxonomiesFactoryTest extends AbstractBulkRegistrarTaxonomiesPostTypesFactoryTestCase {

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->factory = new BulkRegistrarTaxonomiesFactory();
	}
}
