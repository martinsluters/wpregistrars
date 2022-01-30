<?php
declare( strict_types=1 );
namespace martinsluters\WPRegistrars\Factories;

use martinsluters\WPRegistrars\ArgumentSetContainers\PostTypeRegistrationArgumentSetContainer;
use martinsluters\WPRegistrars\Registrars\{ BulkRegistrarPostTypes, AbstractBulkRegistrar };

/**
 * Factory class of BulkRegistrarPostTypes
 */
final class BulkRegistrarPostTypesFactory extends AbstractWPTaxonomyPostTypeBulkRegistrarFactory {

	/**
	 * Stores post type registration argument set factory
	 *
	 * @var PostTypeRegistrationArgumentSetFactory
	 */
	protected PostTypeRegistrationArgumentSetFactory $argument_set_factory;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->argument_set_factory = new PostTypeRegistrationArgumentSetFactory();
	}

	/**
	 * Instantiates a new BulkRegistrarPostTypes instance
	 *
	 * @return BulkRegistrarPostTypes
	 */
	protected function createBulkRegistrar(): BulkRegistrarPostTypes {
		return new BulkRegistrarPostTypes( $this->argument_set_container );
	}

	/**
	 * Fills a new argument set container with argument sets.
	 *
	 * @return void
	 */
	protected function fillArgumentSetContainer(): void {
		$this->argument_set_container = new PostTypeRegistrationArgumentSetContainer();

		foreach ( $this->raw_arguments as $raw_argument_key => $raw_argument_value ) {
			if ( \is_int( $raw_argument_key ) ) {
				$this->argument_set_factory->withArguments( $raw_argument_value, [] );
			} else {
				$this->argument_set_factory->withArguments( $raw_argument_key, $raw_argument_value );
			}

			$this->argument_set_container
				->addArgumentSet( $this->argument_set_factory->create() );
		}
	}
}
