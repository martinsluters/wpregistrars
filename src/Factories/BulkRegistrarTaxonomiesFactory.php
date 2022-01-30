<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Factories;

use martinsluters\WPRegistrars\ArgumentSetContainers\TaxonomyRegistrationArgumentSetContainer;
use martinsluters\WPRegistrars\Registrars\BulkRegistrarTaxonomies;

/**
 * Factory class of BulkRegistrarTaxonomiesFactory
 */
final class BulkRegistrarTaxonomiesFactory extends AbstractWPTaxonomyPostTypeBulkRegistrarFactory {

	/**
	 * Stores taxonomy registration argument set factory
	 *
	 * @var TaxonomyRegistrationArgumentSetFactory
	 */
	protected TaxonomyRegistrationArgumentSetFactory $argument_set_factory;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->argument_set_factory = new TaxonomyRegistrationArgumentSetFactory();
	}

	/**
	 * Instantiates a new BulkRegistrarTaxonomies instance
	 *
	 * @return BulkRegistrarPostTypes
	 */
	protected function createBulkRegistrar(): BulkRegistrarTaxonomies {
		return new BulkRegistrarTaxonomies( $this->argument_set_container );
	}

	/**
	 * Fills a new argument set container with argument sets.
	 *
	 * @return void
	 */
	protected function fillArgumentSetContainer(): void {
		$this->argument_set_container = new TaxonomyRegistrationArgumentSetContainer();

		foreach ( $this->raw_arguments as $raw_argument_key => $raw_argument_value ) {
			if ( \is_int( $raw_argument_key ) ) {
				$this->argument_set_factory->withArguments( $raw_argument_value, null, [] );
			} else {
				$object_type = \array_key_exists( 'object_type', $raw_argument_value ) ? (array) $raw_argument_value['object_type'] : null;
				$this->argument_set_factory->withArguments( $raw_argument_key, $object_type, $raw_argument_value );
			}

			$this->argument_set_container
				->addArgumentSet( $this->argument_set_factory->create() );
		}
	}
}
