<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Tests\Unit\Factories;

use martinsluters\WPRegistrars\Factories\BulkRegistrarPostTypesFactory;

/**
 * Tests of Bulk Registrar Post Types Factory.
 */
class BulkRegistrarPostTypesFactoryTest extends AbstractBulkRegistrarTaxonomiesPostTypesFactoryTestCase {

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->factory = new BulkRegistrarPostTypesFactory();
	}
}
